<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/formulaireConnexion.css">
    <title>Document</title>
</head>
<body>
    <form action="index.php?page=connexion" method="POST" class="hero">
        <?php if(isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        <label>Login</label><br></br>
        <input type="text" name="login" required><br></br>
         <label>password</label><br></br>
        <input type="password" name ="password" required><br></br>
        <button type="Submit" id="submit">submit</button><br></br>
    </form>

    <form action="index.php?page=inscription" method="GET">
        <Button>incription</Button>
    </form>

</body>