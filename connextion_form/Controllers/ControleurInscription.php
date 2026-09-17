<?php

require_once 'config/database.php';
require_once 'Models/IncriptionModel.php';


  if($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        require_once 'View/connexion/FormulaireIncription.php';
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
      require_once 'View/connexion/FormulaireIncription.php';
      return ;
    }

    $create = new IncriptionModel($pdo);
    $create -> createUser($nom, $prenom, $email, $login, $mdp);
    $success = "compt est cree";

    require_once 'View/connexion/FormulaireConnexion.php';
    return;
?>