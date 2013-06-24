<?php get_header(); ?>

	<div id="subhead_container">
		
		<div class="row">

		<div class="twelve columns">
		
		<h1><?php the_title(); ?></h1>
			
			</div>	
			
	</div></div>
	
		<!--content-->
		<div class="row" id="content_container">
			
			<!--left col--><div class="eight columns">
		
				<div id="left-col">
		

				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					
					<div class="post-entry">

						<?php the_content(); ?>
						<div class="clear"></div>
						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'discover' ), 'after' => '' ) ); ?>
						
					</div><!--post-entry end-->
					
					<?php comments_template( '', true ); ?>

<?php endwhile; ?>
	</div> <!--left-col end-->
</div> <!--column end-->

<?php get_sidebar(); ?>

</div>
<!--content end-->
		

<?php get_footer(); ?>