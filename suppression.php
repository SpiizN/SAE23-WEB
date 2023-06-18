<?php
session_start();

// Inclure les fonctions nécessaires
include 'fonctions.php';

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['status'])) {
    redirection("connexion.php"); // Rediriger vers la page de connexion s'il n'est pas connecté
}

// Vérifier si l'utilisateur a les droits d'accès
if ($_SESSION['status'] != 'admin') {
    redirection('index.php'); // Rediriger vers la page index.php s'il n'a pas les droits d'accès
}

$erreur_captcha = ''; // Initialisation de la variable d'erreur

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erreur_captcha = "";
    // Vérifier le captcha
    $captcha = $_POST['captcha'];
    if (!verifierCaptcha($captcha)) {
        $erreur_captcha = "Le code captcha saisi est incorrect.";
    } else {
        // Vérifier si des éléments ont été sélectionnés pour suppression
        if (isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
            // Récupérer les éléments sélectionnés
            $selectedItems = $_POST['selected_items'];

            // Supprimer chaque élément sélectionné
            foreach ($selectedItems as $id) {
                supprimerProduit($id);
            }

            // Rediriger vers la page suppression.php pour afficher à nouveau le tableau mis à jour
            redirection('suppression.php');
        }
    }
}

// Afficher le tableau HTML avec les éléments de la base de données
// Récupérer tous les éléments de la table produit
$produits = getProduits();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression d'éléments</title>

    <link rel="stylesheet" href="css/css_personnel/css_global.css">
    <link rel="stylesheet" href="css/css_bootstrap/bootstrap.min.css">

    <style>
      table {
        border-collapse: collapse;
        width: 80%;
      }

      table th, table td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
      }

      table th {
        background-color: #f2f2f2;
      }

      table tr:nth-child(even) {
        background-color: #dddddd;
      }
    </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container px-4 px-lg-5">
      <a class="navbar-brand" href="index.php">PROJET WEB</a>
      <button
        class="navbar-toggler navbar-toggler-right"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarResponsive"
        aria-controls="navbarResponsive"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        Menu
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Accueil</a>
          </li>
          <?php 
            if ($_SESSION['status'] == 'admin') {
          ?>
          <li class="nav-item">
            <a class="nav-link" href="insertion.php">Insertion</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="modification.php">Modification</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="suppression.php">Suppression</a>
          </li>
          <?php
            }
          ?>
          <li class="nav-item">
            <a class="nav-link" href="connexion.php?action=deconnect">Se déconnecter</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="accueil-resp" id="top">
    <img src="media/images/bg.jpg" alt="Background" id="bg_image" style="width: 100%;">
    <article class="text-home">
      <div class="container px-4 px-lg-5 d-flex h-100 justify-content-center">
        <div class="text-box">
          <h2 style="font-size:50px"><strong>Bienvenue sur la page suppression !</strong></h2>
        </div>
      </div>
    </article>
  </div>

  <div class="bg-light pt-5">
    <div id="cv" class="contenu pt-5">
      <div class="container">
        <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg" id="futur">
          <div class="col-lg-12 p-3 p-lg-5 pt-lg-3" id="formulaire">

    <form method="post" action="suppression.php">
    <fieldset>
    <legend > Supprimer un élément </legend>
        <?php if (!empty($produits)) : ?>
            <table >
                <tr>
                    <th>ID</th>
                    <th>Catégorie</th>
                    <th>Prix TTC</th>
                    <th>Désignation</th>
                    <th>Forfait Livraison</th>
                    <th>Images</th>
                    <th>Sélectionner</th>
                </tr>
                <?php foreach ($produits as $produit) : ?>
                    <tr>
                        <td><?php echo $produit['idPdt']; ?></td>
                        <td><?php echo $produit['idCat']; ?></td>
                        <td><?php echo $produit['prixTTC']; ?></td>
                        <td><?php echo $produit['designation']; ?></td>
                        <td><?php echo $produit['forfaitLivraison']; ?></td>
                        <td><?php echo $produit['images']; ?></td>
                        <td>
                            <!-- Crée une case à cocher pour sélectionner un élément à supprimer -->
                            <input type="checkbox" name="selected_items[]" value="<?php echo $produit['idPdt']; ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <!-- Champ de saisie du captcha -->
            <input class="mt-2" type="text" name="captcha"/>
            <!-- Image du captcha -->
            <img src="captcha/image.php" onclick="this.src='captcha/image.php?' + Math.random();" alt="captcha" style="cursor:pointer;">
            <!-- Message d'erreur du captcha -->
            <span class="error"><?php echo $erreur_captcha; ?></span>
            <br>
            <input class="mt-2" type="submit" value="Supprimer les éléments sélectionnés">
        <?php else : ?>
            <p>Aucun élément trouvé dans la base de données.</p>
        <?php endif; ?>
        </fieldset>
    </form>

    <p><a href="index.php">Retour à la page d'accueil</a></p>
            </div>
        </div>
    </div>
  </div>
</div>
<div class="container">
        <footer
          class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top"
        >
          <p class="col-md-4 mb-0 text-muted">&copy; 2022 Company, Inc</p>

          <a
            href="/"
            class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"
          >
            <svg class="bi me-2" width="40" height="32">
              <use xlink:href="#bootstrap" />
            </svg>
          </a>

          <ul class="nav col-md-4 justify-content-end">
            <li class="nav-item">
              <a href="index.php" class="nav-link px-2 text-muted">Accueil</a>
            </li>
            <?php if ($_SESSION['status'] == 'admin') { ?>
            <li class="nav-item">
              <a href="insertion.php" class="nav-link px-2 text-muted">Insertion</a>
            </li>
            <li class="nav-item">
              <a href="modification.php" class="nav-link px-2 text-muted">Modification</a>
            </li>
            <li class="nav-item">
              <a href="Suppression.php" class="nav-link px-2 text-muted">Suppression</a>
            </li>
            <?php } ?>
            <li class="nav-item">
              <a href="connexion.php?action=deconnect" class="nav-link px-2 text-muted">Se déconnecter</a>
            </li>
          </ul>
        </footer>
  </div>
  <script src="js/js_personnel/navbar.js"></script>
  <script src="js/js_personnel/index.js"></script>
</body>
</html>
