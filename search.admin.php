<?php
/**
 * @brief: Search Admin Pages
 * @fileinfo: /data/modules/search/search.admin.php
 */

	function search_pages_admin() {
		$module_page_admin[] = array(
			'func'  => 'search_admin',
			'title' => 'Search Admin'
		);

		return $module_page_admin;
	}
 
	function search_page_admin_search_admin() {
		echo '<p>' . $lang["search"]["search"] . " " . $lang["general"]["admin"] . '</p>';
	}
 
?>
