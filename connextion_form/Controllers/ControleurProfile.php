<?php
require_once 'config/session.php';
require_once 'config/database.php';
require_login();


$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$email = $_SESSION['email'];
$login = $_SESSION['login'];

require_once 'View/profile.php';
?>