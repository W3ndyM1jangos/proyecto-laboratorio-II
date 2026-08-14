document.addEventListener("DOMContentLoaded", function () {
    const badge = document.getElementById("badgeCarrito");
    if (!badge) return;

    fetch("php/carrito_conteo.php")
        .then(function (respuesta) { return respuesta.json(); })
        .then(function (datos) {
            if (datos.cantidad > 0) {
                badge.textContent = datos.cantidad;
                badge.style.display = "block";
            }
        })
        .catch(function () {});
});
