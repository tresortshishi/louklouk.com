<?php
/*
Template Name: Search advanced Page
*/


/**
* Customized by Tshishi Trésor 24/02/2013
*
*/
get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
			<!--  display form-->
		 <form action="" method="get">
            	<fieldset>
                
			<?php 
			//get current uri
			$current_uri = get_permalink( $post->ID );
			//find code language 
			$uri_part = explode('/', $current_uri);
			$current_lg = (string)$uri_part[3];
			
			switch ($uri_part[3]) {
				case 'fr':
					echo '<h2>Par categories</h2><br/>';
					break;

				case 'en':
					echo '<h2>By categories</h2><br/>';
					break;

				case 'nl':
					echo '<h2>Per categorie</h2><br/>';
					break;

				default:
					echo '<h2>Par categories</h2><br/>';
					break;
			}
			?>
			<?php if (get_category_by_slug($current_lg)->term_id == NULL):?>
			pas d'entreprise inscrite dnas cette langue
			<?php else:?>

               	  <label>Secteur</label>
                          <?php 
                
                	$args = array(
									'show_option_all'    => '',
									'show_option_none'   => '',
									'orderby'            => 'ID', 
									'order'              => 'ASC',
									'show_count'         => 0,
									'hide_empty'         => 0, 
									'child_of'           => 0,
									'exclude'            => '',
									'echo'               => 1,
									'selected'           => 0,
									'hierarchical'       => 0, 
									'name'               => 'cat',
									'id'                 => get_category_by_slug($current_lg)->term_id,
									'class'              => 'postform',
									'depth'              => 0,
									'tab_index'          => 0,
									'taxonomy'           => 'category',
									'hide_if_empty'      => false
								);

                wp_dropdown_categories(args); ?> 
                  <!--
                  <label>Type</label>
                  <select name="" size="1" dir="ltr">
                    <option>Lorem ipsum</option>
                    <option>Lorem ipsum</option>
                    <option>Lorem ipsum</option>
                    <option>Lorem ipsum</option>
                  </select>
              		-->
                  
                  <input name="" class="buttonsearchfilter" type="submit" value="RECHERCHER" width="50px" />
                </fieldset>
              <?php endif;?>
                
        
     </form>



			<!-- display result-->
			<?php // The Query
			if(isset($_REQUEST['cat'])) :
				$the_query = new WP_Query('cat='.$_REQUEST['cat']);

				// The Loop
				while ( $the_query->have_posts() ) :
					$the_query->the_post();
			
					echo '<li><a href="http://www.louklouk.local/blog/'.str_replace(' ', '-', lcfirst(get_the_title())) .'">' . get_the_title() . '</a></li>';
					
				endwhile;

			endif;?>

			

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>