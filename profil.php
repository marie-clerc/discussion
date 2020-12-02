<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier votre Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
    // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirige-le vers la page de connexion
    if (!isset($_SESSION['login'])) 
    {
        header ('location: connexion.php');
    }
        
    $id = $_SESSION['id'];

    // si le bouton "update" est appuyé
    if (isset($_POST['update'])) 
    {
        //définition des variables :
        $login = $_POST['login'];
        $password = $_POST['password'];
        $newpass = $_POST['new_pass'];
        $confirm = $_POST['confirm_new_pass'];

        //on vérif si le login a changé et s'il est déjà prit dans la bdd
        if ((isset($_POST['login'])) && (isset($_SESSION['login'])) && (($_POST['login']) !=($_SESSION['login'])))
        {
            //on se connecte à la base de données:
            $db = mysqli_connect('localhost','root', '', 'discussion');
            $sql = "SELECT * FROM `utilisateurs` WHERE `login`='$login'";
            //on fait la requête dans la bd pour rechercher si ces données existent:
            $query = mysqli_query($db,$sql);
            $var = mysqli_fetch_all($query);
            // si il y a un résultat, mysqli_num_rows() nous donnera alors 1
            // si mysqli_num_rows() retourne 0 c'est qu'il a trouvé aucun résultat
            if(count($var) >0)
            {
                echo ' <div class="signup_link">login existe déjà <br><a href="profil.php">Modifer mon profil</a><br><a href="index.php">Accueil</a></div>';
                exit;
            }// fin login exisxt déjà
        }// fin isset changement du login

        //je verif si le password est bon:
        //on se connecte à la base de données:
        $db = mysqli_connect('localhost','root', '', 'discussion');
        //je fait la requête pour le password qui correspont au login. 
        $query1 = mysqli_query($db, "SELECT password FROM `utilisateurs` WHERE id = $id");
        //je vais créer un tableau avec mon résultat
        $row = mysqli_fetch_array($query1);
        //je transforme ma ligne password (ligne de la bdd) en variable
        $hash = $row['password'];

        //sécurise le mdt, du coup c'est le hashed qu'il faudra rentrée dans la bdd
        $hashpass = password_hash($newpass, PASSWORD_BCRYPT);

        //je vérif si post password et le password dans bdd : row password, sont les mêmes
        if(password_verify($password, $hash)) //toujours dans cet ordre 
        {
            /* problem
            il faut faire deux boucles différentes 
            un pour le nouveau mdp est vide
            l'autre pour quand c'est aps vide */

            //on regarde s'il y a un nouveau mdp
            if ((!empty($_POST['new_pass'])) || !empty($_POST['confirm_new_pass']))
            {
                //vérifier si le nouveau mdp est assez long
                if (strlen($_POST["new_pass"]) <3) 
                {
                    echo 'pas assez de caractères pour le nouveau mdp';
                }
                //on vérifie si nouveaux mdp sont identiques par vérifier s'ils sont différents
                else if($_POST["new_pass"] != $_POST["confirm_new_pass"] )
                {
                    echo 'les nouveaux mots de passe sont différents';
                }
                else
                {
                    //on se connecte à la base de données:
                    $db = mysqli_connect('localhost','root', '', 'discussion');
                    $sql = "UPDATE `utilisateurs` SET `login`= '$login', password='$hashpass' WHERE `id` = '$id'";
                    // Requête de modification d'enregistrement dans la bd
                    $query2 = mysqli_query ($db, $sql);
                        
                    //on redéfini les session avec les nouvelles informations (si on ne fait pas ça les modificatoins ne seront pas visible sur le form)
                    $_SESSION['login'] = $login;
                        
                    //s'assurer que la requ^te a marché, car pas de redirection avec header location
                    if ($query2) {
                        echo 'la modification a été prise en compte';
                    }
                    else {
                        echo 'la modification a échouée';
                    }
                }
            }//fin nouveau mdp
            

            else if (empty($_POST['new_pass']))
            {
                //on se connecte à la base de données:
                $db = mysqli_connect('localhost','root', '', 'discussion');
                $sql = "UPDATE `utilisateurs` SET `login`= '$login' WHERE `id` = '$id'";
                // Requête de modification d'enregistrement dans la bd
                $query2 = mysqli_query ($db, $sql);
                
                //on redéfini les session avec les nouvelles informations (si on ne fait pas ça les modificatoins ne seront pas visible sur le form)
                $_SESSION['login'] = $login;
                
                //s'assurer que la requ^te a marché, car pas de redirection avec header location
                if ($query2) {
                    echo 'la modification a été prise en compte';
                }
                else {
                    echo 'la modification a échouée';
                }
            }// fin changement dans bdd quand new pass is empty

        }// fin password correct avec password verify
        else {
            echo 'mdp incorrect';
        }

    } //fin du isset post update
?>
<body>
    <!--Cette page possède un formulaire permettant à l’utilisateur de modifier son
    login et son mot de passe.-->

    <!--formulaire de modification des informations du user-->
    <div class="center">
        <h1>Modifier mon Profil</h1>

        <form action="profil.php" method="post">
        
            <div class="txt_field">
                <input type="text" name="login" value="<?php echo ($_SESSION['login']);?>">
                <span></span>
                <label for="login">Login</label> <!--champs login dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="password" name="password" placeholder="entrez votre mdp">
                <span></span>
                <label for="password">Votre mot de passe actuel</label> <!--champs password dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="password" name="new_pass">
                <span></span>
                <label for="new_pass">Nouveau mot de passe</label>
            </div>

            <div class="txt_field">
                <input type="password" name="confirm_new_pass">
                <span></span>
                <label for="confirm_new_pass">Confirmez votre nouveau mot de passe</label>
            </div>

            <input type="submit" name="update" value="Mettre à jour"> <!--mon bouton inscription-->

            <div class="signup_link">
                <a href="index.php">Accueil</a>
            </div>

        </form>
    </div>
</body>
</html>
