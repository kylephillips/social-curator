jQuery(document).ready(function(){
	var bulkImport = new SocialCuratorBulkImport;
});


/**
* Bulk Import Page 
*/
var SocialCuratorBulkImport = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.post_ids = '[data-bulk-import-ids]';
	plugin.site = '[data-bulk-import-site]';
	plugin.button = $('[data-bulk-import]');
	plugin.statusAlert = $('[data-import-status]');
	plugin.errorAlert = $('[data-import-error]');
	plugin.successAlert = $('[data-import-success]');

	plugin.init = function(){
		plugin.bindEvents();
	}

	plugin.bindEvents = function(){
		$(plugin.button).on('click', function(e){
			e.preventDefault();
			plugin.startImport();
		});
	}

	plugin.startImport = function(){
		plugin.loading(true);
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : social_curator_admin.social_curator_nonce,
				action : 'social_curator_bulk_import',
				site : $(plugin.site).val(),
				post_ids : $(plugin.post_ids).val()
			},
			success: function(data){
				plugin.loading(false);
				if ( data.status === 'error' ){
					$(plugin.errorAlert).text(data.message).show();
					return;
				}
				plugin.showSuccess(data);
			}
		});
	}

	plugin.showSuccess = function(data){
		html = '<p><strong>' + data.import_count + ' new posts imported.</strong></p>';
		if ( data.errors.length > 0 ){
			html += '<p>There were ' + data.errors.length + ' errors during the import:</p><ul>';
			for ( var i = 0; i < data.errors.length; i++ ){
				html += '<li>' + data.errors[i] + '</li>';
			}
			html += '</ul>';
		}
		$(plugin.successAlert).html(html).show();
	}

	plugin.loading = function(loading){
		if ( loading ){
			$(plugin.statusAlert).show();
			$(plugin.errorAlert).hide();
			$(plugin.successAlert).hide();
			$(plugin.button).text(social_curator_admin.importing).attr('disabled', 'disabled');
			$('[data-bulk-import-loader]').show();
			return;
		}
		$(plugin.statusAlert).hide();
		$(plugin.button).text(social_curator_admin.run_import).attr('disabled', false);
		$('[data-bulk-import-loader').hide();
		return;
	}

	return plugin.init();
}