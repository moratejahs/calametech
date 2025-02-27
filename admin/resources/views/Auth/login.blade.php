<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calamitech Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #005f73;
        }
        .login-container {
            background: #02778f;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: white;
            width: 400px;
        }
        .login-container img {
            width: 120px;
            margin-bottom: 10px;
        }
        .form-control {
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .btn-primary {
            background-color: #00aaff;
            border: none;
            padding: 10px;
            font-size: 16px;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #008ecc;
        }
        .text-muted a {
            color: #00aaff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="{{ asset('assets/images/calametech-logo.png') }}" alt="Calamitech Logo">
        <h4 style="color: white;">Log in to your Account</h4>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" class="form-control" name="email" placeholder="Enter email" required>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <button class="btn btn-primary">Sign in</button>
        </form>

    </div>
</body>

</html>
