<?php

session_start() ;
session_unset() ;

$_SESSION['success'] = 'log out with success' ;
header('location: ../index.php') ;
exit ;

