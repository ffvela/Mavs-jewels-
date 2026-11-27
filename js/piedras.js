/* ==========================================================================
   Mavs Jewelry — significado de las piedras (textos entregados por la clienta).
   Lo usan la página Piedras y la ficha de producto. En la versión PHP
   corresponde a la tabla `piedra` (nombre, significado).
   Cada piedra tiene dos colores para dibujar su gema: [claro, oscuro].
   ========================================================================== */
window.MAVS_PIEDRAS = [
  { id: "amatista", nombre: "Amatista", significado: "Entrega paz y calma, y aleja el estrés. Favorece el equilibrio.", color: ["#c9a3e0", "#5b2d7e"] },
  { id: "aguamarina", nombre: "Aguamarina", significado: "Protege el alma y conecta con la intuición. Ideal para abrir el corazón.", color: ["#cfe9f7", "#5aa6d6"] },
  { id: "granate", nombre: "Granate", significado: "Te da energía, fuerza y vitalidad. Ayuda a superar miedos.", color: ["#d9474f", "#5c0d16"] },
  { id: "cuarzo-rosa", nombre: "Cuarzo rosa", significado: "Aporta amor propio, ternura y armonía. Mejora las relaciones.", color: ["#fdeef4", "#e8a3be"] },
  { id: "citrino", nombre: "Citrino", significado: "Atrae la abundancia, alegra y da confianza.", color: ["#ffe08a", "#e09a1c"] },
  { id: "cornalina", nombre: "Cornalina", significado: "Activa la creatividad y el coraje. Te impulsa a avanzar.", color: ["#ffa36b", "#c43a0c"] },
  { id: "cuarzo-cristal", nombre: "Cuarzo cristal", significado: "Amplifica la energía, potencia intenciones y armoniza.", color: ["#ffffff", "#cfd6dc"] },
  { id: "aventurina", nombre: "Aventurina verde", significado: "Atrae la buena suerte, calma la mente y fomenta la prosperidad.", color: ["#8fd6a8", "#237a4a"] },
  { id: "lapislazuli", nombre: "Lapislázuli", significado: "Estimula la sabiduría, la comunicación y la conexión espiritual.", color: ["#6f8de0", "#16286b"] },
  { id: "ojo-de-tigre", nombre: "Ojo de tigre", significado: "Brinda protección, fortalece la voluntad y atrae estabilidad.", color: ["#d19a44", "#4d2e10"], franjas: true },
  { id: "turmalina-negra", nombre: "Turmalina negra", significado: "Escudo protector contra malas vibras. Te da seguridad.", color: ["#5a5458", "#0d0b0d"] },
  { id: "obsidiana", nombre: "Obsidiana", significado: "Limpia energías, te protege y ayuda a soltar lo negativo.", color: ["#6a5d6c", "#140a16"] },
  { id: "turquesa", nombre: "Turquesa", significado: "Aporta equilibrio, calma y favorece la comunicación.", color: ["#9ce6e2", "#1c9a9a"] },
  { id: "labradorita", nombre: "Labradorita", significado: "Activa la intuición, equilibra el aura y te conecta con tu propósito.", color: ["#8fb7e8", "#3b4148"] },
  { id: "jaspe-rojo", nombre: "Jaspe rojo", significado: "Te da fuerza, resistencia y energía. Ideal para nuevos comienzos.", color: ["#f0745a", "#9a1f12"] },
  { id: "malaquita", nombre: "Malaquita", significado: "Transforma energías, protege y estimula el crecimiento personal.", color: ["#5fcf8f", "#0c5a35"], franjas: true },
  { id: "jade-rojo", nombre: "Jade rojo", significado: "Favorece la vitalidad, la alegría y la pasión por la vida.", color: ["#ff6b6b", "#a3121e"] },
  { id: "onix", nombre: "Ónix", significado: "Aporta estabilidad, protección y fortaleza interior.", color: ["#4a4a4a", "#050505"] },
  { id: "cuarzo-ahumado", nombre: "Cuarzo ahumado", significado: "Disipa la ansiedad, enraíza y brinda tranquilidad.", color: ["#c9a98a", "#5b4330"] }
];

