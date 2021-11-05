<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/transaction.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$transaction = new Transaction($db);

// query products
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->phone_number)  &&
    !empty($data->company_code)  &&
    !empty($data->amount)
)
{
  
    // set product property values
    $transaction->phone_number = $data->phone_number;
    $transaction->company_code= $data->company_code;
    $transaction->amount = $data->amount;


$stmt = $transaction->utility();
$num = $stmt->rowCount();
  
   
// check if more than 0 record found
if($num>0){
  
    // products array
    $products_arr=array();
    $products_arr["records"]=array();
  
    // retrieve our table contents

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $product_item=array(
           
            "Bill paid succesfully.Your new balance is" => $amount,
          
        
        );
  
        array_push($products_arr["records"], $product_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($products_arr);
}
  

       
    
  
    // if unable pay, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "error paying up."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to pay. Data is incomplete."));
}
?>