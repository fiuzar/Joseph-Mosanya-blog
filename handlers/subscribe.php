<?php
// Simple subscribe handler: validate email, prevent duplicates, insert into `subscribers`.
require_once __DIR__ . '/dbh.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit();
}

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /index.php?subscribe_error=' . urlencode('Please provide a valid email address.'));
    exit();
}

// Normalize email
$email = mb_strtolower($email, 'UTF-8');

// Prevent duplicates
$checkSql = "SELECT id FROM subscribers WHERE email = ? LIMIT 1";
if ($stmt = $conn->prepare($checkSql)) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        header('Location: /index.php?subscribe_error=' . urlencode('This email is already subscribed.'));
        exit();
    }
    $stmt->close();
} else {
    header('Location: /index.php?subscribe_error=' . urlencode('Database error.'));
    exit();
}

// Insert
$insertSql = "INSERT INTO subscribers (email) VALUES (?)";
if ($stmt = $conn->prepare($insertSql)) {
    $stmt->bind_param('s', $email);
    if ($stmt->execute()) {
        $stmt->close();
        header('Location: /index.php?subscribe_success=1');
        exit();
    } else {
        $err = $stmt->error;
        $stmt->close();
        header('Location: /index.php?subscribe_error=' . urlencode('Insert failed: ' . $err));
        exit();
    }
} else {
    header('Location: /index.php?subscribe_error=' . urlencode('Database error (prepare insert).'));
    exit();
}

?>
