<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/FormulaireInscription.css">
    <title>Formulaire d'inscription</title>
    
</head>
<body>

<div class="container">
    <h2 class="h2">Créer un compte</h2>
    <form action="index.php?page=inscription" method="POST">
        <label>Nom :</label>
        <input type="text"  name="nom" placeholder="Votre nom" required>

        <label >Prenom :</label>
        <input type="text"  name="prenom" placeholder="Votre prenom" required>

        <label >Adresse e-mail :</label>
        <input type="email"  name="email" placeholder="votre@email.com" required>

        <label >Login :</label>
        <input type="text"  name="login" placeholder="votre login" required>

        <label>Mot de passe :</label>
        <input type="password" name="mdp" placeholder="Mot de passe" required>


        <button type="submit" class = "submit">S'inscrire</button>
                <?php if(isset($error)): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>

    </form>
    <a href="index.php?page=connexion">Sign in</a>
</div>
    
</body>
</html> 