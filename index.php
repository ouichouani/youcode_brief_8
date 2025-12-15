<?php
include 'connection/connection.php';
session_start();
if (isset($_SESSION["error"]) && count($_SESSION["error"])) print_r($_SESSION["error"]);
if (isset($_SESSION["error"]) && !empty($_SESSION["success"])) echo ($_SESSION["success"]);
// if(isset($_SESSION["authUser"])) print_r($_SESSION["authUser"]) ;
include('./cards_controller/index.php') ;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            background-color: gray;
        }
    </style>
</head>

<body>

    <!-- USER TEST -->

    <!-- <h1>register</h1>
    <form action="user_controller/rejester.php" method="POST">
    <br> 
    <input type="text" placeholder="fullname" name="fullname">
    <br>
    <input type="text" placeholder="email" name="email">
    <br>
    <input type="text" placeholder="password" name="password">
    <br>
    <input type="text" placeholder="confirm_password" name="confirm_password">
    <br>
    <button>create</button>
    </form> -->

    <!-- <h1>login</h1>
    <form action="user_controller/login.php" method="POST">
    <br> 
    <br>
    <input type="text" placeholder="email" name="email">
    <br>
    <input type="text" placeholder="password" name="password">
    <br>
    <button>login</button>
    </form>
    <h1>logout</h1>
    <form action="user_controller/logout.php" method="POST">
    <button>login</button>
    </form> -->

    <!-- <h1>create</h1>
    <form action="user_controller/login.php" method="POST">
    <br> 
    <br>
    <input type="text" placeholder="email" name="email">
    <br>
    <input type="text" placeholder="password" name="password">
    <br>
    <button>login</button>
    </form>
    <h1>logout</h1>
    <form action="user_controller/logout.php" method="POST">
    <button>login</button>
    </form> -->

    <!-- EXPENCE TEST -->
    <!-- <h1>create expences</h1>
    <form action="user_controller/login.php" method="POST">
    <br> 
    <br>
    <input type="text" placeholder="email" name="email">
    <br>
    <input type="text" placeholder="password" name="password">
    <br>
    <button>create expences</button>
    </form>

    <h1>delete expences</h1>
    <form action="user_controller/logout.php" method="POST">
    <button>delete expences</button>
    </form>

    <h1>update expence</h1>
    <form action="user_controller/login.php" method="POST">
    <br> 
    <br>
    <input type="text" placeholder="email" name="email">
    <br>
    <input type="text" placeholder="password" name="password">
    <br>
    <button>update expence</button>
    </form>

    <h1>show expences</h1>
    <form action="user_controller/logout.php" method="POST">
    <button>show</button>
    </form> -->


    <!-- CARD TEST -->
    <h1>create CARDS</h1>
    <form action="cards_controller/create.php" method="POST">
        <br>
        <br>
        <input type="text" placeholder="name" name="name">
        <br>
        <input type="text" placeholder="description" name="description">
        <br>
        <input type="text" placeholder="user_id" name="user_id">
        <br>
        <button>create CARDS</button>
    </form>

    <h1>delete CARDS</h1>
    <form action="cards_controller/delete.php" method="POST">
        <input type="hidden" name='method' value ='DELETE'>
        <input type="hidden" name='id' value ='1'>
        <button>delete CARDS</button>
    </form>

    <h1>update expence</h1>
    <form action="cards_controller/update.php" method="POST">
        <br>
        <input type="hidden" name='method' value="PUT">
        <input type="hidden" name='id' value="1">
        <br>
        <input type="text" placeholder="name" name="name">
        <br>
        <input type="text" placeholder="description" name="description">
        <br>
        <button>update expence</button>
    </form>

    <h1>show CARDS</h1>
    <form action="user_controller/logout.php" method="POST">
    
        <select name="" id="">
            <?php foreach($CARDS as $card){ ?>
                <?php echo "<option value='".$card["id"] ."'>".$card["name"]."</option>" ; ?>
            <?php } ?>
        </select>
    </form>

</body>

</html>