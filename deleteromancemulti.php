
<?php

//This script allows you to delete properly all releases which match some criteria
//The nzb, covers and all linked records will be deleted properly.

//##############################################################################
//  If you want to run and maintain your own lists, you need to
//  edit the path below to location your .txt files. It is a lot of work
//  to maintain these lists. I recommend using my lists. If you insist, you
//  can copy these .txt files from my Github and edit away.

$deletefilepath = "https://raw.github.com/hernandito/NN-DeleteRomance/master";

//  Add an additional path to lists you maintain. This way you are able to run 
//  both my list AND your list. If you are maintaining special lists, please
//  share in the forum in case others want to benefit from your list.

$customfilepath = "https://raw.github.com/hernandito/NN-DeleteRomance/master";

//  Valid values are "simple" or "verbose" 
$runverbose = "verbose";
//  Set time in seconds before the "default" action runs when looking at menu
//  screen. 
$timeout = 8;

//##############################################################################
//  Edit the location where your Book Covers are stored
$NNBookCoverPath = "/var/www/newznab/www/covers/book/";

//###############################################################################
//  Edit the below values to true if you want the script to actually
//  perform the deletion of the found releases.
//
//  Delete Releases found in Text Files:
//  Romance, erotic, and fetish books based on publisher, author & keywords
$enabledelete = "false";
//
//
//   Delete Releases from Self Published Authors:
//   Publisher and Author being the same or Publisher not listed w/ book cover.
$enableselfpubdel = "false";
//
//
//  It is recommended to first run script with both values set to "false" then
//  you can scan the found releases and ensure you are not deleting books you want
//  keep. Once you are certain you want to delete, set values to "true".
//
//###############################################################################
// No need to edit anything below
//===============================================================================




define('FS_ROOT', realpath(dirname(__FILE__)));
require_once(FS_ROOT."/../../www/config.php");
require_once(FS_ROOT."/../../www/lib/framework/db.php");
require_once(FS_ROOT."/../../www/lib/releases.php");

$countme = 0;
$countself = 0;
$textcount = 1;
$releases = new Releases();
$db = new Db;
$countself2 = 0;



function read_stdin()
{
        $fr=fopen("php://stdin","r");   // open our file pointer to read from stdin
        $input = fgets($fr,128);        // read a maximum of 128 characters
        $input = rtrim($input);         // trim any trailing spaces.
        fclose ($fr);                   // close the file handle
        return $input;                  // return the text entered
}

$tl = html_entity_decode('&#x2554;', ENT_NOQUOTES, 'UTF-8'); // top left corner
$tr = html_entity_decode('&#x2557;', ENT_NOQUOTES, 'UTF-8'); // top right corner
$bl = html_entity_decode('&#x255a;', ENT_NOQUOTES, 'UTF-8'); // bottom left corner
$br = html_entity_decode('&#x255d;', ENT_NOQUOTES, 'UTF-8'); // bottom right corner
$v = html_entity_decode('&#x2551;', ENT_NOQUOTES, 'UTF-8');  // vertical wall
$h = html_entity_decode('&#x2550;', ENT_NOQUOTES, 'UTF-8');  // horizontal wall



  		passthru('clear');
			echo " \n";
			echo "\033[1;44;33m";
			echo $tl . str_repeat($h, 51)  . $tr . "\n" .
				 $v  . '         Cleanup Newznab\'s eBook Category          '   . $v  . "\n" .
				 $bl . str_repeat($h, 51)  . $br . "\033[0;1;33m\n";
			echo "\n\n";
									 

			echo "    Please type number of activity to run:\n";
			echo " \n";
			echo "\033[0;1;36m        1. \033[0;32;40mRemove Self-published Books  \n";
			echo "\033[0;1;36m        2. \033[0;32;40mRemove Books by Publisher \n";
			echo "\033[0;1;36m        3. \033[0;32;40mRemove Books by Keywords \n";
			echo "\033[0;1;36m        4. \033[0;32;40mRemove Books by Authors \n";
			echo "\033[0;1;36m        5. \033[0;32;40mRemove Books by Abbvr. in Brackets \n";
			echo "\033[0;1;36m        6. \033[0;32;40mRemove using your Custom Lists \n";
			echo "\033[0;1;36m        7. \033[0;37;40mRemove All the Above (default)\n";
			echo " \n";
			echo " \n";
			echo "\033[0;1;33m\n   Type number and [ENTER]\033[0;0;37m or will run default in \033[0;1;31m$timeout \033[0;0;37msecs:\033[0;1;35m ";

			
			
			
