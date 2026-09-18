<?php
session_start();

function redirect_to_page($page = 'connexion')
{
    header("Location: index.php?page=$page");
    exit();
}
function is_logged_in()
{
    $has_login = false;
    if(isset($_SESSION['user_id']))
    {
        $has_login = true;
    }
    return $has_login;
}

function require_login()
{
    if(!is_logged_in())
        {
            redirect_to_page();
        }
}

function get_session_user()
{
    if(!is_logged_in())
        {
            return null;
        }
    return[
        'id' => $_SESSION['user_id'],
        'nom' => $_SESSION['nom'],
        'prenom' => $_SESSION['prenom'],
        'email' => $_SESSION['email'],
        'login' => $_SESSION['login'],
    ];
}
?>