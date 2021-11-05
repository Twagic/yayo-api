<?php
class User{
  
    // database connection and table name
    private $conn;
    private $table_name = "user";
    private $account="account";
    private $agency="agent";
  
    // object properties
    public $user_id;
    public $name;
    public $phone_number;
    public $email;
    public $password;
    public $role;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    function create(){
  
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name, phone_number=:phone_number, email=:email, password=:password, role=:role";
                  
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->phone_number=htmlspecialchars(strip_tags($this->phone_number));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->role=htmlspecialchars(strip_tags($this->role));
      
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);
      
        // execute query
       
       
        if($stmt->execute()){
            //fetch the new user's id            
            $stmt = $this->conn->query("SELECT LAST_INSERT_ID()");
            $lastId = $stmt->fetchColumn();
                    
            $query2 = "INSERT INTO account (user_id, amount, type)
                      VALUES ('$lastId', '0', 'standard')";
                        $prep =$this->conn->prepare($query2);
                               
                        $prep->execute();
                      if($this->role ==="agent"){
                            $agentcode= rand();
                        $query3 = "INSERT INTO agency (user_id, code)
                      VALUES ('$lastId', '$agentcode')";
                        $prep =$this->conn->prepare($query3);
                               
                        $prep->execute();
                      
                      }
          
          
            return true;

        }
   
        return false;
          
    }
}


// read products
function read(){
  
    // select all query
    $query = "SELECT
                user_id,name,phone_number, email, password,role
            FROM
                " . $this->table_name . " ";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
}


// create product


?>