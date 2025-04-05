function ajoutLigne(pNumero) {
    document.getElementById("but" + pNumero).setAttribute("hidden", "true");  
    pNumero++;

    var laDiv = document.getElementById("lignes");

    var label = document.createElement("label");
    label.className = "titre";
    label.innerHTML = "Frais numéro " + pNumero;
    laDiv.appendChild(label);

    var ladate = document.createElement("input");
    ladate.name = "FRA_AUT_DAT" + pNumero;
    ladate.size = "12";
    ladate.className = "zone";
    laDiv.appendChild(ladate);

    var libelle = document.createElement("input");
    libelle.name = "FRA_AUT_LIB" + pNumero;
    libelle.size = "30";
    libelle.className = "zone";
    laDiv.appendChild(libelle);

    var mont = document.createElement("input");
    mont.name = "FRA_AUT_MONT" + pNumero;
    mont.size = "3";
    mont.className = "zone";
    laDiv.appendChild(mont);

    var bouton = document.createElement("input");
    bouton.type = "button";
    bouton.value = "+";
    bouton.className = "zone";
    bouton.id = "but" + pNumero;
    bouton.setAttribute("onclick", "ajoutLigne(" + pNumero + ");");
    laDiv.appendChild(bouton);
}