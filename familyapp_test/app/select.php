<?php
require_once "_includes/connect.php";

$stmt = mysqli_prepare($link, "SELECT demoID, store, number_item, item, user_name FROM demo ORDER BY date DESC");
//execute the stetement / query from adobe
mysqli_stmt_execute($stmt);

//get results
$result = mysqli_stmt_get_result($stmt);

//loop through
while($row = mysqli_fetch_assoc($result)){
    $results[] = $row;
}

//encode & display json
echo json_encode($results);

//close the link to the db
mysqli_close($link);

// ?>