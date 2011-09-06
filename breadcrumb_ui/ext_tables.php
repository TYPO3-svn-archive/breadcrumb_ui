<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:breadcrumb_ui/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');
  
  $tempColumns = array (
	'tx_breadcrumbui_breadcrumb_hide' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:breadcrumb_ui/locallang_db.xml:pages.tx_breadcrumbui_breadcrumb_hide',		
		'config' => array (
			'type' => 'check',
		)
	),
	'tx_breadcrumbui_breadcrumb_unlink' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:breadcrumb_ui/locallang_db.xml:pages.tx_breadcrumbui_breadcrumb_unlink',		
		'config' => array (
			'type' => 'check',
		)
	),
);


t3lib_div::loadTCA('pages');
t3lib_extMgm::addTCAcolumns('pages',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('pages','tx_breadcrumbui_breadcrumb_hide;;;;1-1-1, tx_breadcrumbui_breadcrumb_unlink');


?>