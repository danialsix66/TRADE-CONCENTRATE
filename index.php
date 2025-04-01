<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <?php wp_head(); // Hook for plugins and scripts ?>
</head>
<body <?php body_class(); ?>>

    <header>
        <div class="site-logo">
            <a href="<?php echo home_url(); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?> Logo">
            </a>
        </div>
        <nav>
            <?php
            // Display the main navigation menu
            wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'container' => 'ul',
                'menu_class' => 'main-navigation'
            ));
            ?>
        </nav>
    </header>

    <main>
        <section class="main-content">
            <h1><?php the_title(); ?></h1>
            <article>
                <?php
                // Display the content of the page or post
                if (have_posts()) :
                    while (have_posts()) :
                        the_post();
                        the_content();
                    endwhile;
                else :
                    echo '<p>No content found</p>';
                endif;
                ?>
            </article>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All Rights Reserved.</p>
    </footer>

    <?php wp_footer(); // Hook for plugins and scripts ?>
</body>
</html>
