<?php

//This script allows you to delete properly all releases which match some criteria
//NOT YET - The nzb, covers and all linked records will be deleted properly.

//##############################################################################
//  If you want to run and maintain your own lists, you need to 
//  edit the path below to location your .txt files. It is a lot of work
//  to maintain these lists. I recommend using my lists. If you insist, you 
//  can copy these .txt files from my Github and edit away.

$deletefilepath = "https://raw.github.com/hernandito/NN-DeleteRomance/master";

//###############################################################################
//  Edit the below value to "true" if you want the script to actually
//  perform the deletion of the found releases.

$enabledelete = "false";

//###############################################################################
// No need to edit anything below
//===============================================================================



define('FS_ROOT', realpath(dirname(__FILE__)));
require_once(FS_ROOT."/../../www/config.php");
require_once(FS_ROOT."/../../www/lib/framework/db.php");
require_once(FS_ROOT."/../../www/lib/releases.php");

$releases = new Releases();
$db = new Db;



$countme = 0;


//#############################################################
//                                                           //
//     Delete Books w/ covers and w/out Publisher            //   
//                                                           //
//#############################################################
  	
		echo "\n\n\033[1;44;33m About to delete Books w/ Covers and w/out Publisher \033[1;0;33m\n";

$start_time = MICROTIME(TRUE);		
		$sql = "SELECT r.ID FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher = '' and bi.cover=1";
		$rel = $db->query($sql);
		echo "\033[1;1;33mDeleting ".count($rel)." release(s)\n\033[1;0;36m";

		$countme = $countme + count($rel) ;
	
		if($enabledelete == "true") 
			{
				$start_time = MICROTIME(TRUE);
				foreach ($rel as $r)
				{
					$releases->delete($r['ID']);
				}
				$stop_time = MICROTIME(TRUE);
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mDelete Time: $time seconds\n\n\n\n\n\n\033[1;1;33m";				
		} else {	
				echo "\033[1;0;36mDelete is set to $enabledelete. Nothing will be changed!\n\n\n\n\n\n\033[1;1;33m";
				}					
							
//#############################################################




//#############################################################
//                                                           //
//     Delete from Publisher1.txt file if it exists          //   
//                                                           //
//#############################################################
	$filename = '$deletefilepath/publisher1.txt';
	$file_headers = @get_headers($filename);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
			  echo "The file $filename does not exist";
		} else if ($file_headers[0] == 'HTTP/1.0 302 Found'){
			echo "The file $filename does not exist";
		} else {
		 
				echo "\033[1;44;33m About to delete from Publishers1.txt \033[1;0;36m\n";
				
				$Vdata = file_get_contents("$deletefilepath/publisher1.txt");
				echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
				echo "$Vdata \n\033[1;1;33m";
				$start_time = MICROTIME(TRUE);			
				$sql = "SELECT r.ID FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher  REGEXP '{$Vdata}'";

				$rel = $db->query($sql);
				$stop_time = MICROTIME(TRUE);	
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mScan Time: $time seconds\n\033[1;1;33m";			
				echo "Deleting ".count($rel)." release(s)\n\033[1;0;36m";
				$countme = $countme + count($rel) ;				

		if($enabledelete == "true") 
			{
				$start_time = MICROTIME(TRUE);
				foreach ($rel as $r)
				{
					$releases->delete($r['ID']);
				}
				$stop_time = MICROTIME(TRUE);
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mDelete Time: $time seconds\n\n\n\n\n\n\033[1;1;33m";				
		} else {	
				echo "\033[1;0;36mDelete is set to $enabledelete. Nothing will be changed!\n\n\n\n\n\n\033[1;1;33m";
				}					
		}
			
			
//         End of delete from publishers1.txt                //
//#############################################################






//#############################################################
//                                                           //
//     Delete from Publisher2.txt file if it exists          //   
//                                                           //
//#############################################################
	$filename = '$deletefilepath/publisher2.txt';
	$file_headers = @get_headers($filename);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
			  echo "The file $filename does not exist";
		} else if ($file_headers[0] == 'HTTP/1.0 302 Found'){
			echo "The file $filename does not exist";
		} else {
		 
                echo "\033[1;44;33m About to delete from Publisher2.txt \033[1;0;36m\n";

				$Vdata = file_get_contents("$deletefilepath/publisher2.txt");

				echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
				echo "$Vdata \n\033[1;1;33m";
				
				$sql = "SELECT r.ID FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher  REGEXP '{$Vdata}'";

				$rel = $db->query($sql);
				$stop_time = MICROTIME(TRUE);	
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mScan Time: $time seconds\n\033[1;1;33m";			
				echo "Deleting ".count($rel)." release(s)\n\033[1;0;36m";
				$countme = $countme + count($rel) ;				

		if($enabledelete == "true") 
			{
				$start_time = MICROTIME(TRUE);
				foreach ($rel as $r)
				{
					$releases->delete($r['ID']);
				}
				$stop_time = MICROTIME(TRUE);
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mDelete Time: $time seconds\n\n\n\n\n\n\033[1;1;33m";				
		} else {	
				echo "\033[1;0;36mDelete is set to $enabledelete. Nothing will be changed!\n\n\n\n\n\n\033[1;1;33m";
				}					
		}

				

//         End of delete from publishers2.txt                //
//#############################################################





//#############################################################
//                                                           //
//     Delete from Publisher3.txt file if it exists          //   
//                                                           //
//#############################################################
	$filename = '$deletefilepath/publisher3.txt';
	$file_headers = @get_headers($filename);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
			  echo "The file $filename does not exist";
		} else if ($file_headers[0] == 'HTTP/1.0 302 Found'){
			echo "The file $filename does not exist";
		} else {
		 
				echo "\033[1;44;33m About to delete from Publishers3.txt \033[1;0;36m\n";

				$Vdata = file_get_contents("$deletefilepath/publisher3.txt");
				echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
				echo "$Vdata \n\033[1;1;33m";
$start_time = MICROTIME(TRUE);
				$sql = "SELECT r.ID FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher  REGEXP '{$Vdata}'";

				$rel = $db->query($sql);
				$stop_time = MICROTIME(TRUE);	
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mScan Time: $time seconds\n\033[1;1;33m";			
				echo "Deleting ".count($rel)." release(s)\n\033[1;0;36m";
				$countme = $countme + count($rel) ;				

		if($enabledelete == "true") 
			{
				$start_time = MICROTIME(TRUE);
				foreach ($rel as $r)
				{
					$releases->delete($r['ID']);
				}
				$stop_time = MICROTIME(TRUE);
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mDelete Time: $time seconds\n\n\n\n\n\n\033[1;1;33m";				
		} else {	
				echo "\033[1;0;36mDelete is set to $enabledelete. Nothing will be changed!\n\n\n\n\n\n\033[1;1;33m";
				}					
		}		
				
				

//         End of delete from publishers3.txt                //
//#############################################################







//#############################################################
//                                                           //
//         Delete Books from keywords1.txt                   //   
//                                                           //
//#############################################################
	$filename = '$deletefilepath/keywords1.txt';
	$file_headers = @get_headers($filename);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
			  echo "Friendly Note: \nFile Publishers5.txt not yet available. Reserved for future use.\n";
		} else if ($file_headers[0] == 'HTTP/1.0 302 Found'){
			echo "Friendly Note: \nFile Publishers5.txt not yet available. Reserved for future use.\n\n";
		} else {
		 
				echo "\033[1;44;33m About to delete from Keywords.txt \033[1;0;36m\n";

				$Vdata = file_get_contents("$deletefilepath/keywords1.txt");
				
				echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
				echo "$Vdata \n\033[1;1;33m";
$start_time = MICROTIME(TRUE);				
				$sql = "Select `ID` from `releases` where `categoryID` = 7020 and `searchname` REGEXP '{$Vdata}'";
				
				$rel = $db->query($sql);
				$stop_time = MICROTIME(TRUE);	
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mScan Time: $time seconds\n\033[1;1;33m";			
				echo "Deleting ".count($rel)." release(s)\n\033[1;0;36m";
				$countme = $countme + count($rel) ;				

		if($enabledelete == "true") 
			{
				$start_time = MICROTIME(TRUE);
				foreach ($rel as $r)
				{
					$releases->delete($r['ID']);
				}
				$stop_time = MICROTIME(TRUE);
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mDelete Time: $time seconds\n\n\n\n\n\n\033[1;1;33m";				
		} else {	
				echo "\033[1;0;36mDelete is set to $enabledelete. Nothing will be changed!\n\n\n\n\n\n\033[1;1;33m";
				}					
		}

//         End of delete from keywords1.txt                //
//#############################################################




//#############################################################
//                                                           //
//     Delete from authors1.txt file if it exists          //   
//                                                           //
//#############################################################
	$filename = '$deletefilepath/authors1.txt';
	$file_headers = @get_headers($filename);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
			  echo "Friendly Note: \nFile authors1.txt not yet available. Reserved for future use.\n\n";
		} else if ($file_headers[0] == 'HTTP/1.0 302 Found'){
			echo "Friendly Note: \nFile authors1.txt not yet available. Reserved for future use.\n\n";
		} else {
		 
				echo "\033[1;44;33m About to delete from Authors1.txt \033[1;0;36m\n";

				$Vdata = file_get_contents("$deletefilepath/authors1.txt");

				echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
				echo "$Vdata \n\033[1;1;33m";
$start_time = MICROTIME(TRUE);				
				$sql = "Select `ID` from `releases` where `searchname` REGEXP '{$Vdata}' and `categoryID` = 7020";

				$rel = $db->query($sql);
				$stop_time = MICROTIME(TRUE);	
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mScan Time: $time seconds\n\033[1;1;33m";			
				echo "Deleting ".count($rel)." release(s)\n\033[1;0;36m";
				$countme = $countme + count($rel) ;				

		if($enabledelete == "true") 
			{
				$start_time = MICROTIME(TRUE);
				foreach ($rel as $r)
				{
					$releases->delete($r['ID']);
				}
				$stop_time = MICROTIME(TRUE);
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mDelete Time: $time seconds\n\n\n\n\n\n\033[1;1;33m";				
		} else {	
				echo "\033[1;0;36mDelete is set to $enabledelete. Nothing will be changed!\n\n\n\n\n\n\033[1;1;33m";
				}					
		}

//         End of delete from authors1.txt                //
//#############################################################


//#############################################################
//                                                           //
//     Delete from authors2.txt file if it exists          //   
//                                                           //
//#############################################################
	$filename = '$deletefilepath/authors2.txt';
	$file_headers = @get_headers($filename);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
			  echo "Friendly Note: \nFile authors1.txt not yet available. Reserved for future use.\n\n";
		} else if ($file_headers[0] == 'HTTP/1.0 302 Found'){
			echo "Friendly Note: \nFile authors1.txt not yet available. Reserved for future use.\n\n";
		} else {
		 
				echo "\033[1;44;33m About to delete from Authors2.txt \033[1;0;36m\n";

				$Vdata = file_get_contents("$deletefilepath/authors2.txt");

				echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
				echo "$Vdata \n\033[1;1;33m";
$start_time = MICROTIME(TRUE);				
				$sql = "Select `ID` from `releases` where `searchname` REGEXP '{$Vdata}' and `categoryID` = 7020";

				$rel = $db->query($sql);
				$stop_time = MICROTIME(TRUE);	
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mScan Time: $time seconds\n\033[1;1;33m";			
				echo "Deleting ".count($rel)." release(s)\n\033[1;0;36m";
				$countme = $countme + count($rel) ;				

		if($enabledelete == "true") 
			{
				$start_time = MICROTIME(TRUE);
				foreach ($rel as $r)
				{
					$releases->delete($r['ID']);
				}
				$stop_time = MICROTIME(TRUE);
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mDelete Time: $time seconds\n\n\n\n\n\n\033[1;1;33m";				
		} else {	
				echo "\033[1;0;36mDelete is set to $enabledelete. Nothing will be changed!\n\n\n\n\n\n\033[1;1;33m";
				}					
		}

//         End of delete from authors2.txt                //
//#############################################################




//#############################################################
//                                                           //
//     Delete from brackets1.txt file if it exists          //   
//                                                           //
//#############################################################
	$filename = '$deletefilepath/brackets1.txt';
	$file_headers = @get_headers($filename);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
			  echo "Friendly Note: \nFile brackets1.txt not yet available. Reserved for future use.\n\n";
		} else if ($file_headers[0] == 'HTTP/1.0 302 Found'){
			echo "Friendly Note: \nFile brackets1.txt not yet available. Reserved for future use.\n\n";
		} else {
		 
				echo "\033[1;44;33m About to delete from Brackets1.txt \033[1;0;36m\n";

				$Vdata = file_get_contents("$deletefilepath/brackets1.txt");
				
				echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
				echo "$Vdata \n\033[1;1;33m";
$start_time = MICROTIME(TRUE);				
				$sql = "Select `ID` from `releases` where `searchname` REGEXP '{$Vdata}' and `categoryID` = 7020";

				$rel = $db->query($sql);
				$stop_time = MICROTIME(TRUE);	
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mScan Time: $time seconds\n\033[1;1;33m";			
				echo "Deleting ".count($rel)." release(s)\n\033[1;0;36m";
				$countme = $countme + count($rel) ;				

		if($enabledelete == "true") 
			{
				$start_time = MICROTIME(TRUE);
				foreach ($rel as $r)
				{
					$releases->delete($r['ID']);
				}
				$stop_time = MICROTIME(TRUE);
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mDelete Time: $time seconds\n\n\n\n\n\n\033[1;1;33m";				
		} else {	
				echo "\033[1;0;36mDelete is set to $enabledelete. Nothing will be changed!\n\n\n\n\n\n\033[1;1;33m";
				}					
		}				
				

//         End of delete from brackets1.txt                //
//#############################################################

//#############################################################
//                                                           //
//     Delete from brackets2.txt file if it exists          //   
//                                                           //
//#############################################################
	$filename = '$deletefilepath/brackets2.txt';
	$file_headers = @get_headers($filename);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
			  echo "Friendly Note: \nFile brackets2.txt not yet available. Reserved for future use.\n\n";
		} else if ($file_headers[0] == 'HTTP/1.0 302 Found'){
			echo "Friendly Note: \nFile brackets2.txt not yet available. Reserved for future use.\n\n";
		} else {
		 
				echo "\033[1;44;33m About to delete from Brackets2.txt \033[1;0;36m\n";

				$Vdata = file_get_contents("$deletefilepath/brackets2.txt");
				
				echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
				echo "$Vdata \n\033[1;1;33m";
$start_time = MICROTIME(TRUE);				
				$sql = "Select `ID` from `releases` where `searchname` REGEXP '{$Vdata}' and `categoryID` = 7020";

				$rel = $db->query($sql);
				$stop_time = MICROTIME(TRUE);	
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mScan Time: $time seconds\n\033[1;1;33m";			
				echo "Deleting ".count($rel)." release(s)\n\033[1;0;36m";
				$countme = $countme + count($rel) ;				

		if($enabledelete == "true") 
			{
				$start_time = MICROTIME(TRUE);
				foreach ($rel as $r)
				{
					$releases->delete($r['ID']);
				}
				$stop_time = MICROTIME(TRUE);
				$time = round($stop_time - $start_time);
				echo "\033[1;0;36mDelete Time: $time seconds\n\n\n\n\n\n\033[1;1;33m";				
		} else {	
				echo "\033[1;0;36mDelete is set to $enabledelete. Nothing will be changed!\n\n\n\n\n\n\033[1;1;33m";
				}					
		}

//         End of delete from brackets2.txt                //
//#############################################################



echo "\033[0;1;35mFinished!\033[1;1;36m\n";
echo "Deleted a total of \033[0;1;33m$countme \033[1;1;36mrelease(s) \n\n\n\033[1;0;36m";

?>

