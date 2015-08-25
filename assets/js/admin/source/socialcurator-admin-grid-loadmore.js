var SocialCurator = SocialCurator || {};

/**
* Load More Posts into the Grid
*/
SocialCurator.LoadMore = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.masonry = new SocialCurator.Masonry;
	plugin.selectors = {
		loadMoreButton : '[data-social-curator-load-more]',
	}

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.selectors.loadMoreButton, function(e){
			e.preventDefault();
			plugin.loadPosts();
		});
	}

	plugin.loadPosts = function()
	{
		plugin.loading(true);
		var status = ( $('[data-filter-status]').val() === 'all' ) ? null : $('[data-filter-status]').val();
		var site = ( $('[data-filter-site]').val() === 'all' ) ? null : $('[data-filter-site]').val();

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : SocialCurator.jsData.nonce,
				action: SocialCurator.formActions.getposts,
				offset: SocialCurator.jsData.offset,
				number: SocialCurator.jsData.perpage,
				site: site,
				status: status
			},
			success: function(data){
				console.log(data);
				SocialCurator.jsData.offset = SocialCurator.jsData.offset + SocialCurator.jsData.perpage;
				plugin.loading(false);
				if ( data.posts.length === 0 ){
					$('[data-social-curator-load-more]').attr('disabled', 'disabled').text('No More Posts');
					return;
				}
				plugin.masonry.appendPosts(data.posts, true);
			}
		});
	}

	plugin.loading = function(loading)
	{
		if ( loading ) return $('[data-social-curator-grid-loading]').show();
		return $('[data-social-curator-grid-loading]').hide();
	}

	return plugin.init();
}