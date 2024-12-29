<?php
// app/model/UserModel.php

class UserModel {
    private $db;

    public function __construct() {
        // Correct path to the database connection file
        require_once __DIR__ . '/../db/Dbh.php'; 
        $this->db = new DBh(); // Instantiate the DBh class
    }

    public function getUserById($userId) {
        error_log("User ID in session: " . $userId); // Log user ID being fetched
    
        $query = "SELECT User_ID, FName, LName, Email, BirthDate, Phone, Government, Gender, 
                  DATE_FORMAT(created_at, '%d %M %Y') as join_date 
                  FROM users WHERE User_ID = ?";
    
        // Use mysqli prepared statements to execute queries
        $conn = $this->db->getConn();
    
        // Check if the connection was successful
        if (!$conn) {
            error_log("Database connection failed");
            return ['error' => 'Database connection failed'];
        }
    
        // Prepare the statement
        if ($stmt = $conn->prepare($query)) {
            // Bind the parameter
            $stmt->bind_param("i", $userId); // "i" indicates that the parameter is an integer
    
            // Execute the query
            $stmt->execute();
    
            // Bind result variables
            $stmt->bind_result($userId, $fname, $lname, $email, $birthDate, $phone, $government, $gender, $joinDate);
    
            // Debugging: Log the user ID and query execution
            error_log("Fetching user with ID: $userId");
    
            // Fetch the result
            if ($stmt->fetch()) {
                // Log successful fetch
                error_log("User found: $fname $lname");
                // Return the result
                return [
                    'User_ID' => $userId,
                    'FName' => $fname,
                    'LName' => $lname,
                    'Email' => $email,
                    'BirthDate' => $birthDate,
                    'Phone' => $phone,
                    'Government' => $government,
                    'Gender' => $gender,
                    'join_date' => $joinDate
                ];
            } else {
                // Log if no user was found
                error_log("No user found with ID: $userId");
                return ['error' => 'User not found']; // Return error message
            }
        } else {
            error_log("Query preparation failed: " . $conn->error);
            return ['error' => 'Query preparation failed'];
        }
    }
    
    
    

    public function updateUser($userId, $data) {
        $query = "UPDATE users SET 
                  FName = ?, 
                  LName = ?, 
                  Email = ?, 
                  Phone = ?, 
                  Government = ?, 
                  Gender = ? 
                  WHERE User_ID = ?";

        // Use mysqli prepared statements to execute queries
        $conn = $this->db->getConn();

        // Prepare the statement
        if ($stmt = $conn->prepare($query)) {
            // Bind the parameters
            $stmt->bind_param("ssssssi", 
                $data['fname'], 
                $data['lname'], 
                $data['email'], 
                $data['phone'], 
                $data['government'], 
                $data['gender'], 
                $userId
            );

            // Execute the query
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }

            // Close the statement
            $stmt->close();
        } else {
            return false;
        }
    }

    public function deleteUser($userId) {
        $query = "DELETE FROM users WHERE User_ID = ?";

        // Use mysqli prepared statements to execute queries
        $conn = $this->db->getConn();

        // Prepare the statement
        if ($stmt = $conn->prepare($query)) {
            // Bind the parameter
            $stmt->bind_param("i", $userId);

            // Execute the query
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }

            // Close the statement
            $stmt->close();
        } else {
            return false;
        }
    }
}    