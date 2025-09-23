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
<div class="card p-3">
    <h5>Update Category</h5>
    <br>
    <form action="{{ route('update.categorys') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mt-3">

            <div class="col-md-5">
                <label for="">Category</label>
                <input type="hidden" name="id" value="{{$bannerData->id}}" />
                <input type="text" name="categories" class="form-control" value="{{$bannerData->categories}}" placeholder="Enter Categories">
            </div>
        </div>
        <br>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
    </form>
</div>

@endsection
