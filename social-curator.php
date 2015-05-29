<?php
/*
Plugin Name: Social Curator
Plugin URI: https://github.com/kylephillips/social-curator
Description: Import social feeds as post, with curator functionality.
Version: 0.0.1
Author: Kyle Phillips
Author URI: https://github.com/kylephillips
Text Domain: socialcurator
Domain Path: /languages/
License: GPLv2 or later.
Copyright: Kyle Phillips
*/

/*  Copyright 2015 Kyle Phillips  (email : kylephillipsdesign@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
* Adding Support for an API/site:
* 1) Add method in Config\SupportedSites with necessary keys
* 2) Add directory/namespace for site logic under Entities\Site.
* 3) Copy over an existing Feed directory into the new site and edit the three classes as needed.
* 4) The formatted feed should return an array, whose keys match existing feeds. See Entities\Site\Twitter\Feed\FeedFormatter for example
*/
    
/**
* Check Wordpress and PHP versions before instantiating plugin
*/
register_activation_hook( __FILE__, 'socialcurator_check_versions' );

function socialcurator_check_versions( $wp = '3.9', $php = '5.3.2' )
{
    global $wp_version;
    if ( version_compare( PHP_VERSION, $php, '<' ) ) $flag = 'PHP';
    elseif ( version_compare( $wp_version, $wp, '<' ) ) $flag = 'WordPress';
    else return;
    $version = 'PHP' == $flag ? $php : $wp;
    
    if (function_exists('deactivate_plugins')){
        deactivate_plugins( basename( __FILE__ ) );
    }
    
    wp_die('<p>The <strong>Social Curator</strong> plugin requires'.$flag.'  version '.$version.' or greater.</p>','Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );
}

if( !class_exists('Bootstrap') ) :
    socialcurator_check_versions();
    require_once('vendor/autoload.php');
    require_once('app/SocialCurator.php');
    SocialCurator::init();
endif;