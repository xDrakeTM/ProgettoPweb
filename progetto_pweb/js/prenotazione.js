// Aggiunge un listener per l'evento DOMContentLoaded per eseguire il codice quando il documento è completamente caricato
document.addEventListener("DOMContentLoaded", function() {
    // Recupera l'elemento select per i personal trainer
    const ptSelect = document.getElementById("personal_trainer");

    // Effettua una richiesta per ottenere i personal trainer
    fetch("../php/getPersonalTrainer.php")
        .then(response => response.json()) // Converte la risposta in formato JSON
        .then(data => {
            // Itera su ogni personal trainer e aggiunge un'opzione al select
            data.forEach(pt => {
                const option = document.createElement("option");
                option.value = pt.id;
                option.textContent = pt.nome + " " + pt.cognome;
                ptSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Errore nel caricamento dei PT:", error)); // Gestisce eventuali errori
});

// Funzione per caricare il curriculum di un personal trainer
function caricaCurriculum(ptId) {
    // Recupera l'elemento div per il curriculum
    const curriculumDiv = document.getElementById("curriculum");

    // Se non è stato selezionato un personal trainer, disabilita i campi data e ora
    if (ptId === "") {
        curriculumDiv.textContent = "";
        document.getElementById("data").disabled = true;
        document.getElementById("ora").disabled = true;
        return;
    }

    // Effettua una richiesta per ottenere il curriculum del personal trainer
    fetch('../php/getCurriculum.php?id=' + encodeURIComponent(ptId))
        .then(response => response.json()) // Converte la risposta in formato JSON
        .then(data => {
            // Mostra il curriculum e abilita i campi data e ora
            curriculumDiv.innerHTML = "<strong>Curriculum:</strong><br><br><a href='../curriculum/" + data.curriculum + "' target='_blank'>Visualizza Curriculum</a>";
            document.getElementById("data").disabled = false;
            document.getElementById("ora").disabled = false;
        })
        .catch(error => console.error("Errore nel caricamento del curriculum:", error)); // Gestisce eventuali errori
}

// Aggiunge un listener per l'evento change per gestire la selezione della data
document.addEventListener('change', function() {
    // Recupera la data corrente
    const oggi = new Date();
    const anno = oggi.getFullYear();
    const mese = String(oggi.getMonth() + 1).padStart(2, "0");
    const giorno = String(oggi.getDate()).padStart(2, "0");

    // Imposta la data minima selezionabile come la data corrente
    const giornoCorrente = anno + "-" + mese + "-" + giorno;
    document.getElementById("data").setAttribute("min", giornoCorrente);

    // Recupera l'ID del personal trainer selezionato
    let id_pt = document.getElementById("personal_trainer").value;

    // Crea una nuova richiesta XMLHttpRequest per ottenere la disponibilità del personal trainer
    const x = new XMLHttpRequest();
    x.open('GET', '../php/getDisponibilita.php?id_pt=' + encodeURIComponent(id_pt), true);
    x.onload = function() {
        if (x.status === 200) {
            try {
                const disponibilita = JSON.parse(x.responseText);
                console.log(disponibilita);

                if (Array.isArray(disponibilita)) {
                    const dataInput = document.getElementById("data");
                    const oraSelect = document.getElementById("ora");

                    dataInput.addEventListener('change', function() {
                        const selectedDate = dataInput.value;

                        for (let option of oraSelect.options) {
                            option.disabled = false;
                        }

                        disponibilita.forEach(function(elem) {
                            if (elem.data == selectedDate) {
                                for (let option of oraSelect.options) {
                                    if (option.value == elem.ora) {
                                        option.disabled = true;
                                    }
                                }
                            }
                        });

                        if (selectedDate === giornoCorrente) {
                            const oraCorrente = oggi.getHours();
                            for (let option of oraSelect.options) {
                                const oraOptionValue = parseInt(option.value.split(":")[0], 10);
                                if (oraOptionValue <= oraCorrente) {
                                    option.disabled = true;
                                }
                            }
                        }
                    });
                } 
                else {
                    console.error("La risposta non è un array:", disponibilita);
                }
            } 
            catch (error) {
                console.error("Errore nel parsing JSON:", error);
            }
        } 
        else {
            console.error("Errore nella richiesta:", x.status);
        }
    };

    x.send();
});

function prenotaAppuntamento(event) {
    event.preventDefault();

    let id_pt = document.getElementById("personal_trainer").value;
    let data = document.getElementById("data").value;
    let ora = document.getElementById("ora").value;

    const x = new XMLHttpRequest();
    x.open("POST", "../php/registraAppuntamento.php", true);
    x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    x.send("id_pt=" + encodeURIComponent(id_pt) +
            "&data=" + encodeURIComponent(data) +
            "&ora=" + encodeURIComponent(ora));

    x.onload = function () {
        if (JSON.parse(x.responseText).success) {
            alert("Appuntamento prenotato con successo!");

            document.getElementById("personal_trainer").value = "";
            document.getElementById("data").value = "";
            document.getElementById("ora").value = "";

            location.reload();
        }
    };
}