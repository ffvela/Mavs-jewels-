-- Mavs Jewelry · datos iniciales: técnicas, tipos, piedras (textos de la clienta) y piezas de ejemplo del prototipo.
-- PRECIOS DE EJEMPLO: reemplazar por los que entregue la clienta. Las fotos están en public/img.
SET NAMES utf8mb4;

INSERT INTO tecnica (id_tecnica, nombre, slug) VALUES (1, 'Plata con piedras', 'plata'), (2, 'Miyuki y alambrismo', 'miyuki');
INSERT INTO tipo_pieza (id_tipo, nombre, slug) VALUES (1, 'Aros', 'aros'), (2, 'Collares', 'collares'), (3, 'Pulseras', 'pulseras'), (4, 'Dijes', 'dijes'), (5, 'Anillos', 'anillos');

INSERT INTO piedra (id_piedra, nombre, significado) VALUES
  (1, 'Amatista', 'Entrega paz y calma, y aleja el estrés. Favorece el equilibrio.'),
  (2, 'Aguamarina', 'Protege el alma y conecta con la intuición. Ideal para abrir el corazón.'),
  (3, 'Granate', 'Te da energía, fuerza y vitalidad. Ayuda a superar miedos.'),
  (4, 'Cuarzo rosa', 'Aporta amor propio, ternura y armonía. Mejora las relaciones.'),
  (5, 'Citrino', 'Atrae la abundancia, alegra y da confianza.'),
  (6, 'Cornalina', 'Activa la creatividad y el coraje. Te impulsa a avanzar.'),
  (7, 'Cuarzo cristal', 'Amplifica la energía, potencia intenciones y armoniza.'),
  (8, 'Aventurina verde', 'Atrae la buena suerte, calma la mente y fomenta la prosperidad.'),
  (9, 'Lapislázuli', 'Estimula la sabiduría, la comunicación y la conexión espiritual.'),
  (10, 'Ojo de tigre', 'Brinda protección, fortalece la voluntad y atrae estabilidad.'),
  (11, 'Turmalina negra', 'Escudo protector contra malas vibras. Te da seguridad.'),
  (12, 'Obsidiana', 'Limpia energías, te protege y ayuda a soltar lo negativo.'),
  (13, 'Turquesa', 'Aporta equilibrio, calma y favorece la comunicación.'),
  (14, 'Labradorita', 'Activa la intuición, equilibra el aura y te conecta con tu propósito.'),
  (15, 'Jaspe rojo', 'Te da fuerza, resistencia y energía. Ideal para nuevos comienzos.'),
  (16, 'Malaquita', 'Transforma energías, protege y estimula el crecimiento personal.'),
  (17, 'Jade rojo', 'Favorece la vitalidad, la alegría y la pasión por la vida.'),
  (18, 'Ónix', 'Aporta estabilidad, protección y fortaleza interior.'),
  (19, 'Cuarzo ahumado', 'Disipa la ansiedad, enraíza y brinda tranquilidad.');

