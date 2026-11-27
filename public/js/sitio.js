/* Mavs Jewelry — comportamiento mínimo del sitio: el contenido lo arma el servidor (PHP). */
(function () {
  "use strict";
  var btn = document.getElementById("abrir-menu"), nav = document.getElementById("nav-principal");
  if (btn && nav) btn.addEventListener("click", function () {
    btn.setAttribute("aria-expanded", String(nav.classList.toggle("abierto")));
  });
  /* Galería de la ficha de producto */
  var principal = document.getElementById("foto-principal");
  document.querySelectorAll("[data-foto]").forEach(function (b) {
    b.addEventListener("click", function () {
      principal.src = b.getAttribute("data-foto");
      document.querySelectorAll("[data-foto]").forEach(function (x) { x.removeAttribute("aria-current"); });
      b.setAttribute("aria-current", "true");
    });
  });
})();
