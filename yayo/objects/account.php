<?php
class Account{
  
    // database connection and table name
    private $conn;
    private $table_name = "account";
  
    // object properties
    public $user_id;
    public $id;
    public $phone_number;
    public $amount;
    public $type;

  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
  
    function read(){
      
        // select all query
       $query=("SELECT amount FROM account WHERE user_id=:user_id");
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $this->user_id);
        // execute query
        $stmt->execute();
      
        return $stmt;
    
}


function topup(){
      
    // select all query
   $query=("SELECT user_id FROM user WHERE phone_number=:phone_number");
    
    $stm = $this->conn->prepare($query);

    $stm->bindParam(":phone_number", $this->phone_number);
    // execute query
    $stm->execute();
    $max_row = $stm->fetch(PDO::FETCH_ASSOC);

$max = $max_row['user_id'];
  
        $add=("UPDATE account SET  amount=amount + :amount  WHERE user_id=$max ");

        $execute_add = $this->conn->prepare($add);

    $execute_add->bindParam(":amount", $this->amount);
   
    $execute_add->execute();

   $new_balance=(" SELECT amount FROM account  WHERE user_id=$max");
   
   $stmt = $this->conn->prepare($new_balance);


  
   $stmt->execute();

    return $stmt;

}



}

?>