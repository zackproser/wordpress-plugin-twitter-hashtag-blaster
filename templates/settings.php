
<?php wp_enqueue_style('twitter_hashtag_blaster') ?>

<div class="wrap">
	<h2>Twitter Hashtag Blaster</h2>

	<div class="blaster-pro-ad">
		<h3>Need More Power?</h3>
		<a href="http://www.article-optimize.com/blog/twitter-hashtag-blaster-pro-coming-soon/" target="_blank"><img src="<?php echo plugins_url('twitter-hashtag-blaster/img/hashtag-blaster-pro-logo.png') ?>" /></a>
	
		<table>
			<tbody>
				<tr><td><img src="<?php echo plugin_dir_url(dirname(__FILE__)) . '/img/checkmark.png' ?>"/></td><td><p>Add Different Hashtags to Any Post, Widget or Page to Maximize Exposure</p></td></tr>
				<tr><td><img src="<?php echo plugin_dir_url(dirname(__FILE__)) . '/img/checkmark.png' ?>"/></td><td><p>Link Your Bitly Account to Gather Social Metrics on Your Most Popular Pages</p></td></tr>
				<tr><td><img src="<?php echo plugin_dir_url(dirname(__FILE__)) . '/img/checkmark.png' ?>"/></td><td><p>Let Your Readers Drive Social Traffic to Your Site While You Sleep</p></td></tr>
			</tbody>
		</table>	

	</div>
	

	<form method="post" action="options.php">
		<?php @settings_fields('twitter_hashtag_blaster_group'); ?>
		<?php @do_settings_fields('twitter_hashtag_blaster_group'); ?>

		<?php do_settings_sections('twitter_hashtag_blaster'); ?>

		<?php @submit_button(); ?>
	</form>
</div>		