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
        "guid"             => "6a104bd0265d5aa526ceca2d75adb2f98e370cb2",
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


function wordpress_theme_global_start($page) {
	global $mybb;

	if ($mybb->settings['wordpress_theme_enable'] != 1) {
		return $page;
	}

	if ($mybb->settings['wordpress_theme_url'] == null) {
                return $page;
        }

	$url=$mybb->settings['wordpress_theme_url'];
	$buffer = file_get_contents($url);

	if($buffer == null) {
		return $page;
	}


	// Wordpress html document
        $wp_dom = new DOMDocument();
        $wp_dom->loadHTML($buffer);

	// set base to "_parent" in MyBB generated page
	$page_mod = str_replace('<head>','<head>'."\r\n".'<base target="_parent" />'."\r\n", $page);

	// MyBB html document
	$mybb_dom = new DOMDocument();
        $mybb_dom->loadHTML($page_mod);

	// save MyBB generated page to local cache file
	$mybb_dom->saveHTMLFile('cache.html');

	$iframe = '<iframe id="mybb_iframe" width="100%" height="1000px" src="cache.html" scrolling="no" seamless="seamless">'."\r\n";

	// inject MyBB frame into wordpress page
	$output = str_replace('[MYBB-GOES-HERE]', $iframe, $wp_dom->saveHTML());

	return $output;
}
