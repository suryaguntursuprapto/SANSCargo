<?php
// Create a test blade file to check translation: resources/views/test.blade.php
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Translation Test</title>
</head>
<body>
    <h1>Current Locale: {{ app()->getLocale() }}</h1>
    <h2>Session Locale: {{ session('locale', 'not set') }}</h2>
    
    <h3>Translation Tests:</h3>
    <ul>
        <li>Dashboard: {{ __('app.dashboard') }}</li>
        <li>Profile: {{ __('app.profile') }}</li>
        <li>Settings: {{ __('app.settings') }}</li>
    </ul>
    
    <h3>Change Language:</h3>
    <ul>
        <li><a href="{{ route('set.language', ['locale' => 'en']) }}">English</a></li>
        <li><a href="{{ route('set.language', ['locale' => 'id']) }}">Indonesian</a></li>
    </ul>
</body>
</html>