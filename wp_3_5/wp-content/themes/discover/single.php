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

			<div class="meta-data">
			
			<?php discover_posted_on(); ?> <?php _e( 'in', 'discover' ); ?> <?php the_category(', '); ?> | <?php comments_popup_link( __( 'Leave a comment', 'discover' ), __( '1 Comment', 'discover' ), __( '% Comments', 'discover' ) ); ?>
			
			</div><!--meta data end-->
			<div class="clear"></div>

						<?php the_content( __( '', 'discover' ) ); ?>
						<div class="clear"></div>
			<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'discover' ), 'after' => '' ) ); ?>
						
						<?php the_tags('Social tagging: ',' > '); ?>
						
				 <nav id="nav-single"> <span class="nav-previous">
            <?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> Previous Post '); ?>
            </span> <span class="nav-next">
            <?php next_post_link( '%link', 'Next Post <span class="meta-nav">&rarr;</span>'); ?>
            </span> </nav>
						
					</div><!--post-entry end-->
					
					<?php comments_template( '', true ); ?>

<?php endwhile; ?>
	</div> <!--left-col end-->
</div> <!--column end-->

<?php get_sidebar(); ?>

</div>
<!--content end-->
		

<?php get_footer(); ?>