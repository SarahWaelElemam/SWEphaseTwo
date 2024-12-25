<?php
require_once("../../db/Dbh.php");

class LoginSignupModel {
    private $conn;
    public function __construct() {
        $db = new Dbh();
        $this->conn = $db->getConn(); // Use getConn to ensure persistent connection
    
        if (!$this->conn || $this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }
    
    public function handleSignup($data) {
        extract($data);

        if (!empty($FName) && !empty($LName) && !empty($Email) && !empty($Password) && !empty($ConfirmPassword) &&
            !empty($Government) && !empty($PhoneNumber) && !empty($Gender) && !empty($DOB)) {

            if ($Password !== $ConfirmPassword) {
                echo "<script>alert('Passwords do not match!');</script>";
                return false;
            }

            $checkEmail = $this->conn->prepare("SELECT Email FROM User WHERE Email = ?");
            if (!$checkEmail) {
                die("Prepared statement error: " . $this->conn->error);
            }

            $checkEmail->bind_param("s", $Email);
            $checkEmail->execute();
            $result = $checkEmail->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('Email already exists!');</script>";
                return false;
            } else {
                $stmt = $this->conn->prepare("INSERT INTO User (FName, LName, Email, Password, Government, Phone, Gender, BirthDate, Role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $defaultRole = "Customer";
                    $stmt->bind_param("sssssssss", $FName, $LName, $Email, $Password, $Government, $PhoneNumber, $Gender, $DOB, $defaultRole);
                    if ($stmt->execute()) {
                        $_SESSION = [
                            "ID" => $this->conn->insert_id,
                            "FName" => $FName,
                            "LName" => $LName,
                            "Email" => $Email,
                            "Government" => $Government,
                            "PhoneNumber" => $PhoneNumber,
                            "Gender" => $Gender,
                            "DOB" => $DOB
                        ];
                        header("Location: homepage.php");
                        exit();
                    } else {
                        echo "<script>alert('Error saving to the database.');</script>";
                    }
                }
            }
        } else {
            echo "<script>alert('Please fill in all fields!');</script>";
        }
    }

    public function handleLogin($data) {
        extract($data);

        if (!empty($Email) && !empty($Password)) {
            $stmt = $this->conn->prepare("SELECT * FROM User WHERE Email = ?");
            if (!$stmt) {
                die("Prepared statement error: " . $this->conn->error);
            }

            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                if ($Password === $row['Password']) {
                    $_SESSION = [
                        "ID" => $row["ID"],
                        "FName" => $row["FirstName"],
                        "LName" => $row["LastName"],
                        "Email" => $row["Email"],
                        "Government" => $row["Government"],
                        "PhoneNumber" => $row["Phone"],
                        "Gender" => $row["Gender"],
                        "DOB" => $row["BirthDate"]
                    ];
                    header("Location: homepage.php");
                    exit();
                } else {
                    echo "<script>alert('Invalid Email or Password');</script>";
                }
            } else {
                echo "<script>alert('Invalid Email or Password');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Please fill in both email and password!');</script>";
        }
    }
}

?>
