<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment...</title>
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif; background-color: #f8fafc;">
    <div style="text-align: center;">
        <h2>Processing your payment...</h2>
        <p>You will be redirected shortly.</p>
        <p>If you are not redirected, <a id="redirect-link" href="{{ $url }}">click here</a>.</p>
    </div>
    <script>
        // Redirecting via JS ensures that SameSite=Lax cookies are re-attached to the GET request.
        window.location.href = document.getElementById('redirect-link').href;
    </script>
</body>
</html>
