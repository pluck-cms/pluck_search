<?php
/*
 * This file is part of pluck, the easy content management system
 * Copyright (c) pluck team
 * http://www.pluck-cms.org

 * Pluck is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * See docs/COPYING for the complete license.
*/

//Make sure the file isn't accessed directly.
	defined('IN_PLUCK') or exit('Access denied!');
	
/**
 * @brief Info Block - return module info
 */
	function search_info() {
		global $lang;
		return array(
			'name'          => $lang['search']['search'],
			'intro'         => $lang['search']['info'],
			'version'       => '0.2',
			'author'        => 'grwebguy',
			'website'       => "billcreswell.com",
			'icon'          => 'images/search_32.png',
			'compatibility' => '4.7'
		);
	}
	
/**
 * For Future use
 */
function search_settings_default() {
	return array(
		'search_pages'	=> true,
		'search_posts'	=> true,
		'search_albums'	=> false,
		'blogpage' => '',
		'newpage' => false,
	);
}

function search_pages_is_on() {
	return module_get_setting('search', 'search_pages') === 'true';
}

function search_posts_is_on() {
	return module_get_setting('search', 'search_posts') === 'true';
}

function search_albums_is_on() {
	return module_get_setting('search', 'search_albums') === 'true';
}

function search_blogpage_is() {
	return module_get_setting('search', 'blogpage') === '';
}

function search_newpage_is_on() {
	return module_get_setting('search', 'newpage') === 'true';
}


function search_admin_module_settings_beforepost() {
	global $lang;
	echo '<span class="kop2">'.$lang['search']['search'].'</span>
		<table>
			<tr>
				<td><input type="checkbox" name="search_pages" id="search_pages" value="true" ' . (search_pages_is_on() ? ' checked="checked"' : '') . '/></td>
				<td><label for="search_pages">&emsp; '.$lang['search']['enablepagescheckbox'].'</label></td>
			</tr>
				<tr>
					<td><input type="checkbox" name="search_posts" id="search_posts" value="true" ' . (search_posts_is_on() ? ' checked="checked"' : '') . '/></td>
					<td><label for="search_posts">&emsp; '.$lang['search']['enablepostscheckbox'].'</label></td>
			</tr>
			<tr>
					<td><input type="checkbox" name="search_albums" id="search_albums" value="true" ' . (search_albums_is_on() ? ' checked="checked"' : '') . '/></td>
					<td><label for="search_albums">&emsp; '.$lang['search']['enablealbumscheckbox'].'</label></td>
			</tr>
			<tr>';

			$pages = get_pages();
			echo '<td><select id="blogpage" name="blogpage">';
			
			foreach ($pages as $page){
				require PAGE_DIR.'/'.$page;
				echo '<option value='.get_page_seoname($page);
				if (get_page_seoname($page) == search_blogpage_is()) {
					echo ' selected';
				}
				echo '>'. sanitize($title) .'</option>';



			}
		echo '</select></td>';
		echo '<td><label for="blogpage">&emsp; '.$lang['search']['blogpage'].'</label></td>';
		echo '			</tr>
			<tr>
				<td><input type="checkbox" name="newpage" id="newpage" value="true" ' . (search_newpage_is_on() ? ' checked="checked"' : '') . '/></td>
				<td><label for="newpage">&emsp; '.$lang['search']['newpagecheckbox'].'</label></td>
			</tr>
		</table><br />';
}

function search_admin_module_settings_afterpost() {
	//Compose settings array
	$settings = array(
		'search_pages' => (isset($_POST['search_pages'])) ? 'true' : 'false',
		'search_posts' => (isset($_POST['search_posts'])) ? 'true' : 'false',
		'search_albums' => (isset($_POST['search_albums'])) ? 'true' : 'false',
		'blogpage' => (isset($_POST['blogpage'])) ? $_POST['blogpage'] : '',
		'newpage' => (isset($_POST['newpage'])) ? $_POST['newpage'] : 'false',
	);
	//Save settings
	module_save_settings('search', $settings);
}
?>
