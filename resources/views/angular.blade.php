<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angular Admin</title>
    <base href="/admin/">
    <link rel="stylesheet" href="{{ asset('admin-app/styles.css') }}">
</head>
<body>
    <app-root></app-root>
    <script src="{{ asset('admin-app/runtime.js') }}"></script>
    <script src="{{ asset('admin-app/polyfills.js') }}"></script>
    <script src="{{ asset('admin-app/main.js') }}"></script>
</body>
</html>
