<?php if (!defined('ABSPATH')) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<?php wp_head(); ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XK3WYLPYBL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-XK3WYLPYBL');
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Aline Brito",
  "url": "https://alinedebrito.com.br/",
  "image": "https://alinedebrito.com.br/aline-photo.webp",
  "sameAs": [
    "https://www.linkedin.com/in/alinebritos",
    "https://www.behance.net/alinedbrito"
  ]
}
</script>

<!-- Hotjar Tracking Code for alinedebrito.com.br -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:2080230,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
</head>
<body <?php body_class(); ?>>

<header class="site-header" id="top">
  <a href="<?php echo esc_url(home_url('/')); ?>" class="brand">— <?php bloginfo('name'); ?></a>
  <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" aria-label="<?php bloginfo('name'); ?> — Home">
    <img src="<?php echo esc_url(get_theme_file_uri('assets/logo.svg')); ?>" alt="Logo <?php bloginfo('name'); ?>" />
  </a>
  <?php if (is_singular('projeto')) : ?>
    <a href="<?php echo esc_url(home_url('/#trabalhos')); ?>" class="proj-header-link">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Voltar
    </a>
  <?php else : ?>
    <nav class="site-nav">
      <a href="<?php echo esc_url(home_url('/#trabalhos')); ?>">Projetos</a>
      <a href="<?php echo esc_url(home_url('/#sobre')); ?>" class="hide-sm">Sobre</a>
      <a href="<?php echo esc_url(home_url('/#contato')); ?>">Contato</a>
    </nav>
  <?php endif; ?>
</header>
