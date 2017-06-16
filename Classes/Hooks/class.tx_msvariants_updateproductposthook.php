<?php


class tx_msvariants_updateproductposthook {

  function updateProductPostHook(&$params, &$ref) {
    
    error_log("updateProductPostHook - begin");

    $product_id = $params['products_id'];
    if (!$product_id) { return; }

    if ($this->existVariants($product_id)) {
      $this->updateVariants($ref);
    }
    else {
      $this->insertVariants($params, $ref);
    }

    error_log("updateProductPostHook - end");
    
  }


  private function existVariants($product_id) {
    
    $sql_pa=$GLOBALS ['TYPO3_DB']->SELECTquery('vars.variant_id', // SELECT ...
      'tx_msvariants_domain_model_variants vars, tx_msvariants_domain_model_variantsattributes vattrs', // FROM ...
      "vars.product_id='".$product_id."' and vars.variant_id=vattrs.variant_id", // WHERE.  // TODO should we take into consideration the language popt.language_id = 0?
      '', // GROUP BY...
      '', // ORDER BY...
      '' // LIMIT ...
      );

    $qry_pa=$GLOBALS ['TYPO3_DB']->sql_query($sql_pa);
    return $GLOBALS['TYPO3_DB']->sql_num_rows($qry_pa) > 0;
  }


  private function updateVariants(&$ref) {

    $variants_ids = $ref->post['variants_ids'];
    $variants_prices = $ref->post['variants_prices'];
    $variants_stocks = $ref->post['variants_stocks'];
    $variants_skus = $ref->post['variants_skus'];

    for($i=0; $i < count($variants_ids); $i++) {
      $update_array = array(
        'variant_id' => $variants_ids[$i],
        'variant_price' => $variants_prices[$i],
        'variant_stock' => $variants_stocks[$i],
        'variant_sku' => $variants_skus[$i]
      );

      for($j = 1; $j <= 5; $j++) {
        if ($ref->post['ajax_variants_image_'.$variants_ids[$i].'_'.$j]) {
          $update_array['image'.$j] = $ref->post['ajax_variants_image_'.$variants_ids[$i].'_'.$j];
        }
      }

      $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
        'tx_msvariants_domain_model_variants',
        'variant_id='.$variants_ids[$i], // TODO make this secure with fullQuoteStr
        $update_array
      );
    }

    //error_log("ajax variants images".print_R($ref->post['ajax_variants_image']))    
  }


  private function insertVariants(&$params, &$ref) {
    $insertHook = new tx_msvariants_insertproductposthook;
    $insertHook->insertProductPostHook($params, $ref);
  }


}


?>
