<?php
/**
 * Template Name: Leaders JSON
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
define('WP_USE_THEMES', false);
// require('./blog/wp-blog-header.php');
 
// set header for json mime type
header('Content-type: application/json;');

// delete_transient( 'leader_json' );

if ( false === ( $json = get_transient( 'leader_json' ) ) ) {
	
	$json = 'var leaders = [';

	$leader_args = array(
		'post_type' => 'leader',
		'posts_per_page' => -1,
    	'orderby' => 'meta_value',
    	'meta_key' => 'ordained_date'
	);
	$leader_query = new WP_Query( $leader_args );
	// The Loop
	if ( $leader_query->have_posts() ) {
		while ( $leader_query->have_posts() ) {
			$leader_query->the_post();
			
			
			//CLUB
			// $club_terms = get_the_terms( get_the_ID(), 'club');
			// $clubs = [];
			// foreach ( $club_terms as $term ) {
			// 	$clubs[] = $term->name;
			// }
			// $club = $clubs[0];
			
			//IMG
			$attachment_id = get_post_thumbnail_id( get_the_ID() );
			$image_attributes = wp_get_attachment_image_src( $attachment_id, 'large' ); // returns an array
			$img = $image_attributes[0];
			
			//main
			$main_image = get_field('main_image');
			$youth_image = get_field('youth_image');
			
			//Group
			$leader_group_terms = get_the_terms( get_the_ID(), 'types');
			$leader_group = [];
			foreach ( $leader_group_terms as $term ) {
				$leader_group[] = $term->name;
			}
			$groups = implode(",", $leader_group);
			
			$prophet_group_terms = get_the_terms( get_the_ID(), 'prophet');
			$prophet_group = [];
			foreach ( $prophet_group_terms as $term ) {
				$prophet_group[] = $term->name;
			}
			$served_with = implode(",", $prophet_group);
			
				
	$json .= "{
'name': '" . the_title(null,null,false) . "',
'first_name': '" . get_field('first_name') . "',
'middle_name': '" . get_field('middle_name') . "',
'last_name': '" . get_field('last_name') . "',
'initial': '" . get_field('initial') . "',
'position': '" . get_field('position') . "',
'birthdate': '" . get_field('birthdate') . "',
'ordained_date': '" . get_field('ordained_date') . "',
'ordinal': '" . get_field('quorum_seniority') . "',
'order': '" . get_field('quorum_seniority') . "',
'deathdate': '" . get_field('death_date') . "',
'hometown': '" . get_field('hometown') . "',
'img': '" . $main_image['sizes']['medium'] . "',
'img2': '" . $youth_image['sizes']['medium'] . "',
'conference_talks': '" . get_field('conference_talks') . "',
'profession': '" . get_field('profession') . "',
'military': '" . get_field('military') . "',
'education': '" . get_field('education') . "',
'mission': '" . get_field('mission') . "',
'groups': '" . $groups ."',
'served_with': '" . $served_with ."',";
$json .= '
"reason_called": "' . get_field('reason_called') . '"';
$json .= "},";

			// } //end if
			
		} // end while 
	}// end loop if
		
	wp_reset_query();

	$json .= "];";

	set_transient( 'leader_json', $json, WEEK_IN_SECONDS );
}

echo $json;



die(); ?>