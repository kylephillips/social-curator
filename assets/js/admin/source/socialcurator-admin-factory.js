/**
* Primary Factory Class for Admin Functionality
*/
jQuery(document).ready(function(){
	var socialcurator = new SocialCurator.Factory;
});

var SocialCurator = SocialCurator || {};

SocialCurator.selectors = {
	loadingIndicator : '[data-curation-loader]',
	masonryContainer : '.social-curator-post-grid',
	masonryItem : '.social-curator-post-grid-single'
}

SocialCurator.jsData = {
	nonce : social_curator_admin.social_curator_nonce,
	offset : 10, // Current Post Offset
	perpage : 10, // Number to load per request
	importingsite : "", // Current Site Importing
}

SocialCurator.localizedText = {
	runImport : social_curator_admin.run_import,
	importing : social_curator_admin.importing,
	importText : social_curator_admin.import,
	importAll : social_curator_admin.import_all,
	approvedBy : social_curator_admin.approved_by,
	on : social_curator_admin.on,
	edit : social_curator_admin.edit,
	permanentlyDelete : social_curator_admin.permanently_delete,
	restore : social_curator_admin.restore,
	updating : social_curator_admin.updating,
	update : social_curator_admin.update,
	chooseImage : social_curator_admin.choose_image,
	unapproveTrash : social_curator_admin.unapprove_and_trash,
	fetchingFeed : social_curator_admin.fetching_feed,
	testFeed : social_curator_admin.test_feed
}

SocialCurator.formActions = {
	getposts : 'social_curator_get_posts',
	emptyTrash : 'social_empty_trash',
	doImport : 'social_curator_manual_import',
	singleImport : 'social_curator_single_import',
	trashPost : 'social_curator_trash_post',
	restorePost : 'social_curator_restore_post',
	deletePost : 'social_curator_delete_post',
	approvePost : 'social_curator_approve_post',
	updateStatus : 'social_update_post_status'
}

SocialCurator.dropdowns = new SocialCurator.Dropdowns;


SocialCurator.resetLoading = function()
{
	var $ = jQuery;
	$(SocialCurator.selectors.loadingIndicator).hide();
	SocialCurator.dropdowns.closeAll();
	$('[data-social-curator-import-all], [data-social-curator-single-import]').attr('disabled', false).text(SocialCurator.localizedText.runImport);
	$('[data-import-site], [data-social-curator-single-import]').attr('disabled', false);
	$('[data-social-curator-single-import-id]').val('');
}


SocialCurator.toggleLoading = function(loading)
{
	var $ = jQuery;
	if ( loading ){
		$('[data-curation-loader]').show();
		return;
	}
	$('[data-curation-loader]').hide();
}


SocialCurator.Factory = function()
{
	var plugin = this;
	plugin.logs = new SocialCurator.Logs;
	plugin.avatars = new SocialCurator.Avatar;
	plugin.feedTest = new SocialCurator.FeedTest;
	plugin.modals = new SocialCurator.Modals;
	plugin.bulkimport = new SocialCurator.BulkImport;
	plugin.alerts = new SocialCurator.Alerts;
	plugin.grid = new SocialCurator.Grid;
	plugin.emptyTrash = new SocialCurator.EmptyTrash;
	plugin.postColumns = new SocialCurator.PostColumns;
}