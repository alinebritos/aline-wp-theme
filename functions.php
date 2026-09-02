<?php
/**
 * Aline Brito theme functions.
 */

if (!defined('ABSPATH')) { exit; }

/* ---------- Theme setup ---------- */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    register_nav_menus([
        'primary' => __('Menu principal', 'aline-brito'),
    ]);
    add_image_size('projeto-card', 1280, 960, true);
    add_image_size('projeto-cover', 1920, 1080, true);
});

/* ---------- Assets ---------- */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'aline-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@500;700;800;900&display=swap',
        [],
        null
    );
    wp_enqueue_style(
        'aline-main',
        get_theme_file_uri('assets/styles.css'),
        ['aline-fonts'],
        wp_get_theme()->get('Version')
    );
});

/* ---------- Custom Post Type: Projeto ---------- */
add_action('init', function () {
    register_post_type('projeto', [
        'labels' => [
            'name'               => 'Projetos',
            'singular_name'      => 'Projeto',
            'add_new'            => 'Adicionar novo',
            'add_new_item'       => 'Adicionar novo projeto',
            'edit_item'          => 'Editar projeto',
            'new_item'           => 'Novo projeto',
            'view_item'          => 'Ver projeto',
            'search_items'       => 'Buscar projetos',
            'not_found'          => 'Nenhum projeto encontrado',
            'not_found_in_trash' => 'Nenhum projeto na lixeira',
            'menu_name'          => 'Projetos',
        ],
        'public'       => true,
        'has_archive'  => false,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-portfolio',
        'menu_position'=> 5,
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
        'rewrite'      => ['slug' => 'projetos', 'with_front' => false],
    ]);
});

add_action('after_switch_theme', function () {
    flush_rewrite_rules();
});

/* ---------- Meta boxes ---------- */
add_action('add_meta_boxes', function () {
    add_meta_box('projeto_detalhes',   'Detalhes do projeto',          'aline_mb_detalhes',   'projeto', 'normal', 'high');
    add_meta_box('projeto_problema',   'Problema — o ponto de partida','aline_mb_problema',   'projeto', 'normal', 'high');
    add_meta_box('projeto_pesquisa',   'Pesquisa — escolha do método', 'aline_mb_pesquisa',   'projeto', 'normal', 'default');
    add_meta_box('projeto_processo',   'Processo — como abordei',      'aline_mb_processo',   'projeto', 'normal', 'default');
    add_meta_box('projeto_solucao',    'Solução — o que entregamos',   'aline_mb_solucao',    'projeto', 'normal', 'default');
    add_meta_box('projeto_resultados', 'Resultados — impactos',        'aline_mb_resultados', 'projeto', 'normal', 'default');
    add_meta_box('projeto_citacao',    'Citação final',                'aline_mb_citacao',    'projeto', 'normal', 'default');
    add_meta_box('projeto_proximo',    'Próximo projeto',              'aline_mb_proximo',    'projeto', 'side',   'default');
});

/* ----- Field definitions (used for saving) ----- */
function aline_projeto_all_fields() {
    $f = [
        // Detalhes
        'subtitle' => 'textarea', 'tag' => 'text', 'numero' => 'text',
        'cliente' => 'text', 'papel' => 'text', 'contexto' => 'text', 'plataforma' => 'text',
        // Problema
        'problema' => 'textarea', 'problema_img' => 'image',
        // Pesquisa
        'pesquisa_intro' => 'textarea', 'pesquisa_img' => 'image',
        // Processo
        'processo_img' => 'image',
        // Solução
        'solucao' => 'textarea', 'solucao_img' => 'image',
        // Resultados
        'resultados_img' => 'image',
        // Citação
        'citacao_texto' => 'textarea', 'citacao_autor' => 'text', 'citacao_img' => 'image',
        // Próximo
        'next_id' => 'text',
    ];
    for ($i = 1; $i <= 3; $i++) {
        $f["pesquisa_b{$i}_title"] = 'text';
        $f["pesquisa_b{$i}_items"] = 'textarea';
    }
    for ($i = 1; $i <= 4; $i++) {
        $f["metodo_{$i}_title"] = 'text';
        $f["metodo_{$i}_body"]  = 'textarea';
        $f["resultado_{$i}_num"]   = 'text';
        $f["resultado_{$i}_label"] = 'text';
    }
    return $f;
}

