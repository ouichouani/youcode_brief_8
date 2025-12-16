<?php

include './connection/connection.php';

$CATEGORIES = [];
$ERRORS = [];

$statement = $connection->query('SELECT id , name from categories');

while ($row = $statement->fetch_assoc()) {
    $CATEGORIES[] = $row;
}

$_SESSION['seccess'] = 'data fetched successfully';
if(!count($CATEGORIES)) $CATEGORIES[] = 'no category avilable';
$connection->close();
// exit;


