
<fieldset>

                       
                <h3>Enterprise registration</h3></br>

                <fieldset>
                  <legend>Propriétaire</legend></br>

                    <label><?php echo $labels_register_enterprise["name"];?><span>*</span></label></br>
                    <input name="name" type="text" /></br>
                  
                    <label><?php echo $labels_register_enterprise["firstname"];?><span>*</span></label></br>
                    <input name="firstname" type="text" /></br>

                    <label><?php echo $labels_register_enterprise["login"];?><span>*</span></label></br>
                    <input name="login" type="text" /></br>

                    <label><?php echo $labels_register_enterprise["email"];?><span>*</span></label></br>
                    <input name="email" type="text" /></br>
                  
                    <label><?php echo $labels_register_enterprise["email1"];?><span>*</span></label></br>
                    <input name="email1" type="text" /></br>

                    <label><?php echo $labels_register_enterprise["password"];?><span>*</span></label></br>
                    <input name="password" type="text" /></br>
                  
                    <label><?php echo $labels_register_enterprise["password1"];?><span>*</span></label></br>
                    <input name="password1" type="text" /></br>

                </fieldset>

               	  
                  <!--  
                  <label>Land<span>*</span></label></br>
                  <select name="country" size="1" dir="ltr">
                    <option>Lorem ipsum</option>
                    <option>Lorem ipsum</option>
                    <option>Lorem ipsum</option>
                    <option>Lorem ipsum</option>
                  </select>
                  -->

                  <fieldset>
                    <legend>Information sur l'entreprise</legend></br>

                     <input name="country" type="hidden" value="belgium"/>
                     <input name="activation_code" type="hidden" value="<?php echo md5(strtotime('NOW'))?> "/>
                     <input name="registration_date" type="hidden" value="<?php echo date('YY-mm-dd',strtotime('NOW'))?> "/>
                     <input name="status" type="hidden" value="pending"/>

                     <label><?php echo $labels_register_enterprise["enterprise_name"];?><span>*</span></label></br>
                      <input name="enterprise_name" type="text" /></br>

                    <label><?php echo $labels_register_enterprise["enterprise_description"];?><span>*</span></label></br>
                   
                    <textarea name="enterprise_description" type="text" ></textarea></br>


                    <label><?php echo $labels_register_enterprise["adress"];?><span>*</span></label></br>
                    <input name="adress" type="text" /></br>
                    
                    <label><?php echo $labels_register_enterprise["number"];?><span>*</span></label></br>
                    <input name="number" type="text" /></br>
                    
                    <label><?php echo $labels_register_enterprise["box_number"];?><span>*</span></label></br>
                    <input name="box_number" type="text" /></br>
                    
                    <label><?php echo $labels_register_enterprise["zip_code"];?><span>*</span></label></br>
                    <input name="zip_code" type="text" /></br>
                    
                  
                    
                    <label><?php echo $labels_register_enterprise["phonenumber"];?><span>*</span></label></br>
                    <input name="phonenumber" type="text" /></br>
                  </fieldset>
                
                  <fieldset>
                    <legend>Blog</legend></br>
                    
                    <label><?php echo $labels_register_enterprise["blog_adress"];?><span>*</span></label></br>
                    <input name="blog_adress" type="text" /></br>

                    <label><?php echo $labels_register_enterprise["blog_title"];?><span>*</span></label></br>
                    <input name="blog_title" type="text" /></br>
                  </fieldset>
                  
                  <label>Chargez votre photo d'entreprise<span>*</span></label></br>
                  <input name="thumbnail_path" type="text" /></br>

                  <input name="form" type="hidden" value="register_entreprise" /></br>
                  
                
                  
                  <input name="submit" class="buttonsearchfilter" type="submit" value="REGISTER" width="50px" id="add_enterprise"/></br>
 </fieldset>
