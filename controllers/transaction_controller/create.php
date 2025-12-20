<?php

include '../../connection/connection.php';
session_start();
unset($_SESSION['error']);
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $ERRORS = [];

    $amount = $_POST['amount'] ?? null;
    $description = trim($_POST['description'] ?? '');
    $id_card_sender = $_POST['id_card_sender'] ?? null;
    $email_receiver = isset($_POST["email"]) ? trim($_POST["email"]) : '';

    // VALIDATION AMOUNT
    if (!$amount) {
        $ERRORS['amount'] = 'amount is required';
    } else if ($amount <= 0) {
        $ERRORS['amount'] = 'amount must be positive';
    } else if (!preg_match('/^\d+(\.\d{1,2})?$/', $amount)) {
        $ERRORS['amount'] = 'amount is invalid';
    }

    if (empty($email_receiver)) {
        $ERRORS['email'] = 'receiver email is required';
    } else if (!filter_var($email_receiver, FILTER_VALIDATE_EMAIL)) {
        $ERRORS["email"] = 'email is invalid';
    }

    if (empty($id_card_sender)) {
        $ERRORS['id_card_sender'] = 'sender card is required';
    }

    //IF THERE IS AN ERROR
    if (count($ERRORS)) {
        $_SESSION['error'] = $ERRORS;
        $connection->close();
        header('Location: ../../index.php?error=validation');
        exit;
    }

    // START A NEW TRANSACTION
    $connection->begin_transaction();

    try {
        // CHECK IF EMAIL EXISTS
        $statement = $connection->prepare("SELECT id FROM users WHERE email = ?");
        $statement->bind_param('s', $email_receiver);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Receiver email not found');
        }

        $receiver_user = $result->fetch_assoc();
        $receiver_user_id = $receiver_user['id'];
        $statement->close();


        // CHECK IF SENDER HAS SUFFICIENT BALANCE
        $check_balance = $connection->prepare("SELECT balance FROM cards WHERE id = ?");
        $check_balance->bind_param('i', $id_card_sender);
        $check_balance->execute();
        $balance_result = $check_balance->get_result();
        $sender_card = $balance_result->fetch_assoc();
        $check_balance->close();

        if (!$sender_card) {
            throw new Exception('Sender card not found');
        }

        if ($sender_card['balance'] < $amount) {
            throw new Exception('Insufficient balance');
        }

        //GET RECEIVER DEFAULT CARD
        $statement = $connection->prepare('SELECT id FROM cards WHERE user_id = ? AND default_card = 1');
        $statement->bind_param('i', $receiver_user_id);
        $statement->execute();
        $result = $statement->get_result();
        $receiver_card = $result->fetch_assoc();
        $statement->close();

        if (!$receiver_card) {
            throw new Exception('Receiver does not have a default card');
        }

        $id_card_receiver = $receiver_card['id'];

        $description = $connection->real_escape_string($description);
        $amount = floatval($amount);

        //INSERT TRANSACTION
        $stat = $connection->prepare('INSERT INTO transactions (amount, description, id_card_sender, id_card_receiver) VALUES (?, ?, ?, ?)');
        $stat->bind_param('dsii', $amount, $description, $id_card_sender, $id_card_receiver);

        if (!$stat->execute()) {
            throw new Exception('Failed to create transaction: ' . $stat->error);
        }
        $stat->close();

        //UPDATE SENDER CARD BALANCE
        $update_card_sender = $connection->prepare('UPDATE cards SET balance = balance - ? WHERE id = ?');
        $update_card_sender->bind_param('di', $amount, $id_card_sender);

        if (!$update_card_sender->execute()) {
            throw new Exception('Failed to update sender balance');
        }
        $update_card_sender->close();

        //UPDATE RECEIVER CARD BALANCE
        $update_card_receiver = $connection->prepare('UPDATE cards SET balance = balance + ? WHERE id = ?');
        $update_card_receiver->bind_param('di', $amount, $id_card_receiver);

        if (!$update_card_receiver->execute()) {
            throw new Exception('Failed to update receiver balance');
        }
        $update_card_receiver->close();

        // Commit transaction
        $connection->commit();

        $_SESSION['success'] = 'Transaction created successfully';
    } catch (Exception $e) {
        // Rollback transaction on error
        $connection->rollback();
        $_SESSION['error'] = [$e->getMessage()];
        header('Location: ../../index.php?error=transaction_failed');
        exit;
    }
}

$connection->close();
header('Location: ../../../index.php');
exit;


// what is filter_var() function 
// what is $connection->begin_transaction()
// what is real_escape_string() 
// what is throw new Exception('Sender card not found') // is thet the massage in $e->getMessage ?
// what is $connection->commit();
// what is $connection->rollback();
