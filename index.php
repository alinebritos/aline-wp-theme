<?php
/**
 * Fallback template — redireciona para a home (que é a front-page estática).
 */
if (!defined('ABSPATH')) exit;
get_header();
?>
<section class="section">
  <h1 class="section-title">Página não encontrada</h1>
  <p style="color:var(--muted);margin-top:1rem">
    <a href="<?php echo esc_url(home_url('/')); ?>">Voltar para a home</a>
  </p>
</section>
<?php get_footer(); ?>