$fd = fopen('php://stdin', 'r');

// prepare arguments for stream_select()
$read = array($fd);
$write = $except = array(); 


// wait for maximal 5 seconds for input
	if(stream_select($read, $write, $except, $timeout)) {

			$menuchoice = fgets($fd);
			echo "\033[1;0;36m \n";

			if ( $menuchoice == 1) {
				DelSelfPub();
			}
			elseif 	 ( $menuchoice == 2) {
				$categotextfile = "publisher";
				$sqlstring = "SELECT r.ID, r.BOOKINFOID, r.name FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher  REGEXP '";
				DelByKeyword();
			}
			elseif 	 ( $menuchoice == 3) {
				$categotextfile = "keywords";
				$sqlstring = "Select `ID`, `name` from `releases` where `categoryID` = 7020 and `searchname` REGEXP '";
				DelByKeyword();
			}
			elseif 	 ( $menuchoice == 4) {
				$categotextfile = "authors";
				$sqlstring = "Select `ID`, `name` from `releases` where `categoryID` = 7020 and `searchname` REGEXP '";
				DelByKeyword();
			}
			elseif 	 ( $menuchoice == 5) {
				$categotextfile = "brackets";
				$sqlstring = "Select `ID`, `name` from `releases` where `categoryID` = 7020 and `searchname` REGEXP '";
				DelByKeyword();
			}
			elseif 	 ( $menuchoice == 6) {
				$categotextfile = "mylist";
				$sqlstring = "Select `ID`, `name` from `releases` where `categoryID` = 7020 and `searchname` REGEXP '";
				DelCustomList();
			}
			elseif 	 ( $menuchoice == 7) {
				DelSelfPub();
				$categotextfile = "publisher";
				$sqlstring = "SELECT r.ID, r.BOOKINFOID, r.name FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher  REGEXP '";
				DelByKeyword();	
				$categotextfile = "keywords";
				$sqlstring = "Select `ID`, `name` from `releases` where `categoryID` = 7020 and `searchname` REGEXP '";
				DelByKeyword();	
				$categotextfile = "authors";
				DelByKeyword();	
				$categotextfile = "brackets";
				DelByKeyword();
				$categotextfile = "mylist";
				DelCustomList();
				
				EndReport();
			}
			else  {
				echo "\033[0;1;31m   You must type a number between 1 & 7. EXITING...!\033[1;0;36m\n\n";
			}
	} else {
		echo "\nCleaning All Categories \n";
				DelSelfPub();
				$categotextfile = "publisher";
				$sqlstring = "SELECT r.ID, r.BOOKINFOID, r.name FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher  REGEXP '";
				DelByKeyword();	
				$categotextfile = "keywords";
				$sqlstring = "Select `ID`, `name` from `releases` where `categoryID` = 7020 and `searchname` REGEXP '";
				DelByKeyword();	
				$categotextfile = "authors";
				DelByKeyword();	
				$categotextfile = "brackets";
				DelByKeyword();
				$categotextfile = "mylist";
				DelCustomList();
				
				EndReport();
	}


			
