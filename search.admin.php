<?php
/**
 * @brief: Search Admin Pages
 * @fileinfo: /data/modules/search/search.admin.php
 */

	function search_pages_admin() {
		global $lang;
		$module_page_admin[] = array(
			'func'  => 'search_admin',
			'title' => $lang['search']['title']
		);

		return $module_page_admin;
	}
 
	function search_page_admin_search_admin() {
		global $lang;
		echo '<p>' . $lang['search']['admin_center'] . '</p>';
	}
 
?>
