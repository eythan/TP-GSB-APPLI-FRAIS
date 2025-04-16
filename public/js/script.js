function ajoutLigne(pNumero) {
    document.getElementById("but" + pNumero).setAttribute("hidden", "true");  
    pNumero++;

    var laDiv = document.getElementById("lignes");

    var label = document.createElement("label");
    label.className = "titre";
    label.innerHTML = "Frais numéro " + pNumero;
    laDiv.appendChild(label);

    var ladate = document.createElement("input");
    ladate.name = "fraisDate" + pNumero;
    ladate.size = "12";
    ladate.className = "zone";
    ladate.placeholder = "Date";
    ladate.type = "date";
    laDiv.appendChild(ladate);

    var libelle = document.createElement("input");
    libelle.name = "fraisDescription" + pNumero;
    libelle.size = "30";
    libelle.className = "zone";
    libelle.placeholder = "Libellé";
    laDiv.appendChild(libelle);

    var mont = document.createElement("input");
    mont.name = "fraisMontant" + pNumero;
    mont.size = "3";
    mont.className = "zone";
    mont.placeholder = "Montant";
    mont.type = "number";
    mont.min = "0";
    mont.max = "999999";
    laDiv.appendChild(mont);

    var bouton = document.createElement("input");
    bouton.type = "button";
    bouton.value = "+";
    bouton.className = "zone";
    bouton.id = "but" + pNumero;
    bouton.setAttribute("onclick", "ajoutLigne(" + pNumero + ");");
    laDiv.appendChild(bouton);
}