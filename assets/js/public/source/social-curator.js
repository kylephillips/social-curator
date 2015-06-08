$(document).ready(function(){
	var nonce = new SocialCuratorNonce;
	nonce.injectNonce();
});

// Inject a nonce into the page - for overriding cached nonces
var SocialCuratorNonce = function(){

	/**
	* Inject the nonce into the head as a global variable
	* @param callback function
	*/
	this.injectNonce = function(callback){
		$.ajax({
			url: social_curator.ajaxurl,
			type: 'POST',
			data: {
				action: 'social_curator_nonce'
			},
			success: function(data){
				var nonce = '<script> var social_curator_nonce = "' + data.nonce + '"; </script>';
				$('head').append(nonce);
				if ( callback ) callback();
			}
		});
	}

}