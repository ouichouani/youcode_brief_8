<?php
include '../connection/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $ERRORS = [];

    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';
    $email    = isset($_POST["email"]) ? trim($_POST["email"]) : '';

    if (!preg_match('/^[^@\s]+@[^@\s]+\.[^@\s]+$/', $email)) $ERRORS["email"] = 'email is invalid';
    if (!preg_match('/^[^\s]{8,}$/', $password)) $ERRORS["password"] = 'password is required and contain at least 8 characters';


    $statement = $connection->prepare("SELECT * FROM users WHERE email = ? LIMIT 1  ");

    if (!$statement) {
        $ERRORS['error'] = "statement is not correct";
        $_SESSION["errors"] = $ERRORS;
        $connection->close();
        header('Location: ../index.php?error=statement');
        exit;
    }

    $statement->bind_param('s', $email);
    $status = $statement->execute();
    
    if (!$status) {
        $ERRORS['error'] = "sql error";
        $_SESSION["errors"] = $ERRORS;
        $connection->close();
        header('Location: ../index.php?error=sqllll');
        exit;
    }

    $result = $statement->get_result();
    $row = $result->fetch_assoc();


    if (!$row) {
        $ERRORS['error'] = "email doesn't exists";
        $_SESSION["errors"] = $ERRORS;
        $connection->close();
        header('Location: ../index.php?error=email_is_not_exists');
        exit;
    }

    if (!password_verify($password, $row['password'])) {
        $ERRORS['error'] = "password is not correct";
        $_SESSION["errors"] = $ERRORS;
        $connection->close();
        header('Location: ../index.php?error=password_is_not_correct');
        exit;
    }

    $_SESSION['authUser'] = $row;
    $connection->close();
    header('Location: ../index.php?success=user_loged_in_with_success');
    exit;
}

$connection->close();
header('Location: ../index.php?error=invalid_method_for_login');
exit;
