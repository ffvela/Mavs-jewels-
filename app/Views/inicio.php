    <!-- Portada a sangre -->
    <section class="hero-full">
      <img src="/img/hero-luna.webp" alt="Aros de luna tejidos en alambre dorado con cristales, junto a un cuarzo" width="2000" height="1653">
      <div class="contenedor">
        <span class="rotulo">Joyería artesanal · Santiago</span>
        <h1>Joyas hechas<br>a mano</h1>
        <p class="bajada">Plata con piedras naturales, mostacilla Miyuki y alambrismo. Cada pieza la diseña y la teje María Angélica, de principio a fin.</p>
        <div class="acciones">
          <a class="btn btn-rosa" href="/catalogo">Ver catálogo</a>
          <a class="btn btn-linea" href="/personalizadas">Pieza personalizada</a>
        </div>
      </div>
    </section>

    <!-- Accesos rápidos -->
    <div class="contenedor accesos">
      <a class="acceso" href="/catalogo?tecnica=plata"><span class="mini"><img src="/img/aros-lapislazuli-640.webp" alt=""></span><span>Plata con piedras</span></a>
      <a class="acceso" href="/catalogo?tecnica=miyuki"><span class="mini"><img src="/img/miyuki-azul-dorado-640.webp" alt=""></span><span>Miyuki y alambrismo</span></a>
    </div>

    <!-- Destacados -->
    <section class="banda banda-blanca" style="padding-top:40px">
      <div class="contenedor">
        <h2 class="titulo-grilla">Destacadas</h2>
        <div class="grilla-productos">
          <article class="editorial"><img src="/img/feria-pulseras-640.webp" alt="Pulseras tejidas en la feria" loading="lazy">
            <div class="cuerpo"><h3>Hecho a mano</h3>
              <p>Ninguna pieza de Mavs Jewelry se compra terminada para revenderla. Cada aro, collar y pulsera se diseña y se teje en el taller de María Angélica.</p>
              <p>Las piezas de plata con piedra son únicas: cuando se venden, no vuelven.</p>
              <a class="enlace-flecha" href="/nosotros">Conocer la historia</a></div></article>
          <?php foreach ($destacados as $p) require __DIR__ . '/parciales/tarjeta.php'; ?>
        </div>
        <div class="acciones" style="margin-top:48px"><a class="btn btn-noche" href="/catalogo">Ver todo el catálogo</a></div>
      </div>
    </section>

    <!-- Dos técnicas -->
    <section class="banda banda-clara" id="colecciones">
      <div class="contenedor">
        <h2 class="titulo-grilla">Colecciones</h2>
        <div class="tecnicas">
          <a class="tecnica" href="/catalogo?tecnica=plata">
            <img src="/img/aros-amatista-1200.webp" alt="Aros de amatista en plata" loading="lazy">
            <div>
              <span class="num">01</span>
              <h3>Plata con piedras</h3>
              <p>Amatista, lapislázuli, ojo de tigre y cuarzo en plata. Cada piedra se elige por su color y su energía.</p>
              <span class="enlace-flecha">Ver piezas</span>
            </div>
          </a>
          <a class="tecnica" href="/catalogo?tecnica=miyuki">
            <img src="/img/miyuki-blanco-1200.webp" alt="Aros mandala de mostacilla blanca y dorada" loading="lazy">
            <div>
              <span class="num">02</span>
              <h3>Miyuki y alambrismo</h3>
              <p>Mostacilla japonesa tejida punto a punto y alambre trabajado a mano en mandalas, flores y lunas.</p>
              <span class="enlace-flecha">Ver piezas</span>
            </div>
          </a>
        </div>
      </div>
    </section>

    <!-- Personalizadas -->
    <section class="banda banda-noche">
      <div class="contenedor">
        <div class="encabezado-seccion">
          <span class="rotulo">Personalizadas</span>
          <h2>Una pieza creada <em>para ti</em></h2>
          <p>María Angélica es terapeuta holística. Te ayuda a elegir las piedras según la energía o la protección que buscas, y crea la pieza a tu medida.</p>
        </div>
        <ol class="proceso">
          <li><span class="luna f1" aria-hidden="true"></span><h3>Conversamos</h3><p>Me cuentas qué buscas: una intención, una piedra o un color.</p></li>
          <li><span class="luna f2" aria-hidden="true"></span><h3>Diseñamos</h3><p>Elegimos metal, piedras, corte y dijes. Confirmas con un anticipo del 40%.</p></li>
          <li><span class="luna f3" aria-hidden="true"></span><h3>Creo tu pieza</h3><p>Tejo y armo cada detalle a mano en el taller.</p><span class="plazo">De 4 horas a 1 semana</span></li>
          <li><span class="luna f4" aria-hidden="true"></span><h3>La recibes</h3><p>Te envío fotos del resultado y la despacho por Starken o la entrego en feria.</p></li>
        </ol>
        <div class="acciones" style="margin-top:44px"><a class="btn btn-rosa" href="/personalizadas">Ver piezas personalizadas</a></div>
      </div>
    </section>

    <!-- Piedras -->
    <section class="banda banda-blanca">
      <div class="contenedor">
        <div class="encabezado-seccion">
          <h2 class="titulo-grilla" style="margin:0">Las piedras</h2>
          <p>Las piedras con que más trabajo y lo que aportan a tu vida.</p>
        </div>
        <div class="piedras-lista">
          <div class="piedra"><span class="gema amatista"></span><h3>Amatista</h3><span class="intencion">Calma</span><p>Entrega paz y calma, y aleja el estrés. Favorece el equilibrio.</p></div>
          <div class="piedra"><span class="gema lapis"></span><h3>Lapislázuli</h3><span class="intencion">Sabiduría</span><p>Estimula la sabiduría, la comunicación y la conexión espiritual.</p></div>
          <div class="piedra"><span class="gema ojo-tigre"></span><h3>Ojo de tigre</h3><span class="intencion">Protección</span><p>Brinda protección, fortalece la voluntad y atrae estabilidad.</p></div>
          <div class="piedra"><span class="gema cuarzo"></span><h3>Cuarzo rosa</h3><span class="intencion">Amor propio</span><p>Aporta amor propio, ternura y armonía. Mejora las relaciones.</p></div>
          <div class="piedra"><span class="gema obsidiana"></span><h3>Obsidiana</h3><span class="intencion">Limpieza</span><p>Limpia energías, te protege y ayuda a soltar lo negativo.</p></div>
        </div>
        <div class="acciones" style="margin-top:40px"><a class="btn btn-noche" href="/piedras">Ver todas las piedras</a></div>
      </div>
    </section>

    <!-- Dónde encontrarnos -->
    <section class="banda banda-clara" id="donde">
      <div class="contenedor">
        <h2 class="titulo-grilla">Dónde encontrarnos</h2>
        <div class="donde">
          <?php $actual = $puntos[0] ?? null; ?>
          <div class="esta-semana">
            <span class="rotulo">Esta semana</span>
            <?php if ($actual): ?>
              <div class="lugar"><?= e($actual['lugar']) ?></div>
              <p class="detalle"><?= e($actual['direccion']) ?> · <?= e($actual['horario']) ?></p>
              <p class="detalle">Del <?= date('d-m', strtotime($actual['fecha_inicio'])) ?> al <?= date('d-m', strtotime($actual['fecha_fin'])) ?>. Si buscas una pieza en particular, escríbeme antes y te la llevo.</p>
            <?php else: ?>
              <div class="lugar">Pronto en ferias</div>
              <p class="detalle">Escríbeme para saber dónde estaré esta semana.</p>
            <?php endif; ?>
            <div class="acciones" style="margin-top:8px"><a class="btn btn-rosa" href="<?= e(enlace_whatsapp('Hola, ¿qué días estarás en ' . ($actual['lugar'] ?? 'feria') . '?', 'inicio-punto-venta')) ?>">Preguntar horario</a></div>
          </div>
          <div>
            <ul class="proximas">
              <?php foreach (array_slice($puntos, 1) as $pv): ?>
                <li><span class="fecha"><?= date('d-m', strtotime($pv['fecha_inicio'])) ?></span><span class="sitio"><?= e($pv['lugar']) ?><span><?= e($pv['direccion']) ?> · <?= e($pv['horario']) ?></span></span></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonios -->
    <section class="banda banda-noche" id="testimonios">
      <div class="contenedor">
        <h2 class="titulo-grilla">Historias de <em>clientas</em></h2>
        <div class="testimonios">
          <figure class="testimonio" style="margin:0">
            <video controls preload="none" poster="/img/Testimonio1-poster.webp"><source src="/video/Testimonio1.mp4" type="/video/mp4"></video>
            <blockquote>“Excelente atención y productos de gran calidad.”</blockquote>
            <figcaption class="quien">Clienta de Mavs Jewelry</figcaption>
          </figure>
          <figure class="testimonio" style="margin:0">
            <video controls preload="none" poster="/img/Testimonio2-poster.webp"><source src="/video/Testimonio2.mp4" type="/video/mp4"></video>
            <blockquote>“Estoy feliz con mis aritos.”</blockquote>
            <figcaption class="quien">Clienta de Mavs Jewelry</figcaption>
          </figure>
          <figure class="testimonio" style="margin:0">
            <video controls preload="none" poster="/img/Testimonio3-poster.webp"><source src="/video/Testimonio3.mp4" type="/video/mp4"></video>
            <blockquote>“¡Me encanta!”</blockquote>
            <figcaption class="quien">Clienta de Mavs Jewelry</figcaption>
          </figure>
        </div>
      </div>
    </section>

    <!-- Llamado final -->
    <section class="banda banda-rosa">
      <div class="contenedor llamado">
        <h2 class="titulo-grilla" style="margin-bottom:.2em">¿Buscas algo especial?</h2>
        <p>Cuéntame qué pieza imaginas. Respondo personalmente cada mensaje por WhatsApp.</p>
        <div class="acciones">
          <a class="btn btn-wsp" href="https://wa.me/56989234239?text=Hola%20Mar%C3%ADa%20Ang%C3%A9lica%2C%20quiero%20cotizar%20una%20pieza%20personalizada." target="_blank" rel="noopener">Escribir por WhatsApp</a>
          <a class="btn btn-linea" href="/catalogo">Ver catálogo</a>
        </div>
      </div>
    </section>
  
