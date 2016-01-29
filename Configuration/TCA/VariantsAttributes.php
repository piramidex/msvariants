<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['tx_msvariants_domain_model_variantsattributes'] = array(
	'ctrl' => $GLOBALS['TCA']['tx_msvariants_domain_model_variantsattributes']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, variant_id, product_id, attribute_id, option_id, option_value_id',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, variant_id, product_id, attribute_id, option_id, option_value_id, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
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
				'foreign_table' => 'tx_msvariants_domain_model_variantsattributes',
				'foreign_table_where' => 'AND tx_msvariants_domain_model_variantsattributes.pid=###CURRENT_PID### AND tx_msvariants_domain_model_variantsattributes.sys_language_uid IN (-1,0)',
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

		'variant_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variantsattributes.variant_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'product_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variantsattributes.product_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'attribute_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variantsattributes.attribute_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'option_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variantsattributes.option_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'option_value_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:msvariants/Resources/Private/Language/locallang_db.xlf:tx_msvariants_domain_model_variantsattributes.option_value_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		
	),
);
