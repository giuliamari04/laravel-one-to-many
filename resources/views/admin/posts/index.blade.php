@extends('layouts.app')
@section('content')
    <section class="container">
        <a href="{{ route('admin.posts.create') }}">crea nuovo elemento</a>
        <h1>Post List</h1>
        @foreach ($posts as $post)
            <p><a href="{{ route('admin.posts.show', $post->id) }}">{{ $post->title }}</a>
                <span><img src="{{asset('storage/app/' . $post->image)}}" alt="img"></span>
            <a class="btn btn-secondary " href="{{ route('admin.posts.edit',$post->id) }}">modifica</a>
            </p>
            <form action="{{route('admin.posts.destroy',$post->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="cancel-button btn btn-danger" data-item-title="{{$post->title}}">Cancella</button>
            </form>
        @endforeach
    </section>
@endsection
