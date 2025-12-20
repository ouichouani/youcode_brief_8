<?php

// session_start();
// include '../../connection/connection.php';
include 'connection/connection.php';
unset($_SESSION['error']);
unset($_SESSION['success']);

$CARDS = [];
$ERRORS = [];
if (isset($_SESSION['AuthUser'])) {

    $statement = $connection->query('SELECT * FROM cards WHERE user_id = ' . $_SESSION['AuthUser']['id']);

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
        $_SESSION['CARDS'] = [];
        $_SESSION['info'] = 'No cards available';
    }

    $statement->close();

} else {
    $_SESSION['error'] = ['message' => 'user must be authenticated first'];
    $_SESSION['CARDS'] = [];
}



$connection->close();
