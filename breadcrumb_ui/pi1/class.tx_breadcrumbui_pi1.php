<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Sivakumar, SPRITLE Software <sivakumarv@spritle.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Easy Breadcrumb' for the 'breadcrumb_ui' extension.
 *
 * @author	Sivakumar, SPRITLE Software <sivakumarv@spritle.com>
 * @package	TYPO3
 * @subpackage	tx_breadcrumbui
 */
class tx_breadcrumbui_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_breadcrumbui_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_breadcrumbui_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'breadcrumb_ui';	// The extension key.
	var $pi_checkCHash = true;
	var $rootId=121;
  var $hide=array();
	var $unlink=array();
  var $blank_flag=1;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
  
  
function getFriendlyURL($uid,$pageTitle)
{

$page_id = $uid;
$lang = $GLOBALS["TSFE"]->sys_language_uid;
$configurations['useCacheHash'] = 1; // make it a caching link
$configurations['parameter'] = $page_id; // target page id or external Url, Email, etc.
$cObject = t3lib_div::makeInstance('tslib_cObj');
$cObject = $this->cObj;
$result = ($cObject->typolink($pageTitle, $configurations)); 
return $result;

}

function getTitle($pageid)
{
$query = $GLOBALS['TYPO3_DB']->exec_SELECTquery(" pid , title , nav_title",  " pages ", " uid=".$pageid);
		 $data= mysql_fetch_array($query);

	if($data['nav_title']!='')
	{
	return $data['nav_title'];
	}
	else
	{
	return $data['title'];
	}


}


function getRoot($pageid,$i)
 {

$query = $GLOBALS['TYPO3_DB']->exec_SELECTquery("uid, pid , title, tx_breadcrumbui_breadcrumb_unlink , tx_breadcrumbui_breadcrumb_hide ,nav_hide ",  " pages ", " uid=".$pageid);
		 $data= mysql_fetch_array($query);
		 
		 if(($data['tx_breadcrumbui_breadcrumb_unlink']==1 && $data['nav_hide']==1) || $data['tx_breadcrumbui_breadcrumb_unlink']==1)
		 
{
		$this->unlink[$i]=$data['uid'];


			}               	 
		
		else if($data['tx_breadcrumbui_breadcrumb_hide']==1)
				 {
			 $this->hide[$i]=$data['uid'];
			}

//	echo "<br>". trim($data['pid'])." --".$data[3];;
//print_r($this->hide);
		return $data['pid'];   
		 }





function displayErros()
{
  echo "Root id is not defined";
}
function main($content, $conf) {	 
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
    
    
    
	  if($conf['rootId']!='')
		{
		$rootId=$conf['rootId'];
    } else {echo "Root id is not defined:";}


	    $breadcrumb=array();
			 $breadcrumb_title=array();
			 $temp_rootID=1;
        $pageTitle='';	
                     
					
			 $current_pageID=$GLOBALS['TSFE']->id;
			 //$content=$current_pageID;
			 $counter=1;
                       $breadcrumb[0]=$GLOBALS['TSFE']->id;  //add current page to breadcrumb
		       $i=0;
				while($temp_rootId!=$this->rootId)
				{
                $temp_rootId=$this->getRoot($current_pageID,$i);
		//$content.=$temp_rootId;
		$current_pageID=$temp_rootId;
                $breadcrumb[$counter]=$current_pageID;
		$counter++;
		$i++;
				}
       

   //process breadcrumb aray
                $counter=0;  //reset counter value
		foreach($breadcrumb as $id)
		{
		$pageTitle= $this->getTitle($id);
		$breadcrumb_title[$counter]=$pageTitle;
		$counter++;
		}
	        
             
              // display breadcrumb
		$count_title=count($breadcrumb_title)-1;
                $tmp_count_title=$count_title;
		$counter=0;
                
               $content='<div class="breadcrumbs"> <p>';
               $needle=0;
               $rm_flag=0;
               while($count_title>=0)
			{
	                if($counter==0)
			{
			 
			$f_url=$this->getFriendlyURL($breadcrumb[$count_title],$breadcrumb_title[$count_title]);
                           
		       $content.=$this->getFriendlyURL($breadcrumb[$count_title],$breadcrumb_title[$count_title]); //index.php?id=$breadcrumb[$count_title] 
			 }
 			else
			{
                           if(in_array($breadcrumb[$count_title],$this->hide))
				{ 
					$count_title--; 
                                        $rm_flag=1; continue;//break;
					}
					       if(in_array($breadcrumb[$count_title],$this->unlink))
						   {
						$content.="&raquo; ". $breadcrumb_title[$count_title]." ";
						   }
						 else
							{
				if($breadcrumb[$count_title]==$GLOBALS['TSFE']->id )	
								 $content.=" &raquo; ".$breadcrumb_title[$count_title];
                                                          else 
								{
								 
								$f_url=$this->getFriendlyURL($breadcrumb[$count_title],$breadcrumb_title[$count_title]); 								 
				  	  		     $content.=" &raquo; ".$this->getFriendlyURL($breadcrumb[$count_title],$breadcrumb_title[$count_title]); 
								}
							}
				} 
		       $count_title--;
			$counter++;
			} 
	


//Add current page to breadcrumb

$content.="</p></div>";
		return $this->pi_wrapInBaseClass($content);
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/breadcrumb_ui/pi1/class.tx_breadcrumbui_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/breadcrumb_ui/pi1/class.tx_breadcrumbui_pi1.php']);
}

?>