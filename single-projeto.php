<?php
/**
 * Detalhe de um projeto (case).
 */
if (!defined('ABSPATH')) exit;
get_header();

while (have_posts()) : the_post();
    $pid        = get_the_ID();
    $subtitle   = aline_meta($pid, 'subtitle');
    $tag        = aline_meta($pid, 'tag');
    $numero     = aline_meta($pid, 'numero', '01');
    $cliente    = aline_meta($pid, 'cliente');
    $papel      = aline_meta($pid, 'papel');
    $contexto   = aline_meta($pid, 'contexto');
    $plataforma = aline_meta($pid, 'plataforma');
    $problema   = aline_meta($pid, 'problema');
    $pesq_intro = aline_meta($pid, 'pesquisa_intro');
    $solucao    = aline_meta($pid, 'solucao');
    $cite_txt   = aline_meta($pid, 'citacao_texto');
    $cite_who   = aline_meta($pid, 'citacao_autor');
    $next_id    = aline_meta($pid, 'next_id');
    $cover      = get_the_post_thumbnail_url($pid, 'projeto-cover');

    // Pesquisa blocks
    $pesq_blocks = [];
    for ($i = 1; $i <= 3; $i++) {
        $t = aline_meta($pid, "pesquisa_b{$i}_title");
        $items = aline_lines(aline_meta($pid, "pesquisa_b{$i}_items"));
        if ($t || $items) $pesq_blocks[] = ['title' => $t, 'items' => $items];
    }

    // Método
    $metodo = [];
    for ($i = 1; $i <= 4; $i++) {
        $t = aline_meta($pid, "metodo_{$i}_title");
        $b = aline_meta($pid, "metodo_{$i}_body");
        if ($t || $b) $metodo[] = ['title' => $t, 'body' => $b];
    }

    // Resultados
    $resultados = [];
    for ($i = 1; $i <= 4; $i++) {
        $n = aline_meta($pid, "resultado_{$i}_num");
        $l = aline_meta($pid, "resultado_{$i}_label");
        if ($n || $l) $resultados[] = ['num' => $n, 'label' => $l];
    }
?>

<section class="proj-hero">
  <div class="proj-crumb">
    <span><?php echo esc_html($numero); ?></span><span class="line"></span><span><?php echo esc_html($tag); ?></span>
  </div>
  <h1><?php the_title(); ?></h1>
  <?php if ($subtitle) : ?><p class="sub"><?php echo esc_html($subtitle); ?></p><?php endif; ?>

  <?php if ($cliente || $papel || $contexto || $plataforma) : ?>
  <dl class="proj-meta">
    <?php if ($cliente)    : ?><div><dt>Cliente</dt><dd><?php echo esc_html($cliente); ?></dd></div><?php endif; ?>
    <?php if ($papel)      : ?><div><dt>Papel</dt><dd><?php echo esc_html($papel); ?></dd></div><?php endif; ?>
    <?php if ($contexto)   : ?><div><dt>Contexto</dt><dd><?php echo esc_html($contexto); ?></dd></div><?php endif; ?>
    <?php if ($plataforma) : ?><div><dt>Plataforma</dt><dd><?php echo esc_html($plataforma); ?></dd></div><?php endif; ?>
  </dl>
  <?php endif; ?>
</section>

<?php if ($cover) : ?>
<section class="proj-cover">
  <div class="frame"><img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>" /></div>
</section>
<?php endif; ?>

<?php if ($problema) : ?>
<section class="section split">
  <div>
    <p class="eyebrow">Problema</p>
    <h2 class="section-title">O ponto de partida</h2>
  </div>
  <p class="big-text"><?php echo nl2br(esc_html($problema)); ?></p>
</section>
<?php aline_section_image($pid, 'problema_img'); ?>
<?php endif; ?>

<?php if ($pesq_intro || $pesq_blocks) : ?>
<section class="section">
  <p class="eyebrow">Pesquisa</p>
  <h2 class="section-title">Escolha do método</h2>
  <?php if ($pesq_intro) : ?><p class="intro"><?php echo nl2br(esc_html($pesq_intro)); ?></p><?php endif; ?>
  <?php if ($pesq_blocks) : ?>
  <div class="cards-3">
    <?php foreach ($pesq_blocks as $b) : ?>
    <article class="card">
      <?php if ($b['title']) : ?><h3><?php echo esc_html($b['title']); ?></h3><?php endif; ?>
      <?php if ($b['items']) : ?>
      <ul>
        <?php foreach ($b['items'] as $it) : ?><li><?php echo esc_html($it); ?></li><?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </article>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>
