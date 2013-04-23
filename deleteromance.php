<?php

//This script allows you to delete properly all releases which match some criteria
//The nzb, covers and all linked records will be deleted properly.

//##############################################################################
//  If you want to run and maintain your own lists, you need to
//  edit the path below to location your .txt files. It is a lot of work
//  to maintain these lists. I recommend using my lists. If you insist, you
//  can copy these .txt files from my Github and edit away.

$deletefilepath = "https://raw.github.com/hernandito/NN-DeleteRomance/master";

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

$releases = new Releases();
$db = new Db;


$countme = 0;
$countself = 0;
$countself2 = 0;
//########################################
//  Start Delete Self Publishers Code
//########################################


			//#############################################################
			//                                                           //
			//     Delete Books w/ covers and w/out Publisher            //
			//                                                           //
			//#############################################################

			echo "\n\n\033[1;44;33m Checking Books w/ Covers and w/out Publisher \033[1;0;33m\n";

			$start_time = MICROTIME(TRUE);
					$sql = "SELECT r.ID, r.BOOKINFOID, r.NAME FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher = '' and bi.cover=1";
					$rel = $db->query($sql);


					$countme = $countme + count($rel) ;

		if(count($rel) > 0)
			{	echo "\033[1;1;33mFound ".count($rel)." release(s)\n\033[1;0;36m";
						if($enableselfpubdel == "true")
							{
								$start_time = MICROTIME(TRUE);
								foreach ($rel as $r)
								{
									$countself2 = $countself2 + 1;
									$name = $r['NAME'];
									$releases->delete($r['ID']);
									echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
									
								}
								$stop_time = MICROTIME(TRUE);
								$time = round($stop_time - $start_time);
								echo "\033[1;0;36mDelete Time: $time seconds\n";
								echo "\033[1;1;33mDeleted $countself2 release(s)\n";
								echo "\033[1;1;30mCleaning entries from bookinfo table....";
								$db->query(sprintf("DELETE FROM `bookinfo` WHERE `publisher` = '' and cover=1"));
								echo "\033[1;1;33m Done! \n\n\n\n\n\n\033[1;1;33m";
						} else	 {
								foreach ($rel as $r)
								{
									$countself2 = $countself2 + 1;
									$name = $r['NAME'];
									echo "\033[1;0;32m    Found: \033[1;0;37m$name\n";
								}
								echo "\033[1;1;33mFound $countself2 release(s)\n";
								echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
								echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n\n\n";
								}
			}
			else {
							echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;1;33m";
						}
			//#############################################################

			//#############################################################
			//                                                           //
			//     Delete Where Author is Publisher (Thanks Afly!!)      //
			//                                                           //
			//#############################################################			
			echo "\033[1;44;33m Checking for Author and Publisher Matches \033[1;0;36m\n";
			echo "\033[1;1;36mSearching...\n";
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
									echo "\033[1;0;32m    "    .$book['name']. " -\033[1;1;35m " .$book['publisher']. "\n\033[1;0;36m";
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
					echo "\033[1;0;37m---------------------------------------------\n";
					echo "\033[1;0;32mGreen = \033[1;0;37mBook Name      \033[1;1;35mMagenta = \033[1;0;37mPublisher \n";
					echo "\033[1;1;33m\nDeleted $countself release(s) \n\033[1;0;36m";
					if ($enableselfpubdel == "false")
						{ 	
							echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
							echo "\033[0;1;36m Setting needs editing in script. "; 
						}
					echo "\033[1;0;36m \n\n\n\n\n\n";
			}	else { 
					echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;1;33m";
			}

			
//#############################################################





