document.addEventListener('DOMContentLoaded', function() {
    const filtraInput = document.getElementById('filter');
    const rows = document.querySelectorAll('tbody tr');

    filtraInput.addEventListener('input', function() {
        filtraTabella(filtraInput.value.toLowerCase());
    });

    document.getElementById('filtraOggi').addEventListener('click', filtraOggi);
    document.getElementById('filtraTutti').addEventListener('click', filtraTutti);

    function filtraTabella(filtraValue) {
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const show = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filtraValue));
            row.style.display = show ? '' : 'none';
        });
    }

    function filtraOggi() {
        const today = new Date().toLocaleDateString('it-IT');
        rows.forEach(row => {
            const data = row.getAttribute('data-data');
            row.style.display = (data === today) ? '' : 'none';
        });
    }

    function filtraTutti() {
        rows.forEach(row => {
            row.style.display = '';
        });
    }
});