function DelByKeyword()
{
		//###############################
		//     Start of Keywords Loop
		//###############################
				global $deletefilepath;
				global $runverbose;
				global $sqlstring;
				global $categotextfile;
				global $txtfile;
				global $filename;
				global $Vdata;
				global $sql;
				global $db;
				global $enabledelete;
				global $enableselfpubdel;
				global $releases;
				global $countme;
				global $countself;
				global $countself2;
				global $countloop;
				$countloop = 0;
				echo " \n";
				$catego = strtoupper($categotextfile);
				if ($runverbose =="verbose"){
					echo "\033[0;43;30m=========================================\033[1;0;36m\n";
					echo "\033[1;1;33m        Starting $catego Loop          \033[1;0;36m\n";
					echo "\033[0;43;30m=========================================\033[1;0;36m\n";
				} else {
					echo "\033[1;1;33mStarting $catego Loop\033[1;0;36m\n";
				}
				echo " \n";
				$textcount = 1;
				while ($textcount <= 7)
					{	
						$txtfile = $categotextfile . $textcount . ".txt";
						$filename = "$deletefilepath/$txtfile";
						error_reporting(0);
						
						if (fopen($filename, "r")) {
							$Vdata = file_get_contents("$filename");
							$sql =  $sqlstring . "{$Vdata}'";
							//echo "$sql \n\n";
							ProcessBooks();
						} else {
							echo "\033[1;1;30mFile $txtfile is not used yet. No worries, this is for future use.\n";			
						}
						$textcount++;
					}
					echo "\033[1;1;30m-----------------------------------------------------------------------\n";
					echo "\033[1;1;33m$catego Loop Complete! \n";
					echo "\033[1;1;36mLoop Total: \033[1;1;35m$countloop \033[1;1;36mrelease(s)\n\n\n\n\n\n\033[1;0;36m";
// ##############################	

}

function DelCustomList()
{
		//###############################
		//     Start of Keywords Loop
		//###############################
				global $deletefilepath;
				global $runverbose;
				global $customfilepath;
				global $sqlstring;
				global $categotextfile;
				global $txtfile;
				global $filename;
				global $Vdata;
				global $sql;
				global $db;
				global $enabledelete;
				global $enableselfpubdel;
				global $releases;
				global $countme;
				global $countself;
				global $countself2;
				global $countloop;
				$countloop = 0;
				echo " \n";
				$catego = strtoupper($categotextfile);
				if ($runverbose == "verbose"){
					echo "\033[0;45;30m=========================================\033[1;0;36m\n";
					echo "\033[1;1;35m        Starting $catego Loop          \033[1;0;36m\n";
					echo "\033[0;45;30m=========================================\033[1;0;36m\n";
				} else {
					echo "\033[1;1;35mStarting $catego Loop\033[1;0;36m\n";
				}
				echo " \n";
				$textcount = 1;
				while ($textcount <= 7)
					{	
						$txtfile = $categotextfile . $textcount . ".txt";
						$filename = "$customfilepath/$txtfile";
						error_reporting(0);
						
						if (fopen($filename, "r")) {
							$Vdata = file_get_contents("$filename");
							$sql =  $sqlstring . "{$Vdata}'";
							//echo "$sql \n\n";
							ProcessBooks();
						} else {
							echo "\033[1;1;30mFile $txtfile is not used yet. No worries, this is for future use.\n";			
						}
						$textcount++;
					}
					echo "\033[1;1;30m-----------------------------------------------------------------------\n";
					echo "\033[1;1;33m$catego Loop Complete! \n";
					echo "\033[1;1;36mLoop Total: \033[1;1;35m$countloop \033[1;0;36mrelease(s)\n\n\n\n\n\n\033[1;0;36m";
// ##############################	

}



