jQuery(function($){

var importingsite = ''; // For holding text of currently importing site
var importingbutton = ''; // For holding currently active site import button


/**
* ---------------------------------------------------------------
* Masonry Grid
* ---------------------------------------------------------------
*/
$(document).ready(function(){
	triggerMasonry(false);
});

// Load Masonry
function triggerMasonry(prepend)
{	
	var $masonry_container = $('.social-curator-post-grid').masonry({
		itemSelector: '.social-curator-post-grid-single',
		percentPosition: true ,
		gutter: '.gutter-sizer'
	});
	$masonry_container.imagesLoaded(function(){
		$masonry_container.masonry();
	});
	if ( prepend ){
		$masonry_container.prepend( prepend ).masonry( 'prepended', prepend );
	}
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
	$('[data-import-site]').attr('disabled', 'disabled');
	$('[data-social-curator-import-all]').attr('disabled', 'disabled').text(social_curator_admin.importing);
	
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
			status: ['pending']
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
*/
function appendPosts(posts)
{
	for ( var i = 0; i < posts.length; i++ ){
		appendSinglePost(posts[i]);
	}
	resetPostsLoading();
}

/**
* Append a single post to the grid
* @param post object
*/
function appendSinglePost(post)
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

	triggerMasonry(newpost);

	if ( post.status === 'publish' ){
		displayApproval(post);
	}

	if ( post.status === 'trash' ){
		displayTrashedButtons(post);
	}
	
	return;
}

/**
* Reset the loading indication
*/
function resetPostsLoading()
{
	loadingIndicator(false);
	$('[data-social-curator-import-all]').attr('disabled', false).text(social_curator_admin.run_import);
	$('[data-import-site]').attr('disabled', false);

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
	subtractUnmoderated();
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
			loadingIndicator(false);
		}
	});
}

/**
* Increment the unmoderated count -1
*/
function subtractUnmoderated()
{
	var count = parseInt($('[data-social-curator-unmoderated-count]').text());
	count = count - 1;
	$('[data-social-curator-unmoderated-count]').text(count);
}

/**
* Increment the unmoderated count +1
*/
function addUnmoderated()
{
	var count = parseInt($('[data-social-curator-unmoderated-count]').text());
	count = count + 1;
	$('[data-social-curator-unmoderated-count]').text(count);
}


/**
* Hide the approval buttons and display delete/restore buttons
*/
function displayTrashedButtons(post)
{
	var html = '<div class="social-curator-status-buttons">';
	html += '<a href="#" data-permanent-delete-post data-post-id="' + post.id + '" class="social-curator-trash"><i class="social-curator-icon-blocked"></i>' + social_curator_admin.permanently_delete + '</a>';
	html += '<a href="#" data-restore-post data-post-id="' + post.id + '" class="social-curator-approve"><i class="social-curator-icon-redo"></i>' + social_curator_admin.restore + '</a>';

	var postcontainer = $('[data-post-container-id=' + post.id + ']');
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
			addUnmoderated();
			triggerMasonry();
			loadingIndicator(false);
		}
	});
}
/**
* Hide the approval buttons and display the approval message
*/
function displayApproval(data)
{
	var html = '<div class="social-curator-alert-success">' + social_curator_admin.approved_by + ' ' + data.approved_by + ' ' + social_curator_admin.on + ' ' + data.approved_date + '</div>';
	var postcontainer = $('[data-post-container-id=' + data.id + ']');
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
			site : $('[data-filter-site]').val()
		},
		success: function(data){
			appendPosts(data.posts);
		}
	});
}




/**
* ---------------------------------------------------------------
* Change the Moderation from the Manage Posts Screen
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
			console.log(data);
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
* Close an Alert
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-dismiss="alert"]', function(e){
	e.preventDefault();
	$(this).parents('.social-curator-alert').fadeOut('fast');
});


/**
* ---------------------------------------------------------------
* Dropdowns
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-toggle="social-curator-dropdown"]', function(e){
	e.preventDefault();
	toggleDropdown($(this));
});
$(document).on('click', function(e){
	if ( $(e.target).parents('.social-curator-dropdown').length == 0 ){
		$('.social-curator-dropdown-content').hide();
		$('.social-curator-dropdown').removeClass('open');
	}
});
function toggleDropdown(button)
{
	var dropdown = $(button).siblings('.social-curator-dropdown-content');
	$(button).parents('.social-curator-dropdown').toggleClass('open');
	$(dropdown).toggle();
}



}); // jQuery