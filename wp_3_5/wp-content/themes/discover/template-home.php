<?php
/*
Template Name: Home
*/
?>

<?php get_header(); ?>

<!-- slider -->

<div id="slider_container">

	<div class="row">
	
		<div class="four columns">
		
			<h1><?php if(esc_html(of_get_option('welcome_head')) != NULL){ echo esc_html(of_get_option('welcome_head'));} else echo "Write your welcome headline here." ?></h1>
		<p><?php if(esc_textarea(of_get_option('welcome_text')) != NULL){ echo esc_textarea(of_get_option('welcome_text'));} else echo "Nullam posuere felis a lacus tempor eget dignissim arcu adipiscing. Donec est est, rutrum vitae bibendum vel, suscipit non metus." ?></p>
		
		<?php if(esc_html(of_get_option('welcome_button')) != NULL){ ?> 
	<a class="button large" href="<?php if(esc_url(of_get_option('welcome_button_link')) != NULL){ echo esc_url(of_get_option('welcome_button_link'));} ?>"><?php echo esc_html(of_get_option('welcome_button')); ?></a>
	<?php } else { ?> <a class="button large" href="<?php if(esc_url(of_get_option('welcome_button_link')) != NULL){ echo esc_url(of_get_option('welcome_button_link'));} ?>"> <?php echo "Download Now!" ?></a> <?php } ?>
		
		</div>	

		<div class="eight columns">
			<?php get_template_part( 'element-slider', 'index' ); ?>
		</div>
		
	</div>
</div>

<!-- slider end -->


<!-- home boxes -->
	
	<div class="row" id="box_container">

		<?php get_template_part( 'element-boxes', 'index' ); ?>

	</div>
	
<!-- home boxes end -->

<div class="clear"></div>
		

<?php get_footer(); ?>