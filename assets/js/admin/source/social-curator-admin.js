jQuery(function($){

// Initialize Plugins
var dropdowns = new SocialCuratorDropdown;
var modals = new SocialCuratorModal;

var importingsite = ''; // For holding text of currently importing site
var importingbutton = ''; // For holding currently active site import button

var perpage = 10;
var offset = perpage;

var masonryContainer = $('.social-curator-post-grid');


/**
* --------------------------------------------------------------
* Masonry Grid
* ---------------------------------------------------------------
*/
$(document).ready(function(){
	triggerMasonry();
});

// Load Masonry
function triggerMasonry(posts, append)
{	
	$(masonryContainer).masonry({
		itemSelector: '.social-curator-post-grid-single',
		percentPosition: true ,
		gutter: '.gutter-sizer'
	});
	$(masonryContainer).imagesLoaded(function(){
		$(masonryContainer).masonry();
	});
	if ( posts ){
		if ( append ) return appendMasonry(posts);
		prependMasonry(posts);
	}
}

function appendMasonry(posts)
{
	setTimeout(function() {
    	$(masonryContainer).append(posts).masonry('reload');
    }, 500);
}
function prependMasonry(posts)
{
	setTimeout(function() {
    	$(masonryContainer).prepend(posts).masonry('reload');
    }, 500);
}




/**
* ---------------------------------------------------------------
* Run the Import Manually
* ---------------------------------------------------------------
*/

$(document).on('click', '[data-social-curator-import-all]', function(e){
	e.preventDefault();
	doImport();
});
$(document).on('click', '[data-import-site]', function(e){
	e.preventDefault();
	
	importingsite = $(this).text();
	importingbutton = $(this);

	var site = $(this).attr('data-import-site');
	$(this).text(social_curator_admin.importing);
	doImport(site);
});


/**
* Run the Manual Import
*/
function doImport(site)
{
	$('[data-social-curator-import-error]').parents('.social-curator-alert-error').hide();
	loadingIndicator(true);
	$('[data-import-site], [data-social-curator-single-import]').attr('disabled', 'disabled');
	$('[data-social-curator-import-all], [data-social-curator-single-import]').attr('disabled', 'disabled').text(social_curator_admin.importing);
	
	// Set site-specific vars
	if ( !site ) var site = 'all';
	
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_curator_manual_import',
			site: site
		},
		success: function(data){
			console.log(data);
			if ( data.status === 'success' ){
				$('[data-social-curator-last-import]').text(data.import_date);
				updateLastImportCount(data);
				if ( data.import_count > 30 ) document.location.reload(true);
				getNewPosts(data.posts);
			} else {
				displayImportError(data.message);
			}
		}
	});
}



/**
* ---------------------------------------------------------------
* Run a Single Import
* ---------------------------------------------------------------
*/

$(document).on('click', '[data-social-curator-single-import]', function(e){
	e.preventDefault();
	doSingleImport();
});

/**
* Run the Single Import
*/
function doSingleImport()
{
	$('[data-social-curator-import-error]').parents('.social-curator-alert-error').hide();
	loadingIndicator(true);
	$('[data-import-site], [data-social-curator-single-import]').attr('disabled', 'disabled');
	$('[data-social-curator-import-all], [data-social-curator-single-import]').attr('disabled', 'disabled').text(social_curator_admin.importing);
	
	// Set Vars
	var site = $('[data-social-curator-single-import-site]').val();
	var id = $('[data-social-curator-single-import-id]').val();
	
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_curator_single_import',
			site: site,
			id: id
		},
		success: function(data){
			if ( data.status === 'success' ){
				$('[data-social-curator-last-import]').text(data.import_date);
				updateLastImportCount(data);
				if ( data.import_count > 30 ) document.location.reload(true);
				getNewPosts(data.posts);
			} else {
				displayImportError(data.message);
			}
		}
	});
}


