<!DOCTYPE html>
<?php
    function request_produit($requete) {
        try {
            $madb = new PDO('sqlite:bdd/bdd_produit/bdd_produit.sqlite');
            $madb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
            $request=$requete;
            $res=$madb->query($request);
            $tab=$res->fetchAll(PDO::FETCH_ASSOC);
            return $tab;
        } catch (PDOException $e) {
            print "Erreur : " . $e->getMessage() . "<br/>";
            die();
        }
    }
    function request_login($requete) {
        try {
            $madb = new PDO('sqlite:bdd/bdd_login/bdd_login.sqlite');
            $madb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
            $request=$requete;
            $res=$madb->query($request);
            $tab=$res->fetchAll(PDO::FETCH_ASSOC);
            return $tab;
        } catch (PDOException $e) {
            print "Erreur : " . $e->getMessage() . "<br/>";
            die();
        }
    }
    function request_quote($entree) {
        try {
            $madb = new PDO('sqlite:bdd/bdd_login/bdd_login.sqlite');
            $entree_quoted = $madb->quote($entree);
            return $entree_quoted;
        } catch(PDOException $e) {
            print "Erreur : " . $e->getMessage() . "<br/>";
            die();
        }
    }

    function afficheTableauAssoc($tab) {
        try {
            echo '<table>';
            echo '<tr>';
            foreach($tab[0] as $cle=>$valeur){
                echo "<th>$cle</th>";
            }
            echo "</tr>\n";
            foreach($tab as $ligne){
                echo '<tr>';
                foreach($ligne as $valeur)      {
                    echo "<td>$valeur</td>";
                }
                echo "</tr>\n";
            }
            echo '</table>';
        } catch (PDOException $e) {
            print "Erreur : " . $e->getMessage() . "<br/>";
            die();
        }
    }


    function VarInTab($tab, $val) {
        $retour=false;
        foreach($tab as $ligne) {
            foreach($ligne as $case){
                if($val == request_quote($case)) {
                    $retour=true;
                }
            }
        }
        return $retour;
    }

    function firstTabVal($tab) {
        $retour = null;
        foreach($tab as $ligne) {
            foreach($ligne as $case) {
                $retour = $case;
                return $retour;
            }
        }
    }

    function login($login, $password) {
        try {
            $tab_login = request_login('SELECT login FROM login ;');
            $login = request_quote($login);
            $password = request_quote($password);
        
            if(VarInTab($tab_login, $login)) {
                // $req="SELECT password FROM login_data WHERE login='".$login."';";
                $tab_password = request_login("SELECT password FROM login WHERE login=".$login.";");
                foreach($tab_password as $ligne_password) {
                    foreach($ligne_password as $pass_login) {
                        if($password == request_quote($pass_login)) {
                            $res=true;
                        }
                        else {
                            $res=false;
                        }
                    }
                }
            }
            else {
                $res=false;
            }
        } catch (PDOException $e) {
            print "Erreur : " . $e->getMessage() . "<br/>";
            die();
        }
        return $res;
    }

    function redirection($page) {
        header("location: $page");
    }

    function redirect($url,$tps)
	{
		$temps = $tps * 1000;
		
		echo "<script type=\"text/javascript\">\n"
		. "<!--\n"
		. "\n"
		. "function redirect() {\n"
		. "window.location='" . $url . "'\n"
		. "}\n"
		. "setTimeout('redirect()','" . $temps ."');\n"
		. "\n"
		. "// -->\n"
		. "</script>\n";
		
	}


	    function filtrerProduits($categorie, $forfait) {
        // Définition de la requête de base
        $requete = "SELECT idPdt AS id, designation AS produit, intitule AS categorie, forfaitlivraison.description AS livraison, prixTTC, images 
                    FROM produit 
                    INNER JOIN categorieproduit ON produit.idCat = categorieproduit.idCat 
                    INNER JOIN forfaitlivraison ON produit.forfaitlivraison = forfaitlivraison.idForfait";
    
        // Vérification des paramètres pour ajouter les conditions appropriées à la requête
        if (!empty($categorie) && empty($forfait)) {
            // Si la catégorie est spécifiée mais pas le forfait, ajouter la condition de filtrage par catégorie
            $requete .= " WHERE produit.idCat = $categorie";
        } elseif (empty($categorie) && !empty($forfait)) {
            // Si le forfait est spécifié mais pas la catégorie, ajouter la condition de filtrage par forfait
            $requete .= " WHERE produit.forfaitlivraison = $forfait";
        } elseif (!empty($categorie) && !empty($forfait)) {
            // Si à la fois la catégorie et le forfait sont spécifiés, ajouter les conditions de filtrage par catégorie et par forfait
            $requete .= " WHERE produit.idCat = $categorie AND produit.forfaitLivraison = $forfait";
        }
    
        // Exécuter la requête SQL et retourner les résultats
        return request_produit($requete);
    }

    // Fonction pour ajouter un produit dans la base de données
    function ajouterProduit($designation, $idCat, $prixTTC, $forfaitLivraison, $images) {
        // Connexion à la base de données
        $connexion = new PDO('sqlite:bdd/bdd_produit/bdd_produit.sqlite');

        // Préparation de la requête SQL
        $requete = $connexion->prepare("INSERT INTO produit (designation, idCat, prixTTC, forfaitLivraison, images) VALUES (?, ?, ?, ?, ?)");

        // Exécution de la requête avec les valeurs fournies
        $requete->execute([$designation, $idCat, $prixTTC, $forfaitLivraison, $images]);
        // Fermeture de la connexion à la base de données
        $connexion = null;
    }

    // Fonction pour lister les catégories de produits depuis la base de données
    function listerCategories() {
        // Connexion à la base de données
        $connexion = new PDO('sqlite:bdd/bdd_produit/bdd_produit.sqlite');

        // Exécution de la requête pour récupérer les catégories
        $resultat = $connexion->query("SELECT idCat, intitule FROM categorieproduit");

        // Récupération des résultats dans un tableau
        $categories = $resultat->fetchAll(PDO::FETCH_ASSOC);

        // Retourner le tableau de catégories
        return $categories;
    }

    // Fonction pour lister les forfaits de livraison depuis la base de données
    function listerForfaitsLivraison() {
        // Connexion à la base de données
        $connexion = new PDO('sqlite:bdd/bdd_produit/bdd_produit.sqlite');

        // Exécution de la requête pour récupérer les forfaits de livraison
        $resultat = $connexion->query("SELECT idForfait, description FROM forfaitlivraison");

        // Récupération des résultats dans un tableau
        $forfaitsLivraison = $resultat->fetchAll(PDO::FETCH_ASSOC);

        // Retourner le tableau de forfaits de livraison
        return $forfaitsLivraison;
    }

    function supprimerProduit($id)
    {
        // Connexion à la base de données
        $connexion = new PDO('sqlite:bdd/bdd_produit/bdd_produit.sqlite');
    
        // Supprimer l'élément de la base de données
        $query = "DELETE FROM produit WHERE idPdt = :id";
        $stmt = $connexion->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

        function getProduits()
    {
        // Connexion à la base de données
        $connexion = new PDO('sqlite:bdd/bdd_produit/bdd_produit.sqlite');

        // Récupérer tous les éléments de la table produit
        $query = "SELECT * FROM produit";
        $stmt = $connexion->prepare($query);
        $stmt->execute();
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $produits;
    }

    function verifierCaptcha($captcha) {
        // Vérifier si le captcha soumis correspond à la valeur stockée dans la session
        if (isset($_SESSION['code']) && strtolower($captcha) === strtolower($_SESSION['code'])) {
            // Le captcha est correct
            return true;
        } else {
            // Le captcha est incorrect
            return false;
        }
    }
?>
