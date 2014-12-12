<?php

if(!class_exists('Twitter_Hashtag_Blaster_Settings'))
{
	class Twitter_Hashtag_Blaster_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			add_action('admin_init', array(&$this, 'twitter_hashtag_blaster_admin_init')); 
			add_action('admin_menu', array(&$this, 'twitter_hashtag_blaster_add_menu'));

		} // END public function __construct()

		/**
		 * hook into WP's admin_init action hook
		 */

		public function twitter_hashtag_blaster_admin_init()
		{
			//register custom css
			wp_register_style('twitter_hashtag_blaster', plugins_url('styles/blaster.css', __FILE__));

			//register plugin settings
			register_setting('twitter_hashtag_blaster_group', 'twitter_hashtag_blaster_hashtag', array(&$this, 'twitter_hashtag_blaster_validation')); 
			register_setting('twitter_hashtag_blaster_group', 'twitter_hashtag_blaster_button_size');

			add_settings_section(
				'twitter_hashtag_blaster_section', 
				'Hashtag Settings', 	
				array(&$this, 'twitter_hashtag_blaster_settings_section'), 
				'twitter_hashtag_blaster'
			); // END add_settings_section

			add_settings_field(
				'twitter_hashtag_blaster_hashtag', 
				'Hashtag', 
				array(&$this, 'twitter_hashtag_blaster_settings_render_hash_input'), 
				'twitter_hashtag_blaster', 
				'twitter_hashtag_blaster_section'
			); // END add_settings_field

			add_settings_field(
				'twitter_hashtag_blaster_button_size', 
				'Hashtag Button Size', 
				array(&$this, 'twitter_hashtag_blaster_settings_render_button_size'),
				'twitter_hashtag_blaster',
				'twitter_hashtag_blaster_section'
			); // END add_settings_field
		}	

			/**
			 * Render the settings section
			 */

			public function twitter_hashtag_blaster_settings_section()
			{
				echo 'Set the Hashtag for the Twitter Hashtag Blaster Plugin';
			} // END public function twitter_hashtag_blaster_settings_section()

			/**
			 * Render hashtag input
			 */ 

			public function twitter_hashtag_blaster_settings_render_hash_input() 
			{
				$current_value = get_option('twitter_hashtag_blaster_hashtag'); 

				echo sprintf('<input type="text" name="twitter_hashtag_blaster_hashtag" id="twitter-hashtag-blaster-hashtag" value="%s" />', $current_value); 

			} // END public function twitter_hashtag_blaster_settings_field_input_text()

			/**
			 * Render hashtag button size selector
			 */
			public function twitter_hashtag_blaster_settings_render_button_size()
			{

				$options = get_option('twitter_hashtag_blaster_button_size'); 

				$html = '<input type="radio" id="radio_small" name="twitter_hashtag_blaster_button_size[size]" value="1" ' . checked(1, $options['size'], false) . '/>';
				$html .= '<label for="radio_small">Small</label>';

				$html .= '<br><br>'; // buttons should be on separate lines

				$html .= '<input type="radio" id="radio_large" name="twitter_hashtag_blaster_button_size[size]" value="2" ' . checked(2, $options['size'], false) . '/>';
				$html .= '<label for="radio_large">Large</label>';

				echo $html;

			} // END public function twitter_hashtag_blaster_settings_render_button_size()


			/**
			 * Add a settings menu
			 */

			public function twitter_hashtag_blaster_add_menu()
			{

				// Add a page to manage the plugin's settings
				add_options_page(
					'Twitter Hashtag Blaster Settings', 
					'Twitter Hashtag Blaster',
					'manage_options', //required user permissions
					'twitter_hashtag_blaster',
					array(&$this, 'twitter_hashtag_blaster_settings_page')
				);
			} // END public function twitter_hashtag_blaster_add_menu()

			/**
			 * Menu page callback
			 */

			public function twitter_hashtag_blaster_settings_page()
			{
				if(!current_user_can('manage_options'))
				{
					wp_die(__('You do not have sufficient permissions to access this page.'));
				}

				//Render the settings template
				include(sprintf("%s/templates/settings.php", dirname(__FILE__))); 

			} // END public function twitter_hashtag_blaster_settings_page()

			/**
			 * Hashtag Validation Callback
			 */
			public function twitter_hashtag_blaster_validation($input)
			{
				$hashtag_regex = '/([a-zA-Z0-9_-]{1,120}$)/';

				if(preg_match($hashtag_regex, $input, $matches)) 
				{
					return preg_replace('/\s+/', '', strip_tags( stripslashes($input) ) );
				}
				else
				{
					add_settings_error(
						'twitter_hashtag_blaster_hashtag', 
						'twitter_hashtag_blaster_bad_hashtag',
						'Oops, your hashtag should be between 1 and 120 characters to leave room for bitly links. Please try again'
					); // END add_settings_error
				}
			} // END public function twitter_hashtag_blaster_validation

		} // END class Twitter_Hashtag_Blaster
} // END if(!class_exists('Twitter_Hashtag_Blaster_Settings'))





?>