/**
* ---------------------------------------------------------------
* Update the Help modal target
* ---------------------------------------------------------------
*/
$(document).on('change', '[data-social-curator-single-import-site]', function(){
	var value = $(this).val();
	$('[data-id-help-modal]').attr('data-social-curator-modal-open', 'id-help-' + value);
});



/**
* Update Last Import Count
*/
function updateLastImportCount(data)
{
	$('[data-social-curator-import-count]').text(data.import_count);
	$('[data-social-curator-import-site]').text(data.site);
	$('[data-social-curator-import-count]').parents('.social-curator-alert').show();
}

/**
* Update Unmoderated Count
*/
function updateUnmoderatedCount(count)
{
	$('[data-social-curator-unmoderated-count]').text(count);
}

/**
* Display an import error
*/
function displayImportError(message)
{
	$('[data-social-curator-import-error]').text(message);
	$('[data-social-curator-import-error]').parents('.social-curator-alert-error').show();
	resetPostsLoading();
}


/**
* Toggle the loading indicator
* @param boolean state
*/
function loadingIndicator(visible)
{
	if ( visible ) {
		$('[data-curation-loader]').show();
	} else {
		$('[data-curation-loader]').hide();
	}
}



/**
* ---------------------------------------------------------------
* Append new posts after an import
* ---------------------------------------------------------------
*/

/**
* Make AJAX call for posts
* @param array of post ids
*/
function getNewPosts(posts)
{
	if ( posts.length === 0 ){
		resetPostsLoading();
		return;
	}
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_curator_get_posts',
			posts: posts,
			status: ['pending'],
			adminview: true
		},
		success: function(data){
			updateUnmoderatedCount(data.unmoderated_count);
			appendPosts(data.posts);
		}
	});
}

/**
* Append the new posts to the grid
* @param posts array
* @param append boolean
*/
function appendPosts(posts, append)
{
	var append = ( append ) ? true : false;
	var newposts = [];
	for ( var i = 0; i < posts.length; i++ ){
		newposts[i] = buildSinglePost(posts[i]);
	}
	triggerMasonry(newposts, append);
	resetPostsLoading();
}

/**
* Append a single post to the grid
* @param post object
* @param append boolean
*/
function buildSinglePost(post)
{

	var newpost = $('[data-post-template]').find('.social-curator-post-grid-single').clone();

	$(newpost).find('[data-icon-link]').html(post.icon_link);
	$(newpost).find('[data-profile-image]').attr('src', post.profile_image_link);
	$(newpost).find('[data-profile-link]').attr('href', post.profile_link);
	$(newpost).find('[data-date]').text(post.date);
	$(newpost).find('[data-profile-name]').text(post.profile_name);
	$(newpost).find('[data-post-id]').attr('data-post-id', post.id);
	$(newpost).attr('data-post-container-id', post.id);

	if ( post.thumbnail ){
		var thumbhtml = '<img src="' + post.thumbnail + '" />';
		$(newpost).find('[data-thumbnail]').html(thumbhtml);
	}

	if ( post.content ){
		var html = post.content;
		html += '<p><a href="' + post.edit_link + '">(' + social_curator_admin.edit + ')</a></p>';
		$(newpost).find('[data-post-content]').html(html);
	}

	if ( post.status === 'publish' ){
		displayApproval(post, $(newpost));
	}

	if ( post.status === 'trash' ){
		displayTrashedButtons(post, $(newpost));
	}
	
	// triggerMasonry(newpost);
	return newpost[0];
}

/**
* Reset the loading indication
*/
function resetPostsLoading()
{
	loadingIndicator(false);
	$('[data-social-curator-import-all], [data-social-curator-single-import]').attr('disabled', false).text(social_curator_admin.run_import);
	$('[data-import-site], [data-social-curator-single-import]').attr('disabled', false);
	$('[data-social-curator-single-import-id]').val('');

	dropdowns.closeAll();

	if ( importingsite !== "" ){
		importingbutton.text(importingsite);
		importingsite = '';
		importingbutton = '';
	}
}



