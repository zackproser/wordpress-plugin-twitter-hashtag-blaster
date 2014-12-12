<?php

//if uninstall is not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

$option_names = array(
	'twitter_hashtag_blaster_hashtag',
	'twitter_hashtag_blaster_button_size'
);

// For single site
if ( !is_multisite() )
{
	foreach($option_names as $option) 
	{
		delete_option($option);
	}
}
//For multisite
else
{
	//For regular options
	global $wpdb;
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs");
	$original_blog_id = get_current_blog_id();
	foreach ( $option_names as $option )
	{
		foreach ( $blog_ids as $blog_id )
		{
			switch_to_blog( $blog_id );
			delete_option( $option );
		}
		switch_to_blog( $original_blog_id );

		//For site options
		delete_site_option( $option_name );
	}
	
} // END else

?>