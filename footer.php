<?php if (!defined('ABSPATH')) exit; ?>
<?php if (!is_singular('projeto')) : ?>
<footer class="site-footer">
  <p>© <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. Todos os direitos reservados.</p>
  <div class="social">
    <a href="https://github.com/alinebritos/" target="_blank">Github234</a>
    <a href="https://www.linkedin.com/in/alinebritos/" target="_blank">LinkedIn2</a>
    <a href="https://www.behance.net/alinedbrito" target="_blank">Behance</a>
  </div>
</footer>
<?php else : ?>
<footer class="proj-footer">© <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. Todos os direitos reservados.</footer>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
