function verifierFormulaire() {
    var entree = document.getElementById('id_input_login').value;
    const listeMajuscules = /[A-Z]/;
    const listeSpecialChars = /[!@#$%&*()_+\-={};:|,.?]/;

    return listeMajuscules.test(entree) && listeSpecialChars.test(entree);
}

const formulaire = document.querySelector("form");
formulaire.addEventListener("submit", function(event) { // Quand l'utilisateur envoi le formulaire
    event.preventDefault();
    const estValide = verifierFormulaire(); // Vérifie si le login est valide

    if (estValide) {
        formulaire.submit(); // Envoi le formulaire
    } else {
        alert("Le login doit contenir au moins une majuscule et un caractère spécial.") // Bloque l'envoi
    }
});

function test() {
    console.log(verifierFormulaire());
}
