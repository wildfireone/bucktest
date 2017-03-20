<?php

// @author Alan Thom (1103803)
// A class to represent a News object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class News {

    // ***** PROPERTIES *****    
    private $id, $title, $subTitle, $author, $date, $mainBody;
    
    // ***** CONSTRUCTOR *****
    function __construct($id = -1) {
        $this->id = $id;
    }
        
    // ***** GETTERS *****
    public function getID() {
        return $this->id;
    }
    public function getTitle() {
        return $this->title;
    }    
    public function getSubTitle() {
        return $this->subTitle;
    }    
    public function getAuthor() {
        return $this->author;
    }     
    public function getDate() {
        return $this->date;
    }    
    public function getMainBody() {
        return $this->mainBody;
    }   
    public function getSummary($value) {
        $output = substr($this->mainBody,0,$value);
        $output .= "...";
        return $output;
    }     
        
    // ***** SETTERS *****    
    public function setID($id) {
        $this->id = $id;
    }    
    public function setTitle($title) {
        $this->title = $title;
    }    
    public function setSubTitle($subTitle) {
        $this->subTitle = $subTitle;
    }    
    public function setAuthor($author) {
        $this->author = $author;
    }    
    public function setDate($date) {
        $this->date = $date;
    }    
    public function setMainBody($mainBody) {
        $this->mainBody = $mainBody;
    }
    
    // ***** OTHER METHODS *****    
    public function getAllDetails($conn) {
        
        $sql = "SELECT * FROM news WHERE id = " . $this->getID();
        $stmt = $conn->prepare($sql);
        //$stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            // Iterate through the results and set the details
            foreach ($results as $row) {
                $this->setID($row["id"]);
                $this->setTitle($row["title"]);
                $this->setSubTitle($row["subTitle"]);
                $this->setAuthor($row["author"]);
                $this->setDate($row["date"]);
                $this->setMainBody($row["mainBody"]);
            }
            return true;
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function setAllDetails($id, $title, $subTitle, $author, $date, $mainBody) {
        $this->setID($id);
        $this->setTitle($title);
        $this->setSubTitle($subTitle);
        $this->setAuthor($author);
        $this->setDate($date);   
        $this->setMainBody($mainBody);
    }
    
    public function create($conn) {        
            $sql = "INSERT INTO news (title, subTitle, author, date, mainBody) VALUES (:title, :subTitle, :author, CURDATE(), :mainBody)";
            $stmt = $conn->prepare($sql);
        
            $stmt->bindParam(':title', $this->getTitle(), PDO::PARAM_STR);
            $stmt->bindParam(':subTitle', $this->getSubTitle(), PDO::PARAM_STR);
            $stmt->bindParam(':author', $this->getAuthor(), PDO::PARAM_STR);
            $stmt->bindParam(':mainBody', $this->getMainBody(), PDO::PARAM_STR);
        
        try {    
            $stmt->execute();
            $this->setID($conn->lastInsertId());
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }
    }    
    
    public function update($conn) {
        try {
            $sql = "UPDATE news SET title = :title, subTitle = :subTitle, author = :author, date = CURDATE(), mainBody = :mainBody WHERE id = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);
            $stmt->bindParam(':title', $this->getTitle(), PDO::PARAM_STR);
            $stmt->bindParam(':subTitle', $this->getSubTitle(), PDO::PARAM_STR);
            $stmt->bindParam(':author', $this->getAuthor(), PDO::PARAM_STR);
            $stmt->bindParam(':mainBody', $this->getMainBody(), PDO::PARAM_STR);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Update failed: " . $e->getMessage();
        }
    }    
    
    public function delete($conn) {
        try {
            $sql = "DELETE FROM news WHERE id = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);
            
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Delete failed: " . $e->getMessage();
        }
    }
    
    public function getAllNews($conn) {        
        $sql = "SELECT id FROM news ORDER BY date DESC";
        
        $stmt = $conn->prepare($sql);
        
        try {           
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    public function getMostRecent($conn,$limit) {
        $sql = "SELECT id FROM news ORDER BY Date DESC LIMIT :limit";

        $stmt = $conn->prepare($sql);        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        try {           
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    public function doesExist($conn) {
        $sql = "SELECT id FROM news WHERE id = :id LIMIT 1";
        
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            if (count($results) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }
    
    public function isInputValid($title, $subTitle, $mainBody) {
        if ($this->isTitleValid($title) && $this->isSubTitleValid($subTitle) && $this->isMainBodyValid($mainBody)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isTitleValid($title) {
        if ((strlen($title) > 0) && (strlen($title) <= 250)) {
            return true;
        } else { 
            return false;
        }
    }
            
    public function isSubTitleValid($subTitle) {
        if (strlen($subTitle) <= 250) {
            return true;
        } else { 
            return false;
        }
    }
            
    public function isMainBodyValid($mainBody) {
        if ((strlen($mainBody) > 0) && (strlen($mainBody) <= 5000)) {
            return true;
        } else { 
            return false;
        }
    }

}

?>
