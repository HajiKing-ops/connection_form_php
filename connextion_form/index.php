<?php
require_once 'config/database.php';
require_once  'config/session.php';

$page = $_GET['page'] ?? 'connexion'; // by default is connexion

switch($page)
{
    case 'connexion':
        require_once 'Controllers/ControleurConnexion.php';
        break;
    case 'profile':
        require_once 'Controllers/ControleurProfile.php';
        break;
    case 'inscription':
        require_once 'Controllers/ControleurInscription.php';
        break;
    default:
        require_once 'Controllers/ControleurConnexion.php';
        break;
        
}
?>