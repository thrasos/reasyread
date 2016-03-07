<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package easyread
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="post-inner-content">

				<section class="error-404 not-found">
					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'easyread' ); ?></h1>
					</header><!-- .page-header -->

					<div class="page-content">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'easyread' ); ?></p>

						<?php get_search_form(); ?>

						<div class="row">
							<div class="col-md-6 not-found-widget">
								<?php the_widget( 'WP_Widget_Recent_Posts', 'title='.esc_html__( 'Recent Posts', 'easyread' ) ); ?>
							</div>

							<div class="col-md-6 not-found-widget">
								<?php if ( easyread_categorized_blog() ) : // Only show the widget if site has multiple categories. ?>
								<div class="widget widget_categories">
									<h2 class="widgettitle"><?php esc_html_e( 'Most Used Categories', 'easyread' ); ?></h2>
									<ul>
									<?php
										wp_list_categories( array(
											'orderby'    => 'count',
											'order'      => 'DESC',
											'show_count' => 1,
											'title_li'   => '',
											'number'     => 10,
										) );
									?>
									</ul>
								</div><!-- .widget -->
								<?php endif; ?>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 not-found-widget">
								<?php
								/* translators: %1$s: smiley */
								$archive_content = '<p>' . sprintf( esc_html__( 'Perhaps in the monthly archives. %1$s', 'easyread' ), convert_smilies( ';)' ) ) . '</p>';
								the_widget( 'WP_Widget_Archives', 'dropdown=1&title='.esc_html__( 'Archives', 'easyread' ), "after_title=</h2>$archive_content" );
								?>
							</div>

							<div class="col-md-6 not-found-widget">
								<?php the_widget( 'WP_Widget_Tag_Cloud', 'title='.esc_html__( 'Tags', 'easyread' ) ); ?>
							</div>
						</div>


				</section><!-- .error-404 -->
			</div>
		</main><!-- #main -->
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
