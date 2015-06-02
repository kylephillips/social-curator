<?php
/*
// Twitter Feed
use SocialCurator\Entities\Site\Twitter\Feed\Feed as TwitterFeed;
$feed = new TwitterFeed;
var_dump($feed->getFeed());

// Instagram Feed
use SocialCurator\Entities\Site\Instagram\Feed\Feed as InstagramFeed;

$feed = new InstagramFeed;
var_dump($feed->getFeed());
*/
?>
<div class="wrap social-curator-curation-page">
	
	<h2>
		<?php _e('Social Curator', 'socialcurator'); ?>
		<div class="social-curator-curation-loading-indicator" data-curation-loader>
			<img src="<?php echo SocialCurator\Helpers::plugin_url(); ?>/assets/images/loading-admin.gif" />
		</div>
	</h2>
	
	<div class="social-curator-run-import">
		<p>
			<strong><?php _e('Last Import', 'socialcurator'); ?>:</strong> <?php echo $this->settings_repo->lastImport(); ?>
			<a href="#" data-social-curator-manual-import class="button button-primary"><?php _e('Run Import', 'socialcurator'); ?></a>
		</p>
	</div><!-- .social-curator-import-form -->

</div><!-- .wrap -->