function DelSelfPub()
{
		//########################################
		//  Start Delete Self Publishers Code
		//########################################
				global $runverbose;
				global $txtfile;
				global $filename;
				global $Vdata;
				global $sql;
				global $db;
				global $enabledelete;
				global $enableselfpubdel;
				global $releases;
				global $countme;
				global $countself;
				global $countself2;
				global $countloop;
				echo " \n";
				if ($runverbose =="verbose"){
					echo "\033[0;42;30m=========================================\033[1;0;36m\n";
					echo "\033[1;1;32m  Books w/ Covers and without Publisher  \033[1;0;36m\n";
					echo "\033[0;42;30m=========================================\033[1;0;36m\n";
					echo " \n";
					echo "\033[1;1;36mSearching...\n";
				} else {
					echo "\033[1;1;32mBooks w/ Covers and without Publisher  \033[1;0;36m\n";
				}
			
			$start_time = MICROTIME(TRUE);
			$sql = "SELECT r.ID, r.BOOKINFOID, r.NAME FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher = '' and bi.cover=1";
			$rel = $db->query($sql);
			$countme = count($rel) ;

			if(count($rel) > 0) {
								echo "\033[1;1;33mFound " . count($rel) . " release(s)\n\033[1;0;36m";
								if($enableselfpubdel == "true")
									{
										$start_time = MICROTIME(TRUE);
										foreach ($rel as $r)
										{
											$countself2 = $countself2 + 1;
											$name = $r['NAME'];
											$releases->delete($r['ID']);
											if ($runverbose =="verbose"){
												echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
											}
										}
										$stop_time = MICROTIME(TRUE);
										$time = round($stop_time - $start_time);
										
										if ($runverbose =="verbose"){
											echo "\033[1;1;33mDeleted $countself2 release(s)\n";
											echo "\033[1;0;36mDelete Time: $time seconds\n";

											echo "\033[1;1;30mCleaning entries from bookinfo table....";
										}
										$db->query(sprintf("DELETE FROM `bookinfo` WHERE `publisher` = '' and cover=1"));
										if ($runverbose =="verbose"){
											echo "\033[1;1;33m Done! \n\n\n\n\033[0;1;36m";
										} else {
											echo " \n\n\033[0;1;36m";
										}
								} else	 {
										if ($runverbose =="verbose"){
											foreach ($rel as $r)
											{
												$countself2 = $countself2 + 1;
												$name = $r['NAME'];
												echo "\033[1;0;32m    Found: \033[1;0;37m$name\n";
											}
											echo "\033[1;1;33mFound $countself2 release(s)\n";
										}
										echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
										echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n\n\n";
										}
					}
					else {
							if ($runverbose =="verbose"){
								echo "\033[1;1;33mNothing to delete! \n\n\n\n\033[0;1;36m";	
							} else {
								echo "\033[1;1;33mNothing to delete! \n\n\033[0;1;36m";
							}	
						}
		//########################################	


		//#############################################################
		//                                                           //
		//     Delete Where Author is Publisher (Thanks Afly!!)      //
		//                                                           //
		//#############################################################			

			if ($runverbose =="verbose"){	
				echo "\033[0;42;30m=========================================\033[1;0;36m\n";
				echo "\033[1;1;32m   Books w/ Matching Author & Publisher  \033[1;0;36m\n";
				echo "\033[0;42;30m=========================================\033[1;0;36m\n";
		
				echo "\033[1;1;36mSearching...\n";
			} else {
				echo "\033[1;1;32mBooks w/ Matching Author & Publisher  \033[1;0;36m\n";	
			}	
		$excludedWords = array(
			"the",
			"this",
			"that",
			"a",
			"i",
			"of",
			"at",
			"for",
			"oh",
			"my",
			"and",
			"cinematographer",
			"house",
			"chicken",
			"soup",
			"soul",
			"star",
			"barnes",
			"noble",
			"classics",
			"pc",
			"world",
			"national",
			"geographic",
			"scientific",
			"american",
			"aa",
			"services",
			"teach",
			"yourself",
			"dummies",
			"dk",
			"travel",
			"guinness",
			"records",
			"general",
			"radio",
			"company",
			"mcgraw-hill",
			"professional",
			"artech",
			"current",
			"clinical",
			"strategies",
			"society",
			"industrial",
			"applied",
			"mathematics",
			"arms",
			"armor",
			"quilter",
			"infantry",
			"journal",
			"marco",
			"polo",
			"cold",
			"spring",
			"harbor",
			"laboratory",
			"focal",
			"cisco",
			"how",
			"good",
			"design",
			"originals",
			"breckling",
			"microsoft",
			"peachpit",
			"leisure",
			"arts",
			"course",
			"technology",
			"mysql",
			"institution",
			"structural",
			"engineers",
			"sound",
			"vision",
			"webster's",
			"new",
			"tab",
			"electronics",
			"mcgraw-hill",
			"university",
			"institute",
			"strategic",
			"studies",
			"international",
			"int'l",
			"science",
			"mind",
			"marine",
			"corporation",
			"cambridge",
			"new",
			"word",
			"city",
			"inc",
			"us",
			"army",
			"corps",
			"u.s.",
			"war",
			"department",
			"new",
			"riders",
			"starch",
			"piatkus",
			"prentice",
			"hall",
			"price",
			"pottenger",
			"nutrition",
			"pressure",
			"vessel",
			"handbook",
			"shack",
			"tourism",
			"organisation",
			"serbia",
			"ireland",
			"labouff",
			"creative",
			"print",
			"hard",
			"case",
			"crime",
			"engineering",
			"paraglyph",
			"profile",
			"ltd",
			"books/star",
			"sas",
			"&",
		);		
			
		$excludedPublishers = array(
		);
			
		function getBooks()
		{			
			$db = new DB();
			return $db->query("SELECT r.id, r.name, bi.id as bookid, bi.publisher from releases r join bookinfo bi on r.bookinfoID = bi.ID ");		
		}

		function deleteBook($book)
		{
			$db = new DB();
			global $NNBookCoverPath;
			//if ($book['cover'] == 1)
			//{
				//delete the cover
				if (file_exists ($NNBookCoverPath.$book['bookid'].".jpg"))
				{ 
					unlink($NNBookCoverPath.$book['bookid'].".jpg");
				}
			//}
			
			$db->query(sprintf("DELETE FROM bookinfo WHERE id = %d ",$book['bookid']));
			
		}

		$releases = new Releases();
		$books =  getBooks();


		foreach($books as $book) 
		{		
			$i = 0;
			if (!in_array(strtolower($book['publisher']),$excludedPublishers))
			{	
				$titlewords = explode(" ", strtolower($book['name']));
				$publisherwords = explode(" ", strtolower($book['publisher']));
				
				foreach($titlewords as $word) 
				{
					if (!in_array($word,$excludedWords))
					{
						if(in_array($word,$publisherwords))
						{
							$i = $i + 1;
							if ($i == 2) //two matching words. Change to experiment
							{	
								if ($runverbose =="verbose"){
									echo "\033[1;0;32m    "    .$book['name']. " -\033[1;1;35m " .$book['publisher']. "\n\033[1;0;36m";
								}	
								$countself = $countself + 1;
							
								if ($enableselfpubdel == "true")
								{				
									deleteBook($book);
									$releases->delete($book['id']);
								}
								break;
							}
							
						}
					}
				}
			}
		}

		if ($countself > 0) {
		
				if ($runverbose =="verbose"){
					echo "\033[1;0;37m---------------------------------------------\n";
					echo "\033[1;0;32mGreen = \033[1;0;37mBook Name      \033[1;1;35mMagenta = \033[1;0;37mPublisher \n";
					echo " \n";
				}
				echo "\033[1;1;33mDeleted $countself release(s) \n\033[1;0;36m";
				if ($enableselfpubdel == "false")
					{ 	
						echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
						echo "\033[0;1;36m Setting needs editing in script. "; 
					}
				echo "\033[1;0;36m \n\n\n\n\n\n";
		}	else { 
				echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;0;36m";
		}		
		//#############################################################
}

