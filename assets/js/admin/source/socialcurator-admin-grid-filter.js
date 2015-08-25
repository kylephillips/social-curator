var SocialCurator = SocialCurator || {};

/**
* Filter the grid Based on post status and site
*/
SocialCurator.Filter = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.masonry = new SocialCurator.Masonry;

	plugin.selectors = {
		filterButton : '[data-filter-grid]'
	}

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.selectors.filterButton, function(e){
			e.preventDefault();
			plugin.filterPosts();
		});
	}

	plugin.filterPosts = function()
	{
		SocialCurator.toggleLoading(true);
		$('[data-post-grid]').find('.social-curator-post-grid-single').remove();
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : SocialCurator.jsData.nonce,
				action: SocialCurator.formActions.getposts,
				status: $('[data-filter-status]').val(),
				site : $('[data-filter-site]').val(),
				offset : 0,
				number : SocialCurator.jsData.perpage
			},
			success: function(data){
				$('[data-social-curator-load-more]').attr('disabled', false).text('Load More');
				plugin.masonry.appendPosts(data.posts);
			}
		});
	}

	return plugin.init();
}