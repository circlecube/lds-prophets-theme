<?php
/**
 * Template part for displaying single posts.
 *
 * @package Prophets & Apostles
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="leader_thumb">
			<img src="<?php the_field('main_image'); ?>" />
		</div>
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

		<div class="entry-meta">
			<p>
			<?php the_field('ordained_date') ?>
			<?php if ( get_field('living') ) { ?>
				- current				
			<?php } elseif ( !get_field('excommunicated') ) { ?>
				- <?php the_field('death_date'); ?>
			<?php } elseif ( get_field('excommunicated') ) { ?>
				- <?php the_field('excommunication_date'); ?> (<?php the_field('death_date'); ?>)
			<?php } ?>
			</p>
			<?php if ( get_field('president_date') ) { ?>
				<p>
				<?php the_field('prophet_number'); ?> President, 
				<?php the_field('president_date') ?>
				<?php if ( get_field('living') ) { ?>
					- current
				<?php } else { ?>
				- <?php the_field('death_date'); ?>
				<?php } ?>
				</p>
			<?php } ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
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

