<?php 
/**
 * @package WP Paypal Donate
 * @version 1.0
 */
/*
Plugin Name: WP Paypal Donate
Plugin URI: http://www.diascodes.com/
Description: receive donations with your paypal account in a few steps!
Author: SAID ASSEMLAL
Tags: donate, donations, paypal, paypal form, wordpress donations
Version: 1.0
Author URI: http://www.diascodes.com
*/


if (!defined('WPD_VERSION'))
    define('WPD_VERSION', '1.0');

if (!defined('WPD_ASSETS_URL'))
    define('WPD_ASSETS_URL', plugins_url( '/', __FILE__ ) . 'assets/' );

if (!defined('WPD_VIEWS'))
    define('WPD_VIEWS', WP_PLUGIN_DIR . '/wp-paypal-donate/views/' );


if( !class_exists('wpd') ){

	class wpd{

		function __construct(){

			register_activation_hook( __FILE__, array( $this, 'wpdActivation' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'wpdAdminLibs' ) );
			add_action( 'admin_menu', array( $this, 'wpdSettingsPage' ) ); 

			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'wpdSettingsLink' ) );
		
			add_action( 'wp_ajax_wpdthickbox', array( $this, 'wpdThickboxList' ) );
			
            add_filter('mce_external_plugins', array( $this, 'wpdTmcePlugin' ));
            add_filter('mce_buttons',  array( $this, 'wpdTmceButton' ) , 0);

			add_shortcode('wpd', array( $this, 'wpdShortcode' ));
			
			add_action( 'widgets_init', array( $this, 'wpd_widget_register' ) );

			$stored_options = get_option( 'wpd-saved-forms' );
			if( !is_array( $stored_options ) ){

				$defaults  = array(
								array(
									'title' => 'Buy me a coffe :)',
									'paypal_id' => '6VLWFDFHTRLJQ',
									'default_amount' => 5,
									'currency' => 'USD',
									'custom_message' => 'You found '. get_bloginfo('name' ) .' helpful? Your donation is enough to inspire me to do more. Thanks a bunch!'
								)
							);

				update_option( 'wpd-saved-forms', $defaults );

			}
		}

		/**
		
			- Activation hook ( called once the plugin gets activated )
		
		**/

		function wpdActivation()
		{
			
			$wpdSavedForms = get_option( 'wpd-saved-forms' );

			if( !is_array( $wpdSavedForms ) ) :
				update_option( 'wpd-saved-forms', array() );
			endif;
			
		}
		
		function getForm( $ID, $returnTitle = false )
		{
			$savedForms = get_option('wpd-saved-forms');
			$optionPlain = ( $returnTitle == true )? stripslashes( $savedForms[$ID] ) : $savedForms[$ID];
			if( $returnTitle == true )
				echo $optionPlain;
			else
				return $optionPlain;
		}

		/**
		
			- WPD Shortcode ( [wpd form="form_id"] )
		
		**/

		function wpdShortcode( $atts ){
			$savedForms = get_option('wpd-saved-forms');
			$currForm = $atts['form']; // Attached form
			if( is_array( $savedForms[$currForm] ) ) :
				$currFormData = $savedForms[$currForm];

				$output = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';
				$output .= '<div class="paypal-donations">';
				$output .= '<input type="hidden" value="_donations" name="cmd"/>';
				$output .= '<input type="hidden" value="'. $currFormData['paypal_id'] .'" name="business"/>';
				$output .= '<input type="hidden" name="amount" value="'. $currFormData['default_amount'] .'">';
				$output .= '<input type="hidden" name="return" value="'. $currFormData['return'] .'">';
				$output .= '<input type="hidden" value="'. $currFormData['custom_message'] .'" name="item_name"/>';
				$output .= '<input type="hidden" value="'. $currFormData['currency'] .'" name="currency_code"/>';
				$output .= '<input type="image" alt="PayPal – The safer, easier way to pay online." name="submit" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"/><img width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" alt=""/>';
				$output .= '</div>';
				$output .= '</form>';

				return $output;

			else : 
				return 'Sorry, an error has occurred!';
			endif;
		}

		function wpdTmceButton($buttons){
            array_push($buttons, 'separator', 'wpd');
            return $buttons;
        }

        function wpdTmcePlugin($plugin_array){
            $jsAsset = WPD_ASSETS_URL . 'admin/js/wpd-tinymce.js';

            $plugin_array['wpd'] = $jsAsset;
            return $plugin_array;
        }

        function wpdThickboxList(){
        	$savedForms = get_option('wpd-saved-forms');
        	include_once( WPD_VIEWS . 'forms-selector.php' );
        	die();
        }

		/**
		
			- WPD Widget
		
		**/

		function wpd_widget($args, $widget_args = 1) {
			extract( $args, EXTR_SKIP );
			if ( is_numeric($widget_args) )
				$widget_args = array( 'number' => $widget_args );
			$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
			extract( $widget_args, EXTR_SKIP );

			$options = get_option('wpd_widget');
			if ( !isset($options[$number]) )
				return;

			$formid = $options[$number]['formid'];
			
			$title = ($options[$number]['title'] != "") ? $before_title.$options[$number]['title'].$after_title : "";  
			
			echo $before_widget;
			echo $title;
			echo do_shortcode( '[wpd form="0"]' );
			echo $after_widget;	
		}



		function wpd_widget_control($widget_args) 
		{
			global $wp_registered_widgets;
			static $updated = false;

			if ( is_numeric($widget_args) )
				$widget_args = array( 'number' => $widget_args );
				
			$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
			extract( $widget_args, EXTR_SKIP );

			$options = get_option('wpd_widget');
			if ( !is_array($options) )
				$options = array();

			if ( !$updated && !empty($_POST['sidebar']) ) {
				$sidebar = (string) $_POST['sidebar'];

				$sidebars_widgets = wp_get_sidebars_widgets();
				if ( isset($sidebars_widgets[$sidebar]) )
					$this_sidebar =& $sidebars_widgets[$sidebar];
				else
					$this_sidebar = array();

				foreach ( $this_sidebar as $_widget_id ) {
					if ( 'wpd_widget' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
						$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
						unset($options[$widget_number]);
					}
				}

				foreach ( (array) $_POST['widget-wpd'] as $widget_number => $widget_content ) {
					$title = strip_tags(stripslashes($widget_content['title']));
					$formid = stripslashes( $widget_content['formid'] );
										
					$options[$widget_number] = compact( 'title', 'formid', 'id');
				}

				update_option('wpd_widget', $options);
				$updated = true;
			}

			if ( -1 == $number ) {
				$title = 'Buy me a coffe';
				$formid = 0;
				$number = '%i%';
			} 
			else {
				$title = esc_attr($options[$number]['title']);
				$formid = $options[$number]['formid'];
			}

		?>
			<p>

				<label for="wpd-title-<?php echo $number; ?>">Widget's title:</label>
				<input class="widefat" id="wpd-title-<?php echo $number; ?>" name="widget-wpd[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" />
				
				<label for="wpd-formid-<?php echo $number; ?>">Pick a Form </label>

				<?php 
					$savedForms = get_option('wpd-saved-forms');
					if( is_array( $savedForms ) && count( $savedForms ) > 0 ) :
				?>
				<select class="widefat" id="wpd-formid-<?php echo $number; ?>" name="widget-wpd[<?php echo $number; ?>][formid]">
					<?php 
						foreach( $savedForms as $formkey => $form ):
							printf( '<option value="%s" %s>%s</option>', $formkey, ( $formkey == $formid )? 'selected="selected"' : '', $form['title'] );
						endforeach;
					?>
				</select>
				<?php
					else : 
						return 'Sorry, an error has occurred!';
					endif;
				?>
				<input type="hidden" id="wpd-submit-<?php echo $number; ?>" name="wpd-submit-<?php echo $number; ?>" value="1" />
			</p>
		<?php
		}

		function wpd_widget_register()
		{
			if ( !$options = get_option('wpd_widget') )
				$options = array();
			$widget_ops = array('classname' => 'wpd-widget', 'description' => __('Your generated donates forms.'));
			$control_ops = array('title' => 'Buy me a coffe', 'formid' => 0, 'id_base' => 'wpd');
			$name = __('WP Paypal Donate');
			$id = false;
			foreach ( array_keys($options) as $o ) {

				if (    !isset($options[$o]['title']) || !isset($options[$o]['formid']))
				{
		                        continue;		
				}                        

				$id = "wpd-$o";
				wp_register_sidebar_widget($id, $name, array( $this, 'wpd_widget' ), $widget_ops, array( 'number' => $o ));
				wp_register_widget_control($id, $name, array( $this, 'wpd_widget_control' ), $control_ops, array( 'number' => $o ));
			}
			
		    if ( !$id ) {
				wp_register_sidebar_widget( 'wpd-1', $name, array( $this, 'wpd_widget' ), $widget_ops, array( 'number' => -1 ) );
				wp_register_widget_control( 'wpd-1', $name, array( $this, 'wpd_widget_control' ), $control_ops, array( 'number' => -1 ) );
			}
		        
			
		}

		/**
		
			- WPD Settings
		
		**/

		function wpdSettingsPage(){

			add_submenu_page( 'options-general.php', 'WP Paypal Donate', 'WP Paypal Donate', 'manage_options', 'wpd-settings', array( $this, 'wpdSettings' ) ); 
		}

		function wpdSettings(){
			$savedForms = get_option('wpd-saved-forms');
			add_thickbox();
			if( isset( $_GET['del'] ) && isset($savedForms[$_GET['del']]) ){
				unset( $savedForms[$_GET['del']] );
				update_option( 'wpd-saved-forms', $savedForms );
				echo '<div class="updated">';
				show_message( '<strong>WP Paypal Donate Settings has been updated!</strong>' );
				echo '</div>';
				$savedForms = get_option('wpd-saved-forms');
			}
			if( isset($_POST['action']) ){
				//Got this from the server: {"title":"azeazeaz","form-business":"eazeaze","form-amount":"5azeaze","form-currency":"AUD","form-custom-message":"azeazeaze","action":"wpd_addform"}
				$title = $_POST['form-title'];
				$business = $_POST['form-business'];
				$amount = $_POST['form-amount'];
				$currency = $_POST['form-currency'];
				$return = $_POST['form-return'];
				$message = $_POST['form-custom-message'];

				
				$formData = 	array(
								'title' => stripslashes($title),
								'paypal_id' => $business,
								'default_amount' => $amount,
								'currency' => $currency,
								'return' => $return,
								'custom_message' => stripslashes($message)
							);

				switch ( $_POST['action'] ):

					case 'wpd_edit':
					
						$formID = $_POST['formid'];
						$savedForms[$formID] = $formData;

						if( update_option( 'wpd-saved-forms', $savedForms ) ){
							echo '<div class="updated">';
							show_message( '<strong>WP Paypal Donate Settings has been updated!</strong>' );
							echo '</div>';
						}

						break;

					case 'wpd_addform':
					
						if( array_push($savedForms, $formData) ){
							update_option( 'wpd-saved-forms', $savedForms );
							echo '<div class="updated">';
							show_message( '<strong>WP Paypal Donate Settings has been updated!</strong>' );
							echo '</div>';
						}

						break;

				endswitch;
				/*
				*/

			}
			include_once WPD_VIEWS . '/settings.php';
		}

		function wpdSettingsLink($links) { 
		  $settings_link = '<a class="button button-small button-primary" href="' .  admin_url( 'options-general.php?page=wpd-settings' ) . '">Settings</a>'; 
		  array_unshift($links, $settings_link); 
		  return $links; 
		}

		/**
		
			- WPD Admin enqueued stylesheets & scripts
		
		**/

		function wpdAdminLibs(){
			global $pagenow;
			wp_enqueue_style( 'WPPaypalDonateAdminCSS', WPD_ASSETS_URL . 'admin/css/wp-paypal-donate.css' );
			if( $pagenow == 'options-general.php' )
				wp_enqueue_script( 'WPPaypalDonateAdminJS', WPD_ASSETS_URL . 'admin/js/wp-paypal-donate.js' , array( 'jquery' ), '3.5.2', true );
		}

	}

}

global $wpdInit;
$wpdInit = new wpd;

?>