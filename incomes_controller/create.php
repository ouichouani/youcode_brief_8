<?php

include '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $ERRORS = [] ;

    //IF VALUE NOT EXISTS , SET NULL IN VARIABLE 
    $amount = $_POST['amount'] ?? null;
    $description = trim($_POST['description'] ?? '', ' ');
    $category_id = trim($_POST['category_id'] ?? '', ' ');

    //VALIDATION AMOUNT
    if (!$amount) {
        $ERRORS['amount'] = 'amount is required';
    } else if ($amount < 0) {
        $ERRORS['amount']  = 'amount can\'t be negative';
    } else if (!preg_match('/^\d+(\.\d{1,2})?$/', $amount)) {
        $ERRORS['amount'] = 'amount is invalid';
    }

    //IF THERE IS AN ERROR
    if (count($ERRORS)) {
        session_start();
        $_SESSION['ERRORS'] = $ERRORS;
        $connection->close();
        header('Location: ../index.php?error=validation');
        exit;
    }

    $find_card = $connection->prepare("SELECT * from categories WHERE id = ?") ;

    $find_card->bind_param('i' , $category_id) ;
    $find_card->execute() ;
    $result = $find_card->get_result() ;
    $row = $result->fetch_assoc() ;

    if(!$rom) $ERRORS['category_id']  = 'card is not exists';


    $amount = floatval($amount); // amount VALIDATION 
    $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
    $category_id = intval($category_id);

    $stat = $connection->prepare('INSERT INTO incomes (amount, description , category_id ) VALUES (? , ?  , ?)');


    if (!$stat) {
        session_start();
        $_SESSION['ERRORS'] = ["Database error: " . $connection->error];
        $connection->close();
        header("Location: ../index.php?error=database");
        exit;
    }

    $stat->bind_param('dsi', $amount, $description, $category_id);
    $status = $stat->execute();


    if (!$status) {
        session_start();
        $_SESSION['ERRORS'] = ['creation failed' . $stat->error];
        $stat->close();
        $connection->close();
        header('Location: ../index.php?error=creation_failed');
        exit;
    }

    //CREATE SUCCEED
    $stat->close();
    session_start();
    $_SESSION['SUCCESS'] = 'expense created successfully';
}

$connection->close();
header('Location: ../index.php');
exit;

