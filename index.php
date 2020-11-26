<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Accueil</title>
</head>
<?php 
    session_start();
    //si bouton deconnexion appuyé alors destroy
    if(isset($_POST['deconnexion'])) {
    session_destroy();
    header ('location:connexion.php');
}
?>
<body class="index">
    <h1>Bienvenue 
        <?php //si l'utilisateur est connecté
            if (isset($_SESSION['login'])) {
                //alors affiche son login
                echo  $_SESSION['login'];
            }
        ?>
    </h1>
    <!--un boutton déconnexion apparait si l'utilisateur est connecté-->
    <?php //si l'utilisateur est connecté
        if (isset($_SESSION['login']))
        // echo mon bouton déconnexion
        echo '<form action="index.php" method="post"><input type="submit" name="deconnexion" id="deco" value="Déconnexion"></form>';
    ?>

    <div class="liens">
        <?php //si l'utilisateur est connecté
        if (isset($_SESSION['login']))
        // echo les liens necessaire
        echo ('<a href="profil.php"><i class="fas fa-user-edit"></i><p>Modifier mon profil</p></a>
        <a href="discussion.php"><i class="fas fa-comments"></i><p>Voir le chat</p></a>');
        //si l'utilisateur est connecté
        else if (!isset($_SESSION['login']))
        // echo les liens necessaire
        echo ('<a href="inscription.php"><i class="fas fa-user-plus"></i><p>Inscription</p></a>
        <a href="connexion.php"><i class="fas fa-user-check"></i><p>Connexion</p></a>');
        ?>
    </div>
</body>
</html>