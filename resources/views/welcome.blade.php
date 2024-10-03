<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title >{{ config('app.name', 'Laravel') }}</title>
    @vite([
        'resources/js/app.js',
        'resources/css/app.css',
        ])
</head>
<body class="min-h-screen bg-sky-100">
    <div id="app" class="min-h-screen"></div>
</body>
</html>