<?php
/**
 * The template for displaying all single posts and attachments
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */
			get_template_part( 'content' );
			
			// Previous/next post navigation.
			the_post_navigation( array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'bect' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Next post:', 'bect' ) . '</span> ' .
					'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'bect' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Previous post:', 'bect' ) . '</span> ' .
					'<span class="post-title">%title</span>',
			) );
			
			bect_related_post();
				
				// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		// End the loop.
		endwhile;
		?>
	</div><!-- .content-area -->
<?php get_sidebar();
 get_footer(); ?>
