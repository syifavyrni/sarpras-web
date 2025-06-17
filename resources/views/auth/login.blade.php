<!DOCTYPE html>
<html>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Gambar Kiri */
        .container .image-side {
            flex: 1;
            background: url('/images/background.jpg') no-repeat center center;
            background-size: cover;
        }

        /* Form Kanan */
        .container .form-side {
            flex: 1;
            background-color: #2b2f89;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        form {
            width: 80%;
            max-width: 350px;
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 20px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 25px;
            background-color: #6d8df7;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4d6df0;
        }

        small a {
            color: #cdd6ff;
            text-decoration: none;
        }

        small a:hover {
            text-decoration: underline;
            color: #fff;
        }

    </style>
<head>
    <title>Login | Website</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <div class="image-side"></div>
        <div class="form-side">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h2>LOGIN</h2>

                <label>Username</label>
                <input type="text" name="username" placeholder="Username" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit">LOGIN</button>
            </form>
        </div>
    </div>
</body>
</html>
