<?php

// @author Alan Thom (1103803)
// A class to represent a Shop object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class Shop {

    // ***** PROPERTIES *****    
    private $id, $name, $description, $price, $quantity;
    
    // ***** CONSTRUCTOR *****
    function __construct($id=-1) {
        $this->id = $id;
    }
        
    // ***** GETTERS *****
    public function getID() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }    
    public function getDescription() {
        return $this->description;
    }    
    public function getPrice() {
        return $this->price;
    }     
    public function getQuantity() {
        return $this->quantity;
    }    
        
    // ***** SETTERS *****    
    public function setID($id) {
        $this->id = $id;
    }    
    public function setName($name) {
        $this->name = $name;
    }    
    public function setDescription($description) {
        $this->description = $description;
    }    
    public function setPrice($price) {
        $this->price = $price;
    }    
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }    
        
    // ***** OTHER METHODS *****    
    public function getAllDetails($conn) {
        $sql = "SELECT * FROM shop WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            // Iterate through the results and set the details
            foreach ($results as $row) {
                $this->setID($row["id"]);
                $this->setName($row["name"]);
                $this->setDescription($row["description"]);
                $this->setPrice($row["price"]);
                $this->setQuantity($row["quantity"]);
            }
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function listAllShopItems($conn) {
        $sql = "SELECT id FROM shop";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();            
            return $results;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function create($conn) {        
            $sql = "INSERT INTO shop (name, description, price, quantity) VALUES (:name, :description, :price, :quantity)";
            $stmt = $conn->prepare($sql);
                
            $stmt->bindParam(':name', $this->getName(), PDO::PARAM_STR);
            $stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
            $stmt->bindParam(':price', $this->getPrice(), PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $this->getQuantity(), PDO::PARAM_INT);
        
        try {
            $stmt->execute();
            $this->setID($conn->lastInsertId());
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }
    } 
    
    public function update($conn) {        
            $sql = "UPDATE shop SET name = :name, description = :description, price = :price, quantity = :quantity WHERE id = :id";
            $stmt = $conn->prepare($sql);
                
            $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);
            $stmt->bindParam(':name', $this->getName(), PDO::PARAM_STR);
            $stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
            $stmt->bindParam(':price', $this->getPrice(), PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $this->getQuantity(), PDO::PARAM_INT);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }
    }     

    public function delete($conn) {        
            $sql = "DELETE FROM shop WHERE id = :id";
            $stmt = $conn->prepare($sql);
                
            $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }
    } 
    
    public function isInputValid($name, $description, $price, $quantity) {
        if ($this->isNameValid($name) && $this->isDescriptionValid($description) && $this->isPriceValid($price) && $this->isQuantityValid($quantity)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isNameValid($name) {
        if (strlen($name) > 0 && strlen($name) <= 50) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isDescriptionValid($description) {
        if (strlen($description) <= 250) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isPriceValid($price) {
        if (is_numeric($price)) {
            if ($price >= 0.0 && $price < 1000) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function isQuantityValid($quantity) {
        if (is_numeric($quantity)) {
            if ($quantity > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }    
    
}

?>
