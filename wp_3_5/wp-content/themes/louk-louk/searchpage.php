<?php
/*
Template Name: Search Page
*/


/**
* Customized by Tshishi Trésor 24/02/2013
*
*/
get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
			<?php 
			//get current uri
			$current_uri = get_permalink( $post->ID );
			//find code language 
			$uri_part = explode('/', $current_uri);
			
			switch ($uri_part[3]) {
				case 'fr':
					echo '<h2>Trouvez une entreprise</h2><br/>';
					break;

				case 'en':
					echo '<h2>Find a firme</h2><br/>';
					break;

				case 'nl':
					echo '<h2>Zoek en bedrijf</h2><br/>';
					break;

				default:
					echo '<h2>Trouvez une entreprise</h2><br/>';
					break;
			}
			?>
			
			<?php get_search_form(); ?>

			

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>