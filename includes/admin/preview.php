<?php

/*if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}*/
//wp_enqueue_style( 'siteorigin-preview-style', plugin_dir_url( __FILE__ ) . '../css/live-editor-preview.css', array(), SITEORIGIN_PANELS_VERSION );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="content" class="site-content">
    <div class="entry-content">
        this is data
        <?php

        ?>
    </div><!-- .entry-content -->
</div>
<?php wp_footer(); ?>
</body>
</html>
