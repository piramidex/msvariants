<?php

class tx_msvariants_lib {

  function getVariants($product_id) {

    // Get variants from db
    $sql_pa=$GLOBALS['TYPO3_DB']->SELECTquery('vars.variant_id, vars.product_id, vars.variant_price, vars.variant_stock, vars.variant_sku, vars.image1, vars.image2, vars.image3, vars.image4, vars.image5, vattrs.attribute_id, vattrs.option_id, vattrs.option_value_id', // SELECT ...
      'tx_msvariants_domain_model_variants vars, tx_msvariants_domain_model_variantsattributes vattrs', // FROM ...
      "vars.product_id='".$product_id."' and vars.variant_id=vattrs.variant_id", // WHERE.  // TODO should we take into consideration the language popt.language_id = 0?
      '', // GROUP BY...
      '', // ORDER BY...
      '' // LIMIT ...
      );
    $qry_pa=$GLOBALS ['TYPO3_DB']->sql_query($sql_pa);

    $variants = array();
    while (($row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_pa)) != false) {
      $variants[] = array(
        'variant_id' => $row['variant_id'],
        'product_id' => $row['product_id'],
        'variant_price' => $row['variant_price'],
        'variant_stock' => $row['variant_stock'],
        'variant_sku' => $row['variant_sku'],
        'image1' => $row['image1'],
        'image2' => $row['image2'],
        'image3' => $row['image3'],
        'image4' => $row['image4'],
        'image5' => $row['image5'],
        'option_id' => $row['option_id'],
        'option_value_id' => $row['option_value_id'],
        'option_name' => mslib_fe::getRealNameOptions($row['option_id']),
        'option_value_name' => mslib_fe::getNameOptions($row['option_value_id'])
        );
    }

    return $variants;

  }


  function getMapAttributesToVariants($product_id) {

    $qry = $GLOBALS['TYPO3_DB']->SELECTquery(
      'variant_id, option_id, option_value_id',         // SELECT
      'tx_msvariants_domain_model_variantsattributes',  // FROM
      'product_id='.$product_id,                        // WHERE  // TODO should we take into consideration the language popt.language_id = 0?
      '', // GROUP BY...
      '', // ORDER BY...
      '' // LIMIT ...
      );
    $res = $GLOBALS ['TYPO3_DB']->sql_query($qry);

    $variants = array();
    while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) != false) {
      if (!isset($variants[$row['variant_id']])) {
        $variants[$row['variant_id']] = array();
      }
      $variants[$row['variant_id']][$row['option_id']] = $row['option_value_id'];
    }

    foreach($variants as $k => $v) {
      ksort($variants[$k]);
    }

    $reverse = array();
    foreach($variants as $k => $v) {
      $reverse[tx_msvariants_lib::assocArrayAsKey($v)] = $k;
    }

    error_log('reverse: '.print_r($reverse, true));

    return $reverse;
  }


  function getMapVarIdsToVariants($product_id) {

    // Get variants from db
    $sql_pa=$GLOBALS['TYPO3_DB']->SELECTquery('vars.variant_id, vars.product_id, vars.variant_price, vars.variant_stock, vars.variant_sku, vars.image1, vars.image2, vars.image3, vars.image4, vars.image5, vattrs.attribute_id, vattrs.option_id, vattrs.option_value_id', // SELECT ...
      'tx_msvariants_domain_model_variants vars, tx_msvariants_domain_model_variantsattributes vattrs', // FROM ...
      "vars.product_id='".$product_id."' and vars.variant_id=vattrs.variant_id", // WHERE.  // TODO should we take into consideration the language popt.language_id = 0?
      '', // GROUP BY...
      '', // ORDER BY...
      '' // LIMIT ...
      );
    $qry_pa=$GLOBALS ['TYPO3_DB']->sql_query($sql_pa);

    $variants = array();
    while (($row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_pa)) != false) {
      $variants[$row['variant_id']] = array(
        'variant_id' => $row['variant_id'],
        'product_id' => $row['product_id'],
        'variant_price' => $row['variant_price'],
        'variant_stock' => $row['variant_stock'],
        'variant_sku' => $row['variant_sku'],
        'image1' => $row['image1'],
        'image2' => $row['image2'],
        'image3' => $row['image3'],
        'image4' => $row['image4'],
        'image5' => $row['image5'],
        'option_id' => $row['option_id'],
        'option_value_id' => $row['option_value_id'],
        'option_name' => mslib_fe::getRealNameOptions($row['option_id']),
        'option_value_name' => mslib_fe::getNameOptions($row['option_value_id'])
        );
    }

    return $variants;

  }


function assocArrayAsKey($array) {
  $key = '';
  ksort($array);
  foreach($array as $k => $v) {
    $key .= $k.'|'.$v.'|';
  }
  return $key;
}


}

?>