//############################################
//  Start Deleting Of Searched Terms Code
//############################################
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

							echo "\033[1;44;33m Checking releases from Publishers1.txt \033[1;0;36m\n";

							$Vdata = file_get_contents("$deletefilepath/publisher1.txt");
							echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
							echo "$Vdata \n\033[1;1;33m";
							$start_time = MICROTIME(TRUE);
							$sql = "SELECT r.ID, r.BOOKINFOID, r.name FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher  REGEXP '{$Vdata}'";

							$rel = $db->query($sql);
							$stop_time = MICROTIME(TRUE);
							$time = round($stop_time - $start_time);
							echo "\033[1;0;36mScan Time: $time seconds\n";

							$countme = $countme + count($rel) ;

							if(count($rel) > 0) 
							{	echo "\033[1;1;33mDeleting ".count($rel)." release(s)\n";
								if($enabledelete == "true") 
									{
										$start_time = MICROTIME(TRUE);
										foreach ($rel as $r)
										{
											$name = $r['name'];
											$releases->delete($r['ID']);
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										$stop_time = MICROTIME(TRUE);
										$time = round($stop_time - $start_time);
										echo "\033[1;0;36mDelete Time: $time seconds\n";				
										
										echo "\033[1;1;30mCleaning entries from bookinfo table....";
										$db->query(sprintf("DELETE FROM `bookinfo` WHERE `publisher` REGEXP '{$Vdata}'"));
										echo "\033[1;1;33m Done! \n\n\n\n\n\n\033[1;1;33m";
								} else {	
										foreach ($rel as $r)
										{
											$name = $r['name'];
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
										echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n\n\n";							}	
							} else {
										echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;1;33m";				
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
					 
							echo "\033[1;44;33m Checking releases from Publisher2.txt \033[1;0;36m\n";

							$Vdata = file_get_contents("$deletefilepath/publisher2.txt");

							echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
							echo "$Vdata \n\033[1;1;33m";
							
							$sql = "SELECT r.ID, r.BOOKINFOID, r.name FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher  REGEXP '{$Vdata}'";

							$rel = $db->query($sql);
							$stop_time = MICROTIME(TRUE);	
							$time = round($stop_time - $start_time);
							echo "\033[1;0;36mScan Time: $time seconds\n";			
							
							$countme = $countme + count($rel) ;				

							if(count($rel) > 0) 
							{	echo "\033[1;1;33mDeleting ".count($rel)." release(s)\n";
								if($enabledelete == "true") 
									{
										$start_time = MICROTIME(TRUE);
										foreach ($rel as $r)
										{
											$name = $r['name'];
											$releases->delete($r['ID']);
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										$stop_time = MICROTIME(TRUE);
										$time = round($stop_time - $start_time);
										echo "\033[1;0;36mDelete Time: $time seconds\n";				
										
										echo "\033[1;1;30mCleaning entries from bookinfo table....";
										$db->query(sprintf("DELETE FROM `bookinfo` WHERE `publisher` REGEXP '{$Vdata}'"));
										echo "\033[1;1;33m Done! \n\n\n\n\n\n\033[1;1;33m";
								} else {	
										foreach ($rel as $r)
										{
											$name = $r['name'];
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
										echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n\n\n";							}	
							} else {
										echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;1;33m";				
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
					 
							echo "\033[1;44;33m Checking releases from Publishers3.txt \033[1;0;36m\n";

							$Vdata = file_get_contents("$deletefilepath/publisher3.txt");
							echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
							echo "$Vdata \n\033[1;1;33m";
			$start_time = MICROTIME(TRUE);
							$sql = "SELECT r.ID, r.BOOKINFOID, r.name FROM releases r JOIN bookinfo bi ON bi.id = r.bookinfoid WHERE bi.publisher  REGEXP '{$Vdata}'";

							$rel = $db->query($sql);
							$stop_time = MICROTIME(TRUE);	
							$time = round($stop_time - $start_time);
							echo "\033[1;0;36mScan Time: $time seconds\n";			
							
							$countme = $countme + count($rel) ;				

							if(count($rel) > 0) 
							{	echo "\033[1;1;33mDeleting ".count($rel)." release(s)\n";
								if($enabledelete == "true") 
									{
										$start_time = MICROTIME(TRUE);
										foreach ($rel as $r)
										{
											$name = $r['name'];
											$releases->delete($r['ID']);
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										$stop_time = MICROTIME(TRUE);
										$time = round($stop_time - $start_time);
										echo "\033[1;0;36mDelete Time: $time seconds\n";				
										
										echo "\033[1;1;30mCleaning entries from bookinfo table....";
										$db->query(sprintf("DELETE FROM `bookinfo` WHERE `publisher` REGEXP '{$Vdata}'"));
										echo "\033[1;1;33m Done! \n\n\n\n\n\n\033[1;1;33m";
								} else {	
										foreach ($rel as $r)
										{
											$name = $r['name'];
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
										echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n\n\n";							}	
							} else {
										echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;1;33m";				
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
					 
							echo "\033[1;44;33m Checking releases from Keywords.txt \033[1;0;36m\n";

							$Vdata = file_get_contents("$deletefilepath/keywords1.txt");
							
							echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
							echo "$Vdata \n\033[1;1;33m";
			$start_time = MICROTIME(TRUE);				
							$sql = "Select `ID`, `name` from `releases` where `categoryID` = 7020 and `searchname` REGEXP '{$Vdata}'";
							
							$rel = $db->query($sql);
							$stop_time = MICROTIME(TRUE);	
							$time = round($stop_time - $start_time);
							echo "\033[1;0;36mScan Time: $time seconds\n";			
							
							$countme = $countme + count($rel) ;				

							if(count($rel) > 0) 
							{	echo "\033[1;1;33mDeleting ".count($rel)." release(s)\n";
								if($enabledelete == "true") 
									{
										$start_time = MICROTIME(TRUE);
										foreach ($rel as $r)
										{
											$name = $r['name'];
											$releases->delete($r['ID']);
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										$stop_time = MICROTIME(TRUE);
										$time = round($stop_time - $start_time);
										echo "\033[1;0;36mDelete Time: $time seconds\n";				
										
										echo "\033[1;1;30mCleaning entries from bookinfo table....";
										$db->query(sprintf("DELETE FROM `bookinfo` WHERE `title` REGEXP '{$Vdata}' and `categoryID` = 7020"));
										echo "\033[1;1;33m Done! \n\n\n\n\n\n\033[1;1;33m";
								} else {	
										foreach ($rel as $r)
										{
											$name = $r['name'];
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
										echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n\n\n";
										}	
							} else {
										echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;1;33m";				
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
					 
							echo "\033[1;44;33m Checking releases from Authors1.txt \033[1;0;36m\n";

							$Vdata = file_get_contents("$deletefilepath/authors1.txt");

							echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
							echo "$Vdata \n\033[1;1;33m";
			$start_time = MICROTIME(TRUE);				
							$sql = "Select `ID`, `name` from `releases` where `searchname` REGEXP '{$Vdata}' and `categoryID` = 7020";

							$rel = $db->query($sql);
							$stop_time = MICROTIME(TRUE);	
							$time = round($stop_time - $start_time);
							echo "\033[1;0;36mScan Time: $time seconds\n";			
							
							$countme = $countme + count($rel) ;				

							if(count($rel) > 0) 
							{	echo "\033[1;1;33mDeleting ".count($rel)." release(s)\n";
								if($enabledelete == "true") 
									{
										$start_time = MICROTIME(TRUE);
										foreach ($rel as $r)
										{
											$name = $r['name'];
											$releases->delete($r['ID']);
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										$stop_time = MICROTIME(TRUE);
										$time = round($stop_time - $start_time);
										echo "\033[1;0;36mDelete Time: $time seconds\n";				
										
										echo "\033[1;1;30mCleaning entries from bookinfo table....";
										$db->query(sprintf("DELETE FROM `bookinfo` WHERE `author` REGEXP '{$Vdata}' and `categoryID` = 7020"));
										echo "\033[1;1;33m Done! \n\n\n\n\n\n\033[1;1;33m";
								} else {	
										foreach ($rel as $r)
										{
											$name = $r['name'];
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
										echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n\n\n";							}	
							} else {
										echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;1;33m";				
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
					 
							echo "\033[1;44;33m Checking releases from Authors2.txt \033[1;0;36m\n";

							$Vdata = file_get_contents("$deletefilepath/authors2.txt");

							echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
							echo "$Vdata \n\033[1;1;33m";
			$start_time = MICROTIME(TRUE);				
							$sql = "Select `ID`, `name` from `releases` where `searchname` REGEXP '{$Vdata}' and `categoryID` = 7020";

							$rel = $db->query($sql);
							$stop_time = MICROTIME(TRUE);	
							$time = round($stop_time - $start_time);
							echo "\033[1;0;36mScan Time: $time seconds\n";			
							
							$countme = $countme + count($rel) ;				

							if(count($rel) > 0) 
							{	echo "\033[1;1;33mDeleting ".count($rel)." release(s)\n";
								if($enabledelete == "true") 
									{
										$start_time = MICROTIME(TRUE);
										foreach ($rel as $r)
										{
											$name = $r['name'];
											$releases->delete($r['ID']);
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										$stop_time = MICROTIME(TRUE);
										$time = round($stop_time - $start_time);
										echo "\033[1;0;36mDelete Time: $time seconds\n";				
										
										echo "\033[1;1;30mCleaning entries from bookinfo table....";
										$db->query(sprintf("DELETE FROM `bookinfo` WHERE `author` REGEXP '{$Vdata}' and `categoryID` = 7020"));
										echo "\033[1;1;33m Done! \n\n\n\n\n\n\033[1;1;33m";
								} else {	
										foreach ($rel as $r)
										{
											$name = $r['name'];
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
										echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n\n\n";							}	
							} else {
										echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;1;33m";				
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
					 
							echo "\033[1;44;33m Checking releases from Brackets1.txt \033[1;0;36m\n";

							$Vdata = file_get_contents("$deletefilepath/brackets1.txt");
							
							echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
							echo "$Vdata \n\033[1;1;33m";
			$start_time = MICROTIME(TRUE);				
							$sql = "Select `ID`, `name` from `releases` where `searchname` REGEXP '{$Vdata}' and `categoryID` = 7020";

							$rel = $db->query($sql);
							$stop_time = MICROTIME(TRUE);	
							$time = round($stop_time - $start_time);
							echo "\033[1;0;36mScan Time: $time seconds\n";			
							
							$countme = $countme + count($rel) ;				

							if(count($rel) > 0) 
							{	echo "\033[1;1;33mDeleting ".count($rel)." release(s)\n";
								if($enabledelete == "true") 
									{
										$start_time = MICROTIME(TRUE);
										foreach ($rel as $r)
										{
											$name = $r['name'];
											$releases->delete($r['ID']);
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										$stop_time = MICROTIME(TRUE);
										$time = round($stop_time - $start_time);
										echo "\033[1;0;36mDelete Time: $time seconds\n";				
										
										echo "\033[1;1;30mCleaning entries from bookinfo table....";
										$db->query(sprintf("DELETE FROM `bookinfo` WHERE `title` REGEXP '{$Vdata}' and `categoryID` = 7020"));
										echo "\033[1;1;33m Done! \n\n\n\n\n\n\033[1;1;33m";
								} else {	
										foreach ($rel as $r)
										{
											$name = $r['name'];
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
										echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n\n\n";
										}	
							} else {
										echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;1;33m";				
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
					 
							echo "\033[1;44;33m Checking releases from Brackets2.txt \033[1;0;36m\n";

							$Vdata = file_get_contents("$deletefilepath/brackets2.txt");
							
							echo "\033[1;1;36mSearching for the following: \n\033[1;0;31m";
							echo "$Vdata \n\033[1;1;33m";
			$start_time = MICROTIME(TRUE);				
							$sql = "Select `ID`, `name` from `releases` where `searchname` REGEXP '{$Vdata}' and `categoryID` = 7020";

							$rel = $db->query($sql);
							$stop_time = MICROTIME(TRUE);	
							$time = round($stop_time - $start_time);
							echo "\033[1;0;36mScan Time: $time seconds\n";			
							
							$countme = $countme + count($rel) ;				

							if(count($rel) > 0) 
							{	echo "\033[1;1;33mDeleting ".count($rel)." release(s)\n";
								if($enabledelete == "true") 
									{
										$start_time = MICROTIME(TRUE);
										foreach ($rel as $r)
										{
											$name = $r['name'];
											$releases->delete($r['ID']);
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										$stop_time = MICROTIME(TRUE);
										$time = round($stop_time - $start_time);
										echo "\033[1;0;36mDelete Time: $time seconds\n";				
										
										echo "\033[1;1;30mCleaning entries from bookinfo table....";
										$db->query(sprintf("DELETE FROM `bookinfo` WHERE `title` REGEXP '{$Vdata}' and `categoryID` = 7020"));
										echo "\033[1;1;33m Done! \n\n\n\n\n\n\033[1;1;33m";
								} else {	
										foreach ($rel as $r)
										{
											$name = $r['name'];
											echo "\033[1;0;32m    Deleted: \033[1;0;37m$name\n";
										}
										echo "\033[1;41;33m Delete is set to $enabledelete. Nothing will be changed! ";
										echo "\033[0;1;36m Setting needs editing in script.\n\n\n\n\n\n";							}	
							} else {
										echo "\033[1;1;33mNothing to delete! \n\n\n\n\n\n\033[1;1;33m";				
									}				
					}
			//         End of delete from brackets2.txt                //
			//#############################################################

if($enabledelete == "true") 
	{
		echo "\033[1;1;30mFinal cleaning of orphaned entries in bookinfo table....";
		$db->query(sprintf("DELETE FROM bookinfo bi WHERE NOT EXISTS (SELECT r.ID from releases r WHERE r.bookinfoid = bi.id)"));
		echo "\033[1;1;33m Done! \n\n\n\033[1;1;33m";
	}

echo "\033[0;1;35mFinished!\033[1;1;36m\n";
echo "Deleted a total of \033[0;1;33m$countme \033[1;1;36mrelease(s) \n\n\n\033[1;0;36m";

?>

