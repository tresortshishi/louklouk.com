<!--search form-->
				
				    <form method="get" id="search" action="<?php echo esc_url(home_url()); ?>">

					<div>
					<?php $req=''; ?>
               		<input type="text" value="<?php _e( 'search this site', 'discover' ); ?>" name="s" id="s"  onfocus="if(this.value=='<?php _e( 'search this site', 'discover' ); ?>'){this.value=''};" onblur="if(this.value==''){this.value='<?php _e( 'search this site', 'discover' ); ?>'};" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
               		<input type="submit" id="searchsubmit" value="" />
                	
					</div>
               		</form>