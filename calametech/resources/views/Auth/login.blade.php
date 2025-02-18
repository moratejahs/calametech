<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reygenix</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}">

    <link rel="icon" href="{{ asset('assets/images/logo/reygenix.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/validation/login-error.css') }}">
</head>

<body
    style="background-image: url({{ asset('assets/images/auth-bg.jpg') }}); background-repeat: no-repeat; background-position: center center;">
    <div id="auth">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-4">
                            <img src="{{ asset('assets/images/logo/reygenix.png') }}" alt="" height="200">
                        </div>

                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf
                            <div class="form-group position-relative" style="padding-bottom: 0; margin-bottom: 0;">
                                <input type="text"
                                    class="form-control form-control-md @error('email') input-error @enderror @error('auth-error') input-error @enderror"
                                    value="{{ old('email') }}" name="email" placeholder="Email">
                            </div>
                            <div class="msg-error mb-2">
                                @error('email')
                                    {{ $message }}
                                @enderror
                                @error('auth-error')
                                    {{ $message }}
                                @enderror
                            </div>

                            <div class="form-group position-relative has-icon-right"
                                style="padding-bottom: 0; margin-bottom: 0;">
                                <input type="password"
                                    class="form-control form-control-md @error('password') input-error @enderror @error('auth-error') input-error @enderror"
                                    name="password" placeholder="Password" id="passwordField">
                                <div class="form-control-icon" id="togglePassword">
                                    <i class="bi bi-eye-slash" @error('password') id="icon-error" @enderror></i>
                                </div>
                            </div>
                            <div class="msg-error mb-2">
                                @error('password')
                                    {{ $message }}
                                @enderror
                                @error('auth-error')
                                    {{ $message }}
                                @enderror
                            </div>

                            <button class="btn btn-primary btn-block btn-md shadow-md mb-3">Log In</button>
                            {{-- <div class="text-center text-lg mt-2">
                                <p><a href="#">Forgot password?</a></p>
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation/password.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/form-validation/login.js') }}"></script> --}}
</body>

</html>
