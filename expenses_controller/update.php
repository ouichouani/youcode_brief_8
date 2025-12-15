<?php

include '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['method'] == 'PUT') {

    $ERRORS = [] ;
    
    $id = $_POST['id'] ?? null;
    
    //ID VALIDATION
    if (!$id) {
        $ERRORS['id'] = 'id is required';
    } else if (!is_numeric($id) || $id <= 0) {
        $ERRORS['id'] = 'id must be a positive number';
    }

    $fetch_expence = $connection->prepare("SELECT * FROM expences WHERE id = ? ");
    $fetch_expence->bind_param('i' , $id_card)  ;
    $fetch_expence->execute() ;
    $data_object = $fetch_expence->get_result() ;
    $data_array = $data_object->fetch_assoc()   ; 


    //IF VALUE NOT EXISTS , SET NULL IN VARIABLE 
    $amount = $_POST['amount'] ?? null;
    $description = trim($_POST['description'] ?? '', ' ');
    $id_card = trim($_POST['id_card'] ?? '', ' ');
    
    //VALIDATION AMOUNT
    if (!$amount) {
        $ERRORS['amount'] = 'amount is required';
    } else if ($amount < 0) {
        $ERRORS['amount']  = 'amount can\'t be negative';
    } else if (!preg_match('/^\d+(\.\d{1,2})?$/', $amount)) {
        $ERRORS['amount'] = 'amount is invalid';
    }



    //CARD VALIDATION 
    $find_card = $connection->prepare("SELECT * from cards WHERE id = ?") ;
    $find_card->bind_param('i' , $id_card) ;
    $find_card->execute() ;
    $find_card_result = $find_card->get_result() ;
    $card = $find_card_result->fetch_assoc() ;
    if(!$card)  $ERRORS['id_card']  = 'card is not exists';

    //IF THERE IS AN ERROR
    if (count($ERRORS)) {
        session_start();
        $_SESSION['ERRORS'] = $ERRORS;
        $connection->close();
        header('Location: ../index.php?error=validation');
        exit;
    }


    $amount = floatval($amount); // amount VALIDATION 
    $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
    $id_card = intval($id_card);
    $id = intval($id);
    

    $stat = $connection->prepare('UPDATE expenses SET amount = ? , description = ? , card_id = ? WHERE id = ?');

    if (!$stat) {
        session_start();
        $_SESSION['ERRORS'] = ["Database error: " . $connection->error];
        $connection->close();
        header("Location: ../index.php?error=database");
        exit;
    }

    $stat->bind_param('dsii', $montant, $description, $id_card ,$id);
    $status = $stat->execute();


    if (!$status) {
        session_start();
        $_SESSION['ERRORS'] = ['update failed' . $stat->error];
        $stat->close();
        $connection->close();
        header('Location: ../index.php?error=update_failed');
        exit;
    }

    //UPDATE SUCCEED
    $stat->close();
    session_start();
    $_SESSION['SUCCESS'] = 'expense updated successfully';
}

$connection->close();
header('Location: ../index.php');
exit;

