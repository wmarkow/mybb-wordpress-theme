<?php
//Disallow direct Initialization for extra security.
if(!defined("IN_MYBB"))
{
	die("You Cannot Access This File Directly. Please Make Sure IN_MYBB Is Defined.");
}

// Hooks
$plugins->add_hook('pre_output_page', 'wordpress_theme_global_start');
// Information
function wordpress_theme_info()
{
	return array(
        "name"  => "Wordpress Theme",
        "description"=> "Use your Wordpress theme on your MyBB forum.",
        "website"        => "https://github.com/wmarkow/mybb-wordpress-theme",
        "author"        => "Witold Markowski",
        "authorsite"    => "https://github.com/wmarkow/mybb-wordpress-theme",
        "version"        => "1.0",
        "guid"             => "",
	"codename"      => "wordpress_theme",
        "compatibility" => "18*"
        );
}

// Activate
function wordpress_theme_activate() {
	global $db;

	$wordpress_theme_group = array(
        'gid'    => 'NULL',
        'name'  => 'wordpress_theme',
        'title'      => 'Wordpress Theme',
        'description'    => 'Settings For Wordpress Theme',
        'disporder'    => "1",
        'isdefault'  => "0",
	);
	$db->insert_query('settinggroups', $wordpress_theme_group);
	$gid = $db->insert_id();

	$wordpress_theme_setting = array(
        'sid'            => 'NULL',
        'name'        => 'wordpress_theme_enable',
        'title'            => 'Do you want to enable Wordpress Theme?',
        'description'    => 'If you set this option to yes, this plugin will be active on your board.',
        'optionscode'    => 'yesno',
        'value'        => '1',
        'disporder'        => 1,
        'gid'            => intval($gid),
	);
	$db->insert_query('settings', $wordpress_theme_setting);

	$wordpress_theme_setting = array(
        'sid'            => 'NULL',
        'name'        => 'wordpress_theme_url',
        'title'            => 'Wordpress theme page URL?',
        'description'    => 'In Wordpress, create a page with content [MYBB-GOES-HERE]. Enter the URL of that page here.',
        'optionscode'    => 'text',
        'value'        => '',
        'disporder'        => 2,
        'gid'            => intval($gid),
	);
	$db->insert_query('settings', $wordpress_theme_setting);
	rebuild_settings();
}

// Deactivate
function wordpress_theme_deactivate()
{
	global $db;
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name LIKE 'wordpress_theme_%'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='wordpress_theme'");
	rebuild_settings();
}


function wordpress_theme_global_start($page)
{
	global $mybb;

	if ($mybb->settings['wordpress_theme_enable'] != 1) {
		return $page;
	}

	if ($mybb->settings['wordpress_theme_url'] == null) {
                return $page;
        }

	$url=$mybb->settings['wordpress_theme_url'];
	$wp_page = file_get_contents($url);

	if($wp_page == null) {
		return $page;
	}

	// add java script to Wordpress page
	$bburl=$GLOBALS['settings']['bburl'];
	$wp_page_mod = str_replace('<head>','<head>'."\r\n".'<script type="text/javascript" src="'.$bburl.'/inc/plugins/wordpress_theme/wordpress_theme.js" />'."\r\n", $wp_page);

	// Wordpress html document
        $wp_dom = new DOMDocument();
        $wp_dom->loadHTML($wp_page_mod);

	// set base to "_parent" in MyBB generated page
	$page_mod = str_replace('<head>','<head>'."\r\n".'<base href="'.$bburl.'/" target="_parent" />'."\r\n", $page);
	// deal with quick-login url (very nasty hack below)
	$page_mod = str_replace('$("#quick_login input[name=\'url\']").val($(location).attr(\'href\'))', '$("#quick_login input[name=\'url\']").val('.$bburl.')', $page_mod);

	// MyBB html document
	$mybb_dom = new DOMDocument();
        $mybb_dom->loadHTML($page_mod);
	$mybb_dom_xpath = new DOMXPath($mybb_dom);

	// deal with http-equiv="refresh" (move from MyBB page to Wordpress page)
        $mybb_refresh_nodes = $mybb_dom_xpath->query('/html/head/meta[@http-equiv="refresh"]');
        foreach($mybb_refresh_nodes as $mybb_refresh_node) {
                $mybb_refresh_node->parentNode->removeChild($mybb_refresh_node);

                $import = $wp_dom->importNode($mybb_refresh_node, TRUE);
                $wp_head_node = $wp_dom->getElementsByTagName('head')->item(0);
                $wp_head_node->appendChild($import);
        }

	// save MyBB generated page to local cache file
	$mybb_dom->saveHTMLFile('cache/cache.html');

	$iframe = '<iframe id="mybb_iframe" onload="iframeLoaded()" width="100%" height="1000px" src="cache/cache.html" scrolling="no" seamless="seamless">'."\r\n";

	// inject MyBB frame into wordpress page
	$output = str_replace('[MYBB-GOES-HERE]', $iframe, $wp_dom->saveHTML());

	return $output;
}
