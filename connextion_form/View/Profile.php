<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    
</head>
<body>
    <header> <h1>Profile</h1>  </header>
    <section>
        <div class="container">
            <p> Votre nom : <?php echo $nom ?></p> <br> 
            <p> Votre prenom : <?php echo $prenom ?></p><br>
            <p> Votre email : <?php echo $email ?></p><br> 
            <p> Votre login : <?php echo $login ?></p><br>
        </div>
    </section>
       <section>
        <form action="index.php?page=deconnexion" method="POST">
            
                <Button>Disconnect</Button>
                
                                    
        </form>
    </section>
</body>
</html>