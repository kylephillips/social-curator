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
			appendPosts(data);
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
* Append New Posts
*/
function appendPosts(data){
	console.log(data);
	loadingIndicator(false);
	$('[data-social-curator-manual-import]').attr('disabled', false).text(social_curator_admin.run_import);
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
* Close an Alert
* ---------------------------------------------------------------
*/
$(document).on('click', '[data-dismiss="alert"]', function(e){
	e.preventDefault();
	$(this).parents('.social-curator-alert').fadeOut('fast');
});


}); // jQuery