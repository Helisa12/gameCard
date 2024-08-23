<!DOCTYPE html>

<head>
    <title>LOGIN</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="login.css">
</head>

<body>

    <div class="FORM">

        <div class="form-text">

            <header><a href="../index.php">Game Card</a></header>
            <h1>Login</h1>

            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == "wrongpassword") {
                    echo '<p class="msg-erreur">Mot de passe invalide</p>';
                } else if ($_GET['error'] == "dontexist") {
                    echo '<p class="msg-erreur">Cette utilisateur n\'existe pas !</p>';
                }
            }
            ?>

            <form action="../extern/login.ext.php" method="post">
                <label class="un">Username/Mail</label> <br>
                <input type="text" placeholder="Entrer Le Nom D'Utilisateur/Email" name="username" required> <br>

                <label class="un">Password</label> <br>
                <input type="password" placeholder="Entre Ton Mot De Passe" name="password" required> <br>

                <button type="submit" name="login-submit">Log In</button>
            </form>

            <div class="bottom-text">
                <p>DON'T HAVE AN ACCOUNT ? <a href="signup.php" id="signup">SIGN UP</a></p>

            </div>
            <a href="reset-password.php" class="forgot-pwd">FORGOT YOUR PASSWORD ?</a>
        </div>
    </div>
</body>

</html>