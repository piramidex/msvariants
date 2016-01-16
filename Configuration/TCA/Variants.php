<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['tx_msvariants_domain_model_variants'] = array(
	'ctrl' => $GLOBALS['TCA']['tx_msvariants_domain_model_variants']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, product_id, product_attribute_id, option_id, option_value_id, variant_price, variant_stock, variant_sku',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, product_id, product_attribute_id, option_id, option_value_id, variant_price, variant_stock, variant_sku, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_msvariants_domain_model_variants',
				'foreign_table_where' => 'AND tx_msvariants_domain_model_variants.pid=###CURRENT_PID### AND tx_msvariants_domain_model_variants.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
	
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),

		'product_id' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variants.product_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			)
		),
		'product_attribute_id' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variants.product_attribute_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			)
		),
		'option_id' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variants.option_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			)
		),
		'option_value_id' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variants.option_value_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			)
		),
		'variant_price' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variants.variant_price',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'double2,required'
			)
		),
		'variant_stock' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variants.variant_stock',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			)
		),
		'variant_sku' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variants.variant_sku',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		
	),
);
