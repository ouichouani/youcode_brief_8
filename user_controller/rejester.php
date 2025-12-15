<?php
include '../connection/connection.php' ;

session_start() ;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $ERRORS = [] ;

    $fullname = isset($_POST["fullname"]) ? trim($_POST["fullname"]) : '';
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';
    $email    = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $confirm_password    = isset($_POST["confirm_password"]) ? trim($_POST["confirm_password"]) : '';

    if(!preg_match('/^[ a-zA-Z0-9_-]{3,}$/', $fullname)) $ERRORS["fullname"] = 'name is required and contain at least 3 characters' ;
    if(!preg_match('/^[^@\s]+@[^@\s]+\.[^@\s]+$/' , $email )) $ERRORS["email"] = 'email is invalid' ;
    if(!preg_match('/^[^\s]{8,}$/' , $password )) $ERRORS["password"] = 'password is required and contain at least 8 characters' ;
    if($confirm_password != $password) $ERRORS["confirm_password"] = 'confirmed password should be idontical with password' ;

    //EMAIL VERIFICATION
    $statement = $connection->prepare("SELECT * FROM users WHERE email = ? LIMIT 1  ");
    $statement->bind_param('s', $email);
    $statement->execute();
    $result = $statement->get_result() ;
    $row = $result->fetch_assoc() ;
    if($row) $ERRORS["email"] = 'email is already used' ;
    

    if(count($ERRORS)){
        $_SESSION['error'] = $ERRORS ;
        header('Location: ../index.php?error=invalid_data') ;
        exit ;
    }

    $fullname = htmlspecialchars($fullname) ;
    $email = htmlspecialchars($email) ;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $statment = $connection->prepare("INSERT INTO users (full_name , email , password) VALUES (? , ? , ? )") ;
    
    if(!$statment) {
        $_SESSION['error'] = 'invalid statment' ;
        header('Location: ../index.php?error=invalid_statment') ;
        exit ;
    }

    $statment->bind_param('sss' , $fullname , $email , $hashed_password ) ;
    $status = $statment->execute() ;

    if(!$status){
        $_SESSION['error'] = 'sql error ' . $statment->error ;
        header('Location: ../index.php?error=sql_error') ;
        exit ;
    }
}

$connection->close() ;
$_SESSION['error'] = [] ;
$_SESSION['success'] = "user created with success" ;
header('Location: ../index.php?success=user created successfuly') ;
exit ;