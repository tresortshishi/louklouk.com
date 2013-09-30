<?php get_header(); ?>

<?php if(!is_front_page()) { ?>

	<div id="subhead_container">
		
		<div class="row">

		<div class="twelve columns">
		
		
 <?php

    ?>
			
<h1><?php if ( is_category() ) {
		single_cat_title();
		} elseif (is_tag() ) {
		echo (__( 'Archives for ', 'discover' )); single_tag_title();
		} elseif (is_author() ) {
    $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));		
		echo (__( 'Archives for ', 'discover' )); echo $curauth->nickname;		
	} elseif (is_archive() ) {
		echo (__( 'Archives for ', 'discover' )); single_month_title(' ', true);
	} else {
		wp_title('',true);
	} ?></h1>
			
			</div>	
			
	</div></div>
	
<?php } ?>


<!-- slider -->
<?php if(is_front_page()) { ?>

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

<?php } ?> <!-- slider end -->


<!-- home boxes -->
<?php if(is_front_page()) { ?>
	
	<div class="row" id="box_container">

		<?php get_template_part( 'element-boxes', 'index' ); ?>

	</div>
	
<!-- home boxes end -->

<div class="clear"></div>
<?php } ?> 
<!--content-->

		<div class="row" id="content_container">
				
	<!--left col--><div class="eight columns">
	
		<div id="left-col">
			
			<?php get_template_part( 'loop', 'index' ); ?>

	</div> <!--left-col end-->
</div> <!--column end-->

<?php get_sidebar(); ?>

</div>
<!--content end-->
		

<?php get_footer(); ?>