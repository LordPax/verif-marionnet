<?php
include 'include/config.php';
// include 'include/auth.php';

if (!isset($_SESSION['connect'])) {
?>

<html>
    <head>
        <title>Connexion</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" type="text/css" href="include/design.css">
    </head>
    <body>
        <section>
            <div class="connect">
                <form class="auth" action="include/auth.php" method="post">
                    <input type="text" name="login" id="login" placeholder="Login" required>
                    <input type="password" name="pass" id="pass" placeholder="Password" required>
                    <input type="submit" name="sub" value="Connexion" />
                </form>
            </div>
        </section>
    </body>
</html>

<?php
}
else {
    header("Location: $domain/baremePage.php");
}
?>