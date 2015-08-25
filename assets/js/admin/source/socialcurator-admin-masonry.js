var SocialCurator = SocialCurator || {};

/**
* Masonry Functionality
*/
SocialCurator.Masonry = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.formatter = new SocialCurator.Formatter;

	plugin.triggerMasonry = function(posts, append)
	{	
		$(SocialCurator.selectors.masonryContainer).masonry({
			itemSelector: SocialCurator.selectors.masonryItem,
			percentPosition: true ,
			gutter: '.gutter-sizer'
		});
		$(SocialCurator.selectors.masonryContainer).imagesLoaded(function(){
			$(SocialCurator.selectors.masonryContainer).masonry();
		});
		if ( posts ){
			if ( append ) return plugin.appendMasonry(posts);
			plugin.prependMasonry(posts);
		}
		$('button').blur();
	}

	plugin.appendMasonry = function(posts)
	{
		setTimeout(function() {
			$(SocialCurator.selectors.masonryContainer).append(posts).masonry('reload');
			$('button').blur();
		}, 500);
	}

	plugin.prependMasonry = function(posts)
	{
		setTimeout(function() {
			$(SocialCurator.selectors.masonryContainer).prepend(posts).masonry('reload');
			$('button').blur();
		}, 500);
	}

	plugin.appendPosts = function(posts, append)
	{
		var append = ( append ) ? true : false;
		var newposts = [];
		for ( var i = 0; i < posts.length; i++ ){
			newposts[i] = plugin.buildSinglePost(posts[i]);
		}
		plugin.triggerMasonry(newposts, append);
		SocialCurator.resetLoading();
	}

	// Build the HTML for a single Post
	plugin.buildSinglePost = function(post)
	{
		var newpost = $('[data-post-template]').find(SocialCurator.selectors.masonryItem).clone();

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

		if ( post.status === 'publish' ){
			plugin.formatter.displayApproval(post, $(newpost));
		}

		if ( post.status === 'trash' ){
			plugin.formatter.displayTrashedButtons(post, $(newpost));
		}
		
		return newpost[0];
	}

	// Remove a grid item by post id
	plugin.removeGridItem = function(id)
	{
		var postcontainer = $('[data-post-container-id=' + id + ']');
		$('.social-curator-post-grid').masonry('remove', postcontainer);
		plugin.triggerMasonry();
	}

}