/* Piedras que aparecen en la guía y las combinaciones, sin texto propio */
window.MAVS_COLORES_EXTRA = {
  "Pirita": ["#e8dca0", "#7a6a2a"], "Jade": ["#7fd49a", "#1f7a45"], "Piedra luna": ["#ffffff", "#c8d3e6"],
  "Rodonita": ["#f3a6b8", "#9b3a55"], "Amazonita": ["#a8ecdc", "#2a9c8a"], "Howlita": ["#ffffff", "#bdb8bd"],
  "Prehnita": ["#e6efc2", "#9fb26a"], "Sodalita": ["#6f8fd6", "#1a2c6e"], "Selenita": ["#ffffff", "#dcdcdc"],
  "Aventurina": ["#8fd6a8", "#237a4a"]
};

window.MAVS_GUIA = [
  ["Estoy con mucha ansiedad.", ["Amatista", "Obsidiana", "Aguamarina"]],
  ["Estoy agotada/o.", ["Aventurina", "Cuarzo rosa", "Obsidiana"]],
  ["Tengo la cabeza a mil.", ["Sodalita", "Amatista", "Lapislázuli"]],
  ["Siento que necesito protección.", ["Obsidiana", "Turmalina negra", "Labradorita"]],
  ["Estoy atravesando una ruptura.", ["Cuarzo rosa", "Piedra luna", "Amatista"]],
  ["Estoy bajón / sin energía.", ["Cornalina", "Citrino", "Obsidiana"]],
  ["Estoy acumulando mucha bronca.", ["Turmalina negra", "Cuarzo ahumado", "Jaspe rojo"]],
  ["Quiero atraer abundancia.", ["Citrino", "Jade", "Pirita"]],
  ["Estoy empezando una nueva etapa.", ["Aventurina", "Piedra luna", "Cuarzo cristal"]],
  ["Necesito trabajar el amor propio.", ["Cuarzo rosa", "Rodonita", "Amatista"]],
  ["Necesito calma.", ["Amazonita", "Howlita", "Prehnita"]],
  ["Necesito fuerza y motivación.", ["Granate", "Ojo de tigre", "Cornalina"]],
  ["Siento mucha energía pesada alrededor.", ["Turmalina negra", "Labradorita", "Selenita"]],
  ["Me siento bloqueada/o.", ["Amazonita", "Labradorita", "Turmalina negra"]]
];

window.MAVS_COMBINACIONES = [
  ["Protección", ["Turmalina negra", "Obsidiana", "Ojo de tigre"], "Para protección energética y enraizamiento."],
  ["Abundancia", ["Citrino", "Pirita", "Aventurina verde"], "Para atraer la prosperidad y las oportunidades."],
  ["Amor propio", ["Cuarzo rosa", "Rodonita", "Aventurina"], "Para conectar con el amor y la autoestima."],
  ["Calma y equilibrio", ["Amatista", "Lapislázuli", "Cuarzo cristal"], "Para serenidad, claridad mental y equilibrio emocional."],
  ["Confianza y seguridad", ["Cornalina", "Ojo de tigre", "Sodalita"], "Para motivación, determinación y expresión personal."]
];

/* Dibuja una gema con CSS a partir del nombre de la piedra */
window.MAVS_GEMA = function (nombre, clase) {
  var p = null, n = String(nombre).toLowerCase();
  window.MAVS_PIEDRAS.forEach(function (x) { if (x.nombre.toLowerCase() === n) p = x; });
  var c = p ? p.color : (window.MAVS_COLORES_EXTRA[nombre] || ["#eee", "#999"]);
  var fondo = p && p.franjas
    ? "linear-gradient(115deg," + c[1] + " 0%," + c[0] + " 28%," + c[1] + " 46%," + c[0] + " 62%," + c[1] + " 88%)"
    : "radial-gradient(circle at 32% 28%,#fff 0%," + c[0] + " 22%," + c[1] + " 100%)";
  return '<span class="gema-css ' + (clase || "") + '" style="background:' + fondo + '" aria-hidden="true"></span>';
};
