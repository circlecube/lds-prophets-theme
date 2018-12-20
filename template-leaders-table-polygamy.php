<?php
/**
 * Template Name: Polygamist Leaders Table
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
  * @package Prophets & Apostles
  */

 get_header();

// delete_transient( 'leader_table_polygamy' );

if ( false === ( $leader_table_polygamy = get_transient( 'leader_table_polygamy' ) ) ) {
	
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
			'polygamy_clause' => array(
				'key'       => 'polygamist',
				'value'   => '1',
			),
		),
		'orderby'	=> array(
			'ordination_clause'	=> 'DESC',
		),
	);
	$leader_query = new WP_Query( $leader_args );
	// The Loop
	if ( $leader_query->have_posts() ) {
		
		$leader_table_polygamy = "<table>
			<thead>
				<tr>
					<th>Img</th>
					<th>Name</th>
					<th>Birthdate</th>
					<th>Ordained</th>
					<th>Number of Wives</th>
					<th>Seniority</th>
					<th>Prophet</th>
					<th>Enddate</th>
					<th>Groups</th>
				</tr>
			</thead>
			<tbody>";
		
		while ( $leader_query->have_posts() ) {
			$leader_query->the_post();
			
			//IMG - featured image
			$attachment_id = get_post_thumbnail_id( get_the_ID() );
			$image_attributes = wp_get_attachment_image_src( $attachment_id, 'thumbnail' ); // returns an array
			$img = $image_attributes[0];
			
			$main_image = get_field('main_image');
			
			$end_date = get_field('death_date');
			if ( get_field('excommunication_date') ) {
				$end_date = get_field('excommunication_date');
			}
			
			//STATUS
			$leader_group_terms = get_the_terms( get_the_ID(), 'types');
			$leader_group = [];
			foreach ( $leader_group_terms as $term ) {
				$leader_group[] = $term->name;
			}
			$groups = implode(",", $leader_group);
			
			$seniority = get_field('quorum_seniority') > 0 ? get_field('quorum_seniority') : '';
			$leader_table_polygamy .= "<tr>
				<td><img src='" . $main_image['sizes']['thumbnail'] . "'></td>
				<td><a href='" . get_the_permalink() . "'>" . get_the_title() . "</a></td>
				<td data-sort-value='" . get_field('birthdate') . "'>" . get_field('birthdate') . "</td>
				<td data-sort-value='" . get_field('ordained_date') . "'>" . get_field('ordained_date') . "</td>
				<td>" . get_field('number_of_wives') . "</td>
				<td data-sort-value='" . $seniority . "'>" . $seniority . "</td>
				<td data-sort-value='" . get_field('president_date') . "'>" . get_field('president_date') . "</td>
				<td data-sort-value='" . $end_date . "'>" . $end_date . "</td>
				<td>" . get_the_term_list( $post->ID, 'types', '', ', ' ) . ', ' . get_the_term_list( $post->ID, 'prophet', '', ', ' ) . "</td>
			</tr>";
			
		} // end while 
	}// end loop if
		
	wp_reset_query();
	
	$leader_table_polygamy .= "</tbody>
	</table>";

	set_transient( 'leader_table_polygamy', $leader_table_polygamy, WEEK_IN_SECONDS );
}

echo $leader_table_polygamy;

?>


<?php get_footer(); ?>
