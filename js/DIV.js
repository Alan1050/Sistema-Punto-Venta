// Obtener referencias al botón de cancelar y al div
document.addEventListener("DOMContentLoaded", () => {
    const cancelBtn = document.getElementById('cancelBtn');
    const centeredDiv = document.getElementById('centeredDiv');

    // Verificar si los elementos existen antes de agregar el evento
    if (cancelBtn && centeredDiv) {
        cancelBtn.addEventListener('click', () => {
            // Ocultar el div estableciendo `display` a `none`
            centeredDiv.style.display = 'none';
        });
    }
});