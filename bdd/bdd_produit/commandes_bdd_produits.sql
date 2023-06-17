--COMMANDES DONNEES PAR LE PROF
-------------------------------------------------
CREATE TABLE forfaitlivraison (
	idForfait INTEGER PRIMARY KEY AUTOINCREMENT,
	description VARCHAR(100) NOT NULL,
	montant int(8) NOT NULL
) ;

CREATE TABLE categorieproduit  (
	idCat  INTEGER PRIMARY KEY AUTOINCREMENT,
	intitule  VARCHAR(50) NOT NULL,
	description  VARCHAR(50) NOT NULL
) ;

CREATE TABLE  produit  (
	idPdt  INTEGER PRIMARY KEY AUTOINCREMENT,
	idCat  INTEGER NOT NULL,
	prixTTC  FLOAT(4,2) NOT NULL,
	designation  VARCHAR(100) NOT NULL,
	forfaitLivraison  int(3) NOT NULL,
	images TEXT NOT NULL,
	CONSTRAINT fk_Cat FOREIGN KEY (idCat) REFERENCES categorieproduit(idCat) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_Forfait FOREIGN KEY (forfaitLivraison) REFERENCES forfaitLivraison(idForfait) ON DELETE CASCADE ON UPDATE CASCADE
)  ;


------------------------------------------------


-- Vider tout les tuples de forfaitLivraison :
-- DELETE FROM forfaitlivraison ;

INSERT INTO forfaitLivraison (description, montant) VALUES ('-de 1kg', 1);
INSERT INTO forfaitLivraison (description, montant) VALUES ('- de 5kg', 3);
INSERT INTO forfaitLivraison (description, montant) VALUES ('- de 10kg', 5);
INSERT INTO forfaitLivraison (description, montant) VALUES ('- de 15kg', 8);
INSERT INTO forfaitLivraison (description, montant) VALUES ('- de 30kg', 10);
INSERT INTO forfaitLivraison (description, montant) VALUES ('-de 50kg', 15);
INSERT INTO forfaitLivraison (description, montant) VALUES ('- de 75kg', 20);
INSERT INTO forfaitLivraison (description, montant) VALUES ('- de 100kg', 25);
INSERT INTO forfaitLivraison (description, montant) VALUES ('- de 150kg', 30);
INSERT INTO forfaitLivraison (description, montant) VALUES ('+ de 150kg', 40);

-- Vider tout les tuples de categorieproduit :
-- DELETE FROM categorieproduit ;

INSERT INTO categorieproduit (intitule, description) VALUES ('Nourriture', 'Produits à manger');
INSERT INTO categorieproduit (intitule, description) VALUES ('Electronique', 'Produits à éléctroniques');
INSERT INTO categorieproduit (intitule, description) VALUES ('Cuisine', 'Produits de Cuisine');
INSERT INTO categorieproduit (intitule, description) VALUES ('Pharmacie', 'Produits pharmaceutiques ');
INSERT INTO categorieproduit (intitule, description) VALUES ('Pétrole', 'produits pétroliers ');
INSERT INTO categorieproduit (intitule, description) VALUES ('Construction', 'Matériaux de construction ');
INSERT INTO categorieproduit (intitule, description) VALUES ('Chimique', 'Produits chimiques industriels ');
INSERT INTO categorieproduit (intitule, description) VALUES ('Textile', 'Produits textiles ');
INSERT INTO categorieproduit (intitule, description) VALUES ('Automobile', 'Produits de l''industrie automobile ');
INSERT INTO categorieproduit (intitule, description) VALUES ('Manufacture', 'Produits métalliques ');

-- Vider tout les tuples de categorieproduit :
-- DELETE FROM produit ;

INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Nourriture'), 100.00, 'Carton haribos', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 1), 'media/images/haribo.jpg') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Electronique'), 349.99, 'Nintendo Switch', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 2), 'media/images/ninteno_switch.webp') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Electronique'), 599.99, 'PS5', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 2), 'media/images/ps5.webp') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Cuisine'), 68.99, 'carton de machines à café', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 3), 'media/images/machine_a_cafe.webp') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Pharmacie'), 200.00, 'Carton dentifrices', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 1), 'media/images/dentifrice.jpg') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Pétrole'), 300.00, 'Diesel', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 5), 'media/images/diesel.jpg') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Construction'), 200.00, 'lot de 10 planches', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 6), 'media/images/planches.webp') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Chimique'), 150.00, 'Desinfectants', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 2), 'media/images/desinfectant.jpg') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Textile'), 500.00, 'Pulls', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 2), 'media/images/pull.jpg') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Textile'), 400.00, 'short', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 2), 'media/images/short.webp') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Automobile'), 249.99, 'lot de 5 Pneux', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 7), 'media/images/pneu.jpg') ;
INSERT INTO produit ( idCat, prixTTC, designation, forfaitLivraison, images ) VALUES ( (SELECT idCat FROM categorieproduit WHERE intitule='Manufacture'), 199.99, 'carton de films plastiques', (SELECT idForfait FROM forfaitlivraison WHERE idForfait = 1), 'media/images/film_plastique.webp') ;