/* ----- Allow SVG uploads ----- */
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
});
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
    if (substr(strtolower($filename), -4) === '.svg') {
        $data['ext']  = 'svg';
        $data['type'] = 'image/svg+xml';
    }
    return $data;
}, 10, 4);
// Ensure SVGs render in admin previews
add_filter('wp_prepare_attachment_for_js', function ($response, $attachment) {
    if (isset($response['mime']) && $response['mime'] === 'image/svg+xml') {
        $url = wp_get_attachment_url($attachment->ID);
        $response['sizes'] = [
            'medium'    => ['url' => $url],
            'full'      => ['url' => $url],
            'thumbnail' => ['url' => $url],
        ];
        $response['icon'] = $url;
    }
    return $response;
}, 10, 2);

/* ----- Enqueue media uploader on projeto edit screen ----- */
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) return;
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'projeto') return;
    wp_enqueue_media();
    add_action('admin_footer', 'aline_media_uploader_js');
});

function aline_media_uploader_js() { ?>
<script>
(function($){
  function renderPreviews($wrap, ids){
    var $prev = $wrap.find('.aline-media-previews').empty();
    ids.filter(Boolean).forEach(function(id){
      var att = wp.media.attachment(id);
      att.fetch().then(function(){
        var data = att.toJSON();
        var url  = (data.sizes && data.sizes.medium && data.sizes.medium.url) ? data.sizes.medium.url : data.url;
        var $item = $('<div class="aline-media-item"></div>');
        $item.append('<img src="'+url+'" />');
        $item.append('<button type="button" class="button-link aline-media-remove-one" data-id="'+id+'">Remover</button>');
        $prev.append($item);
      });
    });
  }
  $(document).on('click', '.aline-media-select', function(e){
    e.preventDefault();
    var $btn = $(this), target = $btn.data('target');
    var $wrap = $btn.closest('.aline-media');
    var $input = $('#' + target);
    var frame = wp.media({
      title: 'Selecionar imagens',
      button: { text: 'Usar estas imagens' },
      library: { type: ['image'] },
      multiple: 'add'
    });
    frame.on('select', function(){
      var current = ($input.val() || '').split(',').filter(Boolean);
      frame.state().get('selection').each(function(att){
        var id = String(att.id);
        if (current.indexOf(id) === -1) current.push(id);
      });
      $input.val(current.join(','));
      renderPreviews($wrap, current);
    });
    frame.open();
  });
  $(document).on('click', '.aline-media-remove-one', function(e){
    e.preventDefault();
    var $wrap = $(this).closest('.aline-media');
    var $input = $wrap.find('input[type=hidden]');
    var removeId = String($(this).data('id'));
    var current = ($input.val() || '').split(',').filter(function(v){ return v && v !== removeId; });
    $input.val(current.join(','));
    renderPreviews($wrap, current);
  });
  $(document).on('click', '.aline-media-clear', function(e){
    e.preventDefault();
    var $wrap = $(this).closest('.aline-media');
    $wrap.find('input[type=hidden]').val('');
    $wrap.find('.aline-media-previews').empty();
  });
})(jQuery);
</script>
<style>
.aline-media-previews{display:flex;flex-wrap:wrap;gap:10px;margin:0 0 10px}
.aline-media-item{position:relative;border:1px solid #e2e4e7;border-radius:6px;padding:6px;background:#fff;display:flex;flex-direction:column;align-items:center;gap:4px;width:150px}
.aline-media-item img{max-width:140px;max-height:110px;display:block;object-fit:contain;background:#f6f7f7}
.aline-media-item .aline-media-remove-one{color:#b32d2e;font-size:12px}
</style>
<?php }

/* ----- Image field helper (stores CSV of attachment IDs) ----- */
function aline_field_image($key, $post_id, $label = 'Imagens da seção') {
    $val = (string) get_post_meta($post_id, '_aline_' . $key, true);
    $ids = array_values(array_filter(array_map('intval', explode(',', $val))));
    $id  = 'aline_' . $key;
    echo '<label>' . esc_html($label) . '</label>';
    echo '<div class="aline-media">';
    printf('<input type="hidden" id="%1$s" name="%1$s" value="%2$s" />', esc_attr($id), esc_attr(implode(',', $ids)));
    echo '<div class="aline-media-previews">';
    foreach ($ids as $att_id) {
        $u = wp_get_attachment_image_url($att_id, 'medium');
        if (!$u) $u = wp_get_attachment_url($att_id);
        if (!$u) continue;
        echo '<div class="aline-media-item">';
        echo '<img src="' . esc_url($u) . '" />';
        echo '<button type="button" class="button-link aline-media-remove-one" data-id="' . esc_attr($att_id) . '">Remover</button>';
        echo '</div>';
    }
    echo '</div>';
    printf('<button type="button" class="button aline-media-select" data-target="%1$s">Adicionar imagens</button> ', esc_attr($id));
    echo '<button type="button" class="button aline-media-clear">Remover todas</button>';
    echo '<p class="desc" style="margin-top:6px">Aceita JPG, PNG, WebP e SVG. Você pode selecionar vários arquivos (Ctrl/Cmd + clique).</p>';
    echo '</div>';
}

/* ----- Render helpers ----- */
function aline_field_input($key, $type, $post_id, $placeholder = '') {
    $val = get_post_meta($post_id, '_aline_' . $key, true);
    $id  = 'aline_' . $key;
    if ($type === 'textarea') {
        printf(
            '<textarea id="%1$s" name="%1$s" placeholder="%2$s">%3$s</textarea>',
            esc_attr($id), esc_attr($placeholder), esc_textarea($val)
        );
    } else {
        printf(
            '<input type="text" id="%1$s" name="%1$s" value="%2$s" placeholder="%3$s" />',
            esc_attr($id), esc_attr($val), esc_attr($placeholder)
        );
    }
}

function aline_mb_styles_once() {
    static $done = false;
    if ($done) return;
    $done = true;
    echo '<style>
.aline-mb label{display:block;margin:14px 0 4px;font-weight:600;font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:#1d2327}
.aline-mb input[type=text],.aline-mb textarea{width:100%;padding:8px;border:1px solid #ccd0d4;border-radius:4px;font-size:13px}
.aline-mb textarea{min-height:80px}
.aline-mb .row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.aline-mb .row-4{display:grid;grid-template-columns:1fr 3fr;gap:14px;align-items:end}
.aline-mb .group{border:1px solid #e2e4e7;border-radius:6px;padding:14px;margin-top:14px;background:#fafafa}
.aline-mb .group h4{margin:0 0 4px;font-size:13px;color:#1d2327}
.aline-mb p.desc{margin:10px 0 0;color:#666;font-style:italic;font-size:12px}
</style>';
}

function aline_mb_nonce() {
    wp_nonce_field('aline_projeto_save', 'aline_projeto_nonce');
}

/* ----- Meta box: Detalhes ----- */
function aline_mb_detalhes($post) {
    aline_mb_nonce(); aline_mb_styles_once();
    echo '<div class="aline-mb">';
    echo '<label for="aline_subtitle">Subtítulo (resumo curto exibido no card e no topo do case)</label>';
    aline_field_input('subtitle', 'textarea', $post->ID);
    echo '<div class="row">';
    echo '<div><label for="aline_numero">Número do projeto (ex.: "01")</label>'; aline_field_input('numero', 'text', $post->ID, '01'); echo '</div>';
    echo '<div><label for="aline_tag">Tag (ex.: "Saúde · Mobile")</label>'; aline_field_input('tag', 'text', $post->ID, 'Saúde · Mobile'); echo '</div>';
    echo '</div>';
    echo '<div class="row">';
    echo '<div><label for="aline_cliente">Cliente</label>'; aline_field_input('cliente', 'text', $post->ID); echo '</div>';
    echo '<div><label for="aline_papel">Papel</label>'; aline_field_input('papel', 'text', $post->ID); echo '</div>';
    echo '<div><label for="aline_contexto">Contexto</label>'; aline_field_input('contexto', 'text', $post->ID); echo '</div>';
    echo '<div><label for="aline_plataforma">Plataforma</label>'; aline_field_input('plataforma', 'text', $post->ID); echo '</div>';
    echo '</div>';
    echo '<p class="desc">A imagem de destaque é usada como capa do card e do topo do case.</p>';
    echo '</div>';
}

/* ----- Problema ----- */
function aline_mb_problema($post) {
    aline_mb_styles_once();
    echo '<div class="aline-mb">';
    echo '<label for="aline_problema">Texto do problema</label>';
    aline_field_input('problema', 'textarea', $post->ID, 'Descreva o desafio que motivou o projeto.');
    aline_field_image('problema_img', $post->ID, 'Imagens da seção Problema (opcional, múltiplas)');
    echo '<p class="desc">Aparece como bloco grande na seção "O ponto de partida". Deixe o texto em branco para ocultar a seção.</p>';
    echo '</div>';
}

/* ----- Pesquisa ----- */
function aline_mb_pesquisa($post) {
    aline_mb_styles_once();
    echo '<div class="aline-mb">';
    echo '<label for="aline_pesquisa_intro">Introdução da pesquisa</label>';
    aline_field_input('pesquisa_intro', 'textarea', $post->ID, 'Como você conduziu a pesquisa.');
    aline_field_image('pesquisa_img', $post->ID, 'Imagens da seção Pesquisa (opcional, múltiplas)');
    for ($i = 1; $i <= 3; $i++) {
        echo '<div class="group">';
        echo '<h4>Bloco ' . $i . '</h4>';
        echo '<label>Título</label>'; aline_field_input("pesquisa_b{$i}_title", 'text', $post->ID);
        echo '<label>Itens (um por linha)</label>'; aline_field_input("pesquisa_b{$i}_items", 'textarea', $post->ID, "Item 1\nItem 2\nItem 3");
        echo '</div>';
    }
    echo '<p class="desc">Cada bloco vira um card. Deixe em branco para ocultar.</p>';
    echo '</div>';
}

/* ----- Processo ----- */
function aline_mb_processo($post) {
    aline_mb_styles_once();
    echo '<div class="aline-mb">';
    aline_field_image('processo_img', $post->ID, 'Imagens da seção Processo (opcional, múltiplas)');
    for ($i = 1; $i <= 4; $i++) {
        echo '<div class="group">';
        echo '<h4>Etapa ' . $i . '</h4>';
        echo '<label>Título</label>'; aline_field_input("metodo_{$i}_title", 'text', $post->ID);
        echo '<label>Descrição</label>'; aline_field_input("metodo_{$i}_body", 'textarea', $post->ID);
        echo '</div>';
    }
    echo '<p class="desc">Etapas em branco são ocultadas.</p>';
    echo '</div>';
}

/* ----- Solução ----- */
function aline_mb_solucao($post) {
    aline_mb_styles_once();
    echo '<div class="aline-mb">';
    echo '<label for="aline_solucao">Texto da solução</label>';
    aline_field_input('solucao', 'textarea', $post->ID, 'O que foi entregue ao cliente.');
    aline_field_image('solucao_img', $post->ID, 'Imagens da seção Solução (opcional, múltiplas)');
    echo '</div>';
}

/* ----- Resultados ----- */
function aline_mb_resultados($post) {
    aline_mb_styles_once();
    echo '<div class="aline-mb">';
    aline_field_image('resultados_img', $post->ID, 'Imagens da seção Resultados (opcional, múltiplas)');
    for ($i = 1; $i <= 4; $i++) {
        echo '<div class="group">';
        echo '<h4>Resultado ' . $i . '</h4>';
        echo '<div class="row-4">';
        echo '<div><label>Número (opcional)</label>'; aline_field_input("resultado_{$i}_num", 'text', $post->ID, 'Ex.: 57%'); echo '</div>';
        echo '<div><label>Descrição</label>'; aline_field_input("resultado_{$i}_label", 'text', $post->ID, 'Ex.: redução na abertura dos freezers'); echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '<p class="desc">Se preencher um número, vira card estatístico. Se deixar só a descrição, vira item com check.</p>';
    echo '</div>';
}

/* ----- Citação ----- */
function aline_mb_citacao($post) {
    aline_mb_styles_once();
    echo '<div class="aline-mb">';
    echo '<label for="aline_citacao_texto">Texto da citação</label>';
    aline_field_input('citacao_texto', 'textarea', $post->ID);
    echo '<label for="aline_citacao_autor">Autor / fonte</label>';
    aline_field_input('citacao_autor', 'text', $post->ID, 'Nome — Empresa');
    aline_field_image('citacao_img', $post->ID, 'Imagens da seção Citação (opcional, múltiplas)');
    echo '</div>';
}

/* ----- Próximo projeto ----- */
function aline_mb_proximo($post) {
    aline_mb_styles_once();
    echo '<div class="aline-mb">';
    echo '<label for="aline_next_id">ID do próximo projeto</label>';
    aline_field_input('next_id', 'text', $post->ID);
    echo '<p class="desc">Opcional. Se vazio, o próximo da listagem é usado automaticamente.</p>';
    echo '</div>';
}

/* ---------- Save ---------- */
add_action('save_post_projeto', function ($post_id) {
    if (!isset($_POST['aline_projeto_nonce']) || !wp_verify_nonce($_POST['aline_projeto_nonce'], 'aline_projeto_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    foreach (aline_projeto_all_fields() as $key => $type) {
        $field = 'aline_' . $key;
        if (!isset($_POST[$field])) continue;
        $raw = wp_unslash($_POST[$field]);
        if ($type === 'image') {
            $ids = array_values(array_filter(array_map('intval', explode(',', (string) $raw))));
            $val = implode(',', $ids);
        } else {
            $val = wp_kses_post($raw);
        }
        update_post_meta($post_id, '_aline_' . $key, $val);
    }
});


/* ---------- Helpers ---------- */
function aline_meta($post_id, $key, $fallback = '') {
    $val = get_post_meta($post_id, '_aline_' . $key, true);
    return $val !== '' ? $val : $fallback;
}

function aline_lines($text) {
    $lines = preg_split('/\r\n|\r|\n/', (string) $text);
    return array_values(array_filter(array_map('trim', $lines), 'strlen'));
}

function aline_arrow_up_right($class = 'arrow') {
    return '<svg class="' . esc_attr($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>';
}

/* Render section images (one or many, centered) if attachment IDs are set. */
function aline_section_image($post_id, $key, $size = 'large') {
    $raw = (string) get_post_meta($post_id, '_aline_' . $key, true);
    $ids = array_values(array_filter(array_map('intval', explode(',', $raw))));
    if (!$ids) return;
    $count = count($ids);
    echo '<figure class="section-figure section-figure--count-' . (int) $count . '">';
    foreach ($ids as $att_id) {
        $url = wp_get_attachment_image_url($att_id, $size);
        if (!$url) $url = wp_get_attachment_url($att_id);
        if (!$url) continue;
        $alt = get_post_meta($att_id, '_wp_attachment_image_alt', true);
        echo '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '" loading="lazy" />';
    }
    echo '</figure>';
}