<?php aline_section_image($pid, 'pesquisa_img'); ?>
<?php endif; ?>

<?php if ($metodo) : ?>
<section class="section">
  <p class="eyebrow">Processo</p>
  <h2 class="section-title">Como abordei</h2>
  <div class="cards-4">
    <?php foreach ($metodo as $i => $m) : ?>
    <article class="card method">
      <div class="step-num">0<?php echo $i + 1; ?></div>
      <?php if ($m['title']) : ?><h3><?php echo esc_html($m['title']); ?></h3><?php endif; ?>
      <?php if ($m['body'])  : ?><p><?php echo esc_html($m['body']); ?></p><?php endif; ?>
    </article>
    <?php endforeach; ?>
  </div>
</section>
<?php aline_section_image($pid, 'processo_img'); ?>
<?php endif; ?>

<?php if ($solucao) : ?>
<section class="section split">
  <div>
    <p class="eyebrow">Solução</p>
    <h2 class="section-title">O que entregamos</h2>
  </div>
  <p class="body-text"><?php echo nl2br(esc_html($solucao)); ?></p>
</section>
<?php aline_section_image($pid, 'solucao_img'); ?>
<?php endif; ?>

<?php if ($resultados) : ?>
<section class="results-section">
  <div class="inner">
    <p class="eyebrow">Resultados</p>
    <h2 class="section-title">Impactos positivos</h2>
    <div class="results-grid">
      <?php foreach ($resultados as $r) : ?>
        <?php if ($r['num']) : ?>
          <div class="result-stat">
            <div class="num"><?php echo esc_html($r['num']); ?></div>
            <p class="lbl"><?php echo esc_html($r['label']); ?></p>
          </div>
        <?php else : ?>
          <div class="result-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            <p><?php echo esc_html($r['label']); ?></p>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php aline_section_image($pid, 'resultados_img'); ?>
<?php endif; ?>

<?php if ($cite_txt) : ?>
<section class="section">
  <div class="quote-block">
    <svg class="qmark" viewBox="0 0 24 24" fill="currentColor"><path d="M7 7h4v4H8c0 2 .5 3 2 4v2c-3-1-5-3-5-7V7zm9 0h4v4h-3c0 2 .5 3 2 4v2c-3-1-5-3-5-7V7z"/></svg>
    <blockquote>“<?php echo esc_html($cite_txt); ?>”</blockquote>
    <?php if ($cite_who) : ?><p class="who">— <?php echo esc_html($cite_who); ?></p><?php endif; ?>
  </div>
</section>
<?php aline_section_image($pid, 'citacao_img'); ?>
<?php endif; ?>


<?php
    // Conteúdo livre opcional do editor (caso o admin queira adicionar mais blocos).
    if (trim(strip_tags(get_the_content())) !== '') :
?>
<article class="section">
  <?php the_content(); ?>
</article>
<?php endif; ?>

<?php
    /* Próximo projeto */
    $next_post = null;
    if ($next_id && is_numeric($next_id)) $next_post = get_post((int) $next_id);
    if (!$next_post) {
        $siblings = get_posts([
            'post_type' => 'projeto', 'posts_per_page' => -1,
            'orderby' => 'menu_order date', 'order' => 'ASC', 'fields' => 'ids',
        ]);
        if ($siblings) {
            $idx = array_search($pid, $siblings, true);
            if ($idx !== false) {
                $next_pid = $siblings[($idx + 1) % count($siblings)];
                if ($next_pid != $pid) $next_post = get_post($next_pid);
            }
        }
    }
    if ($next_post && $next_post->ID !== $pid) :
?>
<section class="next-cta">
  <a href="<?php echo esc_url(get_permalink($next_post)); ?>">
    <div>
      <p class="meta">Próximo projeto</p>
      <h3><?php echo esc_html(get_the_title($next_post)); ?></h3>
    </div>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
  </a>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
