var SocialCurator = SocialCurator || {};

/**
* Primary Grid Functionality on Curator Screen
*/
SocialCurator.Grid = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.masonry = new SocialCurator.Masonry;
	plugin.loadMore = new SocialCurator.LoadMore;
	plugin.importer = new SocialCurator.Import;
	plugin.trashPost = new SocialCurator.TrashPost;
	plugin.filter = new SocialCurator.Filter;
	plugin.trash = new SocialCurator.Trash;
	plugin.approve = new SocialCurator.Approve;

	plugin.init = function()
	{
		plugin.bindEvents();
	}

	plugin.bindEvents = function()
	{
		$(document).ready(function(){
			plugin.masonry.triggerMasonry();
		});
	}

	return plugin.init();
}