@extends('layouts.main')
@section('main')
<style>
    .btn-primary:hover {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }

    .btn-primary {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }

    .btn-primary.focus,
    .btn-primary:focus {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }
</style>
<div class="card p-3 w-50">
    <h5>Add Youtube Link</h5>
    <form action="{{ route('youtube.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="">Youtube Link</label>
                <input type="text" name="youtube_link" class="form-control" placeholder="Enter Youtube Link" required>
            </div>
        </div>
        <br>
        <button class="btn btn-primary" type="submit">Save Changes</button>
    </form>
</div>

@endsection
@section('js')


@endsection