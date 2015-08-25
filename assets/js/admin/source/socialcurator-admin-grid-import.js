var SocialCurator = SocialCurator || {};
/**
* Run an Import
*/
SocialCurator.Import = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.masonry = new SocialCurator.Masonry;

	plugin.importingSite = ''; // Current Site Importing (Text)
	plugin.importingButton = ''; // Current Active Button

	plugin.selectors = {
		importAllButton : '[data-social-curator-import-all]',
		importSingleButton : '[data-social-curator-single-import]',
		importSingleSiteButton : '[data-import-site]', 
		lastImportDate : '[data-social-curator-last-import]',
		importError : '[data-social-curator-import-error]',
		unmoderatedCount : '[data-social-curator-unmoderated-count]'
	}

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.selectors.importAllButton, function(e){
			e.preventDefault();
			plugin.doImport();
		});
		$(document).on('click', plugin.selectors.importSingleButton, function(e){
			e.preventDefault();
			var site = $('[data-social-curator-single-import-site]').val();
			plugin.doImport(site, true);
		});
		$(document).on('click', plugin.selectors.importSingleSiteButton, function(e){
			e.preventDefault();
			plugin.importingSite = $(this).text();
			plugin.importingButton = $(this);
			var site = $(this).attr('data-import-site');
			$(this).text(SocialCurator.localizedText.importing);
			plugin.doImport(site);
		});
	}

	plugin.doImport = function(site, single)
	{
		$(plugin.selectors.importError).parents('.social-curator-alert-error').hide();
		SocialCurator.toggleLoading(true);
		$('[data-import-site], [data-social-curator-single-import]').attr('disabled', 'disabled');
		$('[data-social-curator-import-all], [data-social-curator-single-import]').attr('disabled', 'disabled').text(social_curator_admin.importing);
		
		if ( !site ) var site = 'all';

		var formdata = {
			nonce : SocialCurator.jsData.nonce,
			action: SocialCurator.formActions.doImport,
			site: site
		}

		if ( single ){
			formdata.action = SocialCurator.formActions.singleImport;
			formdata.id = $('[data-social-curator-single-import-id]').val();
		}
		
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: formdata,
			success: function(data){
				console.log(data);
				plugin.resetImportButtons();
				if ( data.status === 'success' ){
					if ( data.import_count > 30 ) document.location.reload(true);
					SocialCurator.toggleLoading(false);
					SocialCurator.dropdowns.closeAll();
					$(plugin.selectors.lastImportDate).text(data.import_date);
					plugin.updateLastImportCount(data);
					plugin.getNewPosts(data.posts);
				} else {
					plugin.displayImportError(data.message);
				}
			}
		});
	}

	// Get the new WP posts after an import
	plugin.getNewPosts = function(posts)
	{
		if ( posts.length === 0 ){
			SocialCurator.toggleLoading(false);
			return;
		}
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : SocialCurator.jsData.nonce,
				action: SocialCurator.formActions.getposts,
				posts: posts,
				status: ['pending'],
				adminview: true
			},
			success: function(data){
				plugin.updateUnmoderatedCount(data.unmoderated_count);
				plugin.masonry.appendPosts(data.posts);
			}
		});
	}

	plugin.updateLastImportCount = function(data)
	{
		$('[data-social-curator-import-count]').text(data.import_count);
		$('[data-social-curator-import-site]').text(data.site);
		$('[data-social-curator-import-count]').parents('.social-curator-alert').show();
	}

	plugin.updateUnmoderatedCount = function(count)
	{
		$(plugin.selectors.unmoderatedCount).text(count);
	}

	plugin.displayImportError = function(message)
	{
		$(plugin.selectors.importError).text(message).parents('.social-curator-alert-error').show();
		SocialCurator.resetLoading();
	}

	plugin.resetImportButtons = function()
	{
		if ( plugin.importingSite !== "" ){
			plugin.importingButton.text(plugin.importingSite);
			plugin.importingSite = '';
			plugin.importingButton = '';
		}
		$(plugin.selectors.importSingleSiteButton + ',' + plugin.selectors.importSingleButton + ',' + plugin.selectors.importAllButton).attr('disabled', false);
	}

	return plugin.init();
}