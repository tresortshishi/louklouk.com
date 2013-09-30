		<!--right-col-->
		<div class="four columns">
			
				<!--sidebar-->
				<div id="sidebar">
			
			<ul class="xoxo">

<?php

	if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>


		<?php endif; // end primary widget area ?>
			</ul>

<?php
	// A second sidebar for widgets, just because.
	if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>

			<ul class="xoxo">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>
			

<?php endif; ?>

				</div><!--sb end-->
				
			</div> <!--right-col-->