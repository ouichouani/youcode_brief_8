<?php

// include 'connection/connection.php';

// $expenses = [] ;
// $total_expenses = 0 ;
// function index_expenses($connection, &$expenses , &$total_expenses)
// {
//     $statement = $connection->prepare('SELECT * FROM expenses WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
//     $statement->execute();

//     $results = $statement->get_result();

//     while ($row = $results->fetch_assoc()) {
//         $expenses[] = $row;
//         $total_expenses += $row['montant'] ;
//     }

//     $statement->close();
// }



// index_expenses($connection, $expenses , $total_expenses) ;



// <?php

include '../connection/connection.php';

$expenses = [] ;
$total_expenses = 0 ;

$statement = $connection->prepare('SELECT * FROM expenses WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
$statement->execute();

$results = $statement->get_result();

while ($row = $results->fetch_assoc()) {
    $expenses[] = $row;
    $total_expenses += $row['montant'] ;
}

$statement->close();




