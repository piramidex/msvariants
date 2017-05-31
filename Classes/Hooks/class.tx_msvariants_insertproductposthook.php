<?php


class tx_msvariants_insertproductposthook {

  private $attribute_id = array();
  private $option_id = array();
  private $option_value_id = array();


  function insertProductPostHook(&$params, &$ref) {
    error_log("insertProductPostHook - begin");

    $product_id = $params['products_id'];
    $this->init($product_id);

    foreach($this->variantsFromPost($ref->post) as $v) {
      $variant_id = $this->insertVariantInDb($v, $product_id);

      foreach($v['attributes'] as $a) {

        $this->insertVariantAttributeInDb(
          $variant_id, $product_id,
          $this->option_id($a['option_name_or_id']),
          $this->option_value_id($a['option_value_name_or_id']));
      }
    }

    error_log("insertProductPostHook - end");
  }


  private function variantsFromPost($post) {
    error_log("variantsFromPost() called");

    $variants = array();

    $variants_ids = $post['variants_ids'];
    $variants_prices = $post['variants_prices'];
    $variants_stocks = $post['variants_stocks'];
    $variants_skus = $post['variants_skus'];
    $combinations = $post['combinations'];

    for($i=0; $i < count($variants_ids); $i++) {

      $attributes = array();
      foreach(explode('||', $combinations[$i]) as $c) {
        $temp = explode('|', $c);
        $attr = array();
        $attr['option_name_or_id'] = $temp[0];
        $attr['option_value_name_or_id'] = $temp[1];
        $attributes[] = $attr;
      }

      $variants[] = array(
        'id' => $variants_ids[$i],
        'price' => $variants_prices[$i],
        'stock' => $variants_stocks[$i],
        'sku' => $variants_skus[$i],
        'attributes' => $attributes
      );
    }

    error_log("variants: ".print_r($variants, true));

    return $variants;
  }


  private function insertVariantInDb($variant, $product_id) {
    error_log('insertVariantInDb() called');

    $insert_array = array(
      'variant_id' => 0,
      'product_id' => $product_id,
      'variant_price' => $variant['price'],
      'variant_stock' => $variant['stock'],
      'variant_sku' => $variant['sku']);
    error_log('insert_array: '.print_r($insert_array, true));

    $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_msvariants_domain_model_variants', $insert_array);
    error_log('variant row inserted res: '.$res);

    $variant_id = $GLOBALS['TYPO3_DB']->sql_insert_id();
    $update_array = array( 'variant_id' => $variant_id);
    $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
      'tx_msvariants_domain_model_variants',
      'uid='.$variant_id, // TODO make this secure with fullQuoteStr
       $update_array);
    error_log('variant id updated res: '.$res);

    return $variant_id;

  }


  private function insertVariantAttributeInDb($variant_id, $product_id, $option_id, $option_value_id) {
    error_log('insertVariantAttributeInDb() called');

    $insert_array = array(
      'variant_id' => $variant_id,
      'product_id' => $product_id,
      'attribute_id' => $this->attribute_id($option_id, $option_value_id),
      'option_id' => $option_id,
      'option_value_id' => $option_value_id);
    error_log('insert_array: '.print_r($insert_array, true));

    $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_msvariants_domain_model_variantsattributes', $insert_array);
    error_log('variant attribute row inserted res: '.$res);
  }


  private function attribute_id($option_id, $option_value_id) {
    error_log('attribute_id() called');
    return $this->attribute_id[$option_id.'|'.$option_value_id];
  }


  private function option_id($name_or_id) {
    if (isset($this->$option_id[$name_or_id])) {
      return $this->$option_id[$name_or_id];
    }
    else {
      return $name_or_id;
    }
  }


  private function option_value_id($name_or_id) {
    if (isset($this->$option_value_id[$name_or_id])) {
      return $this->option_value_id[$name_or_id];
    }
    else {
      return $name_or_id;
    }
  }


  private function init($product_id) {
    error_log('init() called');

    $sql_pa=$GLOBALS ['TYPO3_DB']->SELECTquery(
      'popt.required,popt.products_options_id, popt.products_options_name, popt.listtype, patrib.*', // SELECT ...
      'tx_multishop_products_options popt, tx_multishop_products_attributes patrib', // FROM ...
      "patrib.products_id='".$product_id."' and popt.language_id = '0' and patrib.options_id = popt.products_options_id", // WHERE.
      '', // GROUP BY...
      'patrib.sort_order_option_name, patrib.sort_order_option_value', // ORDER BY...
      ''); // LIMIT ...

    $qry_pa=$GLOBALS ['TYPO3_DB']->sql_query($sql_pa);
    if ($GLOBALS['TYPO3_DB']->sql_num_rows($qry_pa)>0) {
        while (($row=$GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_pa))!=false) {
          $this->attribute_id[$row['products_options_id'].'|'.$row['options_values_id']] = $row['products_attributes_id'];
          $this->option_id[$row['products_options_name']] = $row['products_options_id'];
          $this->option_value_id[$row['options_values_id']] = mslib_fe::getNameOptions($row['options_values_id']);
        }
    }

    error_log('$this->attribute_id: '.print_r($this->attribute_id, true));
    error_log('$this->option_id: '.print_r($this->option_id, true));
    error_log('$this->option_value_id: '.print_r($this->option_value_id, true));
  }

}


?>
