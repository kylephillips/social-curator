var SocialCurator = SocialCurator || {};

SocialCurator.Formatter = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.selectors = {
		unmoderatedCount : '[data-social-curator-unmoderated-count]'
	}

	// Increment the unmoderated count -1
	plugin.subtractUnmoderated = function()
	{
		var count = parseInt($(plugin.selectors.unmoderatedCount).first().text());
		count = count - 1;
		$(plugin.selectors.unmoderatedCount).text(count);
	}

	// Increment the unmoderated count +1
	plugin.addUnmoderated = function()
	{
		var count = parseInt($('[data-social-curator-unmoderated-count]').first().text());
		count = count + 1;
		$('[data-social-curator-unmoderated-count]').text(count);
	}

	// Display approval message for a specific post
	plugin.displayApproval = function(data, container)
	{
		var html = '<div class="social-curator-alert-success">' + SocialCurator.localizedText.approvedBy + ' ' + data.approved_by + ' ' + SocialCurator.localizedText.on + ' ' + data.approved_date;
		if ( social_curator_admin.can_delete_posts === '1' ){
			html += '<br><a href="#" data-trash-post data-post-id="' + data.id + '" class="unapprove-link">Unapprove and Trash</a>';
		}
		html += '</div>';
		var postcontainer = (container) ? container : $('[data-post-container-id=' + data.id + ']');
		$(postcontainer).find('.social-curator-status-buttons').remove();
		$(postcontainer).append(html);
		$(postcontainer).addClass('approved');
	}

	// Display buttons for post in the trash
	plugin.displayTrashedButtons = function(post, container)
	{
		var html = '<div class="social-curator-status-buttons">';
		html += '<button data-permanent-delete-post data-post-id="' + post.id + '" class="social-curator-trash"><i class="social-curator-icon-blocked"></i>' + social_curator_admin.permanently_delete + '</button>';
		html += '<button data-restore-post data-post-id="' + post.id + '" class="social-curator-approve"><i class="social-curator-icon-redo"></i>' + social_curator_admin.restore + '</button>';

		var postcontainer = ( container ) ? container : $('[data-post-container-id=' + post.id + ']');
		$(postcontainer).find('.social-curator-status-buttons').remove();
		$(postcontainer).append(html);
	}

}