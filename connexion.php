<?php
    session_start();
    include('fonctions.php');
    $redirect=false;
?>
<?php
    $redirect = false;
    $alert = "";
    if (!empty($_POST) && isset($_POST['login'])) {
        // Récupérer le login et l'adresse IP
        $login = $_POST['login'];
        $ip = $_SERVER['REMOTE_ADDR'];

        // Informations par défaut.
        $statut_connexion = false; // statut de connexion (false = échec, true = réussite)
        $statut_personne = ''; // statut de la personne (vide si la connexion a échoué)
        
        if (login($_POST['login'], $_POST['password'])) {
            $statut_connexion = true;
            $_SESSION['ouverture'] = 'Connected';
            $_SESSION['login'] = $_POST['login'];
            $tab_status = request_login("SELECT status_session FROM login WHERE login='".$_POST['login']."';");
            if (firstTabVal($tab_status) == 'admin') {
                $statut_personne = 'admin';
                $_SESSION['status'] = $statut_personne;
            } else {
                $statut_personne = 'etu';
                $_SESSION['status'] = $statut_personne;
            }
            $redirect = true;

        } else {
            $alert ="<div class='alert alert-danger' role='alert'>Mauvaise paire Login / Mot de passe.</div>";
        }

        // Enregistrer les informations dans le fichier de log
        $log = fopen('logs/connexion', 'a'); // Ouverture en mode ajout
        if ($log) {
            $heure = date('Y-m-d H:i:s');
            $statut = ($statut_connexion) ? 'réussie' : 'échouée';
            $message = "$heure - Tentative de connexion $statut - Login: $login - IP: $ip - Statut de la personne: $statut_personne\n";
            fwrite($log, $message);
            fclose($log);
        }
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/css_personnel/connexion.css">
    <link rel="stylesheet" href="css/css_bootstrap/bootstrap.min.css">
</head>
<body>
    <form action="" method="post">
        <fieldset>
            <?php if (empty($_GET)) {
                ?>
                <legend>Se connecter</legend>

                <?php
            } ?>
        </fieldset>
    </form>

    <form action="" method="post">
        <fieldset>
            <legend>Se connecter</legend>
            <?php if ($alert !== "") { echo $alert; }?>
            <div class="input-box"><input type="text" name="login" id="id_input_login" size="15" required placeholder="@login"><br></div>
            <div class="input-box"><input type="password" name="password" id="id_input_password" size="15" required placeholder="@passwrod"><br></div>
            <input type="submit" name="connect" value="Connexion" />
        </fieldset>
    </form>
    <script src="js/js_personnel/connexion.js" type="text/javascript"></script>
    <script src="js/js_bootstrap/bootstrap.bundle.min.js" type="text/javascript"></script>
</body>
</html>

<?php
    if ($redirect == true) {
        redirect('index.php', 0);
    }

    if (!empty($_GET) && isset($_GET) && $_GET['action'] == 'deconnect') {
        $_SESSION = array();
        session_destroy();
        redirect('connexion.php', 0);
    }
?>