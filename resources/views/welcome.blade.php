<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharma Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
        /* Update or add styles as necessary */
        .card {
            margin-top: 50px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            /* Additional card styles */
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
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">{{ __('Pharma Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>

                            @if (Route::has('password.request'))
                                <p class="mt-2 mb-1 text-center">
                                    <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                </p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyBkFtF3Jqgxims1KkoQ5FkhuFQYu0hUBo" crossorigin="anonymous"></script>
</body>
</html>
