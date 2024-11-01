<?php
/**
 * @package wdc_settings
 * @version 0.1
 */
if (!class_exists('wdc_settings')) {
	class wdc_settings {
		function credits(){
			$credit = (get_option('wdc_credits') || get_option('wdc_credits') === false);
			if ( $credit ) {
				echo '<p>Powered by <a href="http://www.webdesigncompany.net">Web Design Company</a> <a href="http://www.webdesigncompany.net/wordpress/plugins/">Plugins</a></p>';
			}
		}
		
		function admin_menu() {
			add_menu_page('WDC Options', 'WDC Options', 'level_10', 'wdc-settings', array(&$this, 'settings'), wdc_settings::get_url() . 'wdc-icon.png'); 
			add_submenu_page('wdc-settings', 'General', 'General', 'level_10', 'wdc-settings', array(&$this, 'settings')); 
		}
		
		function get_url() {
			return WP_CONTENT_URL.'/plugins/'.basename(dirname(dirname(__FILE__))) . '/' . basename(dirname(__FILE__)) . '/';
		}
		
		function settings() {
			?>
				<div class="wrap">
				<h2>WDC Plugins - General Options</h2>
				
				<?php if ( isset($_GET['updated']) ) : ?>
					<div class='updated fade'><p>Settings saved.</p></div>
				<?php endif; ?>
				<form method="post" action="options.php">
					<input type="hidden" name="action" value="update" />
					<?php wp_nonce_field('update-options'); ?>
					<input type="hidden" name="page_options" value="wdc_credits" />
					<h3>We Need Your Help!</h3>
  				<p>
            You've decided to use our plugins. You are AWESOME!<br />
            It takes a lot of time &amp; resources to create and maintain<br />
            them so we're glad you're making good use of them.
  	      </p>
  	      <p>
  	         If you like our plugins, please show your support by <br />
  	         allowing us to add a link at the bottom of your website.<br />
  				</p>
  				
  				<h4>WDC Plugins that you're currently using:</h4>
  				<?php 
  					$plugins = apply_filters('wdc_plugins', array());
  					sort($plugins);
  					echo '<ul style="list-style: square;list-style-position:inside;"><li>';
  					echo implode('</li><li>', $plugins);
  					echo '</li></ul>';
  				?> 
  				
  				<h4>Would you like to support us?</h4>
					<label for="wdc_credits_yes"><input type="radio" id="wdc_credits_yes" name="wdc_credits" value="1" <?php if ( get_option('wdc_credits') || get_option('wdc_credits') === false ) echo 'checked="checked"' ?> /> Display a short credits line in my footer</label><br />
					<label for="wdc_credits_no"><input type="radio" id="wdc_credits_no" name="wdc_credits" value="0" <?php if ( !get_option('wdc_credits') && get_option('wdc_credits') !== false ) echo 'checked="checked"' ?> /> I'll show my support by writing a review, donating or contributing.</label>
					
					<p>
  				  PS: If the default credit message doesn't look right in your theme, <br />
  				  <a href="mailto:mr@webdesigncompany.net?subject=Customize Credits&body=Make the credits link look good.">email us</a> and we'll make it look great at no cost to you. <br />
  				</p>
  				
  				
					<p class="submit">
						<input type="submit" name="Submit" value="Save Changes" />
					</p>

				</form>
				
				</div>
			<?php
		}
	}
	
	$wdc_settings = new wdc_settings();
	add_action('wp_footer', array(&$wdc_settings, 'credits'));
	add_action('admin_menu', array(&$wdc_settings, 'admin_menu'));
}
