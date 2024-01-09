<?php
include_once './common/header.php';
?>
<?php
if ($_SESSION["login_status"]) {
    echo 'you are already logged in . kindly logout before .';
} else {

    function checkPhoneExist($phone, $conn) {
        $query = "SELECT COUNT(phone) FROM u_c_details WHERE phone = :mobile";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":mobile", $phone);
        $stmt->execute();
        $result = $stmt->fetchAll()[0];
        if ($result['COUNT(phone)'] > 0) {
            return true;
        }
        return false;
    }

    function checkEmailExist($email, $conn) {
        $query = "SELECT COUNT(email) FROM u_c_details WHERE email = :mobile";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":mobile", $email);
        $stmt->execute();

        $result = $stmt->fetchAll()[0];
        if ($result['COUNT(email)'] > 0) {
            return true;
        }
        return false;
    }

    function login($uid, $pswd, $conn) {
        $query = "SELECT id FROM u_c_details WHERE email= :email OR phone = :phone;";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":email", $uid);
        $stmt->bindParam(":phone", $uid);
        $stmt->execute();
        $result = $stmt->fetchAll()[0];
        $id = $result['id'];

        $query = "SELECT password,u_type FROM u_l_details WHERE id = :id;";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetchAll()[0];
        $password = $result['password'];
        $utype = $result['u_type'];

        if ($password == $pswd) {

            $query = "SELECT p_verify,e_verify FROM u_c_details WHERE id= :id;";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll()[0];
            $_SESSION["login_pv"] = $result['p_verify'];
            $_SESSION["login_ev"] = $result['e_verify'];
            $_SESSION["login_status"] = true;
            $_SESSION["login_id"] = $id;
            $_SESSION["login_u_type"] = $utype;
            return true;
        }
        return false;
    }

    $id = $password = null;
    $id_error = $pswd_error = null;
    $error = 0;
    $type = null;
    $login = false;
    if ($_POST):
        echo '<hr>';

        if (filter_var($_POST["id"], FILTER_VALIDATE_EMAIL)):
            $type = "email";
            $id = $_POST["id"];
        else:
            if (preg_match("/^[0-9]{10}$/", $_POST["id"])):
                $type = "phone";
                $id = $_POST["id"];
            else:
                $id_error = "Invalid User Id Format";
                $error++;
            endif;
        endif;

        $pswd = $_POST["password"];

        if ($error == 0):
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "ledger";
            $conn = null;
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $check = 0;
                if ($type == "phone" && !checkPhoneExist($id, $conn)) {
                    $id_error = "Phone is not registered";
                    $check++;
                }

                if ($type == "email" && !checkEmailExist($id, $conn)) {
                    $id_error = "Email is not  registered";
                    $check++;
                }

                if ($check == 0) {
                    if (login($id, $pswd, $conn)) {
                        header("Location: http://localhost/ledger/index.php");
                        $login = true;
                    } else {
                        echo 'Username or password do not match';
                        $login = false;
                    }
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            } finally {
                $conn = null;
            }
        endif;

    endif;

    if ($login == false):
        ?>

        <form action="" method="post" >
            Phone or EMail: <input type="text" name="id"><br>
            <small style="color: red"><?= $id_error ?></small><br>
            Password: <input type="password" name="password"><br>
            <small style="color: red"><?= $pswd_error ?></small><br>
            <input type="submit">
        </form>
    
        <?php
    endif;
}
include_once './common/footer.php';
?>