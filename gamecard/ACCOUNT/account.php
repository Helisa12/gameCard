<?php
session_start();
require '../extern/db.ext.php';



$id = $_SESSION["userId"];
// $status = 'Begginer';


$sql = "SELECT bestScore FROM gamecard WHERE id=" . $id . ";";
$res = mysqli_query($conn, $sql);
if (!$res) {
    header("Location: ../ACCOUNT/account.php?error=sqlerror");
    exit();
}
$tab = mysqli_fetch_assoc($res);
$bestScore = $tab["bestScore"];

if ($bestScore > 1200) {
    $sql = "UPDATE gameCard SET status='Expert' WHERE id=" . $id;
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        header("Location: ../ACCOUNT/account.php?error=sqlerror");
        exit();
    }
} else {
    $sql = "UPDATE gameCard SET status='Beginner' WHERE id=" . $id;
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        header("Location: ../ACCOUNT/account.php?error=sqlerror");
        exit();
    }
}
$sql = "SELECT status FROM gamecard WHERE id=" . $id . ";";
$res = mysqli_query($conn, $sql);
if (!$res) {
    header("Location: ../ACCOUNT/account.php?error=sqlerror");
    exit();
}
$tab = mysqli_fetch_assoc($res);
$status = $tab["status"];

?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="account.css">
    <title>SIGN UP</title>
</head>

<body>
    <div class="FORM">
        <div class="FORM-text">
            <header><a href="../index.php">Game Card</a></header>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "success") {
                    echo '<p class="msg-erreur">Vos données ont bien été enregistrée !</p>';
                }
            }
            ?>
            <img src="../img-site/bo.png" width="150px">
            <p>Best Score :</p><br>
            <p><?php echo $bestScore; ?></p>
            <p>Niveau</p><br>
            <p><?php echo $status; ?></p>
            <h1>Account</h1>
            <form action="../extern/account.ext.php" method="post">
                <label>Update Name</label> <br>
                <input type="text" name="prenom" placeholder="Modifier Votre Prénom"> <br>
                <label>Update Username</label> <br>
                <input type="text" name="username" placeholder="Modifier Votre Nom D'Utilisateur">
                <br>
                <button type="submit" name="validate-submit">Valider</button>
            </form>
            <form action="../extern/delete.ext.php" method="post">
                <button type="submit" name="delete-submit">Delete Account</button>
            </form>
        </div>
    </div>
</body>

</html>