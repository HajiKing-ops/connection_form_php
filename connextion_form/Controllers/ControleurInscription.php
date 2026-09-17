<?php

require_once 'config/database.php';
require_once 'Models/InscriptionModel.php';


  if($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
        require_once 'View/connexion/FormulaireInscription.php';
        exit();
    }
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $login = trim($_POST['login'] ?? '');
    $mdp = $_POST['mdp']?? '';

  if(empty($nom) || empty($prenom) || empty($email) || empty($login) || empty($mdp))
    {
      $error = "les champs est obligateur";
      require_once 'View/connexion/FormulaireInscription.php';
      return ;
    }

    $create = new InscriptionModel($pdo);
    $create -> createUser($nom, $prenom, $email, $login, $mdp);
    $success = "compt est cree";

    require_once 'View/connexion/FormulaireConnexion.php';
    return;
?>