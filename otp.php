<?php
include_once './common/header.php';
?>
<?php
if (!$_SESSION["login_status"]) {
    echo 'you Should Login First.';
} else {
    if ($_SESSION["login_u_type"] == 'U') {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ledger";
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            
        }
        $eotp_error = $potp_error = null;
        if ($_POST) {
            if (isset($_POST['e_otp'])) {
                $query = "SELECT OTP FROM u_otp WHERE id= :id AND TYPE='E';";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":id", $_SESSION["login_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll()[0];
                $otp = $result['OTP'];
                if ($_POST['e_otp'] == $otp) {
                    $query = "UPDATE u_c_details SET e_verify = 'Y' WHERE id=:id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(":id", $_SESSION["login_id"]);
                    $stmt->execute();
                    //delete otp E
                } else {
                    $eotp_error = "ERROR";
                }
            } elseif (isset($_POST['p_otp'])) {
                $query = "SELECT OTP FROM u_otp WHERE id= :id AND TYPE='P';";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":id", $_SESSION["login_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll()[0];
                $otp = $result['OTP'];
                if ($_POST['p_otp'] == $otp) {
                    $query = "UPDATE u_c_details SET P_verify = 'Y' WHERE id=:id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(":id", $_SESSION["login_id"]);
                    $stmt->execute();
                    //delete otp P
                } else {
                    $potp_error = "ERROR";
                }
            }
        }

        $query = "SELECT p_verify,e_verify FROM u_c_details WHERE id= :id;";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $_SESSION["login_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll()[0];
        $e = $result['e_verify'];
        $p = $result['p_verify'];
        if ($e == 'N') {
            if (!$_POST) {
                $otp = random_int(100000, 999999);
                $query = "INSERT into u_otp VALUES(:id,:otp,'E');";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":id", $_SESSION["login_id"]);
                $stmt->bindParam(":otp", $otp);
                $stmt->execute();
            }
            ?>

            <form action="" method="post">
                Email: <input type="text" name="e_otp">
                <?=$eotp_error?>
                <input type="submit">
            </form>


            <?php
        }
        if ($p == 'N') {
            if (!$_POST) {
                $otp = random_int(100000, 999999);
                $query = "INSERT into u_otp VALUES(:id,:otp,'P');";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":id", $_SESSION["login_id"]);
                $stmt->bindParam(":otp", $otp);
                $stmt->execute();
            }
            ?>

            <form action="" method="post">
                Phone : <input type="text" name="p_otp">
                <?=$potp_error?>
                <input type="submit">
            </form>


            <?php
        }
    } else {
        echo 'You are admin';
    }
}
