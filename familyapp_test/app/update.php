<?php
  require_once "_includes/connect.php";

  // ** update.php is a modification of insert_v3.php  **
  // ?? better if integrated with insert_v3.php ??
  /* pseudo code **
    -receive input
      -update record using "demoID" vs "email" in original
 */

  $results = [];
  $insertedRows = 0;

  //just 1 function here... update

  function updateData($link){
    $query = "UPDATE demo SET store = ? number_item = ? item = ? user_name = ? WHERE demoID = ?";

    if($stmt = mysqli_prepare($link, $query)){
      mysqli_stmt_bind_param($stmt, "snssi", $_REQUEST["store"], $_REQUEST["number_item"], $_REQUEST["item"], $_REQUEST["user_name"], $_REQUEST["demoID"]);
      mysqli_stmt_execute($stmt);
      
      if (mysqli_stmt_affected_rows($stmt) <= 0) {
        throw new Exception("Error updating data: " . mysqli_stmt_error($stmt));
      }
      $results[] = ["updatedData() affected_rows man" => mysqli_stmt_affected_rows($stmt)];
      return mysqli_stmt_affected_rows($stmt);
    }
  }


  //main logic of the application is in this try{} block of code.
  try{
    //see if user has entered data **removed full_name & email
    if(!isset($_REQUEST["item"])){
      throw new Exception('Required data is missing i.e. item');
    }else{
      //might as well just go ahead and update
      $results[] = ["updateData() affected_rows " => updateData($link)];

    }
      
  }catch(Exception $error){
    //add to results array rather than echoing out errors
    $results[] = ["error"=>$error->getMessage()];
  }finally{
    //echo out results
    echo json_encode($results);
  }
 
?>