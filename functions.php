<?php
// Add theme support for various features
function mycustomtheme_setup() {
    // Enable dynamic title tag support for better SEO
    add_theme_support('title-tag');
    
    // Enable post thumbnails (featured images)
    add_theme_support('post-thumbnails');
    
    // Register the main navigation menu
    register_nav_menus(array(
        'main-menu' => __('Main Menu', 'mycustomtheme'),
    ));
}
add_action('after_setup_theme', 'mycustomtheme_setup');

// Enqueue theme styles and scripts
function mycustomtheme_enqueue_styles() {
    // Enqueue the main stylesheet
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'mycustomtheme_enqueue_styles');

// Add SEO support for HTML5 elements and RSS
function mycustomtheme_add_seo_support() {
    add_theme_support('html5', array('search-form', 'comment-form', 'gallery', 'caption'));
    add_theme_support('automatic-feed-links');
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 400,
        'flex-height' => true,
        'flex-width' => true,
    ));
}
add_action('after_setup_theme', 'mycustomtheme_add_seo_support');

// Register widgets
function mycustomtheme_widgets_init() {
    register_sidebar(array(
        'name' => __('Main Sidebar', 'mycustomtheme'),
        'id' => 'sidebar-1',
        'description' => __('Widgets for the main sidebar', 'mycustomtheme'),
        'before_widget' => '<section class="widget">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'mycustomtheme_widgets_init');

// Enqueue jQuery and the custom JS file for product filtering
function myplatform_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('product-filter', get_template_directory_uri() . '/js/product-filter.js', array('jquery'), null, true);

    wp_localize_script('product-filter', 'ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'myplatform_enqueue_scripts');

// Function to filter products based on AJAX request
function filter_products() {
    // Get filter values from the AJAX request
    $product_name = isset($_POST['product_name']) ? sanitize_text_field($_POST['product_name']) : '';
    $brix_level = isset($_POST['brix_level']) ? sanitize_text_field($_POST['brix_level']) : '';
    $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';
    $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
    $sort_by = isset($_POST['sort_by']) ? sanitize_text_field($_POST['sort_by']) : '';

    // Set up query arguments
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND'
        )
    );

    // Filter by product name
    if (!empty($product_name)) {
        $args['s'] = $product_name;
    }

    // Filter by Brix level
    if (!empty($brix_level) && $brix_level != 'all') {
        $args['meta_query'][] = array(
            'key' => 'brix_level',
            'value' => $brix_level,
            'compare' => '='
        );
    }

    // Filter by country of origin
    if (!empty($country) && $country != 'all') {
        $args['meta_query'][] = array(
            'key' => 'country_of_origin',
            'value' => $country,
            'compare' => '='
        );
    }

    // Filter by date
    if (!empty($date)) {
        $args['meta_query'][] = array(
            'key' => 'date',
            'value' => $date,
            'compare' => '='
        );
    }

    // Sorting options (price, country, etc.)
    if (!empty($sort_by)) {
        if ($sort_by == 'price-asc') {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'price';
            $args['order'] = 'ASC';
        } elseif ($sort_by == 'price-desc') {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'price';
            $args['order'] = 'DESC';
        } elseif ($sort_by == 'country-asc') {
            $args['orderby'] = 'meta_value';
            $args['meta_key'] = 'country_of_origin';
            $args['order'] = 'ASC';
        } elseif ($sort_by == 'country-desc') {
            $args['orderby'] = 'meta_value';
            $args['meta_key'] = 'country_of_origin';
            $args['order'] = 'DESC';
        }
    }

    // Query products
    $query = new WP_Query($args);

    // Output the results in table rows
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo '<tr>';
            echo '<td><a href="' . get_permalink() . '">' . get_the_title() . '</a></td>';
            echo '<td>' . get_post_meta(get_the_ID(), 'brix_level', true) . '</td>';
            echo '<td>' . get_post_meta(get_the_ID(), 'country_of_origin', true) . '</td>';
            echo '<td>' . display_price_color(get_the_ID()) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="4">No products found</td></tr>';
    }

    wp_die();
}
add_action('wp_ajax_filter_products', 'filter_products');
add_action('wp_ajax_nopriv_filter_products', 'filter_products');

// Function to display color-coded prices based on comparison to the previous day
function display_price_color($post_id) {
    // Get the current price and previous day's price from the product meta
    $current_price = get_post_meta($post_id, 'price', true);
    $previous_price = get_post_meta($post_id, 'previous_price', true);  // Assuming you store the previous price as 'previous_price'

    // Determine color based on price comparison
    if ($previous_price && $current_price > $previous_price) {
        return '<span style="color: green;">$' . $current_price . '</span>';  // Price increased
    } elseif ($previous_price && $current_price < $previous_price) {
        return '<span style="color: red;">$' . $current_price . '</span>';  // Price decreased
    } else {
        return '<span style="color: blue;">$' . $current_price . '</span>';  // Price stayed the same
    }
}

