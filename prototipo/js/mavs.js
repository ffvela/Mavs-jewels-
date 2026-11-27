/* ==========================================================================
   Mavs Jewelry — comportamiento del sitio
   La cabecera, el pie, el carrito y la ficha se generan aquí una sola vez.
   En la versión PHP la cabecera y el pie pasan a includes/header.php y
   includes/footer.php, y el catálogo se lee desde MySQL.
   ========================================================================== */
(function () {
  "use strict";

  var WSP = "56989234239";
  var P = window.MAVS_PRODUCTOS || [];
  var T = window.MAVS_TECNICAS || {};

  var I = {
    estrella: '<svg class="estrella" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 0c.7 6.4 5.6 11.3 12 12-6.4.7-11.3 5.6-12 12-.7-6.4-5.6-11.3-12-12C6.4 11.3 11.3 6.4 12 0z"/></svg>',
    bolsa: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 8h14l-1 12H6L5 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>',
    menu: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>',
    wsp: '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2c-1.5 0-3-.4-4.3-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.4.1-.7.3-.2.3-.9.9-.9 2.2s.9 2.5 1 2.7c.1.2 1.8 2.8 4.4 3.9 1.6.7 2.3.8 3.1.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3z"/></svg>',
    ig: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".8" fill="currentColor"/></svg>',
    tiktok: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M14 3v11.5a3.5 3.5 0 1 1-3.5-3.5"/><path d="M14 3c.4 2.6 2.2 4.4 5 4.6"/></svg>',
    fb: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8z"/></svg>',
    mail: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>'
  };
  window.MAVS_ICONOS = I;

  function precio(n) { return "$" + Number(n).toLocaleString("es-CL"); }
  function img(nombre, tam) { return "../public/img/" + nombre + "-" + (tam || 640) + ".webp"; }
  function wspLink(texto) { return "https://wa.me/" + WSP + "?text=" + encodeURIComponent(texto); }
  function porId(id) { for (var i = 0; i < P.length; i++) if (P[i].id === id) return P[i]; return null; }
  function esc(s) { return String(s).replace(/[&<>"]/g, function (c) { return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[c]; }); }

  /* ---------------- Cabecera y pie ---------------- */
  var pagina = document.body.getAttribute("data-pagina") || "";
  var enlaces = [
    ["index.html", "Inicio", "inicio"],
    ["catalogo.html", "Catálogo", "catalogo"],
    ["especialidad.html", "Personalizadas", "especialidad"],
    ["piedras.html", "Piedras", "piedras"],
    ["nosotros.html", "Nosotros", "nosotros"],
    ["contacto.html", "Contacto", "contacto"]
  ];

  function cabecera() {
    var nav = enlaces.map(function (e) {
      return '<a href="' + e[0] + '"' + (e[2] === pagina ? ' aria-current="page"' : "") + ">" + e[1] + "</a>";
    }).join("");
    return '' +
      '<div class="franja"><div class="contenedor">' +
        '<span>' + I.estrella + '100% hecho a mano</span>' +
        '<span>' + I.estrella + 'Envíos por Starken</span>' +
        '<span>' + I.estrella + 'Pago con tarjeta</span>' +
      '</div></div>' +
      '<header class="cabecera"><div class="contenedor">' +
        '<button class="icono-btn menu-btn" type="button" id="abrir-menu" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-principal">' + I.menu + '</button>' +
        '<nav class="nav" id="nav-principal" aria-label="Navegación principal">' + nav + '</nav>' +
        '<a class="marca" href="index.html" aria-label="Mavs Jewelry, inicio"><img src="../public/img/logo-mavs-negro.png" alt="Mavs Jewelry" width="68" height="50"></a>' +
        '<div class="cab-acciones">' +
          '<a class="icono-btn" href="https://www.instagram.com/mavs_jewelry/" target="_blank" rel="noopener" aria-label="Instagram">' + I.ig + '</a>' +
          '<a class="icono-btn" href="contacto.html" aria-label="Contacto">' + I.mail + '</a>' +
          '<button class="icono-btn" type="button" id="abrir-carrito" aria-label="Abrir carrito">' + I.bolsa + '<span class="contador" id="contador-carrito" hidden>0</span></button>' +
        '</div>' +
      '</div></header>';
  }

  function pie() {
    return '' +
      '<footer class="pie-sitio"><div class="contenedor">' +
        '<div class="columnas">' +
          '<div><img class="logo-pie" src="../public/img/logo-mavs.png" alt="Mavs Jewelry" width="100" height="74">' +
            '<p>Joyería artesanal hecha a mano en Santiago por María Angélica Venegas. Plata, piedras naturales, mostacilla Miyuki y alambrismo.</p></div>' +
          '<div><h4>Tienda</h4><ul>' +
            '<li><a href="catalogo.html#plata">Plata con piedras</a></li>' +
            '<li><a href="catalogo.html#miyuki">Miyuki y alambrismo</a></li>' +
            '<li><a href="especialidad.html">Piezas personalizadas</a></li>' +
            '<li><a href="piedras.html">Significado de las piedras</a></li></ul></div>' +
          '<div><h4>Compra segura</h4><ul>' +
            '<li><a href="contacto.html#politicas">Garantía legal de 6 meses</a></li>' +
            '<li><a href="contacto.html#politicas">Envíos y pago con tarjeta</a></li>' +
            '<li><a href="contacto.html#politicas">Retracto de 10 días</a></li>' +
            '<li><a href="contacto.html#privacidad">Privacidad</a></li>' +
            '<li><a href="index.html#donde">Dónde encontrarnos</a></li></ul></div>' +
          '<div><h4>Conversemos</h4><ul>' +
            '<li><a href="https://wa.me/' + WSP + '" target="_blank" rel="noopener">WhatsApp +56 9 8923 4239</a></li>' +
            '<li><a href="https://www.instagram.com/mavs_jewelry/" target="_blank" rel="noopener">Instagram @mavs_jewelry</a></li>' +
            '<li><a href="https://www.tiktok.com/@mavs_jewerly" target="_blank" rel="noopener">TikTok @mavs_jewerly</a></li>' +
            '<li><a href="https://www.facebook.com/profile.php?id=61570840502174" target="_blank" rel="noopener">Facebook Mavs Jewelry</a></li></ul></div>' +
        '</div>' +
        '<p class="firma" aria-hidden="true">Mavs <span>Jewelry</span></p>' +
        '<div class="legal"><span>Mavs Jewelry · María Angélica Venegas · RUT [por completar] · mavenegas.s.mv@gmail.com · Santiago, Región Metropolitana</span><span>Boleta electrónica en cada compra</span></div>' +
      '</div></footer>' +
      '<a class="wsp-flotante" href="' + wspLink("Hola María Angélica, vi tu página y quiero hacer una consulta.") + '" target="_blank" rel="noopener" aria-label="Escribir por WhatsApp">' + I.wsp + '<span>Escríbeme</span></a>';
  }

  function capas() {
    return '' +
      '<div class="velo" id="velo" hidden></div>' +
      '<aside class="carrito" id="carrito" aria-label="Carrito de compra" aria-hidden="true">' +
        '<div class="carrito-cab"><h2>Tu carrito</h2><button class="cerrar" type="button" id="cerrar-carrito" aria-label="Cerrar carrito">×</button></div>' +
        '<ul class="carrito-items" id="carrito-items"></ul>' +
        '<div class="carrito-pie">' +
          '<div class="total"><span>Total</span><div class="precio" id="carrito-total">$0</div></div>' +
          '<small>Despacho por Starken, por pagar al recibir. Pago con tarjeta de crédito o débito en Mercado Pago.</small>' +
          '<a class="btn btn-noche" href="despacho.html" id="ir-a-pagar">Continuar con la compra</a>' +
          '<a class="enlace-carrito" href="carrito.html">Ver carrito completo</a>' +
        '</div>' +
      '</aside>';
  }

  var slotCab = document.getElementById("cabecera");
  var slotPie = document.getElementById("pie");
  if (slotCab) slotCab.outerHTML = cabecera();
  if (slotPie) slotPie.outerHTML = pie();
  document.body.insertAdjacentHTML("beforeend", capas());

  var btnMenu = document.getElementById("abrir-menu");
  var nav = document.getElementById("nav-principal");
  btnMenu.addEventListener("click", function () {
    var abierto = nav.classList.toggle("abierto");
    btnMenu.setAttribute("aria-expanded", String(abierto));
  });

  /* ---------------- Tarjeta de producto ---------------- */
  var COLORES = { noche: "var(--t-noche)", ciruela: "var(--t-ciruela)", rosa: "var(--t-rosa)", "rosa-oscuro": "var(--t-rosa-oscuro)", oro: "var(--t-oro)" };
  function fondo(p) { return COLORES[p.fondo] || COLORES.noche; }

  function tarjeta(p) {
    var vendida = p.estado === "vendida";
    var etiqueta = vendida ? '<span class="etiqueta vendida">Vendida</span>'
      : (p.unica ? '<span class="etiqueta">Pieza única</span>' : "");
    return '' +
      '<article class="producto' + (vendida ? " es-vendida" : "") + '">' +
        '<a class="foto" href="producto.html?id=' + p.id + '" style="--fondo:' + fondo(p) + '" aria-label="Ver ficha de ' + esc(p.nombre) + '">' + etiqueta +
          '<span class="marco"><img src="' + img(p.imagen) + '" alt="' + esc(p.nombre) + '" loading="lazy" width="640" height="800"></span>' +
          '<span><span class="nombre">' + esc(p.nombre) + '</span>' +
          '<span class="sub">' + T[p.tecnica].corto + ' · ' + esc(p.piedras !== "Sin piedras" ? p.piedras : p.materiales) + '</span></span>' +
        '</a>' +
        '<div class="pie"><span class="precio">' + precio(p.precio) + '</span>' +
          '<button class="agregar" type="button" data-agregar="' + p.id + '"' + (vendida ? " disabled" : "") + '>' + (vendida ? "Vendida" : "Agregar") + '</button></div>' +
      '</article>';
  }
  window.MAVS_TARJETA = tarjeta;
  window.MAVS = {
    precio: precio, img: img, wspLink: wspLink, porId: porId, esc: esc, fondo: fondo,
    carrito: function () { return carrito.slice(); },
    quitar: function (id) { carrito = carrito.filter(function (x) { return x !== id; }); guardar(); pintarCarrito(); },
    agregar: function (id) { agregar(id); }
  };

  /* ---------------- Carrito ---------------- */
  var carrito = [];
  try { carrito = JSON.parse(localStorage.getItem("mavs-carrito") || "[]"); } catch (e) { carrito = []; }
  carrito = carrito.filter(function (id) { var p = porId(id); return p && p.estado !== "vendida"; });

  function guardar() { try { localStorage.setItem("mavs-carrito", JSON.stringify(carrito)); } catch (e) {} }

  function pintarCarrito() {
    var lista = document.getElementById("carrito-items");
    var total = 0;
    if (!carrito.length) {
      lista.innerHTML = '<li class="carrito-vacio">Aún no agregas piezas. <br><a href="catalogo.html">Ver el catálogo</a></li>';
    } else {
      lista.innerHTML = carrito.map(function (id) {
        var p = porId(id); total += p.precio;
        return '<li><img src="' + img(p.imagen) + '" alt=""><div><span class="tec">' + T[p.tecnica].corto + '</span><h3>' + esc(p.nombre) + '</h3>' +
          '<button class="quitar" type="button" data-quitar="' + p.id + '">Quitar</button></div><span class="precio">' + precio(p.precio) + '</span></li>';
      }).join("");
    }
    document.getElementById("carrito-total").textContent = precio(total);
    var c = document.getElementById("contador-carrito");
    c.textContent = carrito.length; c.hidden = !carrito.length;
    document.querySelectorAll("[data-agregar]").forEach(function (b) {
      var id = Number(b.getAttribute("data-agregar"));
      var p = porId(id);
      if (!p || p.estado === "vendida") return;
      var esta = carrito.indexOf(id) > -1;
      b.classList.toggle("en-carrito", esta);
      b.textContent = esta ? "En carrito" : "Agregar";
    });
    var fb = document.getElementById("ficha-agregar");
    if (fb) {
      var fp = porId(Number(fb.dataset.id));
      var dentro = carrito.indexOf(fp.id) > -1;
      fb.textContent = dentro ? "Ya está en tu carrito" : "Agregar al carrito";
    }
    if (window.MAVS_PINTAR_PAGINA) window.MAVS_PINTAR_PAGINA();
  }

  function agregar(id) {
    var p = porId(id);
    if (!p || p.estado === "vendida") return;
    if (carrito.indexOf(id) === -1) carrito.push(id); /* cada pieza es única: máximo una unidad */
    guardar(); pintarCarrito(); abrirCarrito();
  }

  var panel = document.getElementById("carrito"), velo = document.getElementById("velo");
  function abrirCarrito() { panel.classList.add("abierto"); panel.setAttribute("aria-hidden", "false"); velo.hidden = false; }
  function cerrarCarrito() { panel.classList.remove("abierto"); panel.setAttribute("aria-hidden", "true"); velo.hidden = true; }
  document.getElementById("abrir-carrito").addEventListener("click", abrirCarrito);
  document.getElementById("cerrar-carrito").addEventListener("click", cerrarCarrito);
  velo.addEventListener("click", cerrarCarrito);

  document.addEventListener("keydown", function (e) { if (e.key === "Escape") cerrarCarrito(); });

  document.addEventListener("click", function (e) {
    var a = e.target.closest("[data-agregar]");
    if (a) { agregar(Number(a.getAttribute("data-agregar"))); return; }
    var q = e.target.closest("[data-quitar]");
    if (q) { var id = Number(q.getAttribute("data-quitar")); carrito = carrito.filter(function (x) { return x !== id; }); guardar(); pintarCarrito(); }
  });

  /* ---------------- Inicio: piezas destacadas ---------------- */
  var dest = document.getElementById("destacados");
  if (dest) {
    var orden = P.filter(function (p) { return p.destacado && p.estado !== "vendida"; }).concat(P.filter(function (p) { return !p.destacado && p.estado !== "vendida"; }));
    dest.innerHTML = '' +
      '<article class="editorial"><img src="../public/img/feria-pulseras-640.webp" alt="Pulseras tejidas en la feria" loading="lazy">' +
        '<div class="cuerpo"><h3>Hecho a mano</h3>' +
        '<p>Ninguna pieza de Mavs Jewelry se compra terminada para revenderla. Cada aro, collar y pulsera se diseña y se teje en el taller de María Angélica.</p>' +
        '<p>Las piezas de plata con piedra son únicas: cuando se venden, no vuelven.</p>' +
        '<a class="enlace-flecha" href="nosotros.html">Conocer la historia</a></div></article>' +
      orden.slice(0, 7).map(tarjeta).join("");
  }

  /* ---------------- Catálogo ---------------- */
  var grilla = document.getElementById("grilla-catalogo");
  if (grilla) {
    var estado = { tecnica: "todas", tipo: "todos", rango: "todos", orden: "recomendado" };
    var RANGOS = {
      todos: ["Todos los precios", 0, Infinity],
      bajo: ["Hasta $10.000", 0, 10000],
      medio: ["$10.001 a $18.000", 10001, 18000],
      alto: ["Más de $18.000", 18001, Infinity]
    };

    function leerHash() {
      var h = location.hash.replace("#", "");
      if (h === "plata" || h === "miyuki") { estado.tecnica = h; estado.rango = "todos"; }
      else if (h === "") { estado.tecnica = "todas"; }
    }

    function pasa(p, ignorar) {
      if (ignorar !== "tecnica" && estado.tecnica !== "todas" && p.tecnica !== estado.tecnica) return false;
      if (ignorar !== "tipo" && estado.tipo !== "todos" && p.tipo !== estado.tipo) return false;
      var r = RANGOS[estado.rango];
      if (ignorar !== "rango" && (p.precio < r[1] || p.precio > r[2])) return false;
      return true;
    }
    function cuenta(campo, valor) {
      return P.filter(function (p) {
        if (p.estado === "vendida" || !pasa(p, campo)) return false;
        if (campo === "tecnica") return valor === "todas" || p.tecnica === valor;
        if (campo === "tipo") return valor === "todos" || p.tipo === valor;
        var r = RANGOS[valor]; return p.precio >= r[1] && p.precio <= r[2];
      }).length;
    }

    function pintarFiltros() {
      var tec = [["todas", "Todas", "luna-dorada"], ["plata", T.plata.nombre, T.plata.mini], ["miyuki", T.miyuki.nombre, T.miyuki.mini]];
      document.getElementById("f-tecnica").innerHTML = tec.map(function (t) {
        return '<button class="opcion-tec" type="button" data-f="tecnica" data-v="' + t[0] + '" aria-pressed="' + (estado.tecnica === t[0]) + '"><img src="' + img(t[2]) + '" alt="">' + t[1] + '<span class="n">' + cuenta("tecnica", t[0]) + '</span></button>';
      }).join("");
      var tipos = ["todos"].concat(window.MAVS_TIPOS);
      document.getElementById("f-tipo").innerHTML = tipos.map(function (t) {
        var n = cuenta("tipo", t);
        if (t !== "todos" && n === 0 && estado.tipo !== t) return "";
        return '<button class="chip" type="button" data-f="tipo" data-v="' + t + '" aria-pressed="' + (estado.tipo === t) + '">' + (t === "todos" ? "Todos" : t) + '<span class="n">' + n + '</span></button>';
      }).join("");
      document.getElementById("f-rango").innerHTML = Object.keys(RANGOS).map(function (k) {
        return '<button class="chip" type="button" data-f="rango" data-v="' + k + '" aria-pressed="' + (estado.rango === k) + '">' + RANGOS[k][0] + '<span class="n">' + cuenta("rango", k) + '</span></button>';
      }).join("");
    }

    function pintarGrilla() {
      var lista = P.filter(function (p) { return p.estado !== "vendida" && pasa(p); });
      if (estado.orden === "menor") lista.sort(function (a, b) { return a.precio - b.precio; });
      else if (estado.orden === "mayor") lista.sort(function (a, b) { return b.precio - a.precio; });
      else lista.sort(function (a, b) { return (a.estado === "vendida") - (b.estado === "vendida") || (b.destacado ? 1 : 0) - (a.destacado ? 1 : 0); });
      grilla.innerHTML = lista.length ? lista.map(tarjeta).join("")
        : '<div class="vacio">No hay piezas con esos filtros. Prueba con otro tipo o rango de precio, o <a href="especialidad.html">pide una pieza personalizada</a>.</div>';
      var titulo = estado.tecnica === "todas" ? "Todas las piezas" : T[estado.tecnica].nombre;
      document.getElementById("titulo-resultados").textContent = titulo;
      document.getElementById("cuenta-resultados").innerHTML = "<strong>" + lista.length + "</strong> " + (lista.length === 1 ? "pieza" : "piezas");
      pintarCarrito();
    }
    function todo() { pintarFiltros(); pintarGrilla(); }

    document.querySelectorAll(".filtros").forEach(function (zona) {
      zona.addEventListener("click", function (e) {
        var b = e.target.closest("[data-f]"); if (!b) return;
        estado[b.getAttribute("data-f")] = b.getAttribute("data-v");
        todo();
      });
    });
    document.getElementById("orden").addEventListener("change", function () { estado.orden = this.value; pintarGrilla(); });
    document.getElementById("limpiar").addEventListener("click", function () {
      estado = { tecnica: "todas", tipo: "todos", rango: "todos", orden: estado.orden }; todo();
    });
    var vf = document.getElementById("ver-filtros"), pf = document.getElementById("panel-filtros");
    function panel(abrir) {
      pf.classList.toggle("abierto", abrir); vf.setAttribute("aria-expanded", String(abrir));
      velo.hidden = !abrir;
    }
    vf.addEventListener("click", function () { panel(true); });
    document.getElementById("cerrar-filtros").addEventListener("click", function () { panel(false); });
    document.getElementById("aplicar-filtros").addEventListener("click", function () { panel(false); });
    velo.addEventListener("click", function () { panel(false); });
    window.addEventListener("hashchange", function () { leerHash(); todo(); });
    leerHash(); todo();
  }

  /* ---------------- Contacto: mensaje para WhatsApp ---------------- */
  var form = document.getElementById("form-consulta");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!form.reportValidity()) return;
      var d = new FormData(form);
      var texto = "Hola María Angélica, soy " + d.get("nombre") + ".\nMotivo: " + d.get("motivo") + ".\n\n" + d.get("mensaje");
      var link = document.getElementById("link-wsp");
      link.href = wspLink(texto);
      document.getElementById("resultado-form").hidden = false;
      link.focus();
    });
  }

  pintarCarrito();
})();
