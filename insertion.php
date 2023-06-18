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

$success = false;

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $designation = $_POST['designation'];
    $idCat = $_POST['idCat'];
    $prixTTC = $_POST['prixTTC'];
    $forfaitLivraison = $_POST['forfaitLivraison'];

    // Insérer les données dans la base de données
    $images = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];

        // Chemin de destination où vous souhaitez enregistrer le fichier
        $destination = 'media/images/' . $file['name'];

        // Déplacer le fichier téléchargé vers le dossier de destination
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $images = $destination;
            echo 'Le fichier a été téléchargé avec succès.';
        } else {
            echo 'Une erreur s\'est produite lors du téléchargement du fichier.';
        }
    } else {
        echo 'Une erreur s\'est produite lors du téléchargement du fichier.';
    }

    ajouterProduit($designation, $idCat, $prixTTC, $forfaitLivraison, $images);

    $success = true;
}

// Récupérer les catégories de produits depuis la base de données
$categories = listerCategories();

// Récupérer les forfaits de livraison depuis la base de données
$forfaitsLivraison = listerForfaitsLivraison();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertion d'éléments</title>

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
          <h2 style="font-size:50px"><strong>Bienvenue sur la page insertion !</strong></h2>
        </div>
      </div>
    </article>
  </div>


  <div class="bg-light pt-5">
    <div id="cv" class="contenu pt-5">
      <div class="container">
        <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg" id="futur">
          <div class="col-lg-12 p-3 p-lg-5 pt-lg-3" id="formulaire">

    <form method="POST" action="insertion.php" enctype="multipart/form-data">
                <legend > Insérer un élément </legend>
        <label class="mt-2" for="designation">Désignation :</label>
        <input class="mt-2" type="text" name="designation" id="designation" required><br>

        <label class="mt-2" for="idCat">Catégorie :</label>
        <select class="mt-2" name="idCat" id="idCat" required>
            <?php foreach ($categories as $categorie): ?>
                <option value="<?php echo $categorie['idCat']; ?>"><?php echo $categorie['intitule']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label class="mt-2" for="prixTTC">Prix TTC :</label>
        <input class="mt-2" type="number" name="prixTTC" id="prixTTC" step="0.01" required><br>

        <label class="mt-2" for="forfaitLivraison">Forfait de livraison :</label>
        <select class="mt-2" name="forfaitLivraison" id="forfaitLivraison" required>
            <?php foreach ($forfaitsLivraison as $forfait): ?>
                <option value="<?php echo $forfait['idForfait']; ?>"><?php echo $forfait['description']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label class="mt-2" for="image">Image :</label>
        <input class="mt-2" type="file" name="image" id="image" required><br>

        <input class="mt-2" type="submit" value="Insérer">
        <p><a href="index.php">Retour à la page d'accueil</a></p>
    </form>

    <?php if ($success){ ?>
        <p>Succès</p>
    <?php   

    afficheTableauAssocImages(request_produit("SELECT * FROM produit;"));?>
    <br>
    <?php
    echo "<p>Vos informations ont bien étés modifiées.</p>";
       
       } ?>
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
    </div>
  </div>
  <script src="js/js_personnel/navbar.js"></script>
  <script src="js/js_personnel/index.js"></script>
</body>
</html>