/**
* ---------------------------------------------------------------
* Trash a Post
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-trash-post]', function(e){
	e.preventDefault();
	trashPost($(this).attr('data-post-id'));
	if ( !$(this).parents('.social-curator-post-grid-single').hasClass('approved') ){
		subtractUnmoderated();
	}
	$(this).parents('.social-curator-post-grid-single').fadeOut('fast', function(){
		$('.social-curator-post-grid').masonry('remove', $(this));
		triggerMasonry();
	});
});
function trashPost(id)
{
	loadingIndicator(true);
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_curator_trash_post',
			post_id: id
		},
		success: function(){
			$('[data-trash-count]').text(parseInt($('[data-trash-count]').text()) + 1);
			loadingIndicator(false);
		}
	});
}

/**
* Increment the unmoderated count -1
*/
function subtractUnmoderated()
{
	var count = parseInt($('[data-social-curator-unmoderated-count]').first().text());
	count = count - 1;
	$('[data-social-curator-unmoderated-count]').text(count);
}

/**
* Increment the unmoderated count +1
*/
function addUnmoderated()
{
	var count = parseInt($('[data-social-curator-unmoderated-count]').first().text());
	count = count + 1;
	$('[data-social-curator-unmoderated-count]').text(count);
}


/**
* Hide the approval buttons and display delete/restore buttons
*/
function displayTrashedButtons(post, container)
{
	var html = '<div class="social-curator-status-buttons">';
	html += '<a href="#" data-permanent-delete-post data-post-id="' + post.id + '" class="social-curator-trash"><i class="social-curator-icon-blocked"></i>' + social_curator_admin.permanently_delete + '</a>';
	html += '<a href="#" data-restore-post data-post-id="' + post.id + '" class="social-curator-approve"><i class="social-curator-icon-redo"></i>' + social_curator_admin.restore + '</a>';

	var postcontainer = ( container ) ? container : $('[data-post-container-id=' + post.id + ']');
	$(postcontainer).find('.social-curator-status-buttons').remove();
	$(postcontainer).append(html);
}



/**
* ---------------------------------------------------------------
* Approve a Post in the Grid View
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-approve-post]', function(e){
	e.preventDefault();
	approveGridPost($(this).attr('data-post-id'));
});
function approveGridPost(id)
{
	loadingIndicator(true);
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_curator_approve_post',
			post_id: id
		},
		success: function(data){
			displayApproval(data);
			subtractUnmoderated();
			triggerMasonry();
			loadingIndicator(false);
		}
	});
}
/**
* Hide the approval buttons and display the approval message
*/
function displayApproval(data, container)
{
	var html = '<div class="social-curator-alert-success">' + social_curator_admin.approved_by + ' ' + data.approved_by + ' ' + social_curator_admin.on + ' ' + data.approved_date;
	if ( social_curator_admin.can_delete_posts === '1' ){
		html += '<br><a href="#" data-trash-post data-post-id="' + data.id + '" class="unapprove-link">Unapprove and Trash</a>';
	}
	html += '</div>';
	var postcontainer = (container) ? container : $('[data-post-container-id=' + data.id + ']');
	$(postcontainer).find('.social-curator-status-buttons').remove();
	$(postcontainer).append(html);
	$(postcontainer).addClass('approved');
}




