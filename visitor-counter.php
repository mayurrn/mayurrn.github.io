
<?php
header('Content-Type: application/json');

$counterFile = __DIR__ . '/visitor-count.txt';

// Open counter file
$file = fopen($counterFile, 'c+');

if (!$file) {
    http_response_code(500);
    echo json_encode(['error' => 'Unable to access counter']);
    exit;
}

// Lock file to prevent simultaneous update problems
flock($file, LOCK_EX);

// Read current count
$count = (int) stream_get_contents($file);

// Increment count
$count++;

// Save updated count
rewind($file);
ftruncate($file, 0);
fwrite($file, (string)$count);
fflush($file);

// Release lock
flock($file, LOCK_UN);
fclose($file);

// Return count
echo json_encode(['visits' => $count]);
?>
