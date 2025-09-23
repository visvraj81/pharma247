@extends('layouts.main')
@section('main')

<div class="card p-3 w-50">
    <h5>Send Email</h5>
    <br>
    <form action="{{ route('sendemail.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="id" value="{{ $email_data['id'] }}">
                <label for="">Email</label>
                <input type="text" name="email" value="{{ $email_data['email'] }}" class="form-control" readonly>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="">Subject</label>
                <input type="text" name="subject" class="form-control" placeholder="Please Enter Subject">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="">Body</label>
                <textarea name="body" id="" cols="20" rows="5" class="form-control" placeholder="Please Enter Body"></textarea>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">Send</button>
        </div>
    </form>
</div>

@endsection