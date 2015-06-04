jQuery(function($){


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

$(document).on('click', '[data-social-curator-manual-import]', function(e){
	e.preventDefault();
	doManualImport();
});


/**
* Run the Manual Import
*/
function doManualImport()
{
	loadingIndicator(true);
	$('[data-social-curator-manual-import]').attr('disabled', 'disabled').text(social_curator_admin.importing);
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			nonce : social_curator_admin.social_curator_nonce,
			action: 'social_curator_manual_import'
		},
		success: function(data){
			$('[data-social-curator-last-import]').text(data.import_date);
			updateLastImportCount(data.import_count);
			getNewPosts(data.posts);
		}
	});
}

/**
* Update Last Import Count
*/
function updateLastImportCount(count)
{
	$('[data-social-curator-import-count]').find('span').text(count);
	$('[data-social-curator-import-count]').show();
}

/**
* Update Unmoderated Count
*/
function updateUnmoderatedCount(count)
{
	$('[data-social-curator-unmoderated-count]').text(count);
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
			status: ['pending', 'publish']
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
	$('[data-social-curator-manual-import]').attr('disabled', false).text(social_curator_admin.run_import);
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
* Approve a Post
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-approve-post]', function(e){
	e.preventDefault();
	approvePost($(this).attr('data-post-id'));
});
function approvePost(id)
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
	restorePost(id);
});
function restorePost(id)
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
* Close an Alert
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-dismiss="alert"]', function(e){
	e.preventDefault();
	$(this).parents('.social-curator-alert').fadeOut('fast');
});


}); // jQuery