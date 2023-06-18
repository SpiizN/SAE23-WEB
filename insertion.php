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

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Page d'insertion</title>
</head>
<body>
    <h1>Page d'insertion</h1>

    <form method="POST" action="insertion.php" enctype="multipart/form-data">
        <label for="designation">Désignation :</label>
        <input type="text" name="designation" id="designation" required><br>

        <label for="idCat">Catégorie :</label>
        <select name="idCat" id="idCat" required>
            <?php foreach ($categories as $categorie): ?>
                <option value="<?php echo $categorie['idCat']; ?>"><?php echo $categorie['intitule']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="prixTTC">Prix TTC :</label>
        <input type="number" name="prixTTC" id="prixTTC" step="0.01" required><br>

        <label for="forfaitLivraison">Forfait de livraison :</label>
        <select name="forfaitLivraison" id="forfaitLivraison" required>
            <?php foreach ($forfaitsLivraison as $forfait): ?>
                <option value="<?php echo $forfait['idForfait']; ?>"><?php echo $forfait['description']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="image">Image :</label>
        <input type="file" name="image" id="image" required><br>

        <input type="submit" value="Insérer">
    </form>

    <?php if ($success){ ?>
        <p>Succès</p>
        <a href="index.php">Page d'accueil</a>
    <?php   

    afficheTableauAssoc(request_produit("SELECT * FROM produit;"));
    echo "<p>Vos informations ont bien étés modifiées.</p>";
       
       } ?>
</body>
</html>