<?php

include './connection/connection.php';

$CARDS = [];
$ERRORS = [];

$statement = $connection->query('SELECT id , name from cards');

while ($row = $statement->fetch_assoc()) {
    $CARDS[] = $row;
}

$_SESSION['seccess'] = 'data fetched successfully';
if(!count($CARDS)) $CARDS[] = 'no card avilable';
$connection->close();
// exit;


