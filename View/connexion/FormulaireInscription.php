<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="public/css/FormulaireInscription.css">

    <title>Inscription</title>
</head>

<body>

    <div class="container">

        <h2>Créer un compte</h2>

        <form action="index.php?page=inscription" method="POST">

            <label for="nom">Nom :</label>
            <input
                type="text"
                id="nom"
                name="nom"
                placeholder="Votre nom"
                required
            >

            <label for="prenom">Prénom :</label>
            <input
                type="text"
                id="prenom"
                name="prenom"
                placeholder="Votre prénom"
                required
            >

            <label for="email">Adresse e-mail :</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="votre@email.com"
                required
            >

            <label for="login">Login :</label>
            <input
                type="text"
                id="login"
                name="login"
                placeholder="Votre login"
                required
            >

            <label for="mdp">Mot de passe :</label>
            <input
                type="password"
                id="mdp"
                name="mdp"
                placeholder="Mot de passe"
                required
            >

            <button type="submit" class="submit">
                S'inscrire
            </button>

            <?php if (isset($error)): ?>
                <p class="error">
                    <?php echo $error; ?>
                </p>
            <?php endif; ?>

        </form>

        <div class="separator">
            <span>ou</span>
        </div>

        <a href="index.php?page=connexion" class="connexion-link">
            Se connecter
        </a>

    </div>

</body>
</html>