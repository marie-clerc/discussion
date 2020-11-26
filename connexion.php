<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
    // Initialiser la session
    session_start();
    // si le bouton "Connexion" est appuyé
    if(isset($_POST['connexion'])) {
        //on parcours le formulaire et on vérifie quand les champs ne sont pas vides
        if (isset($_POST['login']) && isset($_POST['password']))
        { 
            //et on transforme les $post en variable
            $login = $_POST['login']; //ici
            $password = $_POST['password']; //et ici

            //on se connecte à la base de données:
            $db = mysqli_connect('localhost','root', '', 'discussion');
            //on fait la requête dans la bd pour rechercher si ces données existent et correspondent:
            $query = mysqli_query($db,"SELECT * FROM `utilisateurs` WHERE login=\"$login\"");
            //variable necessaire pour récupérer les infos du l'utilisateur, et pour les utiliser sur d'autre page 
            $var = mysqli_fetch_array($query);// résultat mis dans un tableau, une ligne par résultat si xieurs
            
            // si il y a un résultat, mysqli_num_rows() nous donnera alors 1
            // si mysqli_num_rows() retourne 0 c'est qu'il a trouvé aucun résultat
            if(mysqli_num_rows($query) == 0) {
                echo "Le login n'existe pas";
            }
            //si login exist, vérifier le hash password et le password entré par l'utilisateur
            else if (mysqli_num_rows($query) == 1) 
            {
                //je fait la requête pour le password qui correspont au login. 
                $query2 = mysqli_query($db, "SELECT password FROM `utilisateurs` WHERE login=\"$login\"");
                //je vais créer un tableau avec mon résultat
                $row = mysqli_fetch_array($query2);
                //je transforme ma ligne password (ligne de la bdd) en variable
                $hash = $row['password'];
                //je vérif si post password et le password dans bdd : row password, sont les mêmes
                if(password_verify($password, $hash)) //toujours dans cet ordre
                {
                    // on ouvre la session avec $_SESSION et on redéfini toutes les infos du user (utlise pour la page profil, pour péremplir le form)
                    $_SESSION['id'] = $var[0];
                    $_SESSION['login'] = $var[1];
                    $_SESSION['password'] = $var[2];
                    //on redirige sur la page index.php quand c'est terminer.
                    header ('location:index.php');
                }
                else {
                    echo 'mdp incorrect';
                }
            }
        }
    } //https://www.c2script.com/scripts/formulaire-de-connexion-en-php-s3.html
    //var_dump ($_SESSION); pour voir si une seesionà été créée il faut mettre header en comm
?>
<body>
    <!--Le formulaire doit avoir deux inputs : “login” et “password”. Lorsque le
    formulaire est validé, s’il existe un utilisateur en bdd correspondant à ces
    informations, alors l’utilisateur devient connecté et une (ou plusieurs)
    variables de session sont créées.-->

    <!--formulaire de connexion-->
    <div class="center">
        <h1>Connexion</h1>

        <form action="connexion.php" method="post">

            <div class="txt_field">
                <input type="text" name="login" required>
                <span></span>
                <label for="login">Login</label>
            </div>

            <div class="txt_field">
                <input type="password" name="password" required>
                <span></span>
                <label for="password">Mot de passe</label>
            </div>

            <input type="submit" name="connexion" value="Connexion"> <!--mon bouton connexion-->

            <div class="signup_link">
                Pas encore membre? <a href="inscription.php">S'inscrire</a> <br>
                <a href="index.php">Accueil</a>
            </div>

        </form>
    </div>
</body>
</html>