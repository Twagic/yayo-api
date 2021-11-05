<?php
class Transaction{
  
    // database connection and table name
    private $conn;
    private $table_name = "transaction";
  
    // object properties
    public $user_id;
    public $status;
    public $id;
    public $amount;
    public $recipient_number;
    public $payment_code;
    public $company_code;
    public $phone_number;
    public $payment_date;

  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
   

function transfer(){
  
    // select user who is transferring the funds
    $query1=("SELECT user_id FROM user WHERE phone_number=:phone_number");
    
    $stm = $this->conn->prepare($query1);

    $stm->bindParam(":phone_number", $this->phone_number);
    // execute query
    $stm->execute();
    //fetch the value
    $max_row = $stm->fetch(PDO::FETCH_ASSOC);
    $sender = $max_row['user_id'];
  
        $subtract_funds=("UPDATE account SET  amount=amount - :amount  WHERE user_id=$sender");

        $execute_subtractiom = $this->conn->prepare($subtract_funds);

    $execute_subtractiom->bindParam(":amount", $this->amount);
   
    $execute_subtractiom->execute();

    // select user who is receivng the funds
    $query2=("SELECT user_id FROM user WHERE phone_number=:recipient_number");
    
    $receiving= $this->conn->prepare($query2);

    $receiving->bindParam(":recipient_number", $this->recipient_number);
    // execute query
    $receiving->execute();
    $max = $receiving->fetch(PDO::FETCH_ASSOC);

$receiver = $max['user_id'];
  
        $add_funds=("UPDATE account SET  amount=amount + :amount  WHERE user_id=$receiver ");

        $execute_add = $this->conn->prepare($add_funds);

    $execute_add->bindParam(":amount", $this->amount);
   
    $execute_add->execute();

    $new_balance=(" SELECT amount FROM account  WHERE user_id=$sender");
   
    $stmt = $this->conn->prepare($new_balance);
 
    //$stmt->bindParam(":amount", $this->amount);
   
    $stmt->execute();

    $record=(" INSERT INTO transaction (user_id, amount,status,payment_date)
    VALUES ('$sender', ':amount', 'completed',date('Y-m-d H:i:s'))");
     $record_transaction->bindParam(":amount", $this->amount);
   $record_transaction = $this->conn->prepare($record);
   $record_transaction->bindParam(":amount", $this->amount);
   $record_transaction->execute();
    return $stmt;
}
function utility(){
  
    // select user who is transferring the funds
    $query1=("SELECT user_id FROM user WHERE phone_number=:phone_number");
    
    $stm = $this->conn->prepare($query1);

    $stm->bindParam(":phone_number", $this->phone_number);
    // execute query
    $stm->execute();
    //fetch the value
    $max_row = $stm->fetch(PDO::FETCH_ASSOC);
    $sender = $max_row['user_id'];
  
        $subtract_funds=("UPDATE account SET  amount=amount - :amount  WHERE user_id=$sender");

        $execute_subtractiom = $this->conn->prepare($subtract_funds);

    $execute_subtractiom->bindParam(":amount", $this->amount);
   
    $execute_subtractiom->execute();
    $query2=("SELECT id FROM company WHERE payment_code=:company_code");
    
    $com = $this->conn->prepare($query2);

    $com->bindParam(":company_code", $this->company_code);
    // execute query
    $com->execute();
    //fetch the value
    $max_row = $com->fetch(PDO::FETCH_ASSOC);
    $company_id = $max_row['id'];

    
    $record=(" INSERT INTO transaction (user_id, amount,status,payment_date)
    VALUES ('$sender', ':amount', 'completed',date('Y-m-d H:i:s'))");
   $record_transaction = $this->conn->prepare($record);

   $record_transaction->execute();
   $trans_id = $this->conn->query("SELECT LAST_INSERT_ID()");
   $lastId = $trans_id->fetchColumn();
   $record2=(" INSERT INTO bills (transaction_id,company_id, amount,payment_date)
    VALUES ('$lastId','$company_id', ':amount',date('Y-m-d H:i:s'))");
   $record_bill = $this->conn->prepare($record2);

   $record_bill->execute();
    $new_balance=(" SELECT amount FROM account  WHERE user_id=$sender");
   
    $stmt = $this->conn->prepare($new_balance);
 
    //$stmt->bindParam(":amount", $this->amount);
   
    $stmt->execute();

    
    return $stmt;
} 
}






?>