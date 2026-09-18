<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/formulaireConnexion.css">
    <title>Connexion</title>
</head>

<body>

    <div class="container">

        <h2>Connexion</h2>
        <p class="subtitle">Connectez-vous à votre compte</p>

        <form action="index.php?page=connexion" method="POST">

            <?php if(isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="form-group">
                <label for="login">Login</label>
                <input  type="text" id="login" name="login" placeholder="Votre login"  required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>

            <button type="submit" class="connexion">
                Se connecter
            </button>

        </form>

        <div class="separator">
            <span>ou</span>
        </div>

        <a href="index.php?page=inscription" class="inscription-link">
            Créer un compte
        </a>

    </div>

</body>
</html>