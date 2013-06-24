<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header('nomenu'); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
			<span style="padding:5px"><a href ="http://www.louklouk.local/fr/">Français</a></span><span style="padding:5px"><a href ="http://www.louklouk.local/en/">English</span><span style="padding:5px"><a href ="http://www.louklouk.local/nl/">Nederland</span>
		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>