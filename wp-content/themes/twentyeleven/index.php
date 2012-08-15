<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 */

get_header(); ?>

		<!--<div id="primary">-->
			<div id="content" role="main">

				<div class="logadvanced">
        			<div id="thema">
        
        		</div>
        
        		<div id="arrowcurrent"></div>
        
        		<div class="boxinscription">
        
          		<h2>Inscrivez-vous</h2>
                     <ul>
                        <li><a href="forms.htm">Complétez votre fiche</a></li>
                        <li><a href="#">Entreprises et activités</a></li>
                        <li><a href="#">Galerie photos</a></li>
                        <li><a href="#">Créer votre blog</a></li>
                     </ul>
         		</div>            
         		<div class="boxfan">
                     
           		<h2>Devenez fan</h2>
                     <ul>
                        <li><a href="#">Votre compte client</a></li>
                        <li><a href="#">Votre carnet d'adresse</a></li>
                        <li><a href="#">Plein d'autres offres</a></li>
                     </ul>
                </div>
     		</div>
     		<hr />
            
            
            <div class="image_carousel">
        	<h2>Derniers inscrits</h2>
            	<div>
                    <ul>
                    <li><img src="img/logo-randstad.png" alt="logo randstad" /></li>
                    <li><img src="img/logo-bacardi.png" alt="logo bacardi" /></li>
                    <li><img src="img/logo-rtbf.png" alt="logo rtbf" /></li>
                    <li><img src="img/logo-sncb.png" alt="logo sncb" /></li>
                    </ul>
            	</div>
            
            	<div class="clearfix"></div>
            
            		<a class="prev" id="foo2_prev" href="#"><img src="img/arrow-left-gal-company.png" width="18" height="38" alt="previous" /><span>prev</span></a>
            		<a class="next" id="foo2_next" href="#"><img src="img/arrow-right-gal-company.png" width="18" height="38" alt="next" /><span>next</span></a>
	    
				</div>
      		<hr />
            
            <div class="info">
      
        			<div class="boxevents">
    
          			<h2>Événements</h2>
                     <ul>
                        <li>
                        	<img src="img/img-event.png" width="103" height="94" alt="pic" />
							<h3>Bourse aux projet</h3>
                            	<span>
                                	
                                    Présentez, rejoignez, soutenez des activités innovantes!<br />
                                    <strong>28.04.2011</strong><br />
                                    à Louvain-La-Neuve<br />
                                    www.mindanmarket.be<br />
                                    { soutenu par UCL }
                                </span>
                                <hr />
                        </li>
                        <li>
                        	<img src="img/img-event.png" width="103" height="94" alt="pic" />
							<h3>19ème Salon de l’emploi et de la création d’activités</h3>
                            	<span>
                                	
                                    Présentez, rejoignez, soutenez des activités innovantes!<br />
                                    <strong>28.04.2011</strong><br />
                                    à Louvain-La-Neuve<br />
                                    www.mindanmarket.be<br />
                                    { soutenu par UCL }
                                </span>
                                <hr />
                        </li>
                     </ul>
        			</div>
        
        
                     
                    <div class="boxadvertise">
                             
                    <h2>Annonces</h2>
                            <ul>
                               <li>
                                   <span>
                                    type: <strong>vente occasion</strong><br />
                                    Lots de bureaux contemporains + chaises en tissu.
                                   </span>
                                 <img src="img/img-advertise.jpg" width="235" height="96" alt="bureaux" />                             
                               </li>
                               <li>
                                   <span>
                                    type: <strong>vente occasion</strong><br />
                                    Lots de bureaux contemporains + chaises en tissu. L'ensemble pour un prix hors concurrence.
                                   </span>
                                 <img src="img/img-advertise.jpg" width="235" height="96" alt="bureaux" />                             
                               </li>
                            </ul>
                 
     				</div>
         
      			</div>  		
      		<hr />

			</div><!-- #content -->
		<!--</div>--><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>