<?php /* Template Name: Blog
*/ ?> 

<?php get_header(); ?>

	<div id="subhead_container">
		
		<div class="row">

		<div class="twelve columns">
		
<h1><?php if ( is_category() ) {
		single_cat_title();
		} elseif (is_tag() ) {
		echo (__( 'Archives for ', 'discover' )); single_tag_title();
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
			
<?php

global $more; $more = false; # some wordpress wtf logic

  $query_args = array(
     'post_type' => 'post', 
     'paged' => $paged
      );

$query_args = apply_filters( 'discover_blog_template_query_args', $query_args ); 

query_posts($query_args);

if (have_posts()) : while (have_posts()) : the_post();

	$thumb_small = '';
								
	if(has_post_thumbnail(get_the_ID(), 'large'))
	{
	    $image_id = get_post_thumbnail_id(get_the_ID());
	    $thumb_small = wp_get_attachment_image_src($image_id, 'large', true);

	}
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="post-head">
	
			<h1><a href="<?php esc_url(the_permalink()); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'discover' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php if ( get_the_title() == '' ) { _e( '(No title)', 'discover' ); } else { the_title(); } ?></a></h1>
			
			</div><!--post-heading end-->
			
			<div class="meta-data">
			
			<?php discover_posted_on(); ?> in <?php the_category(', '); ?> | <?php comments_popup_link( __( 'Leave a comment', 'discover' ), __( '1 Comment', 'discover' ), __( '% Comments', 'discover' ) ); ?>
			
			</div><!--meta data end-->
			<div class="clear"></div>

<div class="post-entry <?php if ($thumb_small <> '') {echo "timbg";} ?>">
	
				<?php if($thumb_small<>'') { ?>
		
		<a href="<?php esc_url(the_permalink()); ?>"><img src="<?php echo $thumb_small[0]; ?>" alt="<?php the_title(); ?>" /></a>
		
		<?php } ?>
		
			<?php the_content( __( '<span class="read-more">Read More</span>', 'Hero' ) ); ?>
			<div class="clear"></div>
			<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'Hero' ), 'after' => '' ) ); ?>
				
				
				</div><!--post-entry end-->


		<?php comments_template( '', true ); ?>

</div> <!--post end-->

<?php endwhile; endif; ?>

<!--pagination-->
<?php if (function_exists('wp_pagenavi')) { wp_pagenavi(); } 
		else { ?>
	<div class="navigation">
		<div class="alignleft"><?php next_posts_link( __( '&larr; Older posts', 'Hero' ) ); ?></div>
		<div class="alignright"><?php previous_posts_link( __( 'Newer posts &rarr;', 'Hero' ) ); ?></div>
	</div><?php } ?>

	</div> <!--left-col end-->
</div> <!--column end-->

<?php get_sidebar(); ?>

</div><!--content end-->

<?php get_footer(); ?>