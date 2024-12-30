function validaModificaProfiloUtente(event) {
    event.preventDefault();

    const form = document.getElementById('updateProfileForm');
    const formData = new FormData(form);

    const x = new XMLHttpRequest();
    x.open("POST", "../php/validazioneModificaProfiloUtente.php", true);
    x.onload = function() {
        const statusDiv = document.getElementById("status");

        try {
            const response = JSON.parse(x.responseText);
            if (response.success) {
                statusDiv.textContent = "Profilo aggiornato con successo!";
                statusDiv.style.color = "green";
            } else {
                statusDiv.textContent = response.message;
                statusDiv.style.color = "red";
            }
        } catch (e) {
            statusDiv.textContent = "Errore durante l'aggiornamento del profilo.";
            statusDiv.style.color = "red";
            console.error("Errore nel parsing della risposta JSON:", e);
        }
    };
    x.send(formData);
}

function validaModificaProfiloPT(event) {
    event.preventDefault();

    const form = document.getElementById('updateProfileForm');
    const formData = new FormData(form);

    const x = new XMLHttpRequest();
    x.open("POST", "../php/validazioneModificaProfiloPT.php", true);
    x.onload = function() {
        const statusDiv = document.getElementById("status");

        try {
            const response = JSON.parse(x.responseText);
            if (response.success) {
                statusDiv.textContent = "Profilo aggiornato con successo!";
                statusDiv.style.color = "green";
            } else {
                statusDiv.textContent = response.message;
                statusDiv.style.color = "red";
            }
        } catch (e) {
            statusDiv.textContent = "Errore durante l'aggiornamento del profilo.";
            statusDiv.style.color = "red";
            console.error("Errore nel parsing della risposta JSON:", e);
        }
    };
    x.send(formData);
}

function eliminaAccount() {
    if (confirm("Sei sicuro di voler eliminare il tuo account?")) {
        const x = new XMLHttpRequest();
        x.open("POST", "../php/validazioneEliminazioneAccount.php", true);
        x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        x.send();

        x.onload = function() {
            const statusDiv = document.getElementById("status");

            try {
                const response = JSON.parse(x.responseText);
                if (response.success) {
                    window.location.href = "../php/login.php";
                } else {
                    statusDiv.textContent = response.message;
                    statusDiv.style.color = "red";
                }
            } 
            catch (e) {
                statusDiv.textContent = "Errore durante l'eliminazione dell'account.";
                statusDiv.style.color = "red";
                console.error("Errore nel parsing della risposta JSON:", e);
            }
        };
    }
}

function modificaPassword(event) {
    event.preventDefault();

    let old_password = document.getElementById("old_password").value;
    let new_password = document.getElementById("new_password").value;
    let confirm_password = document.getElementById("confirm_password").value;

    const x = new XMLHttpRequest();
    x.open("POST", "../php/registraNuovaPassword.php", true);
    x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    let info = "old_password=" + encodeURIComponent(old_password) + 
               "&new_password=" + encodeURIComponent(new_password) + 
               "&confirm_password=" + encodeURIComponent(confirm_password);
    
    x.send(info);

    x.onload = function() {
        let response = JSON.parse(x.responseText);
        if (response.success) {
            window.location.href = "../html/cambioPasswordEffettuato.html";
        } 
        else {
            document.getElementById("status").textContent = response.message;
        }
    };
}

function recuperaPassword(event) {
    event.preventDefault();

    let email = document.getElementById("email").value;
    let risposta1 = document.getElementById("risposta1").value;
    let risposta2 = document.getElementById("risposta2").value;
    let new_password = document.getElementById("new_password").value;
    let confirm_password = document.getElementById("confirm_password").value;
    let tipo_utente = document.getElementById("tipo_utente").value;

    const x = new XMLHttpRequest();
    x.open("POST", "../php/validazioneRecuperoPassword.php", true);
    x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    let info = "email=" + encodeURIComponent(email) +
               "&risposta1=" + encodeURIComponent(risposta1) + 
               "&risposta2=" + encodeURIComponent(risposta2) +
               "&new_password=" + encodeURIComponent(new_password) +
               "&confirm_password=" + encodeURIComponent(confirm_password) +
               "&tipo_utente=" + encodeURIComponent(tipo_utente);

    x.onload = function() {
        const response = JSON.parse(x.responseText);
        if (response.success) {
            window.location.href = "../html/cambioPasswordEffettuato.html";
        } 
        else {
            document.getElementById("status").textContent = response.message;
        }
    };

    x.send(info);
}