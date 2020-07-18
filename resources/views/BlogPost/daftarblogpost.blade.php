@extends('Layout.default')

@section('title', 'Daftar BlogPost')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="display-5">Daftar Blog</h1>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn btn-primary text-white" href="{{route('blogpost.create')}}">Tambah Blog</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                @forelse ($blogpost as $item)
                <div class="col-md-6 px-2 py-2">
                    <div style="background-color: white; position: relative; height: 100%;" class="px-3 py-3 mr-3 mb-3">
                        <a href="{{route('blogpost.show', ['blogpost' => $item->id])}}">
                            <h2 class="font-weight-bold d-inline-block">{{$item->title}}</h2>
                        </a>
                        @can('update', $item)
                        <a href="{{ route('blogpost.edit', ['blogpost' => $item->id]) }}">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        @endcan

                        {{--  Conditional Jika Waktu Koten Dibuat Kurang Dari 5 Menit  --}}
                        @if ((new Carbon\Carbon())->diffInMinutes($item->created_at) <= 5) <span
                            class="badge badge-success"><strong>New!</strong></span>
                            @endif
                            {{-- diffForHumans() untuk merubah waktu dari aneh menjadi format yang bisa dibaca manusia --}}
                            <p class="text-muted">Added {{ $item->created_at->diffForHumans() }} by
                                <b>{{ $item->user->name }}</b>
                            </p>
                            <p class="review-text">
                                {{ $item->content }}
                            </p>
                            @if ($item->jumlah_komentar > 0)
                            <p class="text-muted">
                                <small>{{ $item->jumlah_komentar }} Komentar</small>
                            </p>
                            @else
                            <p class="text-muted">
                                <small>Not have a comment</small>
                            </p>
                            @endif
                            @can('delete', $item)
                            <form action="{{route('blogpost.destroy', ['blogpost' => $item->id])}}" method="post">
                                @csrf
                                {{-- Jika Method Tidak Tersedia Pada HTML gunakan @method --}}
                                @method('DELETE')
                                <button class="avatar btn btn-danger btn-sm" type="submit"
                                    style="position: absolute; top: -10px; right: -10px;">
                                    <i class="fa fa-times"></i>
                                </button>
                            </form>
                            @endcan
                    </div>
                </div>
                @empty
                <div class="col-md-12">
                    <p class="text-center bg-white py-3 text-muted">Data Kosong</p>
                </div>
                @endforelse
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="card-title">Most Commented</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Blog paling banyak dibicarakan. </h6>
                </div>
                <ul class="list-group">
                    @forelse ($most_commented as $data)
                    <li class="list-group-item border-right-0 border-left-0">
                        <a href="{{ route('blogpost.show', $data->id) }}">{{ $data->title }}</a>
                    </li>
                    @empty

                    @endforelse
                </ul>
            </div>

            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h5 class="card-title">Most Active User</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Penulis paling aktif membagikan cerita.</h6>
                </div>
                <ul class="list-group">
                    @forelse ($most_user_written_blogpost as $data)
                    <li class="list-group-item border-right-0 border-left-0">
                        {{ $data->name }}
                    </li>
                    @empty

                    @endforelse
                </ul>
            </div>

            <div class="card border-0 mt-3">
                <div class="card-body">
                    <h5 class="card-title">Most Active User Last Month</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Penulis paling aktif membagikan cerita bulan ini.</h6>
                </div>
                <ul class="list-group">
                    @forelse ($most_active_user_last_month as $data)
                    <li class="list-group-item border-right-0 border-left-0">
                        {{ $data->name }}
                    </li>
                    @empty

                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection