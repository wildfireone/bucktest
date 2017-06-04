<?php

function login($username, $password, $conn)
{
    try {
        $sql = "SELECT username, password FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', htmlentities($username), PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $hash = @$results[0]['password'];

        if (isset($results)) {
            if (password_verify($password, $hash)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return "Login failed: " . $e->getMessage();
    }
}

function isEmailValid($email)
{
    if (count($email) <= 250) {
        if (count($email) <> 0) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function addUser($conn, $username, $password)
{
    $sql = "INSERT INTO users VALUES (:username, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', htmlentities($username), PDO::PARAM_STR);
    $stmt->bindParam(':password', md5(htmlentities($password)), PDO::PARAM_STR);

    try {
        $stmt->execute();
        $results = $stmt->fetchAll();
        return true;
    } catch (PDOException $e) {
        return "Query failed: " . $e->getMessage();
    }
}

?> 