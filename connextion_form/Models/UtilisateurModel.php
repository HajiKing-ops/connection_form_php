<?php

class UtilisateurModel
{
    private $pdo;
    
    public function __construct($pdo)
    {
        $this -> pdo = $pdo;
    }

    public function authenticate($login, $password)
    {
        $req = "SELECT * FROM Utilisateur where login =:login";
        $stmt = $this -> pdo -> prepare($req);
        $stmt -> bindParam(":login", $login);
        $stmt -> execute();
        $user = $stmt -> fetch();
        if($user)
            {
                $hash = $user['mdp'];
                $res = password_verify($password, $hash);
                if($res)
                    {
                        return [
                            'id' => $user['id'],
                            'nom' => $user['nom'],
                            'email' => $user['email'],
                            'prenom' => $user['prenom'],
                        ];
                    }
                return null;
            }
    }
}
?>