<?php

if( !class_exists('WPPT_Init') ) :

    class WPPT_Init {

        public function __construct() {
            add_action('add_meta_boxes', array( $this, 'wp_add_post_custom_template' ) );
            add_action('save_post', array( $this, 'save_custom_post_template' ),10,2);
            add_filter('single_template', array( $this, 'load_custom_post_template' ));
        }


        /**
         * Custom template metabox
         * @param $post_type
         */
        function wp_add_post_custom_template( $post_type ) {
            if( $post_type == 'page') return;

            add_meta_box(
                'postparentdiv',
                __('WP Post Template','wpsb'),
                array( $this, 'wp_custom_post_template_meta_box' ),
                $post_type,
                'side',
                'core'
            );
        }

        /**
         * Custom template metabox data
         * @param $post
         */
        function wp_custom_post_template_meta_box( $post ) {
            $templates = get_page_templates();
            $sel_template = get_post_meta( $post->ID, '_wp_page_template', true );
            $blank_template = get_post_meta( $post->ID, 'wpsb_blank_template', true );

            ?>
            <div class="bs-container template_meta">
                <div class="mb10">
                    <input type="hidden" name="wpsb_blank_template" value="false">
                    <label><input type="checkbox" class="wpsb-blank-template" name="wpsb_blank_template" value="true" <?php echo $blank_template == 'true' ? 'checked' : '' ; ?>> <?php _e('Load blank template','wpsb'); ?></label>
                </div>
                <div class="wpsb-template-list">
                    <p><strong><?php _e('Template','wpsb'); ?></strong></p>
                    <label class="screen-reader-text" for="page_template"><?php _e('Page Template','wpsb'); ?></label>
                    <?php if( is_array( $templates ) ) :?>
                        <select name="page_template" id="page_template">
                            <option value="default"><?php _e('Default Template','wpsb'); ?></option>
                            <?php foreach( $templates as $name => $path ):?>
                                <option value="<?php echo $path; ?>" <?php echo $sel_template == $path ? 'selected' : ''; ?>><?php echo $name; ?></option>
                            <?php endforeach;?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>
            <script>
                (function ($) {
                    function show_template_list() {
                        if( $('.wpsb-blank-template').is(':checked') ) {
                            $('.wpsb-template-list').hide();
                        } else {
                            $('.wpsb-template-list').show();
                        }
                    }
                    show_template_list();
                    $('.wpsb-blank-template').change(function () {
                        show_template_list();
                    })
                }(jQuery))

            </script>
            <?php
        }

        /**
         * Saeve template
         * @param $post_id
         * @param $post
         */
        function save_custom_post_template( $post_id, $post ) {
            global $pagenow;

            if( $pagenow != 'post.php' ) return;
            if ( get_post_type( $post_id ) == 'page' ) return;
            if( !current_user_can( 'edit_posts' ) ) return;

            $wpsb_blank_template = isset($_POST['wpsb_blank_template'])?sanitize_text_field($_POST['wpsb_blank_template']) : '';
            $post_template = isset($_POST['page_template'])?sanitize_text_field( $_POST['page_template'] ) : '';

            if( !empty($wpsb_blank_template) ) {
                update_post_meta( $post_id, 'wpsb_blank_template', $wpsb_blank_template );
            }

            if ( !empty( $post_template ) ) {
                update_post_meta( $post_id, '_wp_page_template', $post_template );
            }
        }

        /**
         * Load template in frontend
         * @param $single_template
         * @return mixed
         */
        function load_custom_post_template( $single_template ) {
            global $post;


            if( get_post_meta( $post->ID, 'wpsb_blank_template', true ) == 'true' ) {
                $single_template = WPSB_ROOT.'/templates/_blank.php';
            } else {
                $template_path = get_post_meta( $post->ID , '_wp_page_template', true );
                if( $template_path && $template_path != 'default' ) {
                    if( $custom_template = locate_template($template_path) )
                        return $custom_template;
                }
            }

            return $single_template;
        }




        public static function init() {
            new WPPT_Init();
        }

    }

    WPPT_Init::init();

endif;
