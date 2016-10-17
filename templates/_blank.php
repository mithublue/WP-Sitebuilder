<?php
/**
 * Template Name: Blank Template
 */

get_header(); ?>

    <div id="main-content" class="main-content">
        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">
                <?php
                // Start the Loop.
                while ( have_posts() ) : the_post();

                    //the_title();
                    the_content();
                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
                endwhile;
                ?>
            </div><!-- #content -->
        </div><!-- #primary -->
    </div><!-- #main-content -->

<?php
get_sidebar();
get_footer();