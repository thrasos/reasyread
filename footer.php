<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package easyread
 */
?>
				</div><!-- close .*-inner (main-content or sidebar, depending if sidebar is used) -->
			</div><!-- close .row -->
		</div><!-- close .container -->
	</div><!-- close .site-content -->

	<div id="footer-area">
		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="site-info container">
				<div class="row">
					                               
                                     <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer") ) : ?>

                               
<div class="col-md-4">
<h3></h3>

</div>
<div class="col-md-4">
<h3></h3>

</div>
<div class="col-md-4">
<h3></h3>

</div>
<div class="clear"></div>
                          <?php endif; ?>    
                                    
                                    
                                    
					<div class="copyright col-md-12">
						<?php echo esc_html( get_theme_mod( 'easyread_footer_copyright', 'Easyread' ) ); ?>
						<?php easyread_footer_info(); ?>
					</div>
				</div>
			</div><!-- .site-info -->
			<div class="scroll-to-top"><i class="fa fa-angle-up"></i></div><!-- .scroll-to-top -->
		</footer><!-- #colophon -->
	</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>