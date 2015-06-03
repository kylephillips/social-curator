# Social Curator for WordPress

This plugin is under active development.

## Compatibility

**Social Curator requires PHP v5.3.2+ and WordPress v3.8+.**

### Adding Site Support
1. Copy an existing site directory/namespace from SocialCurator\Entities\Site
2. Namespace must include a Feed namespace, with Classes for Feed, FeedFormatter, FetchFeed, and an optional Parser class
3. The FeedFormatter class should return an array with post data, with specific keys as outlined below (The Importer uses these keys to assign to the appropriate post/meta fields)
4. Register any Site-specific logic in the Registration class under the new site
5. Add a configuration array for the site in SocialCurator\Config\SupportedSites
6. Add a settings tab in the Views namespace for the site

### Formatted Feed
The Feed Formatter should return an array with the following keys for importing:
```
array(
	'id' => string 'Site Specific ID for Post',
	'type' => string 'image|tweet|video|etc',
	'date' => string 'timestamp',
	'content' => string 'Post content',
	'user_id' => string 'User ID from site',
	'screen_name' => string 'Screen name from site',
	'profile_image' => string 'URL for profile image/avatar',
	'image' => string 'URL for primary image (will be imported as post thumbnail)',
	'video_url' => string 'URL for video if type is set to video',
	'link' => string 'Link to the post'
)
```