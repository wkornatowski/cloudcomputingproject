<?php
// It handles users what more do you want 

include_once '../error-enable.php';

class User {

    public function login($username, $password) {
        // Use bcrypt here (and db connection yes)
        // save the user id to session
        try {
	        $db = new CloudSql;
            $conn = $db->connection();
            $enc_pass = sha1($password);
            $query = $conn->prepare("SELECT * FROM User WHERE username=:uname AND password=:password LIMIT 1");
            $query->bindParam(':uname', $username);
            $query->bindParam(':password', $password);
            $query->execute();
            $userRow=$query->fetch(PDO::FETCH_ASSOC);
            if($query->rowCount() > 0) {
                $_SESSION['user_id'] = $userRow['user_id'];
                return $userRow['user_id'];
            } else {
                return false;
            }
        } catch(PDOException $e) {
           echo $e->getMessage();
       }
    }

    public function register($username, $password, $confirm_password, $email) {
        $db = new CloudSql;
        $conn = $db->connection();
        printf("hi");
        try {
            if($password === $confirm_password) {
                $new_password = sha1($password, PASSWORD_DEFAULT);
            }
            $stmt = $conn->prepare("INSERT INTO User (username,email,password) VALUES(:uname, :umail, :upass)");
              
            $stmt->bindparam(":uname", $username);
            $stmt->bindparam(":umail", $email);
            $stmt->bindparam(":upass", $password);
	    $stmt->execute();
            return $conn->lastInsertId();
	    $db = null;
        } catch(PDOException $e) {
           echo $e->getMessage();
        }    
    }

    public function is_loggedin() {
        if(isset($_SESSION['user_id'])) {
            return true;
        }
    }

    public function logout() {
        session_start();
        unset($_SESSION['user_session']);
        session_destroy();
        return true;
    }

    public function getUser($user_id) {
        // username is unique in db, so you can just use this to get the user
        
        return $username;
    }
}
?>
