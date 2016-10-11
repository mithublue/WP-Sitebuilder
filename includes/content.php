<?php

class Lego_Content{

    public function __construct() {
        add_action( 'the_content', array( $this, 'render_lego_layout' ) );
    }


    /**
     * render lego layout
     */
    function render_lego_layout() {
        global $post;

        if ( wpsb_is_lego_enabled( $post->ID ) ) {

            // do some code
            $lego_layout = wpsb_get_post_pagebuilder_data( $post->ID );
            ?>
            <div class="bs-container">
            <?php
            foreach ( $lego_layout['container'] as $cont_id => $cont_data ) {
                ?>
                <div id="<?php echo $cont_id; ?>" class="container<?php echo $cont_data['is_fluid']; ?>">
                    <?php foreach ( $cont_data['child']['row'] as $key => $row_id ) : ?>
                        <div id="<?php echo $row_id; ?>" class="row <?php echo $lego_layout['row'][$row_id]['property']['attribute']['class'];?>" style="<?php echo $lego_layout['row'][$row_id]['property']['style']['style']; ?>">
                            <?php foreach ( $lego_layout['row'][$row_id]['child']['col']  as $col_key => $col_id  ) :?>
                                <div id="<?php echo $col_id; ?>" class="col-sm-<?php echo $lego_layout['col'][$col_id]['span']; ?> <?php echo $lego_layout['col'][$col_id]['property']['attribute']['class']?>" style="<?php echo $lego_layout['col'][$col_id]['property']['style']['style'];?>">
                                    <?php foreach ( $lego_layout['col'][$col_id]['child']['element'] as $elem_key => $elem_id ) : ?>
                                        <div id="<?php echo $elem_id; ?>" class="<?php echo $lego_layout['element'][$elem_id]['property']['attribute']['class']; ?>" style="<?php echo $lego_layout['element'][$elem_id]['property']['style']['style']; ?>">
                                            <?php wpsb_render_element( $elem_id, $lego_layout ); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php
            }
            ?>
            </div>
            <?php
        }
    }


    public static function init() {
        new Lego_Content();
    }
}

Lego_Content::init();