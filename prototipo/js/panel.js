/* ==========================================================================
   Mavs Jewelry — panel de administración (prototipo con datos de ejemplo).
   En PHP: ProductoController, PedidoController y ContenidoController.
   ========================================================================== */
(function () {
  "use strict";
  var T = window.MAVS_TECNICAS;
  var TIPO = { Aros: "ARO", Collares: "COL", Pulseras: "PUL", Dijes: "DIJ", Anillos: "ANI" };
  var FONDOS = { noche: "#dfdbe9", ciruela: "#e7d4ec", rosa: "#f8dce9", "rosa-oscuro": "#efc8dc", oro: "#f1e3c8" };
  function precio(n) { return "$" + Number(n).toLocaleString("es-CL"); }
  function esc(s) { return String(s).replace(/[&<>"]/g, function (c) { return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[c]; }); }
  function codigo(p) { return (p.tecnica === "plata" ? "PL" : "MA") + "-" + TIPO[p.tipo] + "-" + String(p.id).padStart(4, "0"); }

  /* Estados del producto: borrador, publicado, oculto, reservado, vendido */
  var productos = window.MAVS_PRODUCTOS.map(function (p) {
    var e = p.estado === "vendida" ? "vendido" : "publicado";
    if (p.id === 3) e = "reservado";
    if (p.id === 19) e = "borrador";
    if (p.id === 18) e = "oculto";
    return Object.assign({}, p, { est: e, reservaMin: p.id === 3 ? 11 : 0 });
  });

  var pedidos = [
    { cod: "MJ-2026-000048", fecha: "26-11-2026 18:42", clienta: "Camila R.", comuna: "Ñuñoa, Metropolitana", total: 14000, est: "pendiente", piezas: [3], ferias: "Sí" },
    { cod: "MJ-2026-000047", fecha: "25-11-2026 21:10", clienta: "Javiera M.", comuna: "Viña del Mar, Valparaíso", total: 28000, est: "pagado", piezas: [2, 9], ferias: "No" },
    { cod: "MJ-2026-000046", fecha: "24-11-2026 12:03", clienta: "Francisca T.", comuna: "Maipú, Metropolitana", total: 19000, est: "en_preparacion", piezas: [6], ferias: "Sí" },
    { cod: "MJ-2026-000045", fecha: "22-11-2026 09:55", clienta: "Daniela P.", comuna: "Concepción, Biobío", total: 12000, est: "enviado", piezas: [8], ferias: "No" },
    { cod: "MJ-2026-000044", fecha: "21-11-2026 16:20", clienta: "Valentina S.", comuna: "Providencia, Metropolitana", total: 17000, est: "expirado", piezas: [7], ferias: "Sí" },
    { cod: "MJ-2026-000043", fecha: "20-11-2026 11:31", clienta: "Antonia L.", comuna: "Temuco, La Araucanía", total: 15000, est: "reembolsado", piezas: [4], ferias: "No", nota: "Pago aprobado después de vencer la reserva; la pieza ya se había vendido en feria. Devolución solicitada en Mercado Pago." }
  ];
  var NOMBRE_EST = { pendiente: "Pendiente de pago", pagado: "Pagado", rechazado: "Rechazado", expirado: "Expirado", en_preparacion: "En preparación", enviado: "Enviado", reembolsado: "Reembolsado" };
  var NOMBRE_PROD = { publicado: "Publicado", borrador: "Borrador", oculto: "Oculto", reservado: "Reservado", vendido: "Vendido" };

  /* ---------------- Navegación ---------------- */
  document.querySelector(".panel-nav").addEventListener("click", function (e) {
    var b = e.target.closest("[data-vista]"); if (!b) return;
    document.querySelectorAll("[data-vista]").forEach(function (x) { x.removeAttribute("aria-current"); });
    b.setAttribute("aria-current", "page");
    document.querySelectorAll(".vista").forEach(function (v) { v.hidden = v.id !== "vista-" + b.getAttribute("data-vista"); });
  });

  function indicadores() {
    document.getElementById("ind-preparar").textContent = pedidos.filter(function (p) { return p.est === "pagado" || p.est === "en_preparacion"; }).length;
    document.getElementById("ind-publicadas").textContent = productos.filter(function (p) { return p.est === "publicado"; }).length;
    document.getElementById("ind-reservadas").textContent = productos.filter(function (p) { return p.est === "reservado"; }).length;
  }

  /* ---------------- Productos ---------------- */
  var filtroProd = "todos", texto = "";
  var aviso = document.getElementById("aviso-producto");
  function avisar(msg, tipo) { aviso.textContent = msg; aviso.className = "aviso " + tipo; aviso.hidden = false; }

  function pintarProductos() {
    var estados = ["todos", "publicado", "reservado", "borrador", "oculto", "vendido"];
    document.getElementById("filtro-estado").innerHTML = estados.map(function (e) {
      var n = e === "todos" ? productos.length : productos.filter(function (p) { return p.est === e; }).length;
      return '<button class="chip" type="button" data-est="' + e + '" aria-pressed="' + (filtroProd === e) + '">' + (e === "todos" ? "Todos" : NOMBRE_PROD[e]) + '<span class="n">' + n + '</span></button>';
    }).join("");
    var lista = productos.filter(function (p) {
      return (filtroProd === "todos" || p.est === filtroProd) &&
        (!texto || (p.nombre + " " + codigo(p)).toLowerCase().indexOf(texto) > -1);
    });
    document.getElementById("lista-productos").innerHTML = lista.length ? lista.map(function (p) {
      var acciones = p.est === "vendido" ? '<button type="button" data-acc="editar" data-id="' + p.id + '">Editar</button>' :
        '<button type="button" data-acc="editar" data-id="' + p.id + '">Editar</button>' +
        '<button type="button" data-acc="' + (p.est === "oculto" ? "publicar" : "ocultar") + '" data-id="' + p.id + '"' + (p.est === "reservado" ? " disabled" : "") + '>' + (p.est === "oculto" ? "Publicar" : "Ocultar") + '</button>' +
        '<button type="button" class="fuerte" data-acc="vender" data-id="' + p.id + '">Marcar vendida</button>';
      return '<li class="fila-producto">' +
        '<span class="mini" style="background:' + FONDOS[p.fondo] + '"><img src="../public/img/' + p.imagen + '-640.webp" alt=""></span>' +
        '<div class="info"><span class="codigo">' + codigo(p) + '</span><h3>' + esc(p.nombre) + '</h3><span class="sub">' + T[p.tecnica].corto + ' · ' + p.tipo + ' · ' + precio(p.precio) + '</span></div>' +
        '<span class="estado e-' + p.est + '">' + NOMBRE_PROD[p.est] + (p.est === "reservado" ? " · " + p.reservaMin + " min" : "") + '</span>' +
        '<div class="acc">' + acciones + '</div></li>';
    }).join("") : '<li class="vacio">No hay piezas con ese filtro.</li>';
    indicadores();
  }

  document.getElementById("filtro-estado").addEventListener("click", function (e) {
    var b = e.target.closest("[data-est]"); if (!b) return; filtroProd = b.getAttribute("data-est"); pintarProductos();
  });
  document.getElementById("buscar").addEventListener("input", function () { texto = this.value.trim().toLowerCase(); pintarProductos(); });

  document.getElementById("lista-productos").addEventListener("click", function (e) {
    var b = e.target.closest("[data-acc]"); if (!b) return;
    var p = productos.filter(function (x) { return x.id === Number(b.getAttribute("data-id")); })[0];
    var acc = b.getAttribute("data-acc");
    if (acc === "vender") {
      if (p.est === "reservado") {
        avisar("“" + p.nombre + "” está reservada por un pago en curso (quedan " + p.reservaMin + " min). Podrás marcarla como vendida cuando termine la reserva.", "error");
        return;
      }
      p.est = "vendido"; avisar("“" + p.nombre + "” quedó como vendida y dejó de mostrarse en el catálogo.", "ok");
    } else if (acc === "ocultar") { p.est = "oculto"; avisar("“" + p.nombre + "” quedó oculta.", "ok"); }
    else if (acc === "publicar") { p.est = "publicado"; avisar("“" + p.nombre + "” está publicada.", "ok"); }
    else if (acc === "editar") { abrirForm(p); return; }
    pintarProductos();
  });

  var form = document.getElementById("form-pieza");
  function abrirForm(p) {
    form.hidden = false;
    document.getElementById("titulo-form").textContent = p ? "Editar pieza" : "Nueva pieza";
    document.getElementById("f-nombre").value = p ? p.nombre : "";
    document.getElementById("f-precio").value = p ? p.precio : "";
    document.getElementById("f-tecnica").value = p ? p.tecnica : "plata";
    document.getElementById("f-tipo").value = p ? p.tipo : "Aros";
    document.getElementById("f-materiales").value = p ? p.materiales : "";
    document.getElementById("f-desc").value = p ? p.descripcion : "";
    document.getElementById("f-estado").value = p && ["borrador", "publicado", "oculto"].indexOf(p.est) > -1 ? p.est : "borrador";
    form.scrollIntoView({ behavior: "smooth", block: "start" });
    document.getElementById("f-nombre").focus({ preventScroll: true });
  }
  function cerrarForm() { form.hidden = true; }
  document.getElementById("nueva-pieza").addEventListener("click", function () { abrirForm(null); });
  document.getElementById("cerrar-form").addEventListener("click", cerrarForm);
  document.getElementById("cancelar-form").addEventListener("click", cerrarForm);
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var nombre = document.getElementById("f-nombre"), pr = document.getElementById("f-precio");
    var ok = nombre.value.trim().length >= 3 && Number(pr.value) >= 1000 && Number(pr.value) <= 1000000;
    nombre.closest(".campo").classList.toggle("con-error", nombre.value.trim().length < 3);
    pr.closest(".campo").classList.toggle("con-error", !(Number(pr.value) >= 1000 && Number(pr.value) <= 1000000));
    if (!ok) { avisar("Revisa el nombre (mínimo 3 letras) y el precio (entre $1.000 y $1.000.000).", "error"); return; }
    cerrarForm(); avisar("Pieza guardada.", "ok");
    window.scrollTo({ top: 0, behavior: "smooth" });
  });

  /* ---------------- Pedidos ---------------- */
  var filtroPed = "todos", actual = pedidos[1].cod;
  function pintarPedidos() {
    var est = ["todos", "pendiente", "pagado", "en_preparacion", "enviado", "expirado", "reembolsado"];
    document.getElementById("filtro-pedido").innerHTML = est.map(function (e) {
      return '<button class="chip" type="button" data-fp="' + e + '" aria-pressed="' + (filtroPed === e) + '">' + (e === "todos" ? "Todos" : NOMBRE_EST[e]) + '</button>';
    }).join("");
    var lista = pedidos.filter(function (p) { return filtroPed === "todos" || p.est === filtroPed; });
    document.getElementById("lista-pedidos").innerHTML = lista.length ? lista.map(function (p) {
      return '<li><button type="button" class="fila-pedido" data-ped="' + p.cod + '"' + (p.cod === actual ? ' aria-current="true"' : "") + '>' +
        '<span class="codigo">' + p.cod + '</span><strong>' + esc(p.clienta) + '</strong><span class="sub">' + p.fecha + '</span>' +
        '<span class="estado e-' + p.est + '">' + NOMBRE_EST[p.est] + '</span><span class="precio">' + precio(p.total) + '</span></button></li>';
    }).join("") : '<li class="vacio">No hay pedidos en este estado.</li>';
    pintarDetalle();
  }
  function pintarDetalle() {
    var p = pedidos.filter(function (x) { return x.cod === actual; })[0];
    var piezas = p.piezas.map(function (id) { var x = window.MAVS_PRODUCTOS.filter(function (y) { return y.id === id; })[0]; return "<li>" + esc(x.nombre) + " · " + precio(x.precio) + "</li>"; }).join("");
    var editable = p.est === "pagado" || p.est === "en_preparacion" || p.est === "enviado";
    document.getElementById("detalle-pedido").innerHTML = '' +
      '<span class="codigo">' + p.cod + '</span><h2>' + esc(p.clienta) + '</h2>' +
      '<span class="estado e-' + p.est + '">' + NOMBRE_EST[p.est] + '</span>' +
      '<dl><dt>Fecha</dt><dd>' + p.fecha + '</dd><dt>Despacho</dt><dd>Starken por pagar · ' + p.comuna + '</dd><dt>¿Nos conocía de ferias?</dt><dd>' + p.ferias + '</dd><dt>Total</dt><dd>' + precio(p.total) + '</dd></dl>' +
      '<h3>Piezas</h3><ul class="piezas-pedido">' + piezas + '</ul>' +
      (p.nota ? '<p class="aviso">' + p.nota + '</p>' : '') +
      (editable ? '<div class="campo"><label for="cambio-estado">Cambiar estado</label><select id="cambio-estado">' +
        ["pagado", "en_preparacion", "enviado"].map(function (e) { return '<option value="' + e + '"' + (p.est === e ? " selected" : "") + '>' + NOMBRE_EST[e] + '</option>'; }).join("") +
        '</select></div><div class="acciones"><button class="btn btn-noche" type="button" id="guardar-estado">Guardar estado</button>' +
        '<button class="btn btn-linea" type="button" id="reembolso">Registrar reembolso</button></div>' +
        '<div class="form-reembolso" id="form-reembolso" hidden><div class="campo"><label for="motivo-r">Motivo</label><select id="motivo-r"><option>Retracto (10 días)</option><option>Garantía legal (6 meses)</option></select></div>' +
        '<button class="btn btn-noche" type="button" id="confirmar-r">Confirmar reembolso</button><small>La devolución del dinero se hace en Mercado Pago.</small></div>' :
        '<p class="sub">Este pedido no admite cambios de estado.</p>');
    if (editable) {
      document.getElementById("guardar-estado").addEventListener("click", function () { p.est = document.getElementById("cambio-estado").value; pintarPedidos(); indicadores(); });
      document.getElementById("reembolso").addEventListener("click", function () { document.getElementById("form-reembolso").hidden = false; });
      document.getElementById("confirmar-r").addEventListener("click", function () { p.est = "reembolsado"; p.nota = "Reembolso registrado: " + document.getElementById("motivo-r").value + "."; pintarPedidos(); indicadores(); });
    }
  }
  document.getElementById("filtro-pedido").addEventListener("click", function (e) { var b = e.target.closest("[data-fp]"); if (!b) return; filtroPed = b.getAttribute("data-fp"); pintarPedidos(); });
  document.getElementById("lista-pedidos").addEventListener("click", function (e) { var b = e.target.closest("[data-ped]"); if (!b) return; actual = b.getAttribute("data-ped"); pintarPedidos(); });

  /* ---------------- Contenidos ---------------- */
  document.querySelectorAll(".form-contenido").forEach(function (f) {
    f.addEventListener("submit", function (e) { e.preventDefault(); var a = document.getElementById("aviso-contenido"); a.hidden = false; a.scrollIntoView({ block: "nearest" }); });
  });

  pintarProductos(); pintarPedidos();
})();
