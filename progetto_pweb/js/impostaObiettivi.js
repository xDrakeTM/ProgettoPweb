document.addEventListener('DOMContentLoaded', function() {
    const obiettiviForm = document.getElementById('obiettiviForm');
    if (obiettiviForm) {
        obiettiviForm.addEventListener('submit', function(event) {
            event.preventDefault();
            salvaObiettivo(this);
        });
    }
});

function mostraForm(tipo) {
    const formContainer = document.getElementById('altriObiettivi');
    const hr = document.createElement('hr');
    formContainer.appendChild(hr);

    const form = document.createElement('form');
    form.method = 'POST';
    form.onsubmit = function(event) {
        event.preventDefault();
        salvaObiettivo(this, hr);
    };

    const labelTipoObiettivo = document.createElement('label');
    labelTipoObiettivo.setAttribute('for', 'tipo_obiettivo');
    labelTipoObiettivo.textContent = 'Tipo di Obiettivo:';
    form.appendChild(labelTipoObiettivo);

    const inputTipoObiettivo = document.createElement('input');
    inputTipoObiettivo.type = 'text';
    inputTipoObiettivo.id = 'tipo_obiettivo';
    inputTipoObiettivo.name = 'tipo_obiettivo';
    inputTipoObiettivo.value = tipo;
    inputTipoObiettivo.readOnly = true;
    form.appendChild(inputTipoObiettivo);

    const labelObiettivo = document.createElement('label');
    labelObiettivo.setAttribute('for', 'obiettivo');
    labelObiettivo.textContent = 'Obiettivo:';
    form.appendChild(labelObiettivo);

    const inputObiettivo = document.createElement('input');
    inputObiettivo.type = 'text';
    inputObiettivo.id = 'obiettivo';
    inputObiettivo.name = 'obiettivo';
    inputObiettivo.required = true;
    form.appendChild(inputObiettivo);

    const labelDescrizione = document.createElement('label');
    labelDescrizione.setAttribute('for', 'descrizione');
    labelDescrizione.textContent = 'Descrizione:';
    form.appendChild(labelDescrizione);

    const textareaDescrizione = document.createElement('textarea');
    textareaDescrizione.id = 'descrizione';
    textareaDescrizione.name = 'descrizione';
    textareaDescrizione.required = true;
    form.appendChild(textareaDescrizione);

    if (tipo === 'quantitativo') {
        const quantitativoFields = document.createElement('div');
        quantitativoFields.id = 'quantitativoFields';
        quantitativoFields.innerHTML = `
            <div class="input-group">
                <div>
                    <label for="ripetizioni">Ripetizioni:</label>
                    <input type="number" id="ripetizioni" name="ripetizioni" min="1" required>
                </div>
                <div>
                    <label for="serie">Serie:</label>
                    <input type="number" id="serie" name="serie" min="1" required>
                </div>
                <div>
                    <label for="peso">Peso (kg):</label>
                    <input type="number" id="peso" name="peso" step="0.01" min="1" required>
                </div>
            </div>
        `;
        form.appendChild(quantitativoFields);
    } 
    else {
        const continuativoFields = document.createElement('div');
        continuativoFields.id = 'continuativoFields';
        continuativoFields.innerHTML = `
            <div class="input-group">
                <div>
                    <label for="progresso">Progresso:</label>
                    <input type="number" id="progresso" name="progresso" min="0" required>
                </div>
            </div>
        `;
        form.appendChild(continuativoFields);
    }

    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'button-container';

    const submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.className = 'salvaObiettivo';
    submitButton.textContent = 'Salva Obiettivo';
    buttonContainer.appendChild(submitButton);

    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.className = 'eliminaObiettivo';
    deleteButton.textContent = 'Elimina Obiettivo';
    deleteButton.onclick = function() {
        formContainer.removeChild(hr);
        formContainer.removeChild(form);
    };
    buttonContainer.appendChild(deleteButton);

    form.appendChild(buttonContainer);
    formContainer.appendChild(form);
}

function salvaObiettivo(form, hr) {
    const x = new XMLHttpRequest();
    const appuntamentoId = new URLSearchParams(window.location.search).get('appuntamento_id');
    x.open("POST", "../php/salvaObiettivo.php?appuntamento_id=" + encodeURIComponent(appuntamentoId), true);
    x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    const params = new URLSearchParams();
    params.append('tipo_obiettivo', form.querySelector('[name="tipo_obiettivo"]').value);
    params.append('obiettivo', form.querySelector('[name="obiettivo"]').value);
    params.append('descrizione', form.querySelector('[name="descrizione"]').value);

    if (form.querySelector('[name="ripetizioni"]')) {
        params.append('ripetizioni', form.querySelector('[name="ripetizioni"]').value);
    }

    if (form.querySelector('[name="serie"]')) {
        params.append('serie', form.querySelector('[name="serie"]').value);
    }
    
    if (form.querySelector('[name="peso"]')) {
        params.append('peso', form.querySelector('[name="peso"]').value);
    }

    if (form.querySelector('[name="progresso"]')) {
        params.append('progresso', form.querySelector('[name="progresso"]').value);
    }

    x.onload = function() {
        if (x.status === 200) {
            try {
                const response = JSON.parse(x.responseText);
                if (response.success) {
                    alert("Obiettivo salvato con successo!");
                    form.reset();
                    form.style.display = 'none';
                    hr.remove();
                } else {
                    alert("Errore: " + response.message);
                }
            } catch (e) {
                console.error("Errore nel parsing della risposta JSON:", e);
                console.error("Risposta del server:", x.responseText);
            }
        } else {
            alert("Errore nella richiesta: " + x.status);
        }
    };

    x.send(params.toString());
}