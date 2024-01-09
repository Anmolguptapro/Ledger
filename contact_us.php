<?php
include_once './common/header.php';
$showform = true;
$error = 0;
$error_name = $error_phone = $error_email = null;
$name=$email=$phone = null;
if ($_POST):
    if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['name'])):
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];

        if (!preg_match("/[a-zA-Z ]{2,100}/", $name)):
            $error_name = "INVALID NAME";
        $name=null;
            $error++;
        endif;

        if (!preg_match("/[0-9]{10}/", $phone)):
            $error_phone = "INVALID PHONE";
        $phone = null;
            $error++;
        endif;

        if ($error == 0):
            // SAVE DATA IN DB
            echo "YOU WILL BE CONTACTED SOON";
            $showform = false;
        endif;

    endif;
endif;

if ($showform):
    ?>

    <form action="" method="post">
        Name<sup style="color: red">*</sup>: <input type="text" name="name" value="<?=htmlspecialchars($name)?>"><br>
        <small style="color: red"><?= $error_name ?></small><br>
        Phone<sup style="color: red">*</sup>: <input type="tel" name="phone" value="<?=htmlspecialchars($phone)?>"><br>
        <small style="color: red"><?= $error_phone ?></small><br>
        Email: <input type="email" name="email" value="<?=$email?>"><br>
        <small style="color: red"><?= $error_email ?></small><br>
        <input type="submit">
    </form>

    <?php
endif;
include_once './common/footer.php';
?>