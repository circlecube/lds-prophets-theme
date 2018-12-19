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
// define('WP_USE_THEMES', false);
// require('./blog/wp-blog-header.php');
 
// set header for json mime type
header('Content-type: application/json;');

// delete_transient( 'leader_json' );

if ( false === ( $json = get_transient( 'leader_json' ) ) ) {
	
	$leaders = [];

	$leader_args = array(
		'post_type' => 'leader',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation'	=> 'AND',
			'ordination_clause' => array(
				'key'		=> 'ordained_date',
				'compare'	=> 'EXISTS',
				'type'		=> 'DATE',
			),
			'seniority_clause' => array(
				'key'		=> 'quorum_seniority',
				'compare'	=> 'EXISTS',
				'type'		=> 'NUMERIC',
			),
		),
		'orderby'	=> array(
			'ordination_clause'	=> 'DESC',
			'seniority_clause'	=> 'DESC',
		),
	);
	$leader_query = new WP_Query( $leader_args );
	// The Loop
	if ( $leader_query->have_posts() ) {
		while ( $leader_query->have_posts() ) {
			$leader_query->the_post();
			
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
			
			$leader = (object)[];
			$leader->name = the_title(null,null,false);
			$leader->first_name = get_field('first_name');
			$leader->middle_name = get_field('middle_name');
			$leader->last_name = get_field('last_name');
			$leader->initial = get_field('initial');
			$leader->position = get_field('position');
			$leader->birthdate = get_field('birthdate');
			$leader->ordained_date = get_field('ordained_date');
			$leader->seniority = get_field('quorum_seniority');
			$leader->deathdate = get_field('death_date');
			$leader->hometown = get_field('hometown');
			$leader->conference_talks = get_field('conference_talks');
			$leader->profession = get_field('profession');
			$leader->military = get_field('military');
			$leader->education = get_field('education');
			$leader->mission = get_field('mission');
			$leader->reason_called = get_field('reason_called');
			$leader->img = $main_image['sizes']['medium'];
			$leader->img2 = $youth_image['sizes']['medium'];
			$leader->groups = $groups;
			$leader->served_with = $served_with;
			$leader->polygamist = get_field( 'polygamist' );
			$leader->number_of_wives = get_field( 'number_of_wives' );

			array_push($leaders, $leader);
			
		} // end while 
	}// end loop if
		
	wp_reset_query();

	$json = json_encode($leaders);

	set_transient( 'leader_json', $json, WEEK_IN_SECONDS );
}

echo $json;



die(); ?>