<section class="hero-full medio">
  <img src="/img/aros-amatista-1200.webp" alt="Aros de amatista facetada en plata sobre un cuarzo" width="1200" height="1500">
  <div class="contenedor">
    <nav class="migas" aria-label="Ruta"><a href="<?= url() ?>">Inicio</a> › Piedras</nav>
    <h1>Piedras con<br>intención</h1>
    <p class="bajada">Gemoterapia, belleza que sana. Cada piedra tiene un significado. Conócelo y elige la que te acompañe.</p>
    <div class="acciones"><a class="btn btn-rosa" href="#guia">Dime qué te está pasando</a><a class="btn btn-linea" href="#significados">Significado de los minerales</a></div>
  </div>
</section>

<section class="banda banda-rosa" id="guia">
  <div class="contenedor">
    <div class="encabezado-seccion">
      <h2 class="titulo-grilla" style="margin:0">Dime qué te está pasando</h2>
      <p>…y te digo qué mineral elegir. Si te identificas con alguna, María Angélica puede crear una pieza con esas piedras.</p>
    </div>
    <div class="guia">
      <?php foreach ($guia['situaciones'] as [$situacion, $trio]): ?>
        <article class="situacion"><h3><?= e($situacion) ?></h3>
          <ul class="trio"><?php foreach ($trio as $n): ?><li><?= gema($n) ?><span><?= e($n) ?></span></li><?php endforeach; ?></ul>
          <a class="enlace-flecha" href="<?= e(enlace_whatsapp('Hola María Angélica, ' . mb_strtolower(mb_substr($situacion, 0, 1)) . mb_substr($situacion, 1) . ' Me gustaría una pieza con ' . $trio[0] . ', ' . $trio[1] . ' y ' . $trio[2] . '.', 'piedras-guia')) ?>">Quiero una pieza así</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="banda banda-noche" id="combinaciones">
  <div class="contenedor">
    <div class="encabezado-seccion">
      <span class="rotulo">Para cada energía que necesitas</span>
      <h2>Cinco <em>combinaciones</em></h2>
    </div>
    <ol class="combinaciones">
      <?php foreach ($guia['combinaciones'] as [$nombre, $trio, $texto]): ?>
        <li><div class="gemas"><?php foreach ($trio as $n) echo gema($n); ?></div>
          <h3><?= e($nombre) ?></h3><p class="piedras-combo"><?= e(implode(' + ', $trio)) ?></p><p><?= e($texto) ?></p></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="banda banda-blanca" id="significados">
  <div class="contenedor">
    <div class="encabezado-seccion">
      <h2 class="titulo-grilla" style="margin:0">Significado de los minerales</h2>
      <p>Conecta con su energía y descubre lo que pueden aportar a tu vida.</p>
    </div>
    <div class="minerales">
      <?php foreach ($piedras as $pi): ?>
        <article class="mineral"><?= gema($pi['nombre'], 'grande') ?><div><h3><?= e($pi['nombre']) ?></h3><p><?= e($pi['significado']) ?></p></div></article>
      <?php endforeach; ?>
    </div>
    <p class="nota-admin">Significados tradicionales de la gemoterapia, compartidos por María Angélica como orientación para elegir tus piezas.</p>
  </div>
</section>

<section class="banda banda-clara">
  <div class="contenedor llamado">
    <h2 class="titulo-grilla" style="margin-bottom:.2em">Lleva contigo la energía de la naturaleza</h2>
    <p>Elige tus piedras y te creo una pieza a tu medida, o busca en el catálogo las que ya están hechas.</p>
    <div class="acciones">
      <a class="btn btn-wsp" href="<?= e(enlace_whatsapp('Hola María Angélica, quiero una pieza con piedras para lo que estoy viviendo.', 'piedras')) ?>">Escribir por WhatsApp</a>
      <a class="btn btn-linea" href="<?= url('catalogo') ?>">Ver catálogo</a>
    </div>
  </div>
</section>
