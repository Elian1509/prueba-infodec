/**
 * Detalles de comodidad. La página funciona igual sin JavaScript.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Al elegir un país se consulta solo, sin tener que pulsar el botón
    const select = document.getElementById('country');
    if (select) {
        select.addEventListener('change', () => {
            if (select.value !== '') {
                select.form.submit();
            }
        });
    }

    // Filtra la tabla de ciudades mientras se escribe (por nombre o distrito)
    const filter = document.getElementById('filter');
    const table = document.getElementById('cities-table');
    if (filter && table) {
        filter.addEventListener('input', () => {
            const text = filter.value.trim().toLowerCase();
            table.querySelectorAll('tbody tr').forEach((row) => {
                row.hidden = !row.textContent.toLowerCase().includes(text);
            });
        });
    }
});
