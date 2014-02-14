<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Holger McCloy, Leon Dietsch, netzleuchten UG
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class user_Tx_NetlAsyncLoading_Hooks_ContentReplacementHook {

	public function cObjGetSingleExt($name, $conf, $TSkey, &$cObj) {

		$content = NULL;
		$gpVarsParams = '';

		$extTsConf = &$GLOBALS['TSFE']->tmpl->setup['config.']['netlAsyncLoading.'];
		
		if(	
			(
				$cObj->data['tx_netlasyncloading_enable'] 
				|| ($extTsConf['replaceAllUserInt'] == 1 && $name == 'USER_INT')
			)
			&& !isset($GLOBALS['tx_netlasyncloading_uids'][$cObj->data['uid']])
		) {
		
			$GLOBALS['tx_netlasyncloading_uids'][$cObj->data['uid']] = TRUE;

			// merge configuration from the given cobject
			if( (bool) $conf['tx_netlAsyncLoading.'] ) {
				$extTsConf = array_merge($extTsConf,$conf['tx_netlAsyncLoading.']);
			}

			// if this is not the type num to deliver content, replace the content element
			if ($GLOBALS['TSFE']->type != $extTsConf['typeNum']) {

				// Asynchronous loading
				if ($extTsConf['contentReplacement']) {
					
					// Get/Post vars
					if ($extTsConf['GPVars']) {
						// Read all get/post parameters. Namespaces configured via TS
						$gpVars = t3lib_div::compileSelectedGetVarsFromArray($extTsConf['GPVars'], array());
						// Convert to url
						$gpVarsParams = t3lib_div::removeXSS(t3lib_div::implodeArrayForUrl('', $gpVars));
					}
					
					// Return custom content
					$typolink_conf = array(
						'no_cache'			=> 0,
						'parameter'			=> $GLOBALS['TSFE']->id,//$cObj->data['pid'],
						'useCacheHash'		=> 1,
						'additionalParams'	=> '&type='.$extTsConf['typeNum'].
											   '&uid='.$cObj->data['uid'].
											   '&netlAsyncContent=1'.
											   $gpVarsParams
						);

					$url = $cObj->typoLink_URL($typolink_conf);
					
					if( (bool)$url ) {
						
						$protocol = $_SERVER['HTTPS'] != "on" ? 'http' : 'https';
						$domain = $_SERVER['SERVER_NAME']; // $_SERVER['HTTP_X_FORWARDED_HOST'] $_SERVER['HTTP_X_FORWARDED_SERVER']

						$content = str_replace(
							array(
								'###REQUEST_URI###',
								'###FULL_REQUEST_URI###',
								'###DATA_UID###',
								'###DATA_PID###'
							),
							array(
								$url,
								$protocol . '://' . $domain . '/' . $url,
								$cObj->data['uid'],
								$cObj->data['pid']
							),
							$extTsConf['contentReplacement']
						);
					}
				}
			}
		}

		// if there is no content set, we just render the content
		if($content === NULL) {
			// Return Content
			$contentObject = $cObj->getContentObject($name);
			if($contentObject) {
				$content = $contentObject->render($conf);
			}
		}

		return $content;
	}

}
