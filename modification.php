<!DOCTYPE html>
<?php
    session_start();
    include('fonctions.php');
    if ($_SESSION['status'] != 'admin') {
        redirection('index.php');
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
  <div class="bg-light pt-5">
    <div id="cv" class="contenu pt-5">
      <div class="container">
        <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg" id="futur">
          <div class="col-lg-12 p-3 p-lg-5 pt-lg-3">
            <form action="" method="post">
              <select name="value_produit">
                  <?php
                      $produits = request_produit('SELECT DISTINCT idPdt, designation FROM produit ;');
                      foreach ($produits as $ligne_produit) {
                          echo '<option value='.$ligne_produit['idPdt'].'>'.$ligne_produit['designation'].'</option>';
                      }
                  ?>
              </select>
              <input type="submit" name="connect" value="Choisir" />
              <?php
                  if (!empty($_POST) && isset($_POST['connect']) && $_POST['connect'] == 'Choisir') {
                      $designation = firstTabVal(request_produit("SELECT designation FROM produit WHERE idPdt=".$_POST['value_produit'].";"));
                      $prixTTC = firstTabVal(request_produit("SELECT prixTTC from produit WHERE idPdt = ".$_POST['value_produit'].";"));
                      $idForfait = firstTabVal(request_produit("SELECT idForfait FROM produit INNER JOIN forfaitlivraison ON produit.forfaitlivraison = forfaitlivraison.idForfait WHERE idPdt = ".$_POST['value_produit'].";"));
                      ?>

                          <form action="" method="post">
                              <fieldset>
                                  <legend>Modifier les éléments de <?php echo $designation ?>. </legend>
                                  <label for="id_input_designation">Désignation : </label><input type="text" name="designation" id="id_texterea_designation" size="15" value="<?php echo $designation ?>" placeholder="Designation"><br>
                                  <label for="id_input_prix">Prix : </label><input type="text" name="prix" id="id_texterea_prix" size="15" value="<?php echo $prixTTC ?>" placeholder="Prix"><br>
                                  <label for="id_input_password">Forfait de livraison : </label>
                                  <input type="hidden" name="value_produit" value="<?php echo $_POST['value_produit']; ?>">
                                  <select name="forfait_livraison">
                                      <?php
                                          $tab_produit = request_produit('SELECT description, idForfait FROM forfaitlivraison ;');
                                          foreach ($tab_produit as $ligne) {
                                              $selected = ($ligne['idForfait'] == $idForfait) ? 'selected' : '';
                                              echo '<option value="'.$ligne['idForfait'].'" '.$selected.'>'.$ligne['description'].'</option>';
                                          }
                                      ?>
                                  </select>
                                  <br>
                                  <input type="submit" name="connect" value="Modifier" />
                              </fieldset>
                          </form>

                      <?php
                  }
                  if (!empty($_POST) && isset($_POST['connect']) && $_POST['connect'] == 'Modifier') {
                      $designation = $_POST['designation']; $prixTTC = $_POST['prix']; $idForfait = $_POST['forfait_livraison'];
                      $modifications = request_produit("UPDATE produit SET prixTTC='$prixTTC', designation='$designation', forfaitlivraison='$idForfait' WHERE idPdt=".$_POST['value_produit'].";");
                      afficheTableauAssoc(request_produit("SELECT * FROM produit;"));
                      echo "<p>Vos informations ont bien étés modifiées.</p>";
                  }
              ?>
            </form>
          </div>
        </div>
      </div>
      <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
          <p class="col-md-4 mb-0 text-muted">&copy; 2022 Company, Inc</p>
          <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
            <svg class="bi me-2" width="40" height="32">
              <use xlink:href="#bootstrap" />
            </svg>
          </a>
          <ul class="nav col-md-4 justify-content-end">
            <li class="nav-item">
              <a href="index.html" class="nav-link px-2 text-muted">Accueil</a>
            </li>
            <li class="nav-item">
              <a href="competences.html" class="nav-link px-2 text-muted">Compétences</a>
            </li>
            <li class="nav-item">
              <a href="interets.html" class="nav-link px-2 text-muted">Intérêts</a>
            </li>
            <li class="nav-item">
              <a href="#actuellement" class="nav-link px-2 text-muted">Projet Professionnel</a>
            </li>
            <li class="nav-item">
              <a href="liensutiles.html" class="nav-link px-2 text-muted">Liens utiles</a>
            </li>
          </ul>
        </footer>
      </div>
    </div>
    <script src="js/js_personnel/navbar.js"></script>
    <script src="js/js_personnel/index.js"></script>
</body>
</html>