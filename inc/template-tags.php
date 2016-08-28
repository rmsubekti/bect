<?php
/**
 * Custom template tags for Bect
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Bect
 * @since Bect 1.0
 */

if ( ! function_exists( 'bect_comment_nav' ) ) :
/**
 * Display navigation to next/previous comments when applicable.
 *
 * @since Bect 1.0
 */
function bect_comment_nav() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
	?>
	<nav class="navigation comment-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'bect' ); ?></h2>
		<div class="nav-links">
			<?php
				if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'bect' ) ) ) :
					printf( '<div class="nav-previous">%s</div>', $prev_link );
				endif;

				if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'bect' ) ) ) :
					printf( '<div class="nav-next">%s</div>', $next_link );
				endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .comment-navigation -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'bect_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, tags.
 *
 * @since Twenty Fifteen 1.0
 */
function bect_entry_meta() {
	echo '<span class="entry-meta">';
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', __( 'Featured', 'bect' ) );
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'bect' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" title="updated on %4$s" datetime="%1$s">%2$s</time><time class="updated hide" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago',
			esc_attr( get_the_modified_date( 'c' ) ),
			get_the_modified_date()
		);

		printf( '<small class="posted-on"><a href="%1$s" rel="bookmark">%2$s</a></small>',
			esc_url( get_permalink() ),
			$time_string
		);
	}
	

	if ( 'post' == get_post_type() ) {
		if ( is_singular() || is_multi_author() ) {
			printf( '<small class="byline"><span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span></small>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author()
			);
		}
	}

	if ( is_attachment() && wp_attachment_is_image() ) {
		// Retrieve attachment metadata.
		$metadata = wp_get_attachment_metadata();

		printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
			_x( 'Full size', 'Used before full size attachment link.', 'bect' ),
			esc_url( wp_get_attachment_url() ),
			$metadata['width'],
			$metadata['height']
		);
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<small class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'bect' ), get_the_title() ) );
		echo '</small>';
	}
	echo '</span>';
}
endif;

if ( ! function_exists( 'bect_category_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, tags.
 *
 * @since Twenty Fifteen 1.0
 */
function bect_category_meta() {
	if ( 'post' == get_post_type() ) {
		if ( is_singular() || is_multi_author() ) {
			printf( '<small class="byline"><span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span></small>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author()
			);
		}

		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'bect' ) );
		if ( $categories_list && bect_categorized_blog() ) {
			printf( '<small class="cat-links">%s</small>',
				$categories_list
			);
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'bect' ) );
		if ( $tags_list ) {
			printf( '<small class="tags-links">%s</small>',
				$tags_list
			);
		}
	}
}
endif;

/**
 * Determine whether blog/site has more than one category.
 *
 * @since Bect 1.0
 *
 * @return bool True of there is more than one category, false otherwise.
 */
function bect_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'bect_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'bect_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so bect_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so bect_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in {@see bect_categorized_blog()}.
 *
 * @since Bect 1.0
 */
function bect_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'bect_categories' );
}
add_action( 'edit_category', 'bect_category_transient_flusher' );
add_action( 'save_post',     'bect_category_transient_flusher' );

if ( ! function_exists( 'bect_post_thumbnail' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @since Bect 1.0
 */
function bect_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
		<?php the_post_thumbnail(); ?>
	</div><!-- .post-thumbnail -->

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php
			the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title() ) );
		?>
	</a>

	<?php endif; // End is_singular()
}
endif;

if ( ! function_exists( 'bect_get_link_url' ) ) :
/**
 * Return the post URL.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since bect 1.0
 *
 * @see get_url_in_content()
 *
 * @return string The Link format URL.
 */
function bect_get_link_url() {
	$has_url = get_url_in_content( get_the_content() );

	return $has_url ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}
endif;

//Remove emoji script on head 
//https://wordpress.org/support/topic/removing-emoji-code-from-header
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

//remove meta generator on head
//https://wordpress.org/support/topic/removing-meta-generator-wordpress
remove_action('wp_head', 'wp_generator');

