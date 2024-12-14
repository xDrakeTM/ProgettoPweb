document.addEventListener('DOMContentLoaded', function() {
    const x = new XMLHttpRequest();
    x.open('GET', '../php/getAllenamenti.php', true);

    x.onload = function() {
        if (x.status === 200) {
            try {
                const appuntamenti = JSON.parse(x.responseText);

                if (Array.isArray(appuntamenti)) {
                    const appuntamentiTable = document.getElementById("allenamentiTableBody");
                    
                    appuntamenti.forEach(function(appuntamento) {
                        const row = document.createElement("tr");
                        
                        const dataCell = document.createElement("td");
                        dataCell.textContent = appuntamento.data;
                        
                        const oraInizioCell = document.createElement("td");
                        oraInizioCell.textContent = appuntamento.ora_inizio;
                        
                        const oraFineCell = document.createElement("td");
                        oraFineCell.textContent = appuntamento.ora_fine;
                        
                        const trainerCell = document.createElement("td");
                        trainerCell.textContent = appuntamento.nome + " " + appuntamento.cognome;

                        const statusCell = document.createElement("td");
                        statusCell.textContent = appuntamento.stato;
                        statusCell.style.fontWeight = "bold";

                        switch(appuntamento.stato) {
                            case 'prenotato':
                                statusCell.id = "giallo";
                                break;
                            
                            case 'confermato':
                                statusCell.id = "verde";
                                break;
                            
                            case 'cancellato':
                                statusCell.id = "rosso";
                                break;
                        }
                        
                        row.appendChild(dataCell);
                        row.appendChild(oraInizioCell);
                        row.appendChild(oraFineCell);
                        row.appendChild(trainerCell);
                        row.appendChild(statusCell);
                        
                        appuntamentiTable.appendChild(row);
                    });
                } 
                else {
                    console.error("La risposta non è un array:", appuntamenti);
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