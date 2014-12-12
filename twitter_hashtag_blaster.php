<?php

/*
   Plugin Name: Twitter Hashtag Blaster
   Plugin URI: http://www.article-optimize.com/blog/hashtag-blaster-wordpress-plugin/
   Description: Your Readers can blast your posts directly into traffic-driving Twitter conversations with just one click! This simple but powerful plugin makes use of the official Twitter Hashtag button code in addition to shortlinks to get your content out in front of new readers - expanding your audience for you. It also intelligently saves shortlinks to your posts, for maximum performance so it won't slow down your site.
   Version: 1.0
   Author: Zack Proser
   Author URI: http://www.article-optimize.com/blog/twitter-hashtag-blaster-pro-coming-soon/
   License: GPL2
   */

if(!class_exists('Twitter_Hashtag_Blaster'))
{
	class Twitter_Hashtag_Blaster
	{
		/**
		 * Define Constants
		 */

		const BITLY_LOGIN = 'YOUR-BITLY-ACCOUNTNAME-HERE';
		const BITLY_KEY = 'YOUR-BITLY-API-KEY HERE';

		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			//Initialize settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$Twitter_Hashtag_Blaster_Settings = new Twitter_Hashtag_Blaster_Settings();

			//Register hooks and actions
			add_filter('the_content', array(&$this, 'twitter_hashtag_blaster_render_button'));


		} // END public function __construct()

		public static function activate()
		{
			//Nadda
		}

		public static function deactivate()
		{
			//Nadda
		}

		/**
		 * Render Hashtag Button 
		 */

		public function twitter_hashtag_blaster_render_button($content) 
		{
			global $post; //get post object for later queries

			if(get_option('twitter_hashtag_blaster_hashtag') == FALSE )
			{ 
				return $content;

			}
			else
			{
				$hashtag = get_option('twitter_hashtag_blaster_hashtag');
				$size_setting = get_option('twitter_hashtag_blaster_button_size'); 

				if($size_setting['size'] == 1)
				{
					$size_snippet = '';
				}
				else 
				{
					$size_snippet = 'data-size="large"';
				}

				//Check if post already has associated bitly link	

				$post_meta = get_post_meta($post->ID, 'twitter_hashtag_blaster_bitly', true);


				if( empty( $post_meta ) ) // if not
				{
					//Attempt to shorten the link with bitly

					$shortlink = $this->twitter_hashtag_blaster_bitly_shorten(get_permalink($post->ID));

					//$shortlink = $this->twitter_hashtag_blaster_bitly_shorten('http://www.nytimes.com');

					if($shortlink != null)
					{
						$url_snippet = ' data-url="' . $shortlink . '"';

						add_post_meta($post->ID, 'twitter_hashtag_blaster_bitly', $shortlink, true); // Store bitly link in post meta

					} // END if($shortlink != null)
				} // END if( empty( $post_meta ) )
				else // post already has associated bitly link 
				{
					$url_snippet = ' data-url="' . $post_meta . '"';
				}
				
				$script = '<a href="https://twitter.com/intent/tweet?button_hashtag=' . $hashtag . '" class="twitter-hashtag-button" ' . $size_snippet . ' ' . $url_snippet . ' >Tweet #' .  $hashtag . '</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>';					

				return $content . $script; 
				
		} // END if(get_option('twitter_hashtag_blaster_hashtag')
		} // END public function twitter_hashtag_blaster_render_button()

		public function twitter_hashtag_blaster_bitly_shorten($url)
		{

			$apiURL = 'http://api.bit.ly/v3/shorten?';

			$bitly_login = self::BITLY_LOGIN; 
			$bitly_apikey = self::BITLY_KEY;

			$bitly_call = $apiURL . 'login=' . $bitly_login . '&apiKey=' . $bitly_apikey . '&uri=' . urlencode($url);

			return $this->twitter_hashtag_blaster_curl_get_result($bitly_call);
		} // END public function twitter_hashtag_blaster_render_button()

		/**
		 * Sets curl options and makes calls
		 */

		public function twitter_hashtag_blaster_curl_get_result($bitly_call)
		{
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL, $bitly_call);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$bitly_data = curl_exec($ch);
			curl_close($ch);

			$obj = json_decode($bitly_data);

			if ($obj->{'data'}->{'url'})
			{
				return $obj->{'data'}->{'url'};
			}
			else 
			{
				return null;
			}	
			
		} // END public function twitter_hashtag_blaster_curl_get_result()

	} // END class Twitter_Hashtag_Blaster

} // END if(!class_exists('Twitter_Hashtag_Blaster'))


if(class_exists('Twitter_Hashtag_Blaster'))
{
	//Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('Twitter_Hashtag_Blaster', 'activate'));
	register_deactivation_hook(__FILE__, array('Twitter_Hashtag_Blaster', 'deactivate')); 

	//Instantiate the plugin class
	$Twitter_Hashtag_Blaster = new Twitter_Hashtag_Blaster(); 

	if(isset($Twitter_Hashtag_Blaster))
	{
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=twitter_hashtag_blaster">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

		$plugin = plugin_basename(__FILE__);
		add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
		
	}
}




?>