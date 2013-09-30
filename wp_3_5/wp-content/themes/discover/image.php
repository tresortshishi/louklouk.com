<?php get_header(); ?>
		
	<div id="subhead_container">
		
		<div class="row">

		<div class="twelve columns">
		
		<h1><?php if ( is_category() ) {
		single_cat_title();
	} elseif (is_archive() ) {
		echo (__( 'Archives for ', 'discover' )); single_month_title();
	} else {
		wp_title('',true);
	} ?></h1>
			
			</div>	
			
	</div></div>


<!--content-->
		<div class="row" id="content_container">
				
	<!--left col--><div class="eight columns">
	
		<div id="left-col">
        
<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
          
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <p><?php _e('&#8249; Return to', 'discover'); ?> <a href="<?php echo esc_url(get_permalink($post->post_parent)); ?>" rel="gallery"><?php echo get_the_title($post->post_parent); ?></a></p>

			<div class="meta-data">
			
			<?php discover_posted_on(); ?> | <?php comments_popup_link( __( 'Leave a comment', 'discover' ), __( '1 Comment', 'discover' ), __( '% Comments', 'discover' ) ); ?>
			
			</div><!--meta data end-->
			<div class="clear"></div>
                                
                <div class="attachment-entry">
                    <a href="<?php echo esc_url(wp_get_attachment_url($post->ID)); ?>"><?php echo wp_get_attachment_image( $post->ID, 'large' ); ?></a>
					<?php if ( !empty($post->post_excerpt) ) the_excerpt(); ?>
                    <?php the_content(__('Read more &#8250;;', 'discover')); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'discover'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->

          <nav id="nav-single">
				<span class="nav-previous"><?php previous_image_link( false, __('&larr; Previous Image', 'discover' )); ?></span>
				<span class="nav-next"><?php next_image_link( false, __('Next Image &rarr;', 'discover' )); ?></span>
            </span> </nav>
                        
                <?php if ( comments_open() ) : ?>
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'discover') . ' ', ', ', '<br />'); ?> 
                    <?php the_category(__('Posted in %s', 'discover') . ', '); ?> 
                </div><!-- end of .post-data -->
                <?php endif; ?>             

            <div class="post-edit"><?php edit_post_link(__('Edit', 'discover')); ?></div>             
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
			<?php comments_template( '', true ); ?>
            
        <?php endwhile; ?>  

<?php endif; ?>  
      
	</div> <!--left-col end-->
</div> <!--column end-->

<?php get_sidebar(); ?>

</div>
<!--content end-->
		

<?php get_footer(); ?>