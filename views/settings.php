<div id="plugin-information" class="wpd-settings" style="">
	<div id="pages-dropdown" style="display:none">
		<div class="wpd-form-selector">
			<p>
				<label for="wpd-form-selector">Select an existed form bellow </label><br />
				<?php 
					$pages = get_posts('post_type=page');
					if( is_array( $pages ) ) :
						echo '<select id="pages-dropdown" name="pages-dropdown">';
						foreach( $pages as $page ) :
							printf( '<option value="%s">%s</option>', get_permalink( $page->ID ), esc_attr( $page->post_title ) );
						endforeach;
						echo '</select>';
					endif;
				?>
				<a href="#" class="button button-primary" id="wpd-pick-page">Save</a><br />
			</p>

		</div>
	</div>
	<div class="alignright fyi">
		<ul class="list-group">
		  <li class="list-group-item donate-for-dias">
			<p>
				Buy me a coffe ( 5$ )
			</p>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<div class="paypal-donations">
					<input type="hidden" value="_donations" name="cmd"/>
					<input type="hidden" value="6VLWFDFHTRLJQ" name="business"/>
					<input type="hidden" name="amount" value="5">
					<input type="hidden" name="return" value="<?php echo admin_url( 'options-general.php?page=wpd-settings' ); ?>">
					<input type="hidden" value="You found WP Paypal Donate helpful? Your donation is enough to inspire me to do more. Thanks a bunch!" name="item_name"/>
					<input type="hidden" value="USD" name="currency_code"/>
					<input type="image" alt="PayPal – The safer, easier way to pay online." name="submit" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"/><img width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" alt=""/>
				</div>
			</form>		  
			</li>
		</ul>
	</div>
	<div id="section-holder" class="wrap">
		<div id="wpd-wrap">
			<div class="wpd-forms-manager">
				<div class="alignleft wpd-addform">
					<div id="icon-edit" class="icon32"><br></div><h2>Create a form</h2>
					<form id="wpd-add-form" action="<?php echo admin_url( 'options-general.php?page=wpd-settings' ); ?>" method="post" class="form-horizontal" role="form">
					  <div class="form-group">
					    <label for="form-title" class="col-sm-2 control-label">Title</label>
					    <div class="col-sm-10">
					      <input type="text" required="required" class="form-control" id="form-title" name="form-title" >
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="form-business" class="col-sm-2 control-label">Benificier ( Paypal account ID / EMAIL )</label>
					    <div class="col-sm-10">
					      <input type="text" required="required" class="form-control" id="form-business" name="form-business">
					      <small>For security reasons, you should always use IDs instead of emails!</small>
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="form-amount" class="col-sm-2 control-label">Form's default donation amount</label>
					    <div class="col-sm-10">
					      <input type="text" required="required" class="form-control" id="form-amount" name="form-amount" value="5">
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="form-currency" class="col-sm-2 control-label">Currency</label>
					    <div class="col-sm-10">
					      <select name="form-currency" id="form-currency">
								<option value="AUD">Australian Dollar (A $)</option>
								<option value="CAD">Canadian Dollar (C $)</option>
								<option value="EUR">Euro (€)</option>
								<option value="GBP">British Pound (£)</option>
								<option value="JPY">Japanese Yen (¥)</option>
								<option value="USD">US Dollar ($)</option>
								<option value="NZD">New Zealand Dollar ($)</option>
								<option value="CHF">Swiss Franc	</option>
								<option value="HKD">Hong Kong Dollar ($)</option>
								<option value="SGD">Singapore Dollar ($)</option>
								<option value="SEK">Swedish Krona</option>
								<option value="DKK">Danish Krone</option>
								<option value="PLN">Polish Zloty</option>
								<option value="NOK">Norwegian Krone	</option>
								<option value="HUF">Hungarian Forint</option>
								<option value="CZK">Czech Koruna</option>
								<option value="ILS">Israeli New Shekel</option>
								<option value="MXN">Mexican Peso</option>
								<option value="BRL">Brazilian Reals</option>
								<option value="MYR">Malaysian Ringgit</option>
								<option value="PHP">Philippine Peso	</option>
								<option value="TWD">New Taiwan Dollar</option>
								<option value="THB">Thai Baht</option>
								<option value="TRY">Turkish Lire</option>
								<option value="RUB">Russian roubles	</option>
					      </select>
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="form-title" class="col-sm-2 control-label">Return URL</label> <a href="#TB_inline?Width=300&height=550&inlineId=pages-dropdown" class="button button-small button-secondary alignright pagesdropwodn">Select an existed page</a>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="form-return" name="form-return" >
					    </div>
					  </div>					  
					  <div class="form-group">
					    <label for="form-custom-message" class="col-sm-2 control-label">Custom message</label>
					    <div class="col-sm-10">
					      <textarea required="required" name="form-custom-message" id="form-custom-message" cols="54" rows="8"></textarea>
					      <small>shows'up during the payment process</small>
					    </div>
					  </div>
					  <div class="form-group">
					    <div class="col-sm-offset-2 col-sm-10">
					      <button type="submit" class="button button-primary button-large alignright">Save</button>
					    </div>
					  </div>
					  <input type="hidden" name="action" value="wpd_addform">
					</form>
				</div>
				<div class="alignright wpd-listforms">
					<div id="icon-edit-pages" class="icon32"><br></div><h2>Manage existed forms</h2>
					<div id="items-list">
							<?php 
								$savedForms = get_option('wpd-saved-forms');

								//var_dump($savedForms);
								if( is_array($savedForms) ) :
									foreach( $savedForms as $formkey => $form ) :
							?>
					        <a href="<?php echo '#TB_inline?width=300&amp;height=550&amp;inlineId=wpdform-settings-' . intval($formkey) ?>" class="list-group-item thickbox" data-id="<?php echo intval( $formkey ) ?>">
					          <h4 class="list-group-item-heading"><?php echo $form['title'] ?></h4>
					          <span class="list-group-item-text"><?php echo $form['custom_message'] ?></span>
					        </a>
							<div id="wpdform-settings-<?php echo intval($formkey); ?>" style="display:none;">
								<form method="post" class="form-horizontal wpd-edit-form" action="<?php echo admin_url( 'options-general.php?page=wpd-settings' ); ?>" role="form">
								  <div class="form-group">
								    <label for="form-title-<?php echo intval( $formkey ) ?>" class="col-sm-2 control-label">Title</label>
								    <div class="col-sm-10">
								      <input type="text" required="required" class="form-control" id="form-title-<?php echo intval( $formkey ) ?>" name="form-title" value="<?php echo $form['title'] ?>">
								    </div>
								  </div>
								  <div class="form-group">
								    <label for="form-business-<?php echo intval( $formkey ) ?>" class="col-sm-2 control-label">Benificier ( Paypal account ID / EMAIL )</label>
								    <div class="col-sm-10">
								      <input type="text" required="required" class="form-control" id="form-business-<?php echo intval( $formkey ) ?>" name="form-business" value="<?php echo $form['paypal_id'] ?>">
								      <small>For security reasons, you should always use IDs instead of emails!</small>
								    </div>
								  </div>
								  <div class="form-group">
								    <label for="form-amount-<?php echo intval( $formkey ) ?>" class="col-sm-2 control-label">Form's default donation amount</label>
								    <div class="col-sm-10">
								      <input type="text" required="required" class="form-control" id="form-amount-<?php echo intval( $formkey ) ?>" name="form-amount" value="<?php echo $form['default_amount'] ?>">
								    </div>
								  </div>
								  <div class="form-group">
								    <label for="form-currency-<?php echo intval( $formkey ) ?>" class="col-sm-2 control-label">Currency</label>
								    <div class="col-sm-10">
								    	<?php 
								    		$currencies = array(
															'AUD' => 'Australian Dollar (A $)',
															'CAD' => 'Canadian Dollar (C $)',
															'EUR' => 'Euro (€)',
															'GBP' => 'British Pound (£)',
															'JPY' => 'Japanese Yen (¥)',
															'USD' => 'US Dollar ($)',
															'NZD' => 'New Zealand Dollar ($)',
															'CHF' => 'Swiss Franc	',
															'HKD' => 'Hong Kong Dollar ($)',
															'SGD' => 'Singapore Dollar ($)',
															'SEK' => 'Swedish Krona',
															'DKK' => 'Danish Krone',
															'PLN' => 'Polish Zloty',
															'NOK' => 'Norwegian Krone',
															'HUF' => 'Hungarian Forint',
															'CZK' => 'Czech Koruna',
															'ILS' => 'Israeli New Shekel',
															'MXN' => 'Mexican Peso',
															'BRL' => 'Brazilian Reals',
															'MYR' => 'Malaysian Ringgit',
															'PHP' => 'Philippine Peso	',
															'TWD' => 'New Taiwan Dollar',
															'THB' => 'Thai Baht',
															'TRY' => 'Turkish Lire',
															'RUB' => 'Russian roubles'
											);
								    	?>
								      <select name="form-currency" id="form-currency-<?php echo intval( $formkey ) ?>">
											<?php 
												foreach( $currencies as $currency => $currencyLabel ):
													printf( '<option %s value="%s">%s</option>', ( $form['currency'] == $currency )? 'selected="selected"' : '' ,$currency, $currencyLabel );
												endforeach;
											?>
								      </select>
								    </div>
								  </div>	
								  <div class="form-group">
								    <label for="form-return-<?php echo intval( $formkey ) ?>" class="col-sm-2 control-label">Return URL</label>
								    <div class="col-sm-10">
								      <input type="text" class="form-control" id="form-return-<?php echo intval( $formkey ) ?>" name="form-return" value="<?php echo $form['return'] ?>" >
								    </div>
								  </div>				  
								  <div class="form-group">
								    <label for="form-custom-message-<?php echo intval( $formkey ) ?>" class="col-sm-2 control-label">Custom message</label>
								    <div class="col-sm-10">
								      <textarea required="required" name="form-custom-message" id="form-custom-message-<?php echo intval( $formkey ) ?>" cols="54" rows="8"><?php echo stripslashes( $form['custom_message'] ) ?></textarea>
								      <small>shows'up during the payment process</small>
								    </div>
								  </div>
								  <div class="form-group">
								    <div class="col-sm-offset-2 col-sm-10 submitbox">
								    	<button type="submit" class="button button-primary button-large alignright">Save</button>
								    	<a href="<?php echo admin_url( 'options-general.php?page=wpd-settings&del=' . intval( $formkey ) ); ?>" class="button button-primary button-large alignright remove-form">Remove</a>
								    </div>
								  </div>
								  <input type="hidden" name="formid" value="<?php echo intval( $formkey ) ?>">
								  <input type="hidden" name="action" value="wpd_edit">
								</form>
							</div>
					        <?php
					        		endforeach;
					        	endif;
					        ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>