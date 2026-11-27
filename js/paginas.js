/* ==========================================================================
   Mavs Jewelry — ficha de producto, carrito y datos de despacho.
   Se carga después de mavs.js. En la versión PHP estos datos salen de MySQL
   y el formulario se envía a CompraController (validación en el servidor).
   ========================================================================== */
(function () {
  "use strict";
  var M = window.MAVS, T = window.MAVS_TECNICAS, I = window.MAVS_ICONOS;
  var pagina = document.body.getAttribute("data-pagina");

  /* Significado de las piedras (textos de la clienta, js/piedras.js) */
  var WSP = "56989234239";
  function significados(texto) {
    var t = String(texto).toLowerCase();
    return (window.MAVS_PIEDRAS || []).filter(function (p) {
      var base = p.nombre.toLowerCase().replace(" verde", "");
      return t.indexOf(base) > -1;
    });
  }

  /* ---------------- Ficha de producto ---------------- */
  if (pagina === "producto") {
    var id = Number(new URLSearchParams(location.search).get("id")) || 1;
    var p = M.porId(id);
    var raiz = document.getElementById("ficha");
    if (!p || p.estado === "vendida") {
      raiz.innerHTML = '<div class="contenedor vacio-ficha"><h1>Esta pieza ya no está disponible</h1>' +
        '<p>Las piezas únicas dejan de mostrarse cuando se venden. Puedes ver otras piezas o pedir una parecida a tu medida.</p>' +
        '<div class="acciones"><a class="btn btn-noche" href="catalogo.html">Ver catálogo</a><a class="btn btn-linea" href="especialidad.html">Pieza personalizada</a></div></div>';
      return;
    }
    document.title = p.nombre + " · Mavs Jewelry";
    var fotos = p.imagenes || [p.imagen];
    var sig = significados(p.piedras);
    raiz.innerHTML = '' +
      '<div class="contenedor">' +
        '<nav class="migas oscuras" aria-label="Ruta"><a href="index.html">Inicio</a> › <a href="catalogo.html">Catálogo</a> › <a href="catalogo.html#' + p.tecnica + '">' + T[p.tecnica].nombre + '</a></nav>' +
      '</div>' +
      '<div class="contenedor ficha-pagina">' +
        '<div class="galeria" style="--fondo:' + M.fondo(p) + '">' +
          (p.unica ? '<span class="etiqueta-ficha">Pieza única</span>' : '') +
          '<img id="foto-principal" src="' + M.img(fotos[0], 1200) + '" alt="' + M.esc(p.nombre) + '" width="1200" height="1500">' +
          (fotos.length > 1 ? '<div class="miniaturas">' + fotos.map(function (f, n) {
            return '<button type="button" data-foto="' + f + '" aria-label="Ver foto ' + (n + 1) + '"' + (n === 0 ? ' aria-current="true"' : '') + '><img src="' + M.img(f) + '" alt=""></button>';
          }).join("") + '</div>' : '') +
        '</div>' +
        '<div class="ficha-datos">' +
          '<span class="rotulo">' + T[p.tecnica].nombre + ' · ' + p.tipo + '</span>' +
          '<h1>' + M.esc(p.nombre) + '</h1>' +
          '<div class="precio grande">' + M.precio(p.precio) + '</div>' +
          '<p>' + M.esc(p.descripcion) + '</p>' +
          '<span class="hecho-mano">' + I.estrella + '100% hecha a mano por María Angélica</span>' +
          '<div class="acciones">' +
            '<button class="btn btn-noche" type="button" id="ficha-agregar" data-id="' + p.id + '">Agregar al carrito</button>' +
            '<a class="btn btn-linea" href="' + M.wspLink("Hola, vi la pieza “" + p.nombre + "” (" + M.precio(p.precio) + ") en la página de Mavs Jewelry y quiero consultar por ella.") + '" target="_blank" rel="noopener">' + I.wsp + 'Consultar por WhatsApp</a>' +
          '</div>' +
          '<dl class="detalles">' +
            '<dt>Materiales</dt><dd>' + M.esc(p.materiales) + '</dd>' +
            '<dt>Piedras</dt><dd>' + M.esc(p.piedras) + '</dd>' +
            '<dt>Disponibilidad</dt><dd>' + (p.unica ? "Pieza única, 1 disponible" : "Disponible") + '</dd>' +
          '</dl>' +
          (sig.length ? '<div class="significados">' + sig.map(function (s) {
            return '<div>' + window.MAVS_GEMA(s.nombre, "mini") + '<div><h3>' + s.nombre + '</h3><p>' + s.significado + '</p></div></div>';
          }).join("") + '<a class="enlace-politicas" href="piedras.html#significados">Significado de todas las piedras</a></div>' : '') +
          '<ul class="garantias">' +
            '<li><strong>Garantía legal de 6 meses</strong> por fallas de fabricación.</li>' +
            '<li><strong>Retracto de 10 días</strong> desde que la recibes.</li>' +
            '<li><strong>Despacho por Starken</strong>, por pagar al recibir.</li>' +
          '</ul>' +
          '<a class="enlace-politicas" href="contacto.html#politicas">Ver políticas de venta</a>' +
        '</div>' +
      '</div>';

    document.getElementById("ficha-agregar").addEventListener("click", function () { M.agregar(p.id); });
    raiz.addEventListener("click", function (e) {
      var b = e.target.closest("[data-foto]"); if (!b) return;
      document.getElementById("foto-principal").src = M.img(b.getAttribute("data-foto"), 1200);
      raiz.querySelectorAll("[data-foto]").forEach(function (x) { x.removeAttribute("aria-current"); });
      b.setAttribute("aria-current", "true");
    });

    var otras = window.MAVS_PRODUCTOS.filter(function (x) { return x.id !== p.id && x.tecnica === p.tecnica && x.estado !== "vendida"; }).slice(0, 4);
    document.getElementById("relacionadas").innerHTML = otras.map(window.MAVS_TARJETA).join("");
    window.MAVS_PINTAR_PAGINA = function () {};
  }

  /* ---------------- Piedras ---------------- */
  if (pagina === "piedras") {
    var G = window.MAVS_GEMA;
    function wsp(t) { return "https://wa.me/" + WSP + "?text=" + encodeURIComponent(t); }
    document.getElementById("lista-guia").innerHTML = window.MAVS_GUIA.map(function (g) {
      return '<article class="situacion"><h3>' + g[0] + '</h3>' +
        '<ul class="trio">' + g[1].map(function (n) { return '<li>' + G(n) + '<span>' + n + '</span></li>'; }).join("") + '</ul>' +
        '<a class="enlace-flecha" href="' + wsp("Hola María Angélica, " + g[0].charAt(0).toLowerCase() + g[0].slice(1) + " Me gustaría una pieza con " + g[1].slice(0, 2).join(", ") + " y " + g[1][2] + ".") + '" target="_blank" rel="noopener">Quiero una pieza así</a></article>';
    }).join("");
    document.getElementById("lista-combinaciones").innerHTML = window.MAVS_COMBINACIONES.map(function (c) {
      return '<li><div class="gemas">' + c[1].map(function (n) { return G(n); }).join("") + '</div>' +
        '<h3>' + c[0] + '</h3><p class="piedras-combo">' + c[1].join(" + ") + '</p><p>' + c[2] + '</p></li>';
    }).join("");
    document.getElementById("lista-minerales").innerHTML = window.MAVS_PIEDRAS.map(function (p) {
      return '<article class="mineral" id="' + p.id + '">' + G(p.nombre, "grande") + '<div><h3>' + p.nombre + '</h3><p>' + p.significado + '</p></div></article>';
    }).join("");
  }

  /* ---------------- Carrito (página) ---------------- */
  if (pagina === "carrito") {
    window.MAVS_PINTAR_PAGINA = function () {
      var ids = M.carrito(), total = 0;
      var lista = document.getElementById("lista-carrito");
      if (!ids.length) {
        lista.innerHTML = '<div class="vacio">Tu carrito está vacío. <a href="catalogo.html">Ver el catálogo</a></div>';
        document.getElementById("resumen").hidden = true;
        return;
      }
      document.getElementById("resumen").hidden = false;
      lista.innerHTML = ids.map(function (id) {
        var p = M.porId(id); total += p.precio;
        return '<article class="linea-carrito">' +
          '<a class="mini-foto" href="producto.html?id=' + p.id + '" style="--fondo:' + M.fondo(p) + '"><img src="' + M.img(p.imagen) + '" alt="' + M.esc(p.nombre) + '"></a>' +
          '<div><span class="tec">' + T[p.tecnica].corto + ' · ' + p.tipo + '</span><h3>' + M.esc(p.nombre) + '</h3>' +
          '<span class="cantidad">' + (p.unica ? "Pieza única · 1 unidad" : "1 unidad") + '</span>' +
          '<button class="quitar" type="button" data-quitar="' + p.id + '">Quitar</button></div>' +
          '<span class="precio">' + M.precio(p.precio) + '</span></article>';
      }).join("");
      document.getElementById("subtotal").textContent = M.precio(total);
      document.getElementById("total-carrito").textContent = M.precio(total);
      document.getElementById("n-piezas").textContent = ids.length + (ids.length === 1 ? " pieza" : " piezas");
    };
    window.MAVS_PINTAR_PAGINA();
  }

  /* ---------------- Datos de despacho ---------------- */
  if (pagina === "despacho") {
    var REGIONES = ["Arica y Parinacota", "Tarapacá", "Antofagasta", "Atacama", "Coquimbo", "Valparaíso", "Metropolitana de Santiago",
      "Libertador General Bernardo O'Higgins", "Maule", "Ñuble", "Biobío", "La Araucanía", "Los Ríos", "Los Lagos",
      "Aysén del General Carlos Ibáñez del Campo", "Magallanes y de la Antártica Chilena"];
    var sel = document.getElementById("region");
    sel.innerHTML = '<option value="">Elige tu región</option>' + REGIONES.map(function (r) { return "<option>" + r + "</option>"; }).join("");

    /* Costo y plazo estimados de Starken por pagar (valores de ejemplo: la clienta los define desde el panel, RF19) */
    var ESTIMADO = { rm: ["$3.500 a $5.000", "1 a 3 días hábiles"], otras: ["$4.500 a $7.500", "2 a 5 días hábiles"] };
    function pintarEstimado() {
      var e = sel.value === "Metropolitana de Santiago" ? ESTIMADO.rm : ESTIMADO.otras;
      document.getElementById("est-costo").textContent = sel.value ? e[0] : "Elige tu región";
      document.getElementById("est-plazo").textContent = sel.value ? e[1] : "—";
    }
    sel.addEventListener("change", pintarEstimado); pintarEstimado();

    window.MAVS_PINTAR_PAGINA = function () {
      var ids = M.carrito(), total = 0;
      document.getElementById("resumen-items").innerHTML = ids.length ? ids.map(function (id) {
        var p = M.porId(id); total += p.precio;
        return '<li><span class="mini-foto" style="--fondo:' + M.fondo(p) + '"><img src="' + M.img(p.imagen) + '" alt=""></span><span>' + M.esc(p.nombre) + '</span><span class="precio">' + M.precio(p.precio) + '</span></li>';
      }).join("") : '<li class="sin-piezas">No hay piezas en tu carrito. <a href="catalogo.html">Ver catálogo</a></li>';
      document.getElementById("total-despacho").textContent = M.precio(total);
      document.getElementById("pagar").disabled = !ids.length;
    };
    window.MAVS_PINTAR_PAGINA();

    function rutValido(rut) {
      var r = rut.replace(/[.\s]/g, "").toUpperCase();
      var m = /^(\d{7,8})-([\dK])$/.exec(r); if (!m) return false;
      var suma = 0, mul = 2;
      for (var i = m[1].length - 1; i >= 0; i--) { suma += Number(m[1][i]) * mul; mul = mul === 7 ? 2 : mul + 1; }
      var dv = 11 - (suma % 11);
      return m[2] === (dv === 11 ? "0" : dv === 10 ? "K" : String(dv));
    }
    var REGLAS = {
      nombre: [function (v) { return /^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ' -]{3,120}$/.test(v.trim()); }, "Escribe tu nombre completo (solo letras)."],
      rut: [rutValido, "Revisa tu RUT: formato 12.345.678-9 y dígito verificador."],
      correo: [function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()); }, "Escribe un correo válido, por ejemplo nombre@correo.cl."],
      telefono: [function (v) { return /^9\d{8}$/.test(v.replace(/\D/g, "").replace(/^56/, "")); }, "Escribe un celular de 9 dígitos que empiece con 9."],
      direccion: [function (v) { return v.trim().length >= 5 && v.length <= 200; }, "Escribe la calle y el número."],
      comuna: [function (v) { return v.trim().length >= 3; }, "Escribe tu comuna."],
      region: [function (v) { return REGIONES.indexOf(v) > -1; }, "Elige tu región de la lista."],
      ferias: [function (v) { return v === "Sí" || v === "No"; }, "Cuéntanos si nos conocías de ferias."],
      politicas: [function (v, el) { return el.checked; }, "Debes aceptar las políticas de venta para comprar."],
      privacidad: [function (v, el) { return el.checked; }, "Debes aceptar la política de privacidad para comprar."]
    };
    var form = document.getElementById("form-despacho");
    function revisar(campo) {
      var el = form.elements[campo];
      var nodo = el.length && !el.tagName ? el[0] : el;
      var ok = REGLAS[campo][0](el.value || "", nodo);
      var msg = document.getElementById("err-" + campo);
      var cont = nodo.closest(".campo, .casilla, fieldset");
      cont.classList.toggle("con-error", !ok);
      msg.textContent = ok ? "" : REGLAS[campo][1];
      if (nodo.setAttribute) nodo.setAttribute("aria-invalid", String(!ok));
      return ok;
    }
    Object.keys(REGLAS).forEach(function (c) {
      var el = form.elements[c];
      (el.length && !el.tagName ? Array.prototype.slice.call(el) : [el]).forEach(function (n) {
        n.addEventListener("change", function () { revisar(c); });
        if (n.type !== "checkbox" && n.type !== "radio") n.addEventListener("blur", function () { if (n.value) revisar(c); });
      });
    });
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var malos = Object.keys(REGLAS).filter(function (c) { return !revisar(c); });
      var aviso = document.getElementById("aviso-envio");
      if (malos.length) {
        aviso.className = "aviso error"; aviso.hidden = false;
        aviso.textContent = "Revisa " + (malos.length === 1 ? "1 campo marcado" : malos.length + " campos marcados") + " antes de pagar. Tus datos se conservan.";
        var primero = form.elements[malos[0]]; (primero.length && !primero.tagName ? primero[0] : primero).focus();
        return;
      }
      aviso.className = "aviso ok"; aviso.hidden = false;
      aviso.textContent = "Prototipo de diseño: aquí el sitio registra el pedido, reserva tus piezas por 15 minutos y te lleva a Mercado Pago para pagar con tarjeta.";
    });
  }
})();
