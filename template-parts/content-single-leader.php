<?php
/**
 * Template part for displaying single posts.
 *
 * @package Prophets & Apostles
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php //prophets_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<img src="<?php the_field('main_image'); ?>" />
		<?php the_field('birthdate'); ?>
		<?php if ( !get_field('living') ) { ?>
			- <?php the_field('death_date'); ?> (<?php the_field('died_at_age'); ?>)
		<?php } else { ?>
			(<?php echo calculate_age( get_field('birthdate') ); ?>)
		<?php } ?>
		
		<dl>
			<dt>Ordained</dt>
			<dd><?php the_field('ordained_date') ?> (<?php the_field('ordained_at_age'); ?>)</dd>
			
			<?php if ( get_field('living') && get_field('quorum_seniority') ) { ?>
				<dt>Quorum Seniority</dt>
				<dd><?php the_field('quorum_seniority'); ?></dd>
			<?php } ?>
			
			<?php if ( get_field('position') ) { ?>
				<dt>Current Position</dt>
				<dd><?php the_field('position'); ?></dd>
			<?php } ?>
			
			<?php if ( get_field('hometown') ) { ?>
				<dt>Hometown</dt>
				<dd><?php the_field('hometown'); ?></dd>
			<?php } ?>
			
			<?php if ( get_field('excommunicated') ) { ?>
				<?php if ( get_field('excommunication_date') ) { ?>
					<dt>Date Terminated</dt>
					<dd><?php the_field('excommunication_date'); ?>
						(<?php echo calculate_age( get_field('birthdate'), get_field('excommunication_date') ); ?>)
					</dd>
				<?php } ?>
				<?php if ( get_field('reason_terminated') ) { ?>
					<dt>Reason Terminated</dt>
					<dd><?php the_field('reason_terminated'); ?></dd>
				<?php } ?>
				<?php if ( get_field('readmitted_date') ) { ?>
					<dt>Date Readmitted</dt>
					<dd><?php the_field('readmitted_date'); ?>
						(<?php echo calculate_age( get_field('birthdate'), get_field('readmitted_date') ); ?>)</dd>
					</dd>
				<?php } ?>
			<?php } ?>
			
			<?php if ( get_field('conference_talks') ) { ?>
				<dt>Conference Talks</dt>
				<dd><?php the_field('conference_talks'); ?></dd>
			<?php } ?>
			
			<?php if ( get_field('profession') ) { ?>
				<dt>Profession</dt>
				<dd><?php the_field('profession'); ?></dd>
			<?php } ?>
			
			<?php if ( get_field('military') ) { ?>
				<dt>Military</dt>
				<dd><?php the_field('military'); ?></dd>
			<?php } ?>
			
			<?php if ( get_field('education') ) { ?>
				<dt>Education</dt>
				<dd><?php the_field('education'); ?></dd>
			<?php } ?>
			
			<?php if ( get_field('mission') ) { ?>
				<dt>Mission</dt>
				<dd><?php the_field('mission'); ?></dd>
			<?php } ?>
			
			<?php if ( get_field('military') ) { ?>
				<dt>Military</dt>
				<dd><?php the_field('military'); ?></dd>
			<?php } ?>
			

			<?php
				$images = get_field('images');

				if( $images ) { ?>
				    <ul>
				        <?php foreach( $images as $image ) { ?>
				            <li>
				                <a href="<?php echo $image['url']; ?>">
				                     <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
				                </a>
				                <p><?php echo $image['caption']; ?></p>
				            </li>
				        <?php } ?>
				    </ul>
			<?php } ?>
			
			
		</dl>
		
		
		
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'prophets' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php prophets_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

