<?php
require_once 'Models/UtilisateurModel.php';
require_once 'config/database.php';
require_once 'config/session.php';

    if ( is_logged_in())
        {
            redirect_to_page('profile');
        }if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            control($pdo);
        }
        else{
            require_once 'View/connexion/formulaireConnexion.php';
        }

function control($pdo)
{
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if(empty($login) || empty($password))
        {
            $error = "Invalide login ou password";
            require_once 'View/connexion/formulaireConnexion.php';
            return;            
        }
    $find = new UtilisateurModel($pdo);
    $res = $find -> authenticate($login, $password);
    if($res === NULL)
        {
            $error = "Invalide login or password";
            require_once 'View/connexion/formulaireConnexion.php';
            return; 
        }
    $_SESSION['nom'] = $res['nom'];
    $_SESSION['prenom'] = $res['prenom'];
    $_SESSION['email'] = $res['email'];
    $_SESSION['user_id'] = $res['id'];
    $_SESSION['login'] = $res['login'];
       
    redirect_to_page('profile');
}

?>