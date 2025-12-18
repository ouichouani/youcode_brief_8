<?php

include '../../connection/connection.php';
session_start();
$id = $_SESSION["authUser"]['id'];
$transactions = [] ;

$statement = $connection->prepare('SELECT 
    us.name AS sender_name,
    us.email AS sender_email,
    ur.name AS receiver_name,
    ur.email AS receiver_email,
    t.mount ,
    t.created_at,
    CASE
        WHEN  t.id_card_sender = ? THEN "sender"
        WHEN  t.id_card_receiver = ? THEN "receiver"
    END AS transaction_type
    FROM transactions AS t 
    INNER JOIN USER AS us ON us.id = t.id_card_sender 
    INNER JOIN USER AS ur ON ur.id = t.id_card_receiver 
    WHERE t.id_card_sender = ? or t.id_card_receiver = ? 
');

// $_SESSION['authUser'] = $row;

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
    header('Location: ../../index.php?info=no_transactions');
    exit;
}

$_SESSION['success'] = 'data fetched successfully' ;
header('Location: ../../index.php?success=data_fetched_successfully');
exit;
