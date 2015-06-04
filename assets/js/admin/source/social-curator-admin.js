jQuery(function($){


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
	console.log(post);
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
			loadingIndicator(false)
		}
	});
}
function subtractUnmoderated()
{
	var count = $('[data-social-curator-unmoderated-count]').text();
	count = count - 1;
	$('[data-social-curator-unmoderated-count]').text(count);
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
			triggerMasonry();
			loadingIndicator(false)
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
* Close an Alert
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-dismiss="alert"]', function(e){
	e.preventDefault();
	$(this).parents('.social-curator-alert').fadeOut('fast');
});


}); // jQuery