INSERT INTO producto (id_producto, codigo, nombre, descripcion, materiales, precio, stock, es_unica, estado, creado_en, actualizado_en, id_tecnica, id_tipo) VALUES
  (1, 'PL-ARO-0001', 'Aros de lapislázuli', 'Discos de lapislázuli en su azul profundo natural, montados en argollas de plata.', 'Plata 950', 18000, 1, 1, 'publicado', NOW() - INTERVAL 1 HOUR, NOW(), 1, 1),
  (2, 'PL-ARO-0002', 'Aros gota de amatista', 'Gotas facetadas de amatista que cuelgan de una rosa calada en plata.', 'Plata 950', 16000, 1, 1, 'publicado', NOW() - INTERVAL 2 HOUR, NOW(), 1, 1),
  (3, 'PL-ARO-0003', 'Aros barra de piedra negra', 'Barras alargadas de piedra pulida, de líneas simples y elegantes.', 'Plata 950', 14000, 1, 1, 'publicado', NOW() - INTERVAL 3 HOUR, NOW(), 1, 1),
  (4, 'PL-ARO-0004', 'Aros largos con corazón de piedra', 'Cadena de cuentas de piedra que termina en un pequeño corazón tallado.', 'Plata 950', 15000, 1, 1, 'publicado', NOW() - INTERVAL 4 HOUR, NOW(), 1, 1),
  (5, 'PL-COL-0005', 'Collar ojo de tigre', 'Cuentas rectangulares de ojo de tigre con su brillo dorado tornasol.', 'Plata 950', 22000, 1, 1, 'publicado', NOW() - INTERVAL 5 HOUR, NOW(), 1, 2),
  (6, 'PL-PUL-0006', 'Pulsera de amatista', 'Óvalos de amatista facetada intercalados con pequeñas piedras rojizas.', 'Plata 950', 19000, 0, 1, 'vendido', NOW() - INTERVAL 6 HOUR, NOW(), 1, 3),
  (7, 'MA-ARO-0007', 'Aros luna de alambre dorado', 'Medias lunas tejidas en alambre con cristales color miel engastados a mano.', 'Alambre dorado, cristales', 17000, 1, 1, 'publicado', NOW() - INTERVAL 7 HOUR, NOW(), 2, 1),
  (8, 'MA-ARO-0008', 'Aros herradura azul noche', 'Herraduras de mostacilla azul y perla sobre un disco dorado martillado.', 'Mostacilla Miyuki, base dorada', 12000, 1, 0, 'publicado', NOW() - INTERVAL 8 HOUR, NOW(), 2, 1),
  (9, 'MA-ARO-0009', 'Aros flor lila', 'Flores de cinco pétalos tejidas en mostacilla lila con centro dorado.', 'Mostacilla Miyuki, base dorada', 11000, 1, 0, 'publicado', NOW() - INTERVAL 9 HOUR, NOW(), 2, 1),
  (10, 'MA-ARO-0010', 'Aros mandala durazno', 'Mandala circular en tonos durazno y rosa, liviano para uso diario.', 'Mostacilla Miyuki', 12000, 1, 0, 'publicado', NOW() - INTERVAL 10 HOUR, NOW(), 2, 1),
  (11, 'MA-ARO-0011', 'Aros mandala blanco y dorado', 'Mandala calado en blanco perla con anillo dorado, ideal para ocasiones especiales.', 'Mostacilla Miyuki', 12000, 1, 0, 'publicado', NOW() - INTERVAL 11 HOUR, NOW(), 2, 1),
  (12, 'MA-ARO-0012', 'Aros mandala violeta', 'Mandala en violeta intenso con detalles negros y dorados.', 'Mostacilla Miyuki', 12000, 1, 0, 'publicado', NOW() - INTERVAL 12 HOUR, NOW(), 2, 1),
  (13, 'MA-ARO-0013', 'Aros mandala negro y dorado', 'Contraste clásico de negro y dorado en dos círculos superpuestos.', 'Mostacilla Miyuki', 12000, 1, 0, 'publicado', NOW() - INTERVAL 13 HOUR, NOW(), 2, 1),
  (14, 'MA-ARO-0014', 'Aros mandala turquesa', 'Círculos turquesa con centro rojo y negro, inspirados en tejidos andinos.', 'Mostacilla Miyuki, base dorada', 12000, 1, 0, 'publicado', NOW() - INTERVAL 14 HOUR, NOW(), 2, 1),
  (15, 'MA-ARO-0015', 'Aros girasol', 'Girasoles amarillos tejidos punto a punto, alegres y livianos.', 'Mostacilla Miyuki', 10000, 1, 0, 'publicado', NOW() - INTERVAL 15 HOUR, NOW(), 2, 1),
  (16, 'MA-ARO-0016', 'Aros rosas con cristales', 'Rosas de alambre que sostienen un racimo de cristales en tonos violeta.', 'Alambre plateado, cristales', 9000, 1, 1, 'publicado', NOW() - INTERVAL 16 HOUR, NOW(), 2, 1),
  (17, 'MA-ARO-0017', 'Aros corazón violeta', 'Corazones de alambre rellenos con cristales violeta y azul.', 'Alambre dorado, cristales', 8000, 1, 1, 'publicado', NOW() - INTERVAL 17 HOUR, NOW(), 2, 1),
  (18, 'MA-ARO-0018', 'Aros tejidos rojo coral', 'Círculos de alambre tejido con cristales rojos y una rosa dorada.', 'Alambre dorado, cristales', 8000, 1, 1, 'publicado', NOW() - INTERVAL 18 HOUR, NOW(), 2, 1),
  (19, 'MA-ARO-0019', 'Aros corazón de alambre dorado', 'Corazones tejidos en espirales de alambre dorado.', 'Alambre dorado', 7000, 1, 0, 'publicado', NOW() - INTERVAL 19 HOUR, NOW(), 2, 1),
  (20, 'MA-PUL-0020', 'Pulsera tejida en telar', 'Pulseras tejidas en telar con ojos de protección, mandalas y flores. Varios diseños.', 'Mostacilla Miyuki, cordón regulable', 5000, 1, 0, 'publicado', NOW() - INTERVAL 20 HOUR, NOW(), 2, 3);

