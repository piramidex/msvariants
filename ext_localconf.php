<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Register hooks

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/multishop/scripts/admin_pages/includes/admin_edit_product.php']['adminEditProductPreProc'][] = 'tx_msvariants_admineditproductpreproc->adminEditProductPreProc';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/multishop/scripts/admin_pages/includes/admin_edit_product.php']['insertProductPostHook'][] = 'tx_msvariants_insertproductposthook->insertProductPostHook';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/multishop/scripts/admin_pages/includes/admin_edit_product.php']['updateProductPostHook'][] = 'tx_msvariants_updateproductposthook->updateProductPostHook';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/multishop/pi1/classes/class.mslib_fe.php']['updateCartProductPreHook'][] = 'tx_msvariants_updatecartproductprehook->updateCartProductPreHook';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/multishop/pi1/class.tx_multishop_pi1.php']['insertOrdersProductPreProc'][] = 'tx_msvariants_insertordersproductpreproc->insertOrdersProductPreProc';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/multishop/scripts/admin_pages/admin_edit_order.php']['editOrderListItemPreHook'][] = 'tx_msvariants_editorderlistitemprehook->editOrderListItemPreHook';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/multishop/pi1/classes/class.mslib_fe.php']['customAjaxPage'][] = 'tx_msvariants_customajaxpage->customAjaxPage';



?>
