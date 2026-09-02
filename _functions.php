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
        'problema' => 'textarea',
        // Pesquisa
        'pesquisa_intro' => 'textarea',
        // Solução
        'solucao' => 'textarea',
        // Citação
        'citacao_texto' => 'textarea', 'citacao_autor' => 'text',
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
    echo '<p class="desc">Aparece como bloco grande na seção "O ponto de partida". Deixe em branco para ocultar a seção.</p>';
    echo '</div>';
}

/* ----- Pesquisa ----- */
function aline_mb_pesquisa($post) {
    aline_mb_styles_once();
    echo '<div class="aline-mb">';
    echo '<label for="aline_pesquisa_intro">Introdução da pesquisa</label>';
    aline_field_input('pesquisa_intro', 'textarea', $post->ID, 'Como você conduziu a pesquisa.');
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
    echo '</div>';
}

/* ----- Resultados ----- */
function aline_mb_resultados($post) {
    aline_mb_styles_once();
    echo '<div class="aline-mb">';
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
        if (isset($_POST[$field])) {
            $val = wp_kses_post(wp_unslash($_POST[$field]));
            update_post_meta($post_id, '_aline_' . $key, $val);
        }
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
