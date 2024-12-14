document.addEventListener("DOMContentLoaded", function() {
    const ptSelect = document.getElementById("personal_trainer");

    fetch("../php/getPersonalTrainer.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(pt => {
                const option = document.createElement("option");
                option.value = pt.id;
                option.textContent = pt.nome + " " + pt.cognome;
                ptSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Errore nel caricamento dei PT:", error));
});

function caricaCurriculum(ptId) {
    const curriculumDiv = document.getElementById("curriculum");

    if (ptId === "") {
        curriculumDiv.textContent = "";
        document.getElementById("data").disabled = true;
        document.getElementById("ora").disabled = true;
        return;
    }

    fetch('../php/getCurriculum.php?id=' + encodeURIComponent(ptId))
        .then(response => response.json())
        .then(data => {
            curriculumDiv.innerHTML = "<strong>Curriculum:</strong><br><br><a href='../curriculum/" + data.curriculum + "' target='_blank'>Visualizza Curriculum</a>";
            document.getElementById("data").disabled = false;
            document.getElementById("ora").disabled = false;
        })
        .catch(error => console.error("Errore nel caricamento del curriculum:", error));
}

document.addEventListener('change', function() {
    const oggi = new Date();
    const anno = oggi.getFullYear();
    const mese = String(oggi.getMonth() + 1).padStart(2, "0");
    const giorno = String(oggi.getDate()).padStart(2, "0");
    
    const giornoCorrente = anno + "-" + mese + "-" + giorno;
    document.getElementById("data").setAttribute("min", giornoCorrente);

    let id_pt = document.getElementById("personal_trainer").value;

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
                            // console.log(elem.data);
                            if (elem.data == selectedDate) {
                                for (let option of oraSelect.options) {
                                    // console.log(option.value + " " + elem.ora);
                                    // console.log(option.value);
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