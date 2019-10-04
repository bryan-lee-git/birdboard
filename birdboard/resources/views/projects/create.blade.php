@extends('layouts.app')

@section('content')
    <form method="POST" action="/projects">
        @csrf
        <h1>Create a Project</h1>
        <div>
            <label for="title">Title</label>
            <div>
                <input name="title" type="text">
            </div>
        </div>
        <div>
            <label for="description">Description</label>
            <div>
                <textarea name="description"></textarea>
            </div>
        </div>
        <div>
            <div>
                <button type="submit">Create Project</button>
                <a href="/projects">Cancel</a>
            </div>
        </div>
    </form>
@endsection
