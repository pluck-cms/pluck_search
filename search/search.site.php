<?php
/**
 * Pluck Search
 * @fileinfo: /data/modules/search
*/

// main search functions
    require_once ('data/modules/search/functions.php');

/**
 * @brief Insert Content into search page
 */
    function search_theme_main() {
	    global $lang;
        $numresults = false;
	    $query = false;
		
	    if (isset($_REQUEST['q'])) {
		    $query = sanitize($_REQUEST['q']);
			$matches = $null;
			if (preg_match('/~!@#$%^&*()_+=-{};\\:<\/>"\[\]\'\?*/', $query ) == 0 ) {
			    if (strlen($query) > 2) {
			    	echo '<div id="results">';
			    	echo '<h2>' . $lang['search']['results for'] . ': "'. $query . '"</h2>';
				
				//removes the space in beginning or ending
				    $query = trim($query);
				    $directory = '';
				
				// regular pages	
				    $directory_pages = $directory . 'data/settings/pages';
				    $results = searchcontent($query, $directory, $directory_pages, 'pages');
				
				// blog pages
				    $directory_blog = $directory . 'data/settings/modules/blog/posts';
				    $results .= searchcontent($query, $directory, $directory_blog, 'blog');
				
				// albums
				    $results .= search_albums($query, $directory);
				
				//removes the empty beginning element
				    $results = substr($results, 5);
				    if ($results != '') {
					    $results_list = explode('**7**', $results);
					    $results = array_unique($results_list);
				    	foreach ($results_list as $result) {
				    		echo $result;
				    	}
				    	$numresults = sizeof($results_list);
				    }
					if (!$numresults) {
						echo '<ul><li>' . $lang['search']['no results'] . '</li></ul>'; 
					} 
					echo '</div>';
				}
				else {
				// search query is too small
					echo '<p class="error">' . $lang['search']['enter a larger search term'] . '</p>';
				}
			}
			else {
				// contains symbols
				echo '<p class="error">' . $lang['search']['cannot search symbols'] . '</p>';
			}
		}
		?>
	
		<form method="post" action="" id="SearchForm">
			<h1></h1>
			<div class="iwrap">
				<label for="SearchFormQuery"><?php echo $lang['search']['label']; ?></label>
				<input name="q" id="SearchFormQuery" type="search" value="<?php echo $query; ?>"/>
				<input type="submit" name="submit" value="<?php echo $lang['search']['search']; ?>" />
			</div> 
		</form>
<?php
}
