<?php
/**
 * @package easyread
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php $post_format = get_post_format(); ?>
	<div class="blog-item-wrap">
		<div class="post-inner-content">
			<header class="entry-header page-header">
                            	<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php if ( $post_format=='aside' || $post_format=='status'  ){echo '';}else{the_title();}?></a></h1>					
                                    <?php  if ( $post_format=='aside' || $post_format=='status'  ){echo '';}else{echo easyread_get_single_category(get_the_ID()); } ?>
				
				<?php if ( 'post' == get_post_type() ) : ?>
				<div class="entry-meta">
                                     <?php if ( $post_format=='aside' || $post_format=='status' ){?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo ucfirst($post_format).' '; ?></a><?php easyread_posted_on();?>
                                     <?php } else{easyread_posted_on();} ?>

					<?php edit_post_link(sprintf(
								/* translators: %s: Name of current post */
								esc_html__( 'Edit %s', 'easyread' ),
								the_title( '<span class="screen-reader-text">"', '"</span>', false )
							),
							'<span class="edit-link">',
							'</span>'
						);
					?>

				</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->

            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>" ><?php the_post_thumbnail( 'easyread-featured', array( 'class' => 'single-featured' )); ?></a>

			<?php if ( is_search() ) : // Only display Excerpts for Search ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
				<p><a class="btn btn-default read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'easyread' ); ?></a></p>
			</div><!-- .entry-summary -->
			<?php else : ?>
			<div class="entry-content">

				<?php
				if ( get_the_excerpt() != "" ) :
					the_excerpt();
				else :
					the_content();
				endif;
			  ?>

				<?php
				wp_link_pages( array(
					'before'            => '<div class="page-links">'.esc_html__( 'Pages:', 'easyread' ),
					'after'             => '</div>',
					'link_before'       => '<span>',
					'link_after'        => '</span>',
					'pagelink'          => '%',
					'echo'              => 1
						) );
				?>

				<?php if( ! is_single() ) : ?>
				<div class="read-more">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php esc_html_e( 'Read More', 'easyread' ); ?></a>
				</div>
				<?php endif; ?>

                                <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
                                    <div class="entry-footer">
					<span class="comments-link"><?php comments_popup_link( esc_html__( 'Leave a comment', 'easyread' ), esc_html__( '1 Comment', 'easyread' ), esc_html__( '% Comments', 'easyread' ) ); ?></span>
                                    </div><!-- .entry-footer -->
                                <?php endif; ?>
			</div><!-- .entry-content -->
			<?php endif; ?>
		</div>
	</div>
</article><!-- #post-## -->
