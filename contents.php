<article id="post-<?php the_ID(); ?>" class="pyfal-post">
<?php
	
//Show thumbnails as background
	 if ( has_post_thumbnail() ) : 
			$thumb_id = get_post_thumbnail_id();
			$thumb_url = wp_get_attachment_image_src($thumb_id,'medium', true);
		echo '<div class="post-thumbnail" style="background:url('.$thumb_url[0].') center / cover"></div>';
		else: ?>
		<div class="post-thumbnail"></div>
	<?php endif; ?>

<div class="post-body">
<header class="entry-header">
<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
<?php bect_entry_meta(); ?>
</header>
<section class="entry-content">
<?php the_excerpt();?>
</section>
</div>
</article>