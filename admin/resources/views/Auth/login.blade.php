<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calamitech Login</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
            font-family: 'Nunito', sans-serif;
        }

        .login-container {
            background: #87CEEB;
            padding: 2rem;
            border-radius: 20px;
            /* box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2); */
            width: 700px;
        }

        .logo img {
            width: 150px;
            display: block;
            margin: 0 auto;
        }

        .row {
            display: flex;
            align-items: center;
        }

        .login-form {
            text-align: center;
        }

        .form-control {
            width: 100%;
            height: 45px;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #0099FF;
            border: none;
            padding: 12px;
            font-size: 18px;
            width: 100%;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            background-color: #007acc;
        }

        .signup-text {
            margin-top: 15px;
            font-size: 14px;
            color: black;
            text-align: center;
        }

        .signup-text a {
            color: #0099FF;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="row">
            <!-- Logo Column -->
            <div class="col-md-5 text-center">
                <div class="logo">
                    <img src="{{ asset('assets/images/calametech-logo.png') }}" alt="Calamitech Logo">
                </div>
            </div>

            <!-- Form Column -->
            <div class="col-md-7 col-lg-7">
                <div class="login-form">
                    <h4>Log in to your Account</h4>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </form>
                    <p class="signup-text mt-3"></p>

                    <!-- Mobile App Download Section -->
                    <div class="mt-4 text-center">
                        <p>Get our mobile app for a better experience!</p>
                        <a href="https://drive.google.com/file/d/1kMmP4jsN9ti91KfD23JMdrRH9IJkV4QO/view" target="_blank"
                            class="btn text-dark" style="background-color: white;" download>
                            📱 Download Mobile App
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
