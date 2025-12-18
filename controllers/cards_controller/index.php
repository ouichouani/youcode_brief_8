<?php

// session_start();
// include '../../connection/connection.php';
include 'connection/connection.php';

$CARDS = [];
$ERRORS = [];

$statement = $connection->query('SELECT * FROM cards');

if (!$statement) {
    $_SESSION['error'] = 'Database error: ' . $connection->error;
    $ERRORS[] = 'Query execution failed';
    $connection->close();
    header('Location: ../../index.php?error_cards_fetched');
    exit;
}

if ($statement->num_rows > 0) {
    while ($row = $statement->fetch_assoc()) {
        $CARDS[] = $row;
    }
    $_SESSION['CARDS'] = $CARDS;
    $_SESSION['success'] = 'Cards fetched successfully';
} else {
    $CARDS[] = ['message' => 'No cards available'];
    $_SESSION['CARDS'] = $CARDS;
    $_SESSION['info'] = 'No cards available';
}

$statement->close();
$connection->close();
// header('Location: ../../index.php?cards_fetched_successfully');
// exit;