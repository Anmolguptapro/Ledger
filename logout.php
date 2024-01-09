<?php
include_once './common/header.php';
?>

<?php
if ($_SESSION["login_status"]) {
    echo 'YOU HAVE BEEN LOGGED OUT';
    header( "Refresh:3; url=http://localhost/ledger/login.php");
    session_regenerate_id(true);
    session_destroy();
} else {
    echo 'KINDLY LOGIN FIRST';
}

include_once './common/footer.php';

?>