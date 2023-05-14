<?php
// Get the visitor's IP address
$ip = $_SERVER['REMOTE_ADDR'];

// Database configuration
$host = 'localhost';
$dbname = 'visitors_count';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the query to fetch the visit count for the IP address
    $stmt = $pdo->prepare('SELECT visit_count FROM visits WHERE ip_address = :ip');
    $stmt->bindParam(':ip', $ip);
    $stmt->execute();

    $row = $stmt->fetch();

    if ($row) {
        // IP address exists, increment the visit count
        $visitCount = $row['visit_count'] + 1;
        $stmt = $pdo->prepare('UPDATE visits SET visit_count = :visitCount WHERE ip_address = :ip');
        $stmt->bindParam(':visitCount', $visitCount);
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();
    } else {
        // IP address doesn't exist, insert a new row
        $visitCount = 1;
        $stmt = $pdo->prepare('INSERT INTO visits (ip_address, visit_count) VALUES (:ip, :visitCount)');
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':visitCount', $visitCount);
        $stmt->execute();
    }

    // Output the number of visits for the current IP address
    echo "Total visits from your IP: " . $visitCount;
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Error: " . $e->getMessage();
}
?>
