<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\LogAktivity;
use App\BlogPosts;
use Illuminate\Database\Eloquent\Builder;
use App\Image;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email', 'email_verified_at', 'created_at', 'updated_at', 'is_admin'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function log_aktivities()
    {
        return $this->hasMany(LogAktivity::class, 'user_id', 'id');
    }

    public function blogposts()
    {
        return $this->hasMany(BlogPosts::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'comment_for', 'comment_for_type', 'comment_for_id', 'id')->latest();
    }

    public function scopeMostWrittenBlog(Builder $query)
    {
        return $query->withCount('blogposts as jumlah_blog')->orderBy('jumlah_blog', 'desc');
    }

    public function scopeMostActiveUserLastMonth(Builder $query)
    {
        return $query->withCount(['blogposts as jumlah_blog' => function ($query) {
            $query->whereBetween('created_at', [now()->subMonths(1), now()]);
        }])->has('blogposts', '>', 2)->orderBy('jumlah_blog', 'desc');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'image_for', 'image_for_type', 'image_for_id', 'id');
    }
}
