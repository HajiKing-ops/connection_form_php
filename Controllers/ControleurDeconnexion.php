<?php
require_once 'config/database.php';
require_once 'config/session.php';


/*Logout */

if(isset($_GET['page']) && $_GET['page'] === 'deconnexion')
{
     $_SESSION= [];
    session_destroy();
    if(ini_get("session.use_cookies"))
        {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]);
        }
        redirect_to_page();
}


?>