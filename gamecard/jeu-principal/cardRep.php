<?php
session_start();
require '../extern/db.ext.php';
require '../afficherCarte/afficheCarte.php';


$theme = $_GET["theme"];
$id = $_SESSION["userId"];



// Pour les carte suivante 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT * FROM jeuactive WHERE id=" . $id . " AND theme='" . $theme . "';";
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        header("Location: cardRep.php?error=sqlerror");
        exit();
    }
    $tab = mysqli_fetch_assoc($res);
    $ordreActuel = $tab["ordreActuel"];
    $ordreSuivant = $tab["ordreSuivant"];
    $nbrErreur = $tab["nbrErreur"];
    $currentScore = $tab["currentScore"];
    $compteur = $tab["compteur"];
    $ordreActuelexp = explode("/", $ordreActuel);
    $a = $ordreActuelexp[0];

    $sql = "SELECT * FROM " . $theme . " WHERE id=" . $a;
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        header("Location: cardRep.php?error=sqlerror");
        exit();
    }
    $tab = mysqli_fetch_assoc($res);
    $reponse = $tab["reponse"];
    $reponse = strtolower($reponse);
    $type = $tab["type"];
    $level = $tab["level"];

    if (isset($_POST["prop"])) {
        $repClient = $_POST["prop"];
        $repClient = strtolower($repClient);
    } else if (isset($_POST["reponse"])) {
        $repClient = $_POST["reponse"];
        $repClient = strtolower($repClient);
    } else {
        $repClient = "";
    }

    if ($repClient !== $reponse) {
        if ($type == "qcm") {
            $nbrErreur++;
            $ordreSuivant = $a . "/" . $ordreSuivant;
            $currentScore -= 30;
        } else {
            $nbrErreur++;
            $ordreSuivant = $a . "/" . $ordreSuivant;
            $currentScore -= 20;
        }
    } else {
        if ($type == "qcm") {
            if ($level == 1) {
                $currentScore += 35;
                $ordreSuivant = $ordreSuivant . $a . "/";
            } else if ($level == 2) {
                $currentScore += 85;
                $ordreSuivant = $ordreSuivant . $a . "/";
            } else {
                $currentScore += 250;
                $ordreSuivant = $ordreSuivant . $a . "/";
            }
        } else {
            if ($level == 1) {
                $currentScore += 65;
                $ordreSuivant = $ordreSuivant . $a . "/";
            } else if ($level == 2) {
                $currentScore += 165;
                $ordreSuivant = $ordreSuivant . $a . "/";
            } else {
                $currentScore += 550;
                $ordreSuivant = $ordreSuivant . $a . "/";
            }
        }
    }

    $newOrdreActuel = [];
    for ($i = 1; $i < count($ordreActuelexp); $i++) {
        $newOrdreActuel[$i - 1] = $ordreActuelexp[$i];
    }
    $ordreActuel = implode("/", $newOrdreActuel);

    $compteur++;

    $sql = "UPDATE jeuactive SET ordreActuel='" . $ordreActuel . "',ordreSuivant='" . $ordreSuivant . "',nbrErreur=" . $nbrErreur . ",currentScore=" . $currentScore . ",compteur=" . $compteur . " WHERE id=" . $id . " AND theme='" . $theme . "';";
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        header("Location: cardRep.php?error=sqlerror");
        exit();
    }

    if (empty($ordreActuel)) {
        header("Location: endofthegame.php?theme=" . $theme);
        exit();
    }
}

// Initialisation de la prmeière carte
$sql = "SELECT * FROM jeuactive WHERE id=" . $id . " AND theme='" . $theme . "';";
$res = mysqli_query($conn, $sql);
if (!$res) {
    header("Location: cardRep.php?error=sqlerror");
    exit();
}

if (mysqli_num_rows($res) == 0) {
    $ordreSuivant = "";
    $ordreActuel = "";
    $sql = "SELECT COUNT(*) FROM " . $theme;
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        header("Location: cardRep.php?error=sqlerror");
        exit();
    }
    $tab = mysqli_fetch_assoc($res);
    $taille = $tab["COUNT(*)"];

    for ($i = 1; $i <= $taille; $i++) {
        $ordreActuel .= $i . "/";
    }
    $sql = "INSERT INTO jeuactive(id, ordreActuel, ordreSuivant, theme, nbrErreur, currentScore, compteur) VALUES(" . $id . ",'" . $ordreActuel . "','" . $ordreSuivant . "','" . $theme . "',0,0,1);";
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        header("Location: cardRep.php?error=sqlerror");
        exit();
    }
}

$sql = "SELECT * FROM jeuactive WHERE id=" . $id . " AND theme='" . $theme . "';";
$res = mysqli_query($conn, $sql);
if (!$res) {
    header("Location: cardRep.php?error=sqlerror");
    exit();
}
$tab = mysqli_fetch_assoc($res);
$ordreActuel = $tab["ordreActuel"];
$ordreActuelexp = explode("/", $ordreActuel);
$a = $ordreActuelexp[0];

$sql = "SELECT question,type FROM " . $theme . " WHERE id=" . $a;
$res = mysqli_query($conn, $sql);
if (!$res) {
    header("Location: cardRep.php?error=sqlerror");
    exit();
}
$tab = mysqli_fetch_assoc($res);
$question = $tab["question"];
$type = $tab["type"];

if ($type == "qcm") {
    $sql = "SELECT * FROM propositions " . $theme . " WHERE theme='" . $theme . "' AND questionId=" . $a;
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        header("Location: cardRep.php?error=sqlerror");
        exit();
    }
    $tab = mysqli_fetch_assoc($res);
    $prop1 = $tab["proposition1"];
    $prop2 = $tab["proposition2"];
    $prop3 = $tab["proposition3"];
    afficherCarteProp($question, $prop1, $prop2, $prop3, $theme, $a, $compteur);
} else {
    afficherCarteTexte($question, $theme, $a, $compteur);
}