function ProcessBooks()
{	
	global $txtfile;
	global $runverbose;
	global $filename;
	global $Vdata;
	global $sql;
	//global $db;
	global $enabledelete;
	global $enableselfpubdel;
	global $releases;
	global $countme;
	global $countself;
	global $countself2;
	global $countloop;
	
	echo "\033[1;44;33m Checking releases from $txtfile   \033[1;0;36m\n";
				if ($runverbose =="verbose"){
					echo "\033[1;0;36mSearching for the following: \n\033[1;0;31m";
					echo str_replace("\r", '', $Vdata);
				}
				$start_time = MICROTIME(TRUE);	
				$db = new Db;
				$rel = $db->query($sql);
				$foundcount = count($rel);
				$stop_time = MICROTIME(TRUE);	
				$time = round($stop_time - $start_time);
				if ($runverbose =="verbose"){
					echo "\033[1;0;36mScan Time: $time seconds\n";			
				}
				//echo " \n";
				$countloop = $countloop + count($rel);
				$countme = $countme + count($rel) ;		
				$minicount = 0;		

				if(count($rel) > 0) 
				{	
					echo "\033[1;1;33mDeleting ".count($rel)." release(s)\n";
					$foundcount = count($rel);
					if($enabledelete == "true") 
						{
							
							$start_time = MICROTIME(TRUE);
							foreach ($rel as $r)
							{
								$name = $r['name'];
								$minicount++;
								$releases->delete($r['ID']);
								if ($runverbose =="verbose"){
									echo "\033[1;0;32m    Delete $minicount of $foundcount: \033[1;0;37m$name\n";
								}
							}
							$stop_time = MICROTIME(TRUE);
							$time = round($stop_time - $start_time);
							if ($runverbose =="verbose"){
								echo "\033[1;0;36mDelete Time: $time seconds\n";				
								echo "\033[1;1;30mCleaning entries from bookinfo table....";
							}
							$db->query(sprintf("DELETE FROM `bookinfo` WHERE `title` REGEXP '{$Vdata}' and `categoryID` = 7020"));
							if ($runverbose =="verbose"){
								echo "\033[1;1;33m Done! \n\n\n\n\033[0;1;36m";
							} else {
								echo " \n\n\033[0;1;36m";
							}
					} else {	
					
							if ($runverbose =="verbose"){
							foreach ($rel as $r)
								{
									$minicount++;
									$name = $r['name'];
									echo "\033[1;0;32m    Found $minicount of $foundcount: \033[1;0;37m$name\n";
								}
							}	
							echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
							echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n";
							}	
				} else {
							if ($runverbose =="verbose"){
								echo "\033[1;1;33mNothing to delete! \n\n\n\n\033[0;1;36m";	
							} else {
								echo "\033[1;1;33mNothing to delete! \n\n\033[0;1;36m";
							}
						}
	//return $db->query("SELECT r.id, r.name, bi.id as bookid, bi.publisher from releases r join bookinfo bi on r.bookinfoID = bi.ID ");		
}

function EndCleanup()
{
		if($enabledelete == "true") 
			{
				global $db;
				global $countme;
				global $countself;
				global $countself2;
				global $countloop;
				echo "\033[1;1;30mFinal cleaning of orphaned entries in bookinfo table....";
				$db->query(sprintf("DELETE FROM bookinfo bi WHERE NOT EXISTS (SELECT r.ID from releases r WHERE r.bookinfoid = bi.id)"));
				echo "\033[1;1;33m Done! \n\n\n\033[1;1;33m";
			}
}

function EndReport()
{		
		global $countme;
		global $countself;
		global $countself2;
		global $countloop;
		echo "\033[0;1;35mFinished!\033[1;1;36m\n";
		$countme = $countme + $countself ;	
		echo "Deleted a total of \033[0;1;33m$countme \033[1;1;36mrelease(s) \n\n\n\033[1;0;36m";
}




?>
