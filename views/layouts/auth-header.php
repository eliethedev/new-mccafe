<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MacCafe' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --maccafe-primary: #ffc107;
            --maccafe-secondary: #6c757d;
            --maccafe-accent: #ffc107;
            --maccafe-dark: #343a40;
            --maccafe-light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image:url(assets/images/BG.jpg);
            min-height: 100vh;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-header {
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 1000;
        }

        .auth-header .logo {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .btn-success {
            background-color: var(--maccafe-primary);
            border-color: var(--maccafe-primary);
            color: #000;
        }

        .btn-success:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #000;
        }

        .form-control:focus {
            border-color: var(--maccafe-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="auth-header">
        <a href="/" class="logo">
            MC<b style="color: var(--maccafe-primary);">Caffe</b>
        </a>
    </div>
