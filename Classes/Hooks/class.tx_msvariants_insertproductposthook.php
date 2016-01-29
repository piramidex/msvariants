<?php


class tx_msvariants_insertproductposthook {

  function insertProductPostHook(&$params, &$reference) {


    // First time product is created so variants must be generated
    // and updated with the form inputs.....
    // TODO update with the form inputs

    error_log("insertProductPostHook - begin!");

    $products_id = $params['products_id'];

    error_log("products_id: ".$params['products_id']);
    error_log("products_id: ".$products_id);

    // product Attribute
    //if (!$reference->ms['MODULES']['DISABLE_PRODUCT_VARIANTS_TAB_IN_EDITOR'])


    // Get attributes (option and options values) for product from db
    // and create variants based on attributes and insert them into db

    $output = '';

        $sql_pa=$GLOBALS ['TYPO3_DB']->SELECTquery('popt.required,popt.products_options_id, popt.products_options_name, popt.listtype, patrib.*', // SELECT ...
          'tx_multishop_products_options popt, tx_multishop_products_attributes patrib', // FROM ...
          "patrib.products_id='".$products_id."' and popt.language_id = '0' and patrib.options_id = popt.products_options_id", // WHERE.
          '', // GROUP BY...
          'patrib.sort_order_option_name, patrib.sort_order_option_value', // ORDER BY...
          '' // LIMIT ...
        );

        $qry_pa=$GLOBALS ['TYPO3_DB']->sql_query($sql_pa);

        if ($products_id) {

          if ($GLOBALS['TYPO3_DB']->sql_num_rows($qry_pa)>0) {

            $ctr=1;
            $options_data=array();
            $attributes_data=array();
            while (($row=$GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_pa))!=false) {
              $row['options_values_name']=mslib_fe::getNameOptions($row['options_values_id']);
              $options_data[$row['products_options_id']]=$row['products_options_name'];
              $attributes_data[$row['products_options_id']][]=$row;
            }

            if (count($options_data)) {


              $num_options = count($options_data);
              $options_ids = array_keys($options_data);
              $counters = array_fill(0, $num_options, 0);

              $limits = array();
              $k = 0;
              foreach ($options_ids as $option_id) {
                $limits[$k] = count($attributes_data[$option_id])-1;
                $k++;
              }

              $content_variants = '';

              $k = 0;
              while ($k < $num_options) {

                if ($k == 0) {

                  $variant_name = 'Variant: ';
                  $content_options = '';
                  $markerArray = array();

                  // insert (variant_id, product_id, price, stock, sku) into db
                  $variant_row = array(
                    'variant_id' => 0,
                    'product_id' => $products_id,
                    'variant_price' => 0.0,
                    'variant_stock' => 0,
                    'variant_sku' => '',
                  );


                  $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_msvariants_domain_model_variants', $variant_row);
                  error_log('variant row inserted res; '.$res);

                  // TODO there must be a better way to set the variant_id or rename uid
                  $variant_id = $GLOBALS['TYPO3_DB']->sql_insert_id();
                  $update_array = array( 'variant_id' => $variant_id);
                  $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
                    'tx_msvariants_domain_model_variants',
                    'uid='.$variant_id, // TODO make this secure with fullQuoteStr
                    $update_array
                  );

                  // insert list of options, values for the variant into db
                  for($i = ($num_options-1); $i >= 0; $i--) {

                    $attribute_id = $attributes_data[$options_ids[$i]][$counters[$i]]['products_attributes_id'];
                    $option_id = $attributes_data[$options_ids[$i]][$counters[$i]]['options_id'];
                    $option_value_id = $attributes_data[$options_ids[$i]][$counters[$i]]['options_values_id'];
                    $option_name = $attributes_data[$options_ids[$i]][$counters[$i]]['products_options_name'];
                    $option_value = $attributes_data[$options_ids[$i]][$counters[$i]]['options_values_name'];
                    $variant_name .= $attributes_data[$options_ids[$i]][$counters[$i]]['options_values_name']." - ";

                    // insert (variant_id, ..., option, value) into db
                    $variant_option_row = array(
                      'variant_id' => $variant_id,
                      'product_id' => $products_id,
                      'attribute_id' => $attribute_id,
                      'option_id' => $option_id,
                      'option_value_id' => $option_value_id,
                    );
                    $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_msvariants_domain_model_variantsattributes', $variant_option_row);
                    error_log('variantattribute row inserted res; '.$res);
                  }
                }

                $counters[$k]++;

                if ($counters[$k] > $limits[$k]) {
                  $counters[$k] = 0;
                  $k++;
                }
                elseif ($k > 0) {
                  $k = 0;
                }
              }
            }
          }
        }

        error_log("insertProductPostHook - end");

  }


}


?>
