@extends('layouts.app')

@section('content')
<style>
    /* CSS Styles */
    .card {
        margin-top: 50px;
        border: none;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .card-header {
        background-color: #0d6efd;
        color: white;
        border-bottom: none;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        font-size: 1.5rem;
        font-weight: bold;
    }
    body {
        background-color: #f8f9fa; /* Example background color */
        background-image: url('pharma.jpg'); /* Adjusted path */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed; /* Optional: to keep the background fixed while scrolling */
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">{{ __('Send Password Reset Link') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
