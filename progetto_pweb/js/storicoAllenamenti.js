document.addEventListener('DOMContentLoaded', function() {
    const filtraInput = document.getElementById('filtro');

    filtraInput.addEventListener('input', function() {
        const filtraValue = filtraInput.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        for (let row of rows) {
            const cells = row.querySelectorAll('td');
            let show = false;

            for (let cell of cells) {
                if (cell.textContent.toLowerCase().includes(filtraValue)) {
                    show = true;
                    break;
                }
            }

            row.style.display = show ? '' : 'none';
        }
    });
});

function filtraOggi() {
    const today = new Date().toLocaleDateString('it-IT');
    const rows = document.querySelectorAll('tbody tr');

    for (let row of rows) {
        const data = row.getAttribute('data-data');
        row.style.display = (data === today) ? '' : 'none';
    }
}

function filtraTutti() {
    const rows = document.querySelectorAll('tbody tr');
    for (let row of rows) {
        row.style.display = '';
    }
}