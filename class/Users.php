<?php
include('Connection.php');

class Users extends Dbh
{

    public function register($sanitized_username, $sanitized_password)
    {
        $db = $this->connect();

        $hash_password = password_hash($sanitized_password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO users (username, password, date_created) VALUES (?,?,NOW())");
        $stmt->bind_param("ss", $sanitized_username, $hash_password);
        $result = $stmt->execute();

        return $result;
    }

    public function login($username, $password)
    {
        $db = $this->connect(); 

        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pass = $row['password'];

                if (password_verify($password, $pass)) {
                    $_SESSION['id'] = $row['id'];

                    $redirect = ($_SESSION['id'] === 54) ? '../public/admin/home.php' : '../public/user/home.php';

                    return $redirect;
                } else {
                    return 1; //Incorrect password
                }
            }
        } else {
            return 2; //User not found
        }
    }

    public function total($price, $quantity)
    {
        $total = $price * $quantity;
        return $total;

    }

}
