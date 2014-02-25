<?php


class Spw_ajax {
    
    /**
     * Initialization method, sets up the ajax hook
     */

    public static function init(){
        add_action( 'wp_ajax_spw_search', array( __CLASS__, 'search_callback' ) );
    }

    /**
     * ajax callback for searches
     */

    public static function search_callback() {
        if ( !isset( $_POST['query'] )) {
            die();
        }
        $notInArray = array();
        $post_type = Spw_helper::post_types();
        if ( isset( $_POST['alreadySelected'] ) ){
            $notInArray = json_decode( $_POST['alreadySelected'] );
        }
        $query = esc_attr( $_POST['query'] );
        $args = array(
            's' => $query,
            'post__not_in' => $notInArray,
            'post_type' => $post_type,
            'post_status' => 'publish',
        );


        $posts = new WP_Query( $args );
        if ( $posts->have_posts() ) :
            while ( $posts->have_posts() ) : $posts->the_post(); ?>
                    <?php echo '<div class="search-result" data-post-id="' . get_the_ID() . '"><div class="spw-plus"> + </div> ' . get_the_title() . '</div>'; ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p class="no-results">No results</p>
        <?php endif; ?>

        <?php die();  ?>
    <?php }    
}

Spw_ajax::init();