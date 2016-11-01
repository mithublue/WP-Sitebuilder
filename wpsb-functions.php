<?php
if( !function_exists( 'pri' ) ) :
    function pri($data){
        echo '<pre>';print_r($data);echo '</pre>';
    }
endif;

function wpsb_is_lego_enabled( $post_id ) {
    if( get_post_meta($post_id, 'is_lego_enabled', true ) == 'true' ) {
        return true;
    }
    return false;
}

function wpsb_get_post_pagebuilder_data( $post_id ) {
    return maybe_unserialize( get_post_meta($post_id, 'lego_layout', true ) );
}

function wpsb_render_element( $elem_id , $lego_layout ) {

    //validating
    if( !is_array($lego_layout) ) return;
    $elem_id = sanitize_text_field($elem_id);
    if( !isset( $lego_layout['element'][$elem_id] ) ) return;

    if( $lego_layout['element'][$elem_id]['type'] == 'widget' ) {

        if ( !isset( $lego_layout['element'][$elem_id]['instance'] ) ) {
            $lego_layout['element'][$elem_id]['instance'] = '';
        }

        parse_str( $lego_layout['element'][$elem_id]['instance'], $i );

        if( !isset( $i[ 'widget-'.$lego_layout['element'][$elem_id]['id_base']][$elem_id] ) ) {
            $i[ 'widget-'.$lego_layout['element'][$elem_id]['id_base']][$elem_id] = '';
        }

        $instance = $i[ 'widget-'.$lego_layout['element'][$elem_id]['id_base']][$elem_id];

        if( isset($lego_layout['element'][$elem_id]['widgetized']) && $lego_layout['element'][$elem_id]['widgetized'] == 'true' ) {
            the_widget( $lego_layout['element'][$elem_id]['name'],$instance);
        } else {
            wpsb_display_element( $lego_layout['element'][$elem_id]['name'] , $instance );
        }

    }
}

function wpsb_display_element( $widget, $instance = array(), $args = array() ) {
    global $wpsb_widgets;

    if( !class_exists( $widget ) || ( get_parent_class($widget) != 'Wpsb_Base_Element' && get_parent_class($widget) != 'WP_Widget' ) ) return;
    if( !is_array($wpsb_widgets) ) return;
    if( !isset( $wpsb_widgets[$widget] ) ) return;

    $widget_obj = $wpsb_widgets[$widget];

    /*if ( ! ( $widget_obj instanceof WP_Widget ) ) {
        return;
    }*/

    $default_args = array(
        'before_widget' => '<div class="widget %s">',
        'after_widget'  => "</div>",
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    );
    $args = wp_parse_args( $args, $default_args );
    $args['before_widget'] = sprintf( $args['before_widget'], $widget_obj['name'] );

    $instance = wp_parse_args($instance);

    /**
     * Fires before rendering the requested widget.
     *
     * @since 3.0.0
     *
     * @param string $widget   The widget's class name.
     * @param array  $instance The current widget instance's settings.
     * @param array  $args     An array of the widget's sidebar arguments.
     */
    do_action( 'the_widget', $widget, $instance, $args );

    (new $widget_obj['name'])->_set(-1);
    (new $widget_obj['name'])->widget($args, $instance);
}

function wpsb_register_widget( $lego_widget = array() ) {
    global $wpsb_widgets;
    $default = array(
        'label' => '',
        'name' => '',
        'id_base' => '',
        'widget_options' => array(
            'description' => ''
        ),
        //'widgetized' => false
    );

    $lego_widget = array_merge( $default, $lego_widget );
    $wpsb_widgets[$lego_widget['name']] = $lego_widget;
}

function wpsb_preview( $id, $lego_layout ) {
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }
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
        <?php
        $element_id = $_POST['id'];
        $layout = $_POST['lego_layout'];
        wpsb_render_element( $element_id, $layout );
        ?>
    </div><!-- .entry-content -->
</div>
<?php wp_footer(); ?>
</body>
</html>
<?php
}

function wpsb_post_thumbnail() {
    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
        return;
    }

    if ( is_singular() ) :
        ?>

        <div class="post-thumbnail">
            <?php the_post_thumbnail(); ?>
        </div><!-- .post-thumbnail -->

    <?php else : ?>

        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
            <?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
        </a>

    <?php endif; // End is_singular()
}


