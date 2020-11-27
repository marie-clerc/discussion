<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inscription</title>
</head>
<?php
    // Initialiser la session
    session_start();
    // si le bouton "inscription" est appuyé
    if(isset($_POST['inscription']))
    {
        //on vérifie si le login est déjà prit ou non
        if (isset($_POST['login']))
        { 
            //on transforme les $post en variable
            $login = $_POST['login'];
            //on se connecte à la base de données:
            $db = mysqli_connect('localhost','root', '', 'discussion');
            //on fait la requête dans la bd pour rechercher si ces données existent:
            $query = mysqli_query($db,"SELECT * FROM `utilisateurs` WHERE `login`='$login'");
            // si il y a un résultat, mysqli_num_rows() nous donnera alors 1
            // si mysqli_num_rows() retourne 0 c'est qu'il a trouvé aucun résultat
            if(mysqli_num_rows($query) == 0) 
            { 
                //on parcourt le formulaire
                if (isset($_POST['login']) && isset($_POST['password']))
                {
                    // et on transforme les $post en variable
                    $login = $_POST['login'];
                    $password = $_POST['password'];
                    //sécurise le mdt, du coup c'est le hashed qu'il faudra rentrée dans la bdd
                    $hashpass = password_hash($password, PASSWORD_BCRYPT);

                    //on verifie si les deux mots de passe sont identiques
                    if ( $_POST['confirm_pass'] != $_POST['password'] )
                    {
                        echo "Les 2 mots de passe sont différents";
                    }
                    //on vérifie que les input ne sont pas vides
                    else if (strlen($login) < 3 || strlen($password) < 3)
                    {
                        echo 'pas assez de caractères';
                    }
                    else
                    {
                        //on se connecte à la base de données:
                        $db = mysqli_connect ('localhost','root', '', 'discussion');
                        //on fait la requête dans la bd pour insérer les nouvelles infos:
                        $query1 = mysqli_query ($db, "INSERT INTO `utilisateurs`(`login`, `password`) VALUES (\"$login\",\"$hashpass\")");
                        //on redirige sur la page connexion.php quand c'est terminer.
                        header ('location:connexion.php');
                    }
                }
            }
            else 
            {
                echo "Le login est déjà prit";
            }
        }
    }
?>
<body>

    <!--Le formulaire doit contenir l’ensemble des champs présents dans la table
    “utilisateurs” (sauf “id”) ainsi qu’une confirmation de mot de passe. Dès
    qu’un utilisateur remplit ce formulaire, les données sont insérées dans la
    base de données et l’utilisateur est redirigé vers la page de connexion.-->

    <!--formulaire d'inscription-->
    <div class="center">
        <h1>Inscription</h1>

        <form action="inscription.php" method="POST">

            <div class="txt_field">
                <input type="text" name="login" required>
                <span></span>
                <label for="login">Login</label> <!--champs login dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="password" name="password" required>
                <span></span>
                <label for="password">Mot de passe</label> <!--champs password dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="password" name="confirm_pass" required>
                <span></span>
                <label for="confirm_pass">Confirmez votre mot de passe</label> <!--une confirmation de mot de passe-->
            </div>

            <input type="submit" name="inscription" value="S'inscrire" action="connexion.php"><!--mon bouton inscription-->

            <div class="signup_link">
                Déjà membre? <a href="connexion.php">Connexion</a> <br>
                <a href="index.php">Accueil</a>
            </div>
        </form>
    </div>
</body>
</html>