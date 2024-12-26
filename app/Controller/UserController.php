<?php
// Include the DBh class and config
require_once("config.php");
require_once("Dbh.php");

class UserController {

    private $dbh;
    
    public function __construct() {
        $this->dbh = new DBh(); // Establish database connection
    }

    // Fetch user data from the database
    public function getUserDetails($userId) {
        $sql = "SELECT * FROM user WHERE UserId = ?";
        $stmt = $this->dbh->getConn()->prepare($sql);
        $stmt->bind_param("i", $userId); // Bind the userId parameter
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Return user details
    }

    // Update user details
    public function updateUserDetails($userId, $fname, $lname, $email, $phone, $gender, $governamnte) {
        $sql = "UPDATE user SET Fname = ?, Lname = ?, email = ?, phone = ?, gender = ?, governamnte = ? WHERE UserId = ?";
        $stmt = $this->dbh->getConn()->prepare($sql);
        $stmt->bind_param("ssssssi", $fname, $lname, $email, $phone, $gender, $governamnte, $userId);
        return $stmt->execute(); // Execute the update query
    }

    // Delete user account
    public function deleteUserAccount($userId) {
        $sql = "DELETE FROM user WHERE UserId = ?";
        $stmt = $this->dbh->getConn()->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute(); // Execute the delete query
    }

    // Close the connection
    public function __destruct() {
        $this->dbh = null;
    }
}
?>
