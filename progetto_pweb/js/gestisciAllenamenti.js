function confermaAppuntamento(id) {
    if (confirm("Confermare questo appuntamento?")) {
        aggiornaStatoAppuntamento(id, 'confermato');
    }
}

function rifiutaAppuntamento(id) {
    if (confirm("Rifiutare questo appuntamento?")) {
        aggiornaStatoAppuntamento(id, 'cancellato');
    }
}

function aggiornaStatoAppuntamento(id, stato) {
    const x = new XMLHttpRequest();
    x.open("POST", "../php/vediAllenamenti.php", true);
    x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    x.send("id=" + encodeURIComponent(id) + "&stato=" + encodeURIComponent(stato));
    x.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            if (response.success) {
                document.getElementById('appuntamento-' + id).remove();
                alert("Appuntamento " + stato + " con successo.");
            } 
            else {
                alert("Errore: " + response.message);
            }
        }
    };
}