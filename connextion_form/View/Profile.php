<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="public/css/Profile.css">

    <title>Profil</title>
</head>

<body>

    <div class="container">

        <h1>Mon profil</h1>

        <div class="profile-info">

            <p>
                <strong>Nom :</strong>
                <?php echo $nom; ?>
            </p>

            <p>
                <strong>Prénom :</strong>
                <?php echo $prenom; ?>
            </p>

            <p>
                <strong>Email :</strong>
                <?php echo $email; ?>
            </p>

            <p>
                <strong>Login :</strong>
                <?php echo $login; ?>
            </p>

        </div>

        <form action="index.php?page=deconnexion" method="POST">
            <button type="submit" class="deconnexion">
                Se déconnecter
            </button>
        </form>

    </div>

</body>
</html>