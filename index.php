<!DOCTYPE html>
<html>
<head>
    <title>Visit Counter</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .counter-container {
            text-align: center;
            padding: 30px;
        }

        .counter-image {
            width: 200px;
            height: auto;
        }

        .counter-number {
            font-size: 48px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="jumbotron mt-5">
            <h1 class="display-4">Welcome to the Visit Counter</h1>
            <hr class="my-4">
            <div class="counter-container">
                <p class="lead counter-number">
                    <span id="counter"><?php include 'visit_counter.php'; ?></span>
                </p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Function to animate the counter
        function animateCounter(targetElement, start, end, duration) {
            let range = end - start;
            let current = start;
            let increment = end > start ? 1 : -1;
            let stepTime = Math.abs(Math.floor(duration / range));
            let timer = setInterval(function () {
                current += increment;
                targetElement.innerText = current;
                if (current === end) {
                    clearInterval(timer);
                }
            }, stepTime);
        }

        // Call the animateCounter function on page load
        document.addEventListener("DOMContentLoaded", function () {
            let counterElement = document.getElementById("counter");
            let count = parseInt(counterElement.innerText);
            animateCounter(counterElement, 0, count, 2000);
        });
    </script>
</body>
</html>
