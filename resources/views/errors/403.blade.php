<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .error-message {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .error-description {
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="error-message">
        403 Forbidden
    </div>
    <div class="error-description">
        {{ $message ?? 'You do not have permission to access this page.' }}
    </div>
    <a href="{{ route('profile') }}">Return to Profile Page</a>
</body>
</html>
