jQuery(function($){

	$(document).ready(function(){
		var modals = new SocialCuratorModal;
		modals.init();
	});


	var SocialCuratorModal = function()
	{
		var plugin = this;
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
	}

});