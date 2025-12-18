<?php
// session_start();
// include '../../connection/connection.php';
include 'connection/connection.php';

$CATEGORIES = [];
$ERRORS = [];

$statement = $connection->query('SELECT * FROM categories');

if (!$statement) {
    $_SESSION['error'] = 'Database error: ' . $connection->error;
    $ERRORS[] = 'Query execution failed';
    $connection->close();
    header('Location: ../../index.php?error_category_fetched');
    exit;
}

if ($statement->num_rows > 0) {
    while ($row = $statement->fetch_assoc()) {
        $CATEGORIES[] = $row;
    }
    $_SESSION['CATEGORIES'] = $CATEGORIES;
    $_SESSION['success'] = 'Data fetched successfully';
} else {
    $_SESSION['CATEGORIES'] = ['message' => 'No categories available'];
}

$statement->close();
$connection->close();

// header('Location: ../../index.php?category_fetched_successfully');
// exit;