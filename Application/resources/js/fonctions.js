function imprimer(nomSection) {
    var contenuAImprimer = document.getElementById(nomSection).innerHTML;
    var contenuOriginel = document.body.innerHTML;
    document.body.innerHTML = contenuAImprimer;
    window.print();
    document.body.innerHTML = contenuOriginel;
}

function selectionServices(page) {
    var service = document.getElementById("select_services").value;
    if (service == 'Tous') {
        window.location.href = page;
    } else {
        window.location.href = page + '?service=' + service ;
    }
}

function selectionEtat(page) {
    var etat = document.getElementById("select_etat").value;
    if (etat == 'Tous') {
        window.location.href = page;
    } else {
        window.location.href = page + '?etat=' + etat ;
    }
}

function selectionFamilles(page) {
    var famille = document.getElementById("select_familles").value;
    if (famille == 'Toutes') {
        window.location.href = page;
    } else {
        window.location.href = page + '?famille=' + famille ;
    }
}

function afficherMenu(menu) {
    menu.style.visibility = "visible";
}

function cacherMenu(menu) {
    menu.style.visibility = "hidden";
}
