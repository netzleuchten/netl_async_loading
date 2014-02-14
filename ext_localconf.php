<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] =
		array('USER_INT', 'EXT:netl_async_loading/Classes/Hooks/ContentReplacementHook.php:&user_Tx_NetlAsyncLoading_Hooks_ContentReplacementHook');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] =
		array('USER', 'EXT:netl_async_loading/Classes/Hooks/ContentReplacementHook.php:&user_Tx_NetlAsyncLoading_Hooks_ContentReplacementHook');

// TODO: Let user decide to disable this in extension configuration 

if(version_compare(TYPO3_version,'4.7.0','>=')) {
	$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = '_';
}

if(empty($GLOBALS['TYPO3_CONF_VARS']['FE']['cHashExcludedParameters'])) {
	$GLOBALS['TYPO3_CONF_VARS']['FE']['cHashExcludedParameters'] = '_';
} else {
	$GLOBALS['TYPO3_CONF_VARS']['FE']['cHashExcludedParameters'] = 
		$GLOBALS['TYPO3_CONF_VARS']['FE']['cHashExcludedParameters'].',_';
}

?>