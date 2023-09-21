<?php

require_once "_includes/connect.php";

 /* _v2 adds try / catch / finally error checking */
 $results = [];
 $insertedRows = 0;

  //SQL query copied from phpMyAdmin:

try{
    if(!isset($_REQUEST["item"])){
      throw new Exception('Required data is missing i.e. item');
    }

    $query = "INSERT INTO demo (store, number_item, item, user_name) VALUES (?, ?, ?, ?)";

    if($stmt = mysqli_prepare($link, $query)){
      mysqli_stmt_bind_param($stmt, 'ssss', $_REQUEST["store"],  $_REQUEST["number_item"], $_REQUEST["item"], $_REQUEST["user_name"]);
      mysqli_stmt_execute($stmt);
      $insertedRows = mysqli_stmt_affected_rows($stmt);
  
      if($insertedRows > 0){
        $results[] = [
          "insertedRows"=>$insertedRows,
          "demoID" => $link->$insert_id,
          "store" => $_REQUEST["store"],
          "number_item" => $_REQUEST["number_item"],
          "item" => $_REQUEST["item"],
          "user_name" => $_REQUEST["user_name"]
        ];
      }else{
        throw new Exception("No rows were inserted");
      }
      //removed the echo from here
      //echo json_encode($results);
    }else{
      throw new Exception("Prepared statement did not insert records.");
    }

  }catch(Exception $error){
    //add to results array rather than echoing out errors
    $results[] = ["error"=>$error->getMessage()];
  }finally{
    //echo out results
    echo json_encode($results);
  }

  ?>