INSERT INTO imagen_producto (id_producto, ruta, texto_alt, orden) VALUES
  (1, 'img/aros-lapislazuli-1200.webp', 'Aros de lapislázuli', 1),
  (2, 'img/aros-amatista-1200.webp', 'Aros gota de amatista', 1),
  (3, 'img/aros-obsidiana-1200.webp', 'Aros barra de piedra negra', 1),
  (4, 'img/aros-corazon-lapis-1200.webp', 'Aros largos con corazón de piedra', 1),
  (5, 'img/collar-ojo-tigre-1200.webp', 'Collar ojo de tigre', 1),
  (6, 'img/pulsera-amatista-1200.webp', 'Pulsera de amatista', 1),
  (7, 'img/luna-dorada-1200.webp', 'Aros luna de alambre dorado', 1),
  (8, 'img/miyuki-azul-dorado-1200.webp', 'Aros herradura azul noche', 1),
  (9, 'img/miyuki-flores-1200.webp', 'Aros flor lila', 1),
  (10, 'img/miyuki-durazno-1200.webp', 'Aros mandala durazno', 1),
  (11, 'img/miyuki-blanco-1200.webp', 'Aros mandala blanco y dorado', 1),
  (12, 'img/miyuki-morados-1200.webp', 'Aros mandala violeta', 1),
  (13, 'img/miyuki-negro-dorado-1200.webp', 'Aros mandala negro y dorado', 1),
  (14, 'img/miyuki-verde-dorado-1200.webp', 'Aros mandala turquesa', 1),
  (15, 'img/miyuki-girasoles-1200.webp', 'Aros girasol', 1),
  (16, 'img/alambrismo-cristales-1200.webp', 'Aros rosas con cristales', 1),
  (17, 'img/aros-corazon-violeta-1200.webp', 'Aros corazón violeta', 1),
  (18, 'img/aros-tejidos-rojos-1200.webp', 'Aros tejidos rojo coral', 1),
  (19, 'img/cat-alambrismo-1200.webp', 'Aros corazón de alambre dorado', 1),
  (20, 'img/feria-pulseras-1200.webp', 'Pulsera tejida en telar', 1);

INSERT INTO producto_piedra (id_producto, id_piedra) VALUES
  (1, 9),
  (2, 1),
  (4, 9),
  (4, 10),
  (5, 10),
  (6, 1);

-- Fechas de ejemplo: la clienta las actualiza desde el panel
INSERT INTO punto_venta (lugar, direccion, fecha_inicio, fecha_fin, horario, visible) VALUES
  ('Estación Baquedano', 'Metro de Santiago, Líneas 1 y 5', CURDATE(), CURDATE() + INTERVAL 6 DAY, 'Lunes a viernes', 1),
  ('Estación Quinta Normal', 'Metro de Santiago, Línea 5', CURDATE() + INTERVAL 7 DAY, CURDATE() + INTERVAL 13 DAY, 'Lunes a viernes', 1);

INSERT INTO testimonio (nombre_mostrado, tipo_identificacion, texto, visible) VALUES
  ('Clienta de Mavs Jewelry', 'anonimo', 'Excelente atención y productos de gran calidad.', 1),
  ('Clienta de Mavs Jewelry', 'anonimo', 'Estoy feliz con mis aritos.', 1),
  ('Clienta de Mavs Jewelry', 'anonimo', '¡Me encanta!', 1);
