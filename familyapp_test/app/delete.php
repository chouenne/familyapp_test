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


    // Prepare and execute the DELETE query
    function deleteRecord($link) {
    $query = "DELETE FROM demo WHERE demoID = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("s", $_REQUEST["demoID"]); // Assuming demoID is a string, adjust type if it's different
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $link->error;
    }

    // Close the statement and connection
    $stmt->close();
    $link->close();
}


  //main logic of the application is in this try{} block of code.
  try{
    //see if user has entered data **removed full_name & email
    if(!isset($_REQUEST["item"])){
      throw new Exception('Required data is missing i.e. item');
    }else{
      //might as well just go ahead and update
      $results[] = ["deleteData() affected_rows " => deleteRecord($link)];

    }
      
  }catch(Exception $error){
    //add to results array rather than echoing out errors
    $results[] = ["error"=>$error->getMessage()];
  }finally{
    //echo out results
    echo json_encode($results);
  }
 
?>