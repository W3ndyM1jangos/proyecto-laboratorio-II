const scrollContainer = document.getElementById('productosScroll');
const btnNext = document.getElementById('btnNext');
const btnPrev = document.getElementById('btnPrev');
const progressBar = document.getElementById('progressBar');
const scrollAmount = 800;

btnNext.addEventListener('click', () => {
    scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
});
btnPrev.addEventListener('click', () => {
    scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
});
scrollContainer.addEventListener('scroll', () => {
    const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;

    // Mostrar y Ocultar Flechas
    if (scrollContainer.scrollLeft > 20) {
        btnPrev.classList.remove('d-none');
    } else {
        btnPrev.classList.add('d-none');
    }
    if (scrollContainer.scrollLeft >= maxScroll - 20) {
        btnNext.classList.add('d-none');
    } else {
        btnNext.classList.remove('d-none');
    }
    // Calcular porcentaje y actualizar la barra de progreso
    const scrollPercentage = (scrollContainer.scrollLeft / maxScroll) * 100;
    progressBar.style.width = scrollPercentage + '%';
 });
 // discover
function moverDerecha() {
    const carrusel = document.getElementById('carrusel');
    carrusel.scrollLeft += carrusel.clientWidth;
}
function moverIzquierda() {
    const carrusel = document.getElementById('carrusel');
    carrusel.scrollLeft -= carrusel.clientWidth;
}