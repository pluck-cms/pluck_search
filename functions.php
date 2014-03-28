<?php
/**
 * @brief functions for Pluck Search
 * Searches blog, album, pages for title, description, keyword matches.
 * 
 * @fileinfo: /data/modules/search/functions.php
 * 
 * @todo highlight result.
 */
 
//Make sure the file isn't accessed directly.
	defined('IN_PLUCK') or exit('Access denied!');

/**
 * Replace Special Characters
 */
 	function SustituyeSpecialChars($texto){
	// encode for substitution	
		$texto = urlencode($texto);
		// accented chars
		$acentos = array("%C3%A1", "%C3%A9", "%C3%AD", "%C3%B3", "%C3%BA", "%C3%B1", "%C3%A7", "%C3%81", "%C3%89", "%C3%8D", "%C3%93", "%C3%9A", "%C3%91", "%C3%87","%C3%A0", "%C3%80", "%C3%A8", "%C3%AF", "%C3%B2", "%C3%BC", "%C3%88", "%C3%8F", "%C3%92", "%C3%9C");
		$validos = array("a", "e", "i", "o", "u", "n", "c", "A", "E", "I", "O", "U", "N", "C","a", "A", "e", "i", "o", "u", "E", "I", "O", "U");
		$texto= str_replace($acentos, $validos, $texto);
	// return to regular text	
		$texto = urldecode($texto);	
		return $texto;
	}
	
/**
* @brief Read files or directories recursively in a directory, and return the names in an array.
*
* @since 4.7
* @package search
* @param string $directory The directory where the files are in.
* @param string $mode Set to 'dirs' or 'files', to return directories or files respectively.
* @return array The directories or files.
*/
function read_dir_contents_recurse($directory, $mode) {
    if (!is_dir($directory))
	return false;
    
    $path = opendir($directory);
    while (false !== ($file = readdir($path))) {
    	if ($file != '.' && $file != '..') {
    	   $testfile = $directory.'/'.$file;
    	    if (is_file($testfile))
    		$files[] = $testfile;
    	    elseif (is_dir($testfile)){
    		$dirs[] = $testfile;
    		$subdirs = read_dir_contents_recurse($testfile, $mode);
    		$result = array_merge($files, $subdirs);
    		$files = $result;
    	    }
    	}
    }
    closedir($path);
    
    if ($mode == 'files' && isset($files))
	return $files;
    elseif ($mode == 'dirs' && isset($dirs))
    	return $dirs;
    else
    	return false;
}
   																		

/**
 * Search Content
 * @param $query str // search string
 * @param $usuarios str // ??
 * @param $directory str //
 * @param $types str // 
 */
	function searchcontent($querys, $usuario, $directory, $types)
	{
		global $page1_link, $page2_link, $blog1_link, $blog2_link, $search_query, $seach_found_in;
		$resultados = "";
		if (is_dir($directory)){
			$files = read_dir_contents_recurse($directory,'files');
			if ($files){	
				$querys = SustituyeSpecialChars($querys);
				natcasesort($files);
				foreach ($files as $file) {
					include ("$file");
					$pattern = "/$querys/i";
					$expl_file = explode("/",$file);
					$filename = $expl_file[count($expl_file)-1];
					$b = strpos($filename, '.');
					$e = strlen($filename) - $b - 3;
					$pagestart = "";
					if (count($expl_file) >4){
						$number = count ($expl_file);

						switch ($number){
						case 5:
							$pagestart = $expl_file[3];
					    break;
						case 6:
							$pagestart = $expl_file[3] . "/" . $expl_file[4];
					    break;
						case 7:
							$pagestart = $expl_file[3] . "/" . $expl_file[4] . "/" . $expl_file[5];
					    break;
						case 8:
							$pagestart = $expl_file[3] . "/" . $expl_file[4] . "/" . $expl_file[5] . "/" . $expl_file[6];
					    break;
						}
						$pagestart = $pagestart . "/";
					}
					
					$page = $pagestart . substr($filename, $b+1,$e-2);
					
					switch ($types){
					
					case "pages":
						if(!isset($description)) $description = "";
						if(!isset($keywords)) $keywords = "";	
						$results = strip_tags("$title $content $description $keywords");
						$results= SustituyeSpecialChars($results);
						
						if (
							preg_match($pattern, $results) 
							&& !strpos($file,"sitemap")
							&& !strpos($file,"search")
							)
						{
							$link = "**7**
								<ul>
									<li>
										<a href='?file=" . $page . "'>$title</a>
										<p>$description</p>
									</li>
								</ul>";
							$resultados.=$link;
						}
						$filelist[]=$page;
						$title=""; $content=""; $description=""; $keywords="";
						break;
						
					case "blog":
					
						$title=""; $content=""; $description=""; $keywords="";
						$plink = "?file=blog&module=blog&page=viewpost&post=$page";
						$results = strip_tags("$post_title $post_content");
						$results= SustituyeSpecialChars($results);
						if (preg_match($pattern, $results)) 
						{					
							$resultados .= "**7**
								<ul>
									<li>BLOG/$post_category/
										<a href='" . $plink . "'\>
										$post_title</a>
									</li>
								</ul>";
						}
						break;
					}
				}
			}
				$title=""; $content=""; $description=""; $keywords="";
		}
		return $resultados;
	}
	
/**
 * @brief Search Albums
 */
 	function search_albums($querys, $usuario) 
	{
		$dirs = "";
		$types = "";
		global $album1_link, $album2_link, $album3_link, $search_query, $seach_found_in;
		$resultados = "";
		$directory = $usuario."data/settings/modules/albums";
		if (file_exists($directory))
		{
			$querys=SustituyeSpecialChars($querys);
			$pattern = "/$querys/i";
			$path = opendir($directory);
			while (false !== ($file = readdir($path))) {
				if(($file !== '.') and ($file !== '..')) {
					if(!is_file($dir.'/'.$file))
						$dirs[]=$file;
				}
			}
			if($dirs) {
				natcasesort($dirs);
				foreach ($dirs as $dir) 
				{
					if ($types == 3) {
						if (preg_match($pattern, $dir)) {
							$resultados .= "**7**
								<ul>
									<li><a href=\"".$usuario."index.php?module=albums&page=viewalbum&album=$dir&pageback=kop1.php\">
										$dir</a>
									</li>
								</ul>";
						}
					}
					
					$files = read_dir_contents($directory.'/'.$dir,'files');
					
					if ($files) {
						natcasesort($files);
						foreach ($files as $file) {
							if (preg_match('/(.+)\.php/', $file)) {
							include $directory.'/'.$dir.'/'.$file;
							$results = strip_tags($name);
							$results=SustituyeSpecialChars($results);
								if (preg_match($pattern, $results)) {
								$resultados .= "**7**
									<ul>
										<li>
											<a href=\"".$usuario."index.php?module=albums&page=viewalbum&album=$dir&pageback=kop1.php\">
											$dir</a>
										</li>
									</ul>";
								}
							}
						}
					}
				}
		   }
		   closedir($path);
		}
		return $resultados;
	}

?>
