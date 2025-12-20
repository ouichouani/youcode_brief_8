<?php

session_start() ;
include '../../connection/connection.php';
unset($_SESSION['error']);
unset($_SESSION['success']);

$ERRORS = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    isset($_POST['name']) ? $name = trim($_POST['name']) : $name = null;
    isset($_POST['balance']) ? $balance = $_POST['balance'] : $balance = null;
    isset($_POST['description']) ? $description = trim($_POST['description']) : $description = null;
    isset($_POST['user_id']) ? $user_id = $_POST['user_id'] : $user_id = null;
    isset($_POST['default_card']) ? $default_card = (boolean) $_POST['default_card'] : $default_card = false;

    if (!$name) $ERRORS['name'] = 'name is required';
    if (!$user_id) $ERRORS['user_id'] = 'user_id is required';
    if (!$balance) $ERRORS['balance'] = 'balance is required';

    if (count($ERRORS)) {
        $connection->close();
        $_SESSION['error'] = $ERRORS ;
        header('location: ../../index.php?error=messing_values');
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9\s.,!?-]{3,100}$/', $name)) $ERRORS['name'] = 'Name must be 3-100 characters with valid characters';
    if (!preg_match('/^[1-9][0-9]*$/', $user_id)) $ERRORS['user_id'] = 'user_id is unvalid regex';
    if(!floatval($balance) or $balance<= 0) $ERRORS['balance'] = 'balance is invalide';

    if (count($ERRORS)) {
        $connection->close();
        $_SESSION['error'] = $ERRORS ;
        header('location: ../../index.php?error=invalide_values');
        exit;
    }

    $name = htmlspecialchars($name);
    $balance = floatval($balance) ;

    if(!empty($description)) $description = htmlspecialchars($description);

    $statement = $connection->prepare('INSERT INTO cards (name , description , balance  , default_card , user_id) VALUES( ? , ? , ? , ? , ? )') ;
    if(!$statement){
        $ERRORS['connection'] = "connection: $connection->error" ;
        $connection->close();
        $_SESSION['error'] = $ERRORS ;
        header('location: ../../index.php?error=invalid_statement');
        exit;
    }


    $statement->bind_param('ssdii' , $name , $description  , $balance , $default_card , $user_id) ;
    $status = $statement->execute() ;

    if(!$status){
        $ERRORS['error'] = "sql error : $statement->error" ;
        $_SESSION['error'] = $ERRORS ;
        $connection->close();
        header('location: ../../index.php?error=invalide_values');
        exit;
    }

}

$connection->close();
$_SESSION['success'] = 'card created successfuly' ;
header('location: ../../index.php?success=card_created_successfuly');
exit;
