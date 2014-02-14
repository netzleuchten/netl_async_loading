<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array(
	'tx_netlasyncloading_enable' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:netl_async_loading/locallang_db.xml:tt_content.tx_netlasyncloading_enable',
		'config' => array(
			'type' => 'check',
		)
	),
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_content','tx_netlasyncloading_enable;;;;1-1-1', 'list', 'after:hidden');

t3lib_extMgm::addStaticFile($_EXTKEY,'Configuration/TypoScript/Ajax', 'Async. Loading with AJAX');
t3lib_extMgm::addStaticFile($_EXTKEY,'Configuration/TypoScript/Esi', 'Async. Loading with ESI');
?>