<?php
  require_once "_includes/connect.php";

  // ** v3 adds a select query to see if an email already exists **
  /* pseudo code **
    -receive input
      -check (select) to see if user exists > selectUser();
        -if yes > update > updateData();
        -if no > insert > insertData();
 */

  $results = [];
  $insertedRows = 0;

  //3 functions abstracted from main code
  function selectUser($link){
    //need to pass db $link to the function due to scope
    $query = "SELECT * FROM demo WHERE item = ?";

    if($stmt = mysqli_prepare($link, $query)){
      mysqli_stmt_bind_param($stmt, "s", $_REQUEST["item"]);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      //$results[] = ["mysqli_num_rows" => mysqli_num_rows($result)];
      $results[] = ["mysql_num_rows" => mysqli_num_rows($result)];
      return mysqli_num_rows($result) > 0;

    }else{
      throw new Exception("No item was found");
    }
  }

  function updateData($link){
    $query = "UPDATE demo SET store = ? number_item = ? item = ? user_name = ? WHERE demoID = ?";

    if($stmt = mysqli_prepare($link, $query)){
      mysqli_stmt_bind_param($stmt, "siss", $_REQUEST["store"], $_REQUEST["number_item"], $_REQUEST["item"], $_REQUEST["user_name"], $_REQUEST["demoID"]);
      mysqli_stmt_execute($stmt); 
      
      if (mysqli_stmt_affected_rows($stmt) <= 0) {
        throw new Exception("Error updating data: " . mysqli_stmt_error($stmt));
      }
      $results[] = ["updatedData() affected_rows" => mysqli_stmt_affected_rows($stmt)];
      return mysqli_stmt_affected_rows($stmt);
    }
  }

  function insertData($link){
    $query = "INSERT INTO demo (store, number_item, item, user_name) VALUES (?, ?, ?, ?)";

    if($stmt = mysqli_prepare($link, $query)){
      mysqli_stmt_bind_param($stmt, "siss", $_REQUEST["store"], $_REQUEST["number_item"], $_REQUEST["item"], $_REQUEST["user_name"]);
      mysqli_stmt_execute($stmt);
      $insertedRows = mysqli_stmt_affected_rows($stmt);

      if($insertedRows > 0){
        $results[] = [
            "insertedRows"=>$insertedRows,
            "demoID" => $link->insert_id,
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
    }
  }

//   function deleteRecord($link) {

//     // Prepare and execute the DELETE query
//     $query = "DELETE FROM demo WHERE demoID = ?";
//     $stmt = $link->prepare($query);
//     $stmt->bind_param("s", $_REQUEST["demoID"]); // Assuming demoID is a string, adjust type if it's different
//     $stmt->execute();

//     // Check if the deletion was successful
//     if ($stmt->affected_rows > 0) {
//         echo "Record deleted successfully.";
//     } else {
//         echo "Error deleting record: " . $link->error;
//     }

//     // Close the statement and connection
//     $stmt->close();
//     $link->close();
// }

  // main logic of the application is in this try{} block of code.
  try{
    //see if user has entered data
    if(!isset($_REQUEST["item"])|| !isset($_REQUEST["store"])|| !isset($_REQUEST["user_name"])){
      throw new Exception('Required data is missing i.e. item');
    }else{
      //if they have see if user (email) exists & update data
      if(selectUser($link)){
        $results[] = ["selectUser()" => "called updateData()"];
        $results[] = ["updateData() affected_rows" => updateData($link)];
      }else{
        //if user does not exist, insert the data
        $results[] = ["insertData()" => "called insertData()"];
        $results[] = ["insertData() affected_rows" => insertData($link)];
      }
    }
      
  }catch(Exception $error){
    //add to results array rather than echoing out errors
    $results[] = ["error"=>$error->getMessage()];
  }finally{
    //echo out results
    echo json_encode($results);
  }

  // try{
  //   //see if user has entered data **removed full_name & email
  //   if(!isset($_REQUEST["item"])|| !isset($_REQUEST["store"])|| !isset($_REQUEST["unumber_item"])){
  //     throw new Exception('Required data is missing i.e. item');
  //   }else{
  //     //might as well just go ahead and update
  //     $results[] = ["updateData() affected_rows " => updateData($link)];

  //   }
      
  // }catch(Exception $error){
  //   //add to results array rather than echoing out errors
  //   $results[] = ["error"=>$error->getMessage()];
  // }finally{
  //   //echo out results
  //   echo json_encode($results);
  // }
 

 
?>