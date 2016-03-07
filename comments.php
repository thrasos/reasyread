<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package easyread
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

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf( _nx( '1 Comment', '%1$s Comment', get_comments_number(), 'comments title', 'easyread' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'easyread' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'easyread' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'easyread' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size'=> 80,
					'callback'   => 'easyread_cb_comment'
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'easyread' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'easyread' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'easyread' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'easyread' ); ?></p>
	<?php endif; ?>

	<?php
        $commenter = wp_get_current_commenter();
        $req = get_option( 'require_name_email' );
        $aria_req = ( $req ? " aria-required='true'" : '' );
        
	$fields =  array(
		'author' => '<div class="row">' .
			'<div class="col-sm-4"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30"' . $aria_req . ' placeholder="' . esc_attr__( 'Name', 'easyread' ) . '" /></div>',

		'email' =>
			'<div class="col-sm-4"><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
			'" size="30"' . $aria_req . ' placeholder="' . esc_attr__( 'Email', 'easyread' ) . '" /></div>',

		'url' =>
			'<div class="col-sm-4"><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" placeholder="' . esc_attr__( 'Website', 'easyread' ) . '" /></div>' .
			'</div>',
	);

	?>

	<?php comment_form(
		array(
			'fields' => $fields,
			'label_submit' => __( 'Post Reply', 'easyread' ),
			'comment_notes_before' => '',
			'comment_field' => '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="'. esc_attr( _x( 'Comment', 'comment form placeholder', 'easyread' ) ).'"></textarea>',
		)
	); ?>

</div><!-- #comments -->