<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

$options = twentyeleven_get_theme_options();
$current_layout = $options['theme_layout'];

if ( 'content' != $current_layout ) :
?>
		<div id="sidebar" class="widget-area" role="complementary">
        <div class="search">
        	<form action="" method="get">
            	<fieldset>
               	  <input name="" class="fieldsearch" type="text" dir="ltr" />
                  <input name="" class="buttonsearch" type="button" value="RECHERCHER" />
                </fieldset>
            </form>
        </div>
				<img src="<?php bloginfo('stylesheet_directory')?>/img/teaser-ing.png" width="195" height="168" alt="#" />
  				<img src="<?php bloginfo('stylesheet_directory')?>/img/img-advertise1.jpg" width="195" height="162" alt="#" /></div>
			<hr />
            
		<div class="push">
        </div>
<!-- hacked by Trésor Tshishi 15/04/2012
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

				<aside id="archives" class="widget">
					<h3 class="widget-title"><?php _e( 'Archives', 'twentyeleven' ); ?></h3>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>

				<aside id="meta" class="widget">
					<h3 class="widget-title"><?php _e( 'Meta', 'twentyeleven' ); ?></h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</aside>

			<?php endif; // end sidebar widget area ?>
-->
		</div><!-- #secondary .widget-area -->
<?php endif; ?>