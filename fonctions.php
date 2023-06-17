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
?>

