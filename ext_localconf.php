<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Register hooks

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/multishop/scripts/admin_pages/includes/admin_edit_product.php']['adminEditProductPreProc'][] = 'tx_msvariants_admineditproductpreproc->adminEditProductPreProc';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/multishop/scripts/admin_pages/includes/admin_edit_product.php']['insertProductPostHook'][] = 'tx_msvariants_insertproductposthook->insertProductPostHook';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/multishop/scripts/admin_pages/includes/admin_edit_product.php']['updateProductPostHook'][] = 'tx_msvariants_updateproductposthook->updateProductPostHook';

?>
