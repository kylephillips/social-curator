jQuery(document).ready(function(){
	var testfeed = new SocialCuratorTestFeed;
});

var SocialCuratorTestFeed = function()
{
	var plugin = this;
	var $ = jQuery;
	
	plugin.loadButton = '[data-test-feed]';
	plugin.feedContainer = '[data-test-feed-results]';
	plugin.site = '';
	plugin.feedType = 'search';
	plugin.loadingText = social_curator_admin.fetching_feed;
	plugin.buttonText = social_curator_admin.test_feed;
	plugin.errorDiv = '[data-test-feed-error]';

	// Initialize
	plugin.init = function(){
		plugin.bindEvents();
	}

	// Bind Events
	plugin.bindEvents = function(){
		$(document).on('click', plugin.loadButton, function(e){
			e.preventDefault();
			plugin.site = $(this).attr('data-site');
			plugin.getFeed();
		});
		$(document).on('change', '[data-feed-type]', function(){
			var selected = $('input[name=feed-type]:checked').val();
			if ( selected === 'single' ){
				$('[data-feed-id-container]').show();
				return;
			}
			$('[data-feed-id-container]').hide();
		});
	}

	// Get the feed
	plugin.getFeed = function(){
		plugin.loading(true);
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				nonce : social_curator_admin.social_curator_nonce,
				action: 'social_curator_test_feed',
				site: plugin.site,
				type: $('input[name=feed-type]:checked').val(),
				format: $('input[name=feed-format]:checked').val(),
				id: $('[data-feed-id]').val()
			},
			success: function(data){
				if ( data.status === 'success' ){
					plugin.populateFeed(data.feed);
				} else {
					$(plugin.errorDiv).text(data.message).show();
					$(plugin.loading(false));
					$(plugin.feedContainer).hide();
				}
			}
		});
	}

	// Populate the Feed Container
	plugin.populateFeed = function(feed){
		var out = print_r(feed);
		$(plugin.feedContainer).html('<pre>' + out + '</pre>');
		plugin.loading(false);
	}

	// Update loading indication
	plugin.loading = function(loading){
		$(plugin.feedContainer).show();
		if ( loading ){
			$(plugin.errorDiv).hide();
			$(plugin.feedContainer).empty().addClass('loading');
			$(plugin.loadButton).attr('disabled', 'disabled').text(plugin.loadingText);
			return;
		}
		$(plugin.feedContainer).removeClass('loading');
		$(plugin.loadButton).attr('disabled', false).text(plugin.buttonText);
	}

	return plugin.init();
}


var print_r = function (obj, t) {
 
	// define tab spacing
	var tab = t || '';
 
	// check if it's array
	var isArr = Object.prototype.toString.call(obj) === '[object Array]';
	
	// use {} for object, [] for array
	var str = isArr ? ('Array\n' + tab + '[\n') : ('Object\n' + tab + '{\n');
 
	// walk through it's properties
	for (var prop in obj) {
		if (obj.hasOwnProperty(prop)) {
			var val1 = obj[prop];
			var val2 = '';
			var type = Object.prototype.toString.call(val1);
			switch (type) {
				
				// recursive if object/array
				case '[object Array]':
				case '[object Object]':
					val2 = print_r(val1, (tab + '\t'));
					break;
					
				case '[object String]':
					val2 = '\'' + val1 + '\'';
					break;
					
				default:
					val2 = val1;
			}
			str += tab + '\t' + prop + ' => ' + val2 + ',\n';
		}
	}
	
	// remove extra comma for last property
	str = str.substring(0, str.length - 2) + '\n' + tab;
	
	return isArr ? (str + ']') : (str + '}');
};