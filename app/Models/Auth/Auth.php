<?php

namespace App\Models\Auth;

use App\Models\Query\Query;
use PDO;

abstract class Auth extends Query
{
    protected static function Log_in()
    {
        // Sanitize inputs
        $firstname = trim($_POST['firstname']);
        $lastname  = trim($_POST['lastname']);
        $sirname   = trim($_POST['sirname']);
        $admission = trim($_POST['admission']);
        $index     = trim($_POST['index']);
        $year     = trim($_POST['year']);
        $password  = $_POST['password'];

        $username = trim($firstname . " " . $lastname . " " . $sirname);

        // Using self::Connection_resource() (ensure this matches your db_connect.php file)
        $stmt = self::Connection_resource()->prepare("SELECT * FROM login WHERE `admission number`=? AND `index number`=? AND `username`=? AND `year`=?");
        $stmt->execute([$admission, $index, $username, $year]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $user = $result;
            unset($stmt);
            // Verify the hashed password
            if (password_verify($password, $user['password']) && $user['usertype'] == "student") {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);

                $_SESSION['admission'] = $user['admission number'];
                $_SESSION['login_success'] = "Login successful!";
                $get_ip = $_SERVER['REMOTE_ADDR'];

                $sql = self::Connection_resource()->prepare("INSERT INTO `login register` (username, admission, ip) VALUE (?,?,?)");
                $sql->execute([$username, $admission, $get_ip]);
                unset($sql);
                header("location: /dashboard");
                exit();
            } else {
                $message = "Invalid credentials. Please try again.";
            }
        } else {
            $message = "No account found with those details. Please try again with the right credentials.";
        }

        return [
            "message" => $message
        ];
    }
    protected static function Register()
    {
        if (isset($_POST['sign']) && !empty($_POST['lastname'])) {

            //ollect all the form data
            $firstname = trim($_POST['firstname']);
            $lastname  = trim($_POST['lastname']);
            $sirname   = trim($_POST['sirname']);
            $admission = (int)$_POST['admission'];
            $index     = (int)$_POST['index'];
            $year      = (int)$_POST['year'];
            $password  = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $usertype  = "student";

            //heck if the password and comfirm passwords field are the same.
            if ($password !== $confirm_password) {
                $error = "The passwords you entered do not match.";
            } else {
                //Hashing the password 
                $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
                //oncatenate the firstname last name and user name
                $username = trim("$firstname $lastname $sirname");
                $username = htmlspecialchars($username);

                //Check if user exists in the database
                $check = self::Connection_resource()->prepare("SELECT `admission number` FROM login WHERE `admission number` = ? OR `index number` = ?");
                $check->bindParam("1", $admission);
                $check->bindParam("2", $index);
                $check->execute();
                $result = $check->fetch(PDO::FETCH_ASSOC);

                if ($result->num_rows > 0) {
                    $error = "An account with this Admission or Index number already exists.";
                } else {
                    //ploading the user image to the user profile folder and inserting the image link in the database

                    //Final Insert (using $hashed_pass and the correct $sql statement)
                    if (true) {
                        $sql = self::Connection_resource()->prepare("INSERT INTO login (username, `admission number`, `index number`, year, usertype, password) VALUES (?, ?, ?, ?, ?, ?, )");

                        if ($sql->execute([$username, $admission, $index, $year, $usertype, $hashed_pass])) {
                            $_SESSION['sign_success'] = "Registration successful! Please log in.";
                            $message = $username . " admission " . $admission . " has successfully signed up.";
                            $check = self::Connection_resource()->prepare("INSERT INTO notifications (username, admission, message) VALUES (?, ?, ?)");
                            $check->execute([$username, $admission, $message]);
                        } else {
                            $error = "Database error: Could not register user.";
                        }
                    }
                }
            }
        }
    }
    protected static function Pay_physically()
    {
        //payment physically option.
        $admission = $_SESSION['admission'];
        $dept_phy = $_GET['department'] . " status";
        $status = "pending_physical_payment";
        $sql = self::Connection_resource()->prepare("UPDATE studentgeneraldata SET `$dept_phy`=? WHERE `admission number`=?");
        $sql->execute([$status, $admission]);
    }
    protected static function No_debt()
    {
        $admission = $_SESSION['admission'];
        $dept_phy = $_GET['department'] . " status";
        $status = "cleared";
        $sql = self::Connection_resource()->prepare("UPDATE studentgeneraldata SET `$dept_phy`=? WHERE `admission number`=?");
        $sql->execute([$status, $admission]);
    }
}