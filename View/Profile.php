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

           <p>Nom :
                <?php echo htmlspecialchars($_SESSION['nom'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
            </p>

            <p>Prénom :
                <?php echo htmlspecialchars($_SESSION['prenom'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
            </p>

            <p>Email :
                <?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
            </p>

            <p>Login :
                <?php echo htmlspecialchars($_SESSION['login'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
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