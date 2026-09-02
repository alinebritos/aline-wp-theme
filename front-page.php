<?php
/**
 * Home estática + grid de projetos dinâmico.
 */
if (!defined('ABSPATH')) exit;
get_header();
$assets = get_theme_file_uri('assets');
?>

<section class="hero">
  <div class="hero-grid">
    <div>
      <p class="eyebrow">Seja bem-vindo(a).</p>
      <h1>Aline Brito,<br /><span class="accent">Design</span> com Alma.</h1>
      <p class="lead">Transformando desafios em resultados, do UX Research à prototipação de interfaces que emocionam.</p>
      <div class="hero-cta">
        <a href="#contato" class="btn btn-outline">
          Contato
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
        <a href="#trabalhos" class="btn btn-ghost">Ver projetos</a>
      </div>
    </div>
    <div class="portrait">
      <img src="<?php echo esc_url($assets . '/aline-photo.webp'); ?>" alt="Retrato de Aline Brito" />
    </div>
  </div>

  <div class="tools">
    <p class="label">Ferramentas</p>
    <div class="chips">
      <?php foreach (['Figma','Notion','Miro','Lovable','FigmaMake','Maze','Hotjar','Jira'] as $t): ?>
        <span class="chip"><?php echo esc_html($t); ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="results">
  <div class="inner">
    <div>
      <div class="num">+30%</div>
      <p class="lbl">de engajamento em plataformas educacionais após validação de fluxos críticos</p>
    </div>
    <div>
      <div class="num">-25%</div>
      <p class="lbl">de retrabalho em sistemas internos de saúde, impactando 2.000+ colaboradores</p>
    </div>
    <div>
      <div class="num">10+ anos</div>
      <p class="lbl">atuando em agências, ensino e projetos de impacto social</p>
    </div>
  </div>
</section>

<section class="section" id="trabalhos">
  <div class="projects-head">
    <div>
      <p class="eyebrow-mini">01</p>
      <h2 class="section-title">Projetos</h2>
    </div>
    <p class="desc">Experiências em saúde, educação e impacto social — onde empatia encontra estratégia.</p>
  </div>

  <div class="projects-grid">
    <?php
    $projetos = new WP_Query([
        'post_type'      => 'projeto',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
    ]);
    if ($projetos->have_posts()) :
      while ($projetos->have_posts()) : $projetos->the_post();
        $tag      = aline_meta(get_the_ID(), 'tag');
        $subtitle = aline_meta(get_the_ID(), 'subtitle', get_the_excerpt());
        $thumb    = get_the_post_thumbnail_url(get_the_ID(), 'projeto-card');
    ?>
      <a href="<?php the_permalink(); ?>" class="project-card">
        <div class="img">
          <?php if ($thumb) : ?>
            <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
          <?php endif; ?>
        </div>
        <div class="body">
          <div>
            <?php if ($tag) : ?><p class="tag"><?php echo esc_html($tag); ?></p><?php endif; ?>
            <h3><?php the_title(); ?></h3>
            <?php if ($subtitle) : ?><p><?php echo esc_html($subtitle); ?></p><?php endif; ?>
          </div>
          <?php echo aline_arrow_up_right(); ?>
        </div>
      </a>
    <?php
      endwhile;
      wp_reset_postdata();
    else : ?>
      <p style="grid-column:1/-1;color:var(--muted)">Nenhum projeto cadastrado. Vá em <strong>Projetos → Adicionar novo</strong> no painel do WordPress para criar o primeiro destaque.</p>
    <?php endif; ?>
  </div>
</section>

<section class="section" id="sobre">
  <p class="eyebrow-mini">02</p>
  <h2 class="section-title" style="margin-bottom:3rem">Sobre mim</h2>
  <div class="about-grid">
    <p class="about-lead">Designer com alma curiosa e olhar sensível para o que conecta pessoas e tecnologia. Há mais de uma década transformando complexidade em clareza.</p>
    <div class="about-body">
      <p>Atuo no universo do design digital, com passagens por agências, instituições de ensino e projetos de impacto social, incluindo a Instituto Butantan, IBGE e Cultura Inglesa. Da pesquisa à prototipação, busco criar produtos que não apenas funcionam, mas também emocionam.</p>
      <p>Sou movida por propósito, ética e curiosidade constante. Acredito no design como ponte: uma forma de tornar o digital mais humano, acessível e inclusivo. Especialista em UX pela Universidade Anhembi Morumbi.</p>
      <p>No dia a dia, o que me diferencia é a empatia genuína, o pensamento sistêmico e a curiosidade criativa, qualidades que me ajudam a unir visão estratégica e execução com propósito.</p>
    </div>
  </div>
</section>

<section class="cta" id="contato">
  <div class="cta-card">
    <p class="meta">Vamos conversar</p>
    <h2>Tem um desafio pra transformar em resultado? Bora desenhar juntos.</h2>
    <a href="mailto:aline.debrito@gmail.com" class="btn btn-light" style="margin-top:3rem">
      aline.debrito@gmail.com
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
    </a>
  </div>
</section>

<?php get_footer(); ?>
