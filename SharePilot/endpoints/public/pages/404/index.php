<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Page Not Found</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }
        .message-container {
            max-width: 400px;
            font-size: 2rem; /* Increased font size */
            font-weight: bold; /* Bold text */
        }
    </style>
</head>
<body>
    <div class='message-container'>
        <p>Page not found. Redirecting to the main page in <span id='countdown'>2</span> seconds...</p>
    </div>
    <script>
        let countdown = 2; // Initial countdown value in seconds
        let countdownElement = document.getElementById('countdown');

        // Update countdown every second
        let interval = setInterval(function() {
            countdown--;
            countdownElement.textContent = countdown; // Update the countdown text

            // Redirect after countdown reaches 0
            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = '/'; // Redirect to the main page
            }
        }, 1000); // 1000 milliseconds = 1 second
    </script>
</body>
</html>