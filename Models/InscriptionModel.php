<?php
class InscriptionModel
{
    private $pdo;
    public function __construct($pdo)
    {
        $this -> pdo = $pdo;
    }
    public function createUser($nom, $prenom, $email, $login, $mdp)
    
    {
        $query = "Select * from Utilisateur where login = :login";
        $stm = $this -> pdo -> prepare($query);
        $stm -> bindParam(":login", $login);
        $stm -> execute();
        if ($stm -> fetchColumn())
            {
                return[
                    'success' => false,
                    'error' => "ce login exists deja "
                ];
            }else 
            {
                $req = "insert into Utilisateur (nom, prenom, email, login, mdp) values (:nom, :prenom, :email, :login, :mdp)";
                $stmt = $this -> pdo -> prepare($req);
                $stmt -> bindParam(":nom", $nom);
                $stmt -> bindParam(":prenom", $prenom);
                $stmt -> bindParam(":email", $email);
                $stmt -> bindParam(":login", $login);
                $hash = password_hash($mdp, PASSWORD_DEFAULT);
                $stmt -> bindParam(":mdp", $hash);
                $stmt -> execute();
                return ['success' => true];
            }
        
    }
}


?>
