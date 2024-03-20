<?php

// Inclusion du fichier de la classe database
include_once('db/Database.php');

class Users{

    // Connection
    private $conn;

    //Table name
    private $db_table = "users";

    // field database
    public $id;
    public $name;
    public $email;
    public $password;
    public $age;
    public $designation;
    public $created;

    // Db connection
    public function __construct($db){
        $this->conn = $db;
    }

    // GET ALL
    public function getUsers(){
        $sqlQuery = "SELECT id, name, email, age, designation, created FROM " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    public function getAllProducts(){
        $sqlQuery = "SELECT * FROM products";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // CREATE
    public function createUser(){
        $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        name = :name, 
                        email = :email, 
                        password = :password,
                        age = :age, 
                        designation = :designation";

        $stmt = $this->conn->prepare($sqlQuery);
        // bind data
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":designation", $this->designation);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // READ single
public function getSingleUser($name){
    $sql = "SELECT * FROM users WHERE name = :name";

    try {
        // Préparation de la requête
        $stmt = $this->conn->prepare($sql);

        // Exécution de la requête
        $stmt->execute([':name' => $name]);

        // récupération des données
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user;
        } else {
            return "ok"; 
        }
    } catch(PDOException $e) {
        // Gestion de l'erreur
        return $e->getMessage(); 
    }
}


    // UPDATE
    public function updateUser(){
        $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        name = :name, 
                        email = :email, 
                        age = :age, 
                        designation = :designation, 
                        created = :created
                    WHERE 
                        id = :id";

        $stmt = $this->conn->prepare($sqlQuery);
        // bind data
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":designation", $this->designation);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // DELETE
    function deleteUser(){
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

}