/**
* Auto Thumbnail
*/
! defined( 'ABSPATH' ) and exit;
if ( ! function_exists( 'featured_image' ) ) {
	add_action( 'save_post', 'featured_image' );
	function featured_image() {
		if ( ! isset( $GLOBALS['post']->ID ) )
			return NULL;
		if ( has_post_thumbnail( get_the_ID() ) )
			return NULL;
		$args = array(
				'numberposts' => 1,
				'order' => 'ASC', // DESC for the last image
				'post_mime_type' => 'image',
				'post_parent' => get_the_ID(),
				'post_status' => NULL,
				'post_type' => 'attachment'
		);
		$attached_image = get_children( $args );
		if ( $attached_image ) {
			foreach ( $attached_image as $attachment_id => $attachment )
			set_post_thumbnail( get_the_ID(), $attachment_id );
		}
	}
}

/**
 * Custom Excerpt length and Remove […] string using Filters
 */
function new_excerpt_more( $more ) {
	return ' ';
}
add_filter('excerpt_more', 'new_excerpt_more');

function custom_excerpt_length( $length ) {
	return 26;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


if ( ! function_exists( 'bect_related_post' ) ) :
/**
 * Display Related post by category.
 * http://www.wphats.com/get-related-post-category-wordpress-without-plugin/
 *
 * @since Pyfal 1.0
 */
	function bect_related_post(){
		echo '<div class="related-post">';
		global $post;
		$catArgs = array(
			'category__in'	=> wp_get_post_categories($post->ID),
			'showposts'	=> 4,//display number of posts
			'orderby'	=>'rand',//display random posts
			'post__not_in'	=> array($post->ID)
 		);

		$cat_post_query = new WP_Query($catArgs); 

		if( $cat_post_query->have_posts() ) { 
			while ($cat_post_query->have_posts()) : $cat_post_query->the_post();?>

		<div class="related-item"><a href="<?php the_permalink() ?>"> 

			<?php if ( has_post_thumbnail() ) :
			$thumb_id = get_post_thumbnail_id();
			$thumb_url = wp_get_attachment_image_src($thumb_id,'medium', true);
		echo '<div class="post-thumbnail" style="background:url('.$thumb_url[0].') center / cover"></div>';
		else: 
		echo '<div class="post-thumbnail"></div>';
		endif;?>
		 		 <?php the_title(); ?></a></div>
			<?php endwhile; 

		wp_reset_query(); }
		echo '</div>';
	}
endif;


//http://crunchify.com/how-to-create-social-sharing-button-without-any-plugin-and-script-loading-wordpress-speed-optimization-goal/
function social_sharing_buttons() {
	if(is_singular() || is_home()){
	
		// Get current page URL 
		$post_URL = get_permalink();
 
		// Get current page title
		$post_Title = str_replace( ' ', '%20', get_the_title());
		
		// Get Post Thumbnail for pinterest
		$post_Thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
 
		// Construct sharing URL without using any script
		$twitterURL = 'https://twitter.com/intent/tweet?text='.$post_Title.'&amp;url='.$post_URL.'&amp;via=pyfal';
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$post_URL;
		$googleURL = 'https://plus.google.com/share?url='.$post_URL;
		$bufferURL = 'https://bufferapp.com/add?url='.$post_URL.'&amp;text='.$post_Title;
		
		// Based on popular demand added Pinterest too
		$pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$post_URL.'&amp;media='.$post_Thumbnail[0].'&amp;description='.$post_Title;
 
		// Add sharing button at the end of page/page content
		$var .= '<div class="social-sharer">';
		$var .= '<a class="twitter" href="'.$twitterURL.'" target="_blank">Twitter</a>';
		$var .= '<a class="facebook" href="'.$facebookURL.'" target="_blank">Facebook</a>';
		$var .= '<a class="googleplus" href="'.$googleURL.'" target="_blank">Google+</a>';
		$var .= '<a class="buffer" href="'.$bufferURL.'" target="_blank">Buffer</a>';
		$var .= '<a class="pinterest" href="'.$pinterestURL.'" target="_blank">Pin It</a>';
		$var .= '</div>';
		
		echo $var;
	}
}



add_action( 'wp_dashboard_setup', 'bect_add_dashboard_widget' );
// call function to create our dashboard widget function prowp_add_dashboard_widget() {
function bect_add_dashboard_widget(){
    wp_add_dashboard_widget( 'bect_dashboard_widget',
	         'Bect Widget', 'bect_create_dashboard_widget' );
}
// function to display our dashboard widget content function prowp_create_dashboard_widget() {
function bect_create_dashboard_widget(){
    echo '<p>Hello World! This is my Dashboard Widget</p>';
}