<?php
/**
 * The template for displaying comments
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'bect' ); ?></p>
	<?php endif; 
	//Custom field
	$fields =  array(

  'author' =>
    '<p class="comment-field author"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
    '" placeholder="Nama: *" size="30"' . $aria_req . ' /></p>',

  'email' =>
    '<p class="comment-field email"><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
    '" placeholder="Email: *" size="30"' . $aria_req . ' /></p>',

  'url' =>
    '<p class="comment-field url"><input id="url" placeholder="Website" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
    '" size="30" /></p>',
);
	
	$comment_args = array(
  'id_form'           => 'commentform',
  'id_submit'         => 'submit',
  'class_submit'      => 'submit',
  'name_submit'       => 'submit',
  'title_reply'       => __( 'Tambahkan Komentar' ),
  'title_reply_to'    => __( 'Leave a Reply to %s' ),
  'cancel_reply_link' => __( 'Cancel Reply' ),
  'label_submit'      => __( 'Kirim' ),
  'format'            => 'xhtml',

  'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="Tulis Yang Ingin Kamu Sampaikan" cols="45" rows="3" aria-required="true">' .
    '</textarea></p>',

  'must_log_in' => '<p class="must-log-in">' .
    sprintf(
      __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
      wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
    ) . '</p>',

  'logged_in_as' => '<p class="logged-in-as">' .
    sprintf(
    __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ),
      admin_url( 'profile.php' ),
      $user_identity,
      wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
    ) . '</p>',

  'comment_notes_before' => '<p class="comment-notes">' .
    __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) .
    '</p>',

  'fields' => apply_filters( 'comment_form_default_fields', $fields ),
);
	 comment_form($comment_args); 
   
   if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf( _nx( '1 Comment on &ldquo;%2$s&rdquo;', '%1$s Comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'bect' ),
					number_format_i18n( get_comments_number() ), get_the_title() );
			?>
		</h2>

		<?php bect_comment_nav(); ?>

		<div class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'div',
					'short_ping'  => true,
					'avatar_size' => 56,
				) );
			?>
		</div><!-- .comment-list -->

		<?php bect_comment_nav(); ?>

	<?php endif; // have_comments() ?>

</div><!-- .comments-area -->
