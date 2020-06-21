<?php

namespace Tests\Feature;

use App\BlogPosts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogPostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testNoBlogPostsWhenNothinInDatabase()
    {
        $response = $this->get('/blogpost');
        $response->assertSeeText('Data Kosong');
    }

    public function testSee1BlogPostWhenThereIs1()
    {
        /* 
        ? This Is Arrange Part
        */

        $post = new BlogPosts();
        $post->title = 'New Title';
        $post->content = 'Content of the blog post';
        $post->save();

        /* 
        ? Act Part
        */

        $response = $this->get('/blogpost');

        /* 
        ? Assert Part
        */

        $response->assertSeeText('New Title');

        /*
        ? Untuk check Sebuah Database Dengan Data Spesifik
        */

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New Title'
        ]);
    }

    public function testStoreIsValid()
    {
        $params = [
            'title' => 'Valid Title',
            'content' => 'Valid Content'
        ];

        $this->post('/blogpost', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'News Was Created!');
    }

    public function testValidationIsWork()
    {
        $params = [
            'title' => null,
            'content' => null
        ];

        $this->post('/blogpost', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title field is required.');
        $this->assertEquals($messages['content'][0], 'The content field is required.');
    }

    public function testUpdateIsValid()
    {
        $post = new BlogPosts();
        $post->title = 'New Title';
        $post->content = 'Content of the blog post';
        $post->save();

        $this->assertDatabaseHas('blog_posts', [
            'id' => $post->id,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'title' => $post->title,
            'content' => $post->content
        ]);

        $newParams = [
            'title' => 'Changed Title',
            'Content' => 'Changed Content'
        ];

        $this->put("/blogpost/{$post->id}", $newParams)
            ->assertStatus(302);

        // $this->assertDatabaseMissing('blog_posts', [
        //     'id' => $post->id,
        //     'created_at' => $post->created_at,
        //     'updated_at' => $post->updated_at,
        //     'title' => $post->title,
        //     'content' => $post->content
        // ]);
    }
}
