<?php
    session_start();
    include('fonctions.php');
    if (empty($_SESSION)) {
      redirect('connexion.php', 0);
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="css/css_personnel/css_global.css">
    <link rel="stylesheet" href="css/css_bootstrap/bootstrap.min.css">
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
              <h2 style="font-size:50px"><strong>Bienvenue sur notre site !</strong></h2>
            </div>
          </div>
        </article>
      </div>
      
    <div id="main">
      <?php if (empty($_GET)) { ?>
        <div class="container px-4 py-5" id="custom-cards">
          <h1><strong>Nos produits</strong></h1>
          <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
            <?php
              $tab_produits = request_produit('SELECT idPdt id, designation produit, intitule categorie, forfaitlivraison.description livraison, prixTTC, images FROM produit INNER JOIN categorieproduit ON produit.idCat = categorieproduit.idCat INNER JOIN forfaitlivraison ON produit.forfaitlivraison = forfaitlivraison.idForfait;');
              foreach ($tab_produits as $ligne_produits) {
            ?>
            <div class="col" onclick="redirection(<?php echo $ligne_produits['id']; ?>)">
              <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg" style="background-image: url(<?php echo $ligne_produits['images'] ?>); background-size: cover;">
                <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                  <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold" style="color: black;"><?php echo $ligne_produits['produit'] ?></h3>
                  <ul class="d-flex list-unstyled mt-auto">
                    <li class="me-auto">
                      <img src="<?php echo $ligne_produits['images'] ?>" alt="Bootstrap" width="32" height="32" class="rounded-circle border border-white">
                    </li>
                    <li class="d-flex align-items-center me-3" style="color: black;">
                      <svg class="bi me-2" width="1em" height="1em"><use xlink:href="#geo-fill"/></svg>
                      <small><strong><?php echo $ligne_produits['categorie']; ?></strong></small>
                    </li>
                    <li class="d-flex align-items-center" style="color: black;">
                      <svg class="bi me-2" width="1em" height="1em"><use xlink:href="#calendar3"/></svg>
                      <small><strong><?php echo $ligne_produits["prixTTC"] ?>€</strong></small>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <?php
              }
            ?>
          </div>
        </div>
      <?php } elseif (!empty($_GET) && isset($_GET['view'])) {
        $infos_produits = request_produit("SELECT idPdt id, designation produit, intitule categorie, forfaitlivraison.description livraison, prixTTC, images FROM produit INNER JOIN categorieproduit ON produit.idCat = categorieproduit.idCat INNER JOIN forfaitlivraison ON produit.forfaitlivraison = forfaitlivraison.idForfait WHERE idPdt = " . $_GET['view'] . ";");
        foreach ($infos_produits as $ligne) {
          ?>
            <div class="container my-5">
              <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg">
                <div class="col-lg-7 p-3 p-lg-5 pt-lg-3">
                  <h1 class="display-4 fw-bold lh-1 text-body-emphasis"><?php echo $ligne['produit']; ?></h1>
                  <p class="lead mt-3">Catégorie: <?php echo $ligne['categorie']; ?> <br> Prix: <?php echo $ligne['prixTTC']; ?>€</p>
                  <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4 mb-lg-3">
                    <a href="index.php#main"><button type="button" class="btn btn-primary btn-lg px-4 me-md-2 fw-bold mt-3">Choisir un autre produit</button></a>
                  </div>
                </div>
                <div class="col-lg-4 offset-lg-1 p-0 overflow-hidden">
                    <img style="width: 80%; margin: 5%;" class="rounded-lg-3" src="<?php echo $ligne['images']; ?>" alt="Image de <?php echo $ligne['produit']; ?>" width="720">
                </div>
              </div>
            </div>
          <?php
          }
        } 
      ?>
    </div>
        
    <form method="GET" action="#categorie">
      <label for="categorie">Catégorie :</label>
      <select id="categorie" name="categorie">
        <option value="">Toutes les catégories</option>
        <option value="1">Nourriture</option>
        <option value="2">Électronique</option>
        <option value="3">Cuisine</option>
        <option value="4">Pharmacie</option>
        <option value="5">Pétrole</option>
        <option value="6">Construction</option>
        <option value="7">Chimique</option>
        <option value="8">Textile</option>
        <option value="9">Automobile</option>
        <option value="10">Manufacture</option>
      </select>
      
      <label for="forfait">Forfait de livraison :</label>
      <select id="forfait" name="forfait">
        <option value="">Tous les forfaits</option>
        <option value="1">- de 1kg</option>
        <option value="2">- de 5kg</option>
        <option value="3">- de 10kg</option>
        <option value="4">- de 15kg</option>
        <option value="5">- de 30kg</option>
        <option value="6">- de 50kg</option>
        <option value="7">- de 75kg</option>
        <option value="8">- de 100kg</option>
        <option value="9">- de 150kg</option>
        <option value="10">+ de 150kg</option>
      </select>
      <button type="submit">Filtrer</button>
    </form>
    <?php
    
    // Vérifier si le formulaire a été soumis
    if (isset($_GET['categorie']) && isset($_GET['forfait'])) {
      // Récupérer les valeurs du formulaire
      $categorie = $_GET['categorie'];
      $forfait = $_GET['forfait'];
    
      // Filtrer les produits en fonction des valeurs sélectionnées
      $tab_produits_filtre = filtrerProduits($categorie, $forfait);
    
      // Vérifier s'il y a des produits à afficher
      if (!empty($tab_produits_filtre)) {
          // Afficher les produits
          foreach ($tab_produits_filtre as $ligne_produits_filtre) {
              ?>
              <article class="article_produit">
                  <h2><?php echo $ligne_produits_filtre["produit"]; ?></h2>
                  <img src="<?php echo $ligne_produits_filtre['images']; ?>" alt="Image de <?php echo $ligne['designation']; ?>" style="width:30%">
                  <h5>Prix : <?php echo $ligne_produits_filtre["prixTTC"] ?>€</h5>
              </article>
              <?php
          }
      } else {
          echo "Aucun produit ne correspond aux critères de filtrage.";
      }
    }
    ?> 
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
