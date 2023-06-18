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

<!DOCTYPE html>
<html>
<head>
    <title>Suppression d'éléments</title>
</head>
<body>
    <h1>Suppression d'éléments</h1>

    <form method="post" action="suppression.php">
        <?php if (!empty($produits)) : ?>
            <table>
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
            <input type="text" name="captcha"/>
            <!-- Image du captcha -->
            <img src="image.php" onclick="this.src='image.php?' + Math.random();" alt="captcha" style="cursor:pointer;">
            <!-- Message d'erreur du captcha -->
            <span class="error"><?php echo $erreur_captcha; ?></span>
            <br>
            <input type="submit" value="Supprimer les éléments sélectionnés">
        <?php else : ?>
            <p>Aucun élément trouvé dans la base de données.</p>
        <?php endif; ?>
    </form>

    <p><a href="index.php">Retour à la page d'accueil</a></p>
</body>
</html>