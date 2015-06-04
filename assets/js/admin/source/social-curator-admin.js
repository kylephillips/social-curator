jQuery(function($){


/**
* ---------------------------------------------------------------
* Run the Import Manually
* ---------------------------------------------------------------
*/

$(document).on('click', '[data-social-curator-manual-import]', function(e){
	e.preventDefault();
	//doManualImport();
	var temp_posts = [1379, 1380, 1384, 1386, 1402, 1406];
	getNewPosts(temp_posts);
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
			console.log(data);
			updateUnmoderatedCount(data.unmoderated_count);
			appendPosts(data.posts);
		}
	});
}

/**
* Append the new posts to the grid
*/
function appendPosts(posts)
{
	console.log(posts);
	loadingIndicator(false);
	$('[data-social-curator-manual-import]').attr('disabled', false).text(social_curator_admin.run_import);
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