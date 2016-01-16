<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Multishop Variants');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_msvariants_domain_model_variants', 'EXT:msvariants/Resources/Private/Language/locallang_csh_tx_msvariants_domain_model_variants.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_msvariants_domain_model_variants');
$GLOBALS['TCA']['tx_msvariants_domain_model_variants'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variants',
		'label' => 'product_id',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'product_id,product_attribute_id,option_id,option_value_id,variant_price,variant_stock,variant_sku,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Variants.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_msvariants_domain_model_variants.gif'
	),
);

require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('msvariants').'/Classes/Hooks/class.tx_msvariants_admineditproductpreproc.php');
