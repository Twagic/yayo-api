<?php
class Company{
  
    // database connection and table name
    private $conn;
    private $table_name = "company";
  
    // object properties
   
    public $id;
    public $name;
    public $payment_code;

  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    function create(){
  
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                      name=:name, payment_code=:payment_code";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
   
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->payment_code=htmlspecialchars(strip_tags($this->payment_code));
     
      
        // bind values
  
        $stmt->bindParam(":name", $this->name);
    
        $stmt->bindParam(":payment_code", $this->payment_code);
     
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }
}






?>