/**
* ---------------------------------------------------------------
* Restore a Post from the Trash
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-restore-post]', function(e){
	e.preventDefault();
	var id = $(this).attr('data-post-id');
	restoreGridPost(id);
});
function restoreGridPost(id)
{
	loadingIndicator(true);
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_curator_restore_post',
			post_id: id
		},
		success: function(data){
			addUnmoderated();
			removeGridItem(id);
			loadingIndicator(false);
			$('[data-trash-count]').text(parseInt($('[data-trash-count]').text()) - 1);
		}
	});
}

/**
* ---------------------------------------------------------------
* Delete a Post Permanently
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-permanent-delete-post]', function(e){
	e.preventDefault();
	var id = $(this).attr('data-post-id');
	deletePost(id);
});
function deletePost(id)
{
	loadingIndicator(true);
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_curator_delete_post',
			post_id: id
		},
		success: function(data){
			console.log(data);
			removeGridItem(id);
			loadingIndicator(false);
			$('[data-trash-count]').text(parseInt($('[data-trash-count]').text()) - 1);
		}
	});
}

/**
* Remove an item from the grid by ID
*/
function removeGridItem(id)
{
	var postcontainer = $('[data-post-container-id=' + id + ']');
	$('.social-curator-post-grid').masonry('remove', postcontainer);
	triggerMasonry();
}





/**
* ---------------------------------------------------------------
* Filter Posts
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-filter-grid]', function(e){
	e.preventDefault();
	filterPosts();
});
function filterPosts()
{
	loadingIndicator(true);
	$('[data-post-grid]').find('.social-curator-post-grid-single').remove();
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_curator_get_posts',
			status: $('[data-filter-status]').val(),
			site : $('[data-filter-site]').val(),
			offset : 0,
			number : perpage
		},
		success: function(data){
			$('[data-social-curator-load-more]').attr('disabled', false).text('Load More');
			appendPosts(data.posts);
		}
	});
}




/**
* ---------------------------------------------------------------
* Change the Moderation from the Manage Posts Screen (default post listing, not curation screen)
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-social-curator-moderate-select-button]', function(e){
	e.preventDefault();
	var post_id = $(this).attr('data-post-id');
	var status = $(this).siblings('[data-social-curator-moderate-select]').val();
	var button = $(this);
	$(this).attr('disabled', 'disabled').text(social_curator_admin.updating);
	changeStatus(post_id, status, button);
});
/**
* Change the post status
*/
function changeStatus(post_id, status, button)
{
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_update_post_status',
			post_id: post_id,
			status: status
		},
		success: function(data){
			$(button).attr('disabled', false).text(social_curator_admin.update);
			if ( status === 'trash' ){
				$(button).parents('tr').fadeOut('fast', function(){
					$(this).remove();
				});
			}
		}
	});
}



/**
* ---------------------------------------------------------------
* Empty the Trash
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-empty-social-trash]', function(e){
	e.preventDefault();
	if ( window.confirm('Are you sure you want to empty the trash?') ){
		$(this).attr('disabled', 'disabled');
		emptyTrash();
	}
});
function emptyTrash()
{
	loadingIndicator(true);
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_empty_trash'
		},
		success: function(data){
			loadingIndicator(false);
			$('[data-trash-count]').text('0');
			$('[data-empty-social-trash]').attr('disabled', false);
			
			if ( $('[data-filter-status]').val() === 'trash' ) {
				$('[data-post-grid]').find('.social-curator-post-grid-single').remove();
			}
		}
	});
}



/**
* ---------------------------------------------------------------
* Load More Posts
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-social-curator-load-more]', function(e){
	e.preventDefault();
	loadMorePosts();
});
function loadMorePosts()
{
	loadMoreIndicator(true);
	var status = ( $('[data-filter-status]').val() === 'all' ) ? null : $('[data-filter-status]').val();
	var site = ( $('[data-filter-site]').val() === 'all' ) ? null : $('[data-filter-site]').val();

	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_curator_get_posts',
			offset: offset,
			number: perpage,
			site: site,
			status: status
		},
		success: function(data){
			offset = offset + perpage;
			loadMoreIndicator(false);
			if ( data.posts.length === 0 ){
				$('[data-social-curator-load-more]').attr('disabled', 'disabled').text('No More Posts');
			}
			appendPosts(data.posts, true);
		}
	});
}


// Update the Load More Posts status
function loadMoreIndicator(status)
{
	if ( status ) return $('[data-social-curator-grid-loading]').show();
	return $('[data-social-curator-grid-loading]').hide()
}



}); // jQuery