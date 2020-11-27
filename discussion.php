<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<!--Sur cette page, les utilisateurs connectés peuvent voir l’ensemble des
messages dans un fil de discussion. En dessous du fil de discussion se
trouvent un champs contenant le message et un bouton permettant de
l’envoyer. Les utilisateurs non connectés souhaitant accéder à cette page
sont redirigés vers la page de connexion.-->
<?php
    // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirige-le vers la page de connexion
    if (!isset($_SESSION['login'])) {
        header ('location: connexion.php');
    }
    
    // si le bouton "send" est appuyé
    else if (isset($_POST['send']))
    {
        //on récupère les infos et on les transforme en variables
        $message = $_POST['message'];
        $id = $_SESSION['id'];
        $date = date('y-m-j');

        //on vérifie si le message n'est pas vide
        if (empty($_POST['message']))
        {
            echo 'entrer votre message';
        }
        if (strlen($_POST['message'])> 140)
        {
            echo '140 caractères max. svp';
        }
        //si le message n'est pas vide
        else if (isset ($_POST['message'])) 
        {
          //on se connecte à la base de données:
          $db = mysqli_connect('localhost','root', '', 'discussion');
          //on fait la requête pour mettre le message dans la bdd
          $query1 = mysqli_query($db,"INSERT INTO `messages`(`message`, `id_utilisateur`, `date`) VALUES (\"$message\",\"$id\",\"$date\")");
        }
    }
?>
<body id="discussion">
    <!--un fil de discussion-->
    <div class="thread">
        <?php
            $db = mysqli_connect('localhost','root', '', 'discussion');
            $query2 = mysqli_query($db,"SELECT messages.message as Messages, utilisateurs.login as Logins, messages.date as Date FROM messages, utilisateurs WHERE utilisateurs.id = messages.id_utilisateur");
            //echo les messages dans la bdd  avec id utilisateur = login;
            while ($message = mysqli_fetch_assoc($query2)) //afficher les values tant qu'il y en a plus.
            {
                echo '<div class="gris">Posté le ' .$message['Date']. ' par ' .$message['Logins']. '</div><br>'.$message['Messages']. '<br><br>';
            } 
        ?>
    </div>
    <!--En dessous du fil de discussion se trouvent un champs contenant le message et un bouton permettant de
    l’envoyer-->
    <form action="discussion.php" id="formthread" method="post">
        <div class="post">
            <input type="text" name="message" placeholder="votre message ici (140 caractères)">
            <input type="submit" name="send" id="chatbttn" value="Envoyer">
        </div>
        
        <div class="signup_link">
                <a href="index.php">Accueil</a>
        </div>
    </form>
    
</body>
</html>