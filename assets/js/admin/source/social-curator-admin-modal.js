jQuery(function($){

	$(document).ready(function(){
		var modals = new SocialCuratorModal;
		modals.init();
	});


	var SocialCuratorModal = function()
	{
		var plugin = this;
		plugin.modal_id = "";

		plugin.init = function()
		{
			console.log('initializing');
			this.bindEvents();
		}

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

		plugin.openModal = function()
		{
			$('[data-social-curator-modal="' + plugin.modal_id + '"]').addClass('open');
		}

		plugin.closeModals = function()
		{
			$('[data-social-curator-modal]').removeClass('open');
		}
	}

});