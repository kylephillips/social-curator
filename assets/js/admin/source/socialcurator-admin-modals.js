var SocialCurator = SocialCurator || {};

/**
* Modal Windows
*/
SocialCurator.Modals = function()
{
	var plugin = this;
	var $ = jQuery;
	plugin.modal_id = "";

	// Initialize
	plugin.init = function()
	{
		this.bindEvents();
	}

	// Bind Events
	plugin.bindEvents = function()
	{
		$(document).on('click', '[data-social-curator-modal-open]', function(e){
			e.preventDefault();
			plugin.modal_id = $(this).attr('data-social-curator-modal-open');
			plugin.openModal();
		});

		$(document).on('click', '[data-social-curator-modal-close]', function(e){
			e.preventDefault();
			plugin.closeModals();
		});

		$(document).on('click', '[data-social-curator-modal]', function(e){
			if ( $(e.target).parents('.social-curator-modal-content').length === 0 ){
				plugin.closeModals();
			}
		});
		$(document).on('change', '[data-social-curator-single-import-site]', function(){
			var value = $(this).val();
			$('[data-id-help-modal]').attr('data-social-curator-modal-open', 'id-help-' + value);
		});
	}

	// Open the Modal
	plugin.openModal = function()
	{
		$('[data-social-curator-modal="' + plugin.modal_id + '"]').addClass('open');
	}

	// Close the Modal
	plugin.closeModals = function()
	{
		$('[data-social-curator-modal]').removeClass('open');
	}

	return plugin.init();
}