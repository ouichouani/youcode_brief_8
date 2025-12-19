<?php

include '../../connection/connection.php';
session_start() ;
unset($_SESSION['error']);
unset($_SESSION['success']);

$ERRORS = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['method'] = 'PUT') {

    isset($_POST['name']) ? $name = trim($_POST['name']) : $name = null;
    isset($_POST['description']) ? $description = trim($_POST['description']) : $description = null;
    isset($_POST['id']) ? $id = $_POST['id'] : $id = null;


    if (!empty($name)) {
        if (!preg_match('/^[a-zA-Z0-9\s.,!?-]{3,100}$/', $name)) $ERRORS['name'] = 'Name must be 3-100 characters with valid characters';
        $name = htmlspecialchars($name);
    }

    if (!$id){
        $ERRORS['id'] = 'id is required';
        if (!preg_match('/^[1-9][0-9]*$/', $id)) $ERRORS['id'] = 'id is unvalid regex';
    }


    if (!empty($description)) $description = htmlspecialchars($description);

    if (count($ERRORS)) {
        $connection->close();
        $_SESSION['error'] = $ERRORS;
        header('location: ../index.php?error=invalide_values');
        exit;
    }

    if (!empty($name) && !empty($description)) {
        $statement = $connection->prepare("UPDATE cards SET name = ? , description = ? WHERE id = ? ");
        $statement->bind_param('ssi', $name, $description, $id);

    } else if (!empty($name) && empty($description)) {
        $statement = $connection->prepare("UPDATE cards SET name = ? WHERE id = ? ");
        $statement->bind_param('si', $name, $id);

    } else if (empty($name) && !empty($description)) {
        $statement = $connection->prepare("UPDATE cards SET description = ? WHERE id = ? ");
        $statement->bind_param('si', $description, $id);

    } else{
        $connection->close(); 
        header('location: ../index.php?error=nothing_to_update');
        exit;
    }
    
    $status = $statement->execute();
    
    if (!$status) {
        $ERRORS['error'] = "sql error : $statement->error";
        $_SESSION['error'] = $ERRORS;
        $connection->close();
        header('location: ../index.php?error=invalide_values');
        exit;
    }
}

$connection->close();
$_SESSION['success'] = 'card created successfuly';
header('location: ../index.php?success=card_created_successfuly');
exit;
