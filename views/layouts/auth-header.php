<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CafeCat' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/bw-theme.css" rel="stylesheet">
    <style>
        :root {
            --cafecat-primary: #111111;
            --cafecat-secondary: #6c757d;
            --cafecat-accent: #000000;
            --cafecat-dark: #000000;
            --cafecat-light: #f8f9fa;
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
            background-color: var(--cafecat-primary);
            border-color: var(--cafecat-primary);
            color: #fff;
        }

        .btn-success:hover {
            background-color: #333333;
            border-color: #000000;
            color: #fff;
        }

        .form-control:focus {
            border-color: var(--cafecat-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.25);
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

        /* Enhanced Back to Home Button */
        .btn-outline-secondary {
            color: var(--cafecat-secondary);
            border-color: var(--cafecat-secondary);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-outline-secondary:hover {
            color: white;
            background-color: var(--cafecat-secondary);
            border-color: var(--cafecat-secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-outline-secondary i {
            transition: transform 0.3s ease;
        }

        .btn-outline-secondary:hover i {
            transform: translateX(-3px);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .gap-2 {
            gap: 0.5rem;
        }
    </style>
</head>
<body>
    