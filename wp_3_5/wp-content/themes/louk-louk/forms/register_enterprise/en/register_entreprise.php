
<fieldset>
	<!-- $labels_register_enterprise=  array('name'        =>  'Name',
                              'firstname'   =>  'Firstname',
                              'email'       =>  'E-mail',
                              'email1'      =>  'Enter your e-mail again',
                              'password'    =>  'Your password',
                              'password2'   =>  'Enter your password again',
                              'adress'      =>  'Enterprise adress',
                              'number'      =>  'Number',
                              'box_number'  =>  'Box number',
                              'zip_code'    =>  'Zip code',
                              'phonenumber' =>  'Phone number');
                            -->
                        
                <h3>Enterprise registration</h3></br>

                <fieldset>
                  <legend>Owner</legend></br>

                    <label><?php echo $labels_register_enterprise["name"];?><span>*</span></label></br>
                    <input name="name" type="text" /></br>
                  
                    <label><?php echo $labels_register_enterprise["firstname"];?><span>*</span></label></br>
                    <input name="firstname" type="text" /></br>

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
                    <legend>Enterprise information</legend></br>

                     <input name="country" type="hidden" value="belgium"/>

                    <label><?php echo $labels_register_enterprise["adress"];?><span>*</span></label></br>
                    <input name="adress" type="text" /></br>
                    
                    <label><?php echo $labels_register_enterprise["number"];?><span>*</span></label></br>
                    <input name="number" type="text" /></br>
                    
                    <label><?php echo $labels_register_enterprise["box_number"];?><span>*</span></label></br>
                    <input name="box_number" type="text" /></br>
                    
                    <label><?php echo $labels_register_enterprise["zip_code"];?><span>*</span></label></br>
                    <input name="zip_code" type="text" /></br>
                    
                  
                    
                    <label>Tél<span>*</span></label></br>
                    <input name="phonenumber" type="text" /></br>
                  </fieldset>
                
                  
                  <label>Chargez votre photo d'entreprise<span>*</span></label></br>
                  <input name="owner" type="text" /></br>
                  
                
                  
                  <input name="submit" class="buttonsearchfilter" type="button" value="REGISTER" width="50px" id="add_enterprise"/></br>
                </fieldset>
