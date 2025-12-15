<?php


$username = 'root' ;
$password = 'ouichouani' ;
$servername = 'mysql' ; // that's the name of my docker container connected to the same network
$dbname = 'youcode_brief_8' ;

$connection = new mysqli($servername , $username , $password , $dbname) ;
if(!$connection) die('connection is not working !') ;


