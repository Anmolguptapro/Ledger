<?php
include_once './common/header.php';
if ($e_verify == false) {
    echo 'KINDLY <a href="otp.php">CLICK HERE</a> TO VERIFY YOUR ACCOUNT';
} else {
    ?>

    <html>

    <head>
        <title>Home</title>
        <link rel="stylesheet" href="./common/style.css">
    </head>

    <body class="home-body">
        <h1>Welcome to Ledger.</h1>
        <p>Your personal ledger for easy and efficient accounting</p>
        <section id="features">
            <h2 class="key">Key Features</h2>
            <ul>
                <li>Easy-to-use ledger for transactions</li>
                <li>Automated calculation of balances</li>
                <li>Secure and cloud-based storage</li>
                <li>Access your accounts from anywhere</li>
            </ul>
        </section>
        <section id="testimonial">
            <h2>What Our Users Say</h2>
            <blockquote>
                "Ledger has made accounting so much simpler for my business. I can't imagine managing without it!"
            </blockquote>
            <cite>- Happy User</cite>
        </section>

    </body>

    </html>


























    <?php
}
include_once './common/footer.php';
?>