function wpsb_excerpt( $class = 'entry-summary' ) {
    $class = esc_attr( $class );

    if ( has_excerpt() || is_search() ) : ?>
        <div class="<?php echo $class; ?>">
            <?php the_excerpt(); ?>
        </div><!-- .<?php echo $class; ?> -->
    <?php endif;
}


if ( ! function_exists( 'wpsb_entry_meta' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags.
     *
     * Create your own wpsb_entry_meta() function to override in a child theme.
     *
     * @since wpsb 1.0
     */
    function wpsb_entry_meta() {
        if ( 'post' === get_post_type() ) {
            $author_avatar_size = apply_filters( 'wpsb_author_avatar_size', 49 );
            printf( '<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
                get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
                _x( 'Author', 'Used before post author name.', 'wpsb' ),
                esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                get_the_author()
            );
        }

        if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
            wpsb_entry_date();
        }

        $format = get_post_format();
        if ( current_theme_supports( 'post-formats', $format ) ) {
            printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
                sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'wpsb' ) ),
                esc_url( get_post_format_link( $format ) ),
                get_post_format_string( $format )
            );
        }

        if ( 'post' === get_post_type() ) {
            wpsb_entry_taxonomies();
        }

        if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'wpsb' ), get_the_title() ) );
            echo '</span>';
        }
    }
endif;

if ( ! function_exists( 'wpsb_entry_date' ) ) :
    /**
     * Prints HTML with date information for current post.
     *
     * Create your own wpsb_entry_date() function to override in a child theme.
     *
     * @since wpsb 1.0
     */
    function wpsb_entry_date() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf( $time_string,
            esc_attr( get_the_date( 'c' ) ),
            get_the_date(),
            esc_attr( get_the_modified_date( 'c' ) ),
            get_the_modified_date()
        );

        printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
            _x( 'Posted on', 'Used before publish date.', 'wpsb' ),
            esc_url( get_permalink() ),
            $time_string
        );
    }
endif;


if ( ! function_exists( 'wpsb_entry_taxonomies' ) ) :
    /**
     * Prints HTML with category and tags for current post.
     *
     * Create your own wpsb_entry_taxonomies() function to override in a child theme.
     *
     * @since wpsb 1.0
     */
    function wpsb_entry_taxonomies() {
        $categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'wpsb' ) );
        if ( $categories_list && wpsb_categorized_blog() ) {
            printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                _x( 'Categories', 'Used before category names.', 'wpsb' ),
                $categories_list
            );
        }

        $tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'wpsb' ) );
        if ( $tags_list ) {
            printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                _x( 'Tags', 'Used before tag names.', 'wpsb' ),
                $tags_list
            );
        }
    }
endif;

if ( ! function_exists( 'wpsb_categorized_blog' ) ) :
    /**
     * Determines whether blog/site has more than one category.
     *
     * Create your own wpsb_categorized_blog() function to override in a child theme.
     *
     * @since wpsb 1.0
     *
     * @return bool True if there is more than one category, false otherwise.
     */
    function wpsb_categorized_blog() {
        if ( false === ( $all_the_cool_cats = get_transient( 'wpsb_categories' ) ) ) {
            // Create an array of all the categories that are attached to posts.
            $all_the_cool_cats = get_categories( array(
                'fields'     => 'ids',
                // We only need to know if there is more than one category.
                'number'     => 2,
            ) );

            // Count the number of categories that are attached to the posts.
            $all_the_cool_cats = count( $all_the_cool_cats );

            set_transient( 'wpsb_categories', $all_the_cool_cats );
        }

        if ( $all_the_cool_cats > 1 ) {
            // This blog has more than 1 category so wpsb_categorized_blog should return true.
            return true;
        } else {
            // This blog has only 1 category so wpsb_categorized_blog should return false.
            return false;
        }
    }
endif;

/***
 * check if the given post type
 * is enabled for post type
 */
function is_pagebuilder_enabled_post_type( $post_type ) {
    $non_pagebuilder_post_types = get_transient( 'non_pagebuilder_post_types' );

    if( is_array( $non_pagebuilder_post_types ) ) {
        if( isset( $non_pagebuilder_post_types[$post_type] ) && $non_pagebuilder_post_types[$post_type] == 'true' ) return false;
        else return true;
    } else {
        return true;
    }
}

function get_non_pagebuilder_post_types() {
    $non_pagebuilder_post_types = get_transient( 'non_pagebuilder_post_types' );
    if( !is_array( $non_pagebuilder_post_types ) ) $non_pagebuilder_post_types = array();
    return $non_pagebuilder_post_types;
}