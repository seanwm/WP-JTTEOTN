<?php
/*
Plugin Name: JTTEOTN
Plugin URI: https://github.com/seanwm/WP-JTTEOTN
Description: The JTTEOTN WordPress plugin adds features to WordPress necessary for Journey to the End of the Night's website.
Version: 0.01
Author: Sean W. Mahan
Author URI: http://www.intendedeffect.com/
License: GPLv2
*/

add_action( 'init', 'create_journey_event_type' );
add_action( 'init', 'journey_taxonomies', 0 );
add_action( 'save_post', 'add_journey_event_fields', 10, 2 );
add_action( 'admin_init', 'journey_event_admin' );
add_filter( 'template_include', 'include_template_function', 1 );

function journey_taxonomies() {
	$args = array();
	$labels = array(
			'name'              => _x( 'Journey Cities', 'taxonomy general name' ),
			'singular_name'     => _x( 'Journey City', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Journey Cities' ),
			'all_items'         => __( 'All Journey Cities' ),
			'parent_item'       => __( 'Parent Journey City' ),
			'parent_item_colon' => __( 'Parent Journey City:' ),
			'edit_item'         => __( 'Edit Journey City' ), 
			'update_item'       => __( 'Update Journey City' ),
			'add_new_item'      => __( 'Add New Journey City' ),
			'new_item_name'     => __( 'New Journey City' ),
			'menu_name'         => __( 'Journey Cities' ),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => true,
			'show_tagcloud' => false
		);
	register_taxonomy( 'cities', 'journeys', $args );
}

function create_journey_event_type() {
    register_post_type( 'journeys',
        array(
            'labels' => array(
                'name' => 'Journey Events',
                'singular_name' => 'Journey Event',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Journey Event',
                'edit' => 'Edit',
                'edit_item' => 'Edit Journey Event',
                'new_item' => 'New Journey Event',
                'view' => 'View',
                'view_item' => 'View Journey Event',
                'search_items' => 'Search Journey Events',
                'not_found' => 'No Journey Events found',
                'not_found_in_trash' => 'No Journey Events found in Trash',
                'parent' => 'Parent Journey Event'
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
            'has_archive' => true
        )
    );
}

function journey_event_admin() {
    add_meta_box( 'journey_event_meta_box',
        'Journey Event Details',
        'display_journey_event_meta_box',
        'journeys', 'normal', 'high'
    );
}

function display_journey_event_meta_box( $journey_event ) {
	
		global $post;

		$custom = get_post_custom($post->ID);
		
		wp_nonce_field( plugin_basename( __FILE__ ), 'journey_fields_nonce' );

		$journey_subtitle = esc_html(get_post_meta($post->ID, 'journey_subtitle', true ) );		

		if (get_post_meta( $post->ID, 'journey_date', true ) == '' || get_post_meta( $post->ID, 'journey_date', true )==null)
		{
			$journey_date = time();
		}
		else
		{
			$journey_date = strtotime( get_post_meta( $post->ID, 'journey_date', true ) );
		}
		
		$journey_time = esc_html(get_post_meta( $post->ID, 'journey_time', true ) );
		
		$journey_location_str = esc_html( get_post_meta( $post->ID, 'journey_location_str', true ) );
		
		$journey_location_url = esc_html( get_post_meta( $post->ID, 'journey_location_url', true ) );
		
		$journey_event_details_url = esc_html( get_post_meta( $post->ID, 'journey_event_details_url', true ) );
		
		$journey_facebook_url = esc_html( get_post_meta( $post->ID, 'journey_facebook_url', true ) );
		
		$journey_sfzero_url = esc_html( get_post_meta( $post->ID, 'journey_sfzero_url', true ) );
								
    $journey_num_players = intval( get_post_meta( $post->ID, 'journey_num_players', true ) );

    ?>
    <table>
        <tr>
            <td style="width: 100%">Subtitle (e.g. "Part of Come Out & Play SF 2012")</td>
            <td><input type="text" size="80" name="journey_subtitle_f" value="<?php echo $journey_subtitle; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Date</td>
            <td><input type="date" size="80" name="journey_date_f" value="<?php echo date("Y-m-d",$journey_date); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Time (plain old string)</td>
            <td><input type="text" size="80" name="journey_time_f" value="<?php echo $journey_time; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Location (name, text)</td>
            <td><input type="text" size="80" name="journey_location_str_f" value="<?php echo $journey_location_str; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Location URL (map, facility webpage)</td>
            <td><input type="text" size="80" name="journey_location_url_f" value="<?php echo $journey_location_url; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Details URL (your webpage for this event, if any)</td>
            <td><input type="text" size="80" name="journey_event_details_url_f" value="<?php echo $journey_event_details_url; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Facebook Event URL</td>
            <td><input type="text" size="80" name="journey_facebook_url_f" value="<?php echo $journey_facebook_url; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">SFZero Event URL</td>
            <td><input type="text" size="80" name="journey_sfzero_url_f" value="<?php echo $journey_sfzero_url; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Number of Players&mdash;just to keep track of such things!</td>
            <td><input type="number" size="80" name="journey_num_players_f" value="<?php echo $journey_num_players; ?>" /></td>
        </tr>
    </table>
    <?php
}

function add_journey_event_fields( $journey_event_id, $journey_event ) {
    // Check post type for journey
    if ( $journey_event->post_type == 'journeys' ) {
	
				if ( !wp_verify_nonce( $_POST['journey_fields_nonce'], plugin_basename( __FILE__ ) ) )
				return;
				
        // Store data in post meta table if present in post data
	if (isset($_POST['journey_subtitle_f']) && $_POST['journey_subtitle_f'] != ''){
		update_post_meta($journey_event_id, 'journey_subtitle', strip_tags($_POST['journey_subtitle_f'],"<a>"));
	}
        if ( isset( $_POST['journey_date_f'] ) && $_POST['journey_date_f'] != '' ) {
						$date = strtotime($_POST['journey_date_f']);
            update_post_meta( $journey_event_id, 'journey_date', date('Y-m-d',$date) );
        }
        if ( isset( $_POST['journey_time_f'] ) && $_POST['journey_time_f'] != '' ) {
            update_post_meta( $journey_event_id, 'journey_time', $_POST['journey_time_f'] );
        }
        if ( isset( $_POST['journey_location_str_f'] ) && $_POST['journey_location_str_f'] != '' ) {
            update_post_meta( $journey_event_id, 'journey_location_str', $_POST['journey_location_str_f'] );
        }
        if ( isset( $_POST['journey_location_url_f'] ) && $_POST['journey_location_url_f'] != '' ) {
            update_post_meta( $journey_event_id, 'journey_location_url', $_POST['journey_location_url_f'] );
        }
        if ( isset( $_POST['journey_event_details_url_f'] ) && $_POST['journey_event_details_url_f'] != '' ) {
            update_post_meta( $journey_event_id, 'journey_event_details_url', $_POST['journey_event_details_url_f'] );
        }
        if ( isset( $_POST['journey_facebook_url_f'] ) && $_POST['journey_facebook_url_f'] != '' ) {
            update_post_meta( $journey_event_id, 'journey_facebook_url', $_POST['journey_facebook_url_f'] );
        }
        if ( isset( $_POST['journey_sfzero_url_f'] ) && $_POST['journey_sfzero_url_f'] != '' ) {
            update_post_meta( $journey_event_id, 'journey_sfzero_url', $_POST['journey_sfzero_url_f'] );
        }
        if ( isset( $_POST['journey_num_players_f'] ) && $_POST['journey_num_players_f'] != '' ) {
            update_post_meta( $journey_event_id, 'journey_num_players', intval($_POST['journey_num_players_f']) );
        }
    }
}

function include_template_function( $template_path ) {
    if ( get_post_type() == 'journeys' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-journey.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-journey.php';
            }
        }
    }
    return $template_path;
}

