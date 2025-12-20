<?php

// include '../../connection/connection.php';
include 'connection/connection.php';

$id = $_SESSION["AuthUser"]['id'];
$transactions = [] ;

$statement = $connection->prepare('SELECT 
    us.full_name AS sender_name,
    us.email AS sender_email,
    ur.full_name AS receiver_name,
    ur.email AS receiver_email,
    t.amount ,
    t.created_at,
    t.description ,
    CASE
        WHEN  us.id = ? THEN "sender"
        WHEN  ur.id = ? THEN "receiver"
    END AS transaction_type
    FROM transactions AS t 
    INNER JOIN cards AS cs ON cs.id = t.id_card_sender  
    INNER JOIN cards AS cr ON cr.id = t.id_card_receiver 
    INNER JOIN users as us ON us.id = cs.user_id
    INNER JOIN users as ur ON ur.id = cr.user_id
    WHERE cs.user_id = ? or cr.user_id = ? 
');

$statement->bind_param("iiii", $id, $id, $id, $id);

$statement->execute();

$result = $statement->get_result();

while($row = $result->fetch_assoc()){
    $transactions[] = $row;
}
$statement->close() ;
$connection->close();

if (empty($transactions)) {
    $_SESSION['message'] = 'No transactions found';
}


$_SESSION['History'] = $transactions ;
$_SESSION['success'] = 'data fetched successfully' ;

