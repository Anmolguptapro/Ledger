<?php
include_once './common/header.php';
?>
<?php
if ($_SESSION["login_status"]) {
    echo 'you are already logged in . kindly logout before .';
} else {
    function phash($pass){
        $option =[
            'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
            'time_cost'=> 4,
            'thread'=> 4,
        ];
    $pass = password_hash($pass, PASSWORD_ARGON2ID, $option);
    return $pass;
    }
    function checkPhoneExist($phone, $conn)
    {
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

    function registeration($name, $email, $phone, $dob, $gender, $password, $conn)
    {
        $query = "INSERT INTO u_c_details(phone,email) VALUES(:phone,:email);";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->execute();
        $query = "SELECT id FROM u_c_details WHERE email= :email AND phone = :phone;";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->execute();
        $result = $stmt->fetchAll()[0];
        $id = $result['id'];
        $query = "INSERT INTO u_l_details(id,password,l_u_on,r_on) VALUES(:id,:password,:now,:now);";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":password", $password);
        $time = (new DateTime)->getTimestamp();
        $stmt->bindParam(":now", $time);
        $password = phash($password);
        $stmt->execute();
        $query = "INSERT INTO u_p_details VALUES(:id,:name,:dob,:gender);";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $time = strtotime($dob);
        $stmt->bindParam(":dob", $time);
        $stmt->bindParam(":gender", $gender);
        $stmt->execute();
    }

    function checkEmailExist($email, $conn)
    {
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

    $name = $email = $phone = $gender = $dob = $password = null;
    $name_error = $email_error = $phone_error = $dob_error = null;
    $error = 0;
    $reg = false;
    if ($_POST):
        echo '<hr>';

        if (preg_match("/^[a-zA-Z ]{2,50}$/", $_POST["name"])):
            $name = $_POST["name"];
        else:
            $name_error = "Invalid Name";
            $error++;
        endif;

        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)):
            $email = $_POST["email"];
        else:
            $email_error = "Invalid Email Format";
            $error++;
        endif;

        if (preg_match("/^[0-9]{10}$/", $_POST["phone"])):
            $phone = $_POST["phone"];
        else:
            $phone_error = "Invalid Email Format";
            $error++;
        endif;

        if ($_POST["dob"] != null): //pattern
            $date = explode('-', $_POST["dob"]);
            if (checkdate($date[1], $date[2], $date[0])):
                $date1 = date_create($_POST["dob"]);
                $date2 = date_create();
                if (date_diff($date1, $date2)->y >= 18):
                    $dob = $_POST["dob"];
                else:
                    $dob_error = "You are not above 18";
                    $error++;
                endif;
            else:
                $dob_error = "Invalid Date";
                $error++;
            endif;
        else:
            $dob_error = "Invalid Date Format";
            $error++;
        endif;

        $gender = $_POST["gender"];
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
                if (checkPhoneExist($phone, $conn)) {
                    $phone_error = "Phone is =alredy registered";
                    $check++;
                }

                if (checkEmailExist($email, $conn)) {
                    $email_error = "Email is =alredy registered";
                    $check++;
                }

                if ($check == 0) {
                    registeration($name, $email, $phone, $dob, $gender, $pswd, $conn);
                    $reg = true;
                    echo 'REGISTRATION SUCCESFUL';
                    header("Refresh:3; url=http://localhost/ledger/login.php");
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            } finally {
                $conn = null;
            }
        endif;

    endif;

    if ($reg == false):
        ?>

        <form action="" method="post" margin-top="20px">
            Name: <input type="text" name="name" value="<?= $name ?>"><br>
            <small style="color: red">
                <?= $name_error ?>
            </small><br>
            Phone: <input type="tel" name="phone"><br>
            <small style="color: red">
                <?= $phone_error ?>
            </small><br>
            Email: <input type="text" name="email" value="<?= $email ?>"><br>
            <small style="color: red">
                <?= $email_error ?>
            </small><br>
            Date of Birth: <input type="date" name="dob" value="<?= $dob ?>"><br>
            <small style="color: red">
                <?= $dob_error ?>
            </small><br>
            Gender: <input type="radio" name="gender" value="M"> Male <input type="radio" name="gender" value="F"> Female<br>
            <small></small><br>
            Password: <input type="password" name="password"><br>
            <small></small><br>
            Confirm Password:<input type="password" name="cpassword"><br>
            <small></small><br>
            <input type="submit">
        </form>


        <?php
    endif;
}
include_once './common/footer.php';
?>