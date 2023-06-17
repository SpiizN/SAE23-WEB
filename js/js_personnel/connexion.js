function verifierFormulaire() {
    var entree = document.getElementById('id_input_login').value;
    const listeMajuscules = /[A-Z]/;
    const listeSpecialChars = /[!@#$%&*()_+\-={};:|,.?]/;

    return listeMajuscules.test(entree) && listeSpecialChars.test(entree);
}


/*function test_login() {
    var div_login = document.getElementById("div_login");

    if (!verifierFormulaire()) {
        div_login.innerHTML = "<div class='alert alert-danger' role='alert'>Login invalide.</div>";
        div_login.classList.add("alert", "alert-danger");
    } else {
        div_login.innerHTML = "<div class='alert alert-success' role='alert'>Login valide.</div>";
        div_login.classList.remove("alert", "alert-danger");
    }
}*/


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