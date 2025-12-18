<?php

session_start() ;
session_destroy() ;

$_SESSION['success'] = 'log out with success' ;
header('location: ../../index.php') ;
exit ;

