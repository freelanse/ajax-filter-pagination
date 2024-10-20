<?php
function my_enqueue_scripts() {
    wp_enqueue_script('my-ajax-script', get_template_directory_uri() . '/js/my-ajax.js', ['jquery'], null, true);
    wp_localize_script('my-ajax-script', 'my_ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('load_products_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');



function load_products() {
    // Проверка nonce для безопасности
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'load_products_nonce')) {
        wp_send_json_error('Ошибка проверки nonce', 400);
        return;
    }

    $category = isset($_POST['category']) && $_POST['category'] !== 'all' ? sanitize_text_field($_POST['category']) : '';
    $offset = intval($_POST['offset']);
    $limit = intval($_POST['limit']);

    // Основные параметры запроса WP_Query
    $args = [
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'offset' => $offset,
    ];

    // Добавляем фильтрацию по категории, если она указана
    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'product-categories',
                'field'    => 'slug',
                'terms'    => $category,
            ],
        ];
    }

    $query = new WP_Query($args);
    $response = ['html' => '', 'has_more' => false];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $response['html'] .= '
                <div class="item">
                    <img loading="lazy" src="' . get_the_post_thumbnail_url() . '" alt="img">
                    <div class="sub">
                        <p class="title">' . get_the_title() . '</p>
                        <a href="' . get_permalink() . '">Посмотреть</a>
                    </div>
                </div>';
        }

        // Проверяем, есть ли еще записи для загрузки
        if ($query->found_posts > $offset + $limit) {
            $response['has_more'] = true;
        }
    }

    wp_send_json($response);
}
add_action('wp_ajax_load_products', 'load_products');
add_action('wp_ajax_nopriv_load_products', 'load_products');
