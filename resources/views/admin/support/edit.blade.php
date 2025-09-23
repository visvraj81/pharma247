@extends('layouts.main')
@section('main')
<div class="card p-3">
    <h5>Edit Support Ticket</h5>
    <br>
    <form action="{{ route('support.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="">Name</label>
                <input type="hidden" name="id" value="{{$editDetails['id']}}" />
                <input type="text" name="name" class="form-control" value="{{$editDetails['name']}}" placeholder="Please Enter Name">
            </div>
            <div class="col-md-6">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" value="{{$editDetails['email']}}" placeholder="Please Enter Email">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="">Title</label>
                <input type="text" name="title" class="form-control" value="{{$editDetails['title']}}">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="">Content</label>
                <input type="textarea" name="content" class="form-control" value="{{$editDetails['content']}}">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="">status</label>
              <select name="status" value="" class="form-control">
                <option value=""> Select Status</option>
                  <option value="0" {{ $editDetails['status'] == '0' ? 'selected' : '' }}>Open</option>
                  <option value="1" {{ $editDetails['status'] == '1' ? 'selected' : '' }}>Close</option>
              </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="">Image</label>
                <input type="file" name="attachments" class="form-control dark_logo">
                <img class="image1" src="{{$editDetails['attachments']}}" width="100px" style="margin-top: 10px;">
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary ">Save Change</button>
        </div>
    </form>
</div>
@endsection
@section('js')
<script>
    $('.dark_logo').change(function() {
        var curElement = $('.image1');
        console.log(curElement);
        var reader = new FileReader();

        reader.onload = function(e) {
            curElement.attr('src', e.target.result);
        };

        reader.readAsDataURL(this.files[0]);
    });
    </script>
    @endsection