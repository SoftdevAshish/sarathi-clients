<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: /views/index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <title>Login</title>
    <!-- MDB icon -->
    <link rel="icon" href="/assets/images/logo.png" type="image/png"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"/>
    <!-- MDB -->
    <link rel="stylesheet" href="/assets/css/mdb.min.css"/>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
            position: relative;
        }

        video.background-video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1;
            transform: translate(-50%, -50%);
            object-fit: cover;
            filter: brightness(0.4);
        }

        .login-card {
            position: relative;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: scale-animation 3s ease infinite;
            margin-right: 10%;
            margin-top: 10%;
        }

        @keyframes scale-animation {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #3a7bd5; /* Title color */
        }

        .input-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: box-shadow 0.3s ease;
            margin-top: 20px; /* Add margin for 3D effect */
        }

        .form-control:focus {
            outline: none;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }

        .label-text {
            position: absolute;
            top: -15px; /* Position it above the input */
            left: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            color: rgba(0, 0, 0, 0.66); /* Color for the label text */
            text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3); /* 3D effect */
            transition: all 0.3s ease;
        }

        .form-control:focus + .label-text,
        .form-control:not(:placeholder-shown) + .label-text {
            top: -30px; /* Move label higher when focused or not empty */
            font-size: 0.75rem; /* Shrink label size */
            color: rgba(0, 0, 0, 0.66); /* Change color on focus */
        }

        .btn-primary:hover {
            background: rgba(44, 92, 145, 0.22);
            color: rgba(0, 0, 0, 1);
        }

        .footer-text {
            color: #fff;
            text-align: center;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>

<!-- Background Video -->
<video autoplay muted loop class="background-video">
    <source src="/assets/images/back.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<div class="container mt-5">
    <div class="row mt-5 pt-5">
        <div class="col-4 offset-8 login-card">
            <div class="login-title">Welcome Back!</div>
            <form action="/actions/_login.php" method="POST">
                <!-- Username input -->
                <div class="input-box my-5">
                    <input type="text" id="form1Example1" name="username" class="form-control" required placeholder=" "/>
                    <label class="label-text" for="form1Example1">Username</label>
                </div>

                <!-- Password input -->
                <div class="input-box my-5">
                    <input type="password" id="form1Example2" name="password" class="form-control" required placeholder=" "/>
                    <label class="label-text" for="form1Example2">Password</label>
                </div>

                <!-- Submit button -->
                <div class="text-center mb-4">
                    <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-md btn-block">Sign in</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="footer-text">
    <div>
        © 2024 Sarathi Consults. All Rights Reserved.
    </div>
</footer>

<!-- MDB -->
<script type="text/javascript" src="/assets/js/mdb.umd.min.js"></script>
</body>
</html>
