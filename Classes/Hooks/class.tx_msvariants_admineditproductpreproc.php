<?php

class tx_msvariants_admineditproductpreproc {

  function adminEditProductPreProc(&$params, &$reference) {

    xdebug_break();


    // now parse all the objects in the tmpl file
    if ($reference->conf['edit_variants_tmpl_path']) {
    	$template=$reference->cObj->fileResource($reference->conf['edit_variants_tmpl_path']);
    } else {
    	$template=$reference->cObj->fileResource(t3lib_extMgm::siteRelPath('msvariants').'Templates/edit_variants.tmpl');
    }
    // Extract the subparts from the template
    $subparts=array();
    $subparts['template']=$reference->cObj->getSubpart($template, '###TEMPLATE###');
    $subparts['item_variant']=$reference->cObj->getSubpart($subparts['template'], '###ITEM_VARIANT###');
    $subparts['item_option']=$reference->cObj->getSubpart($subparts['template'], '###ITEM_OPTION###');


    $product = $params['product'];


    // product Attribute
    //if (!$reference->ms['MODULES']['DISABLE_PRODUCT_VARIANTS_TAB_IN_EDITOR']) {

    $variants_tab_block='';



    // get attributes (option and options values) for product from db
    // and create variants based on attributes and insert them into db

    $output = '';



        $sql_pa=$GLOBALS ['TYPO3_DB']->SELECTquery('popt.required,popt.products_options_id, popt.products_options_name, popt.listtype, patrib.*', // SELECT ...
          'tx_multishop_products_options popt, tx_multishop_products_attributes patrib', // FROM ...
          "patrib.products_id='".$product['products_id']."' and popt.language_id = '0' and patrib.options_id = popt.products_options_id", // WHERE.
          '', // GROUP BY...
          'patrib.sort_order_option_name, patrib.sort_order_option_value', // ORDER BY...
          '' // LIMIT ...
        );
        $qry_pa=$GLOBALS ['TYPO3_DB']->sql_query($sql_pa);
        $variants_tab_block.='<table width="100%" cellpadding="2" cellspacing="2" id="product_attributes_table">';
        ///$js_select2_cache='';
        ///$js_select2_cache_options=array();
        ///$js_select2_cache_values=array();
        ///$js_select2_cache='
        ///<script type="text/javascript">
        ///  var attributesSearchOptions=[];
        ///  var attributesSearchValues=[];
        ///  var attributesOptions=[];
        ///  var attributesValues=[];'."\n";
        if ($product['products_id']) {
          if ($GLOBALS['TYPO3_DB']->sql_num_rows($qry_pa)>0) {
            $ctr=1;
            $options_data=array();
            $attributes_data=array();
            while (($row=$GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_pa))!=false) {
              $row['options_values_name']=mslib_fe::getNameOptions($row['options_values_id']);
              $options_data[$row['products_options_id']]=$row['products_options_name'];
              $attributes_data[$row['products_options_id']][]=$row;
              // js cache
              ///$js_select2_cache_options[$row['products_options_id']]='attributesOptions['.$row['products_options_id'].']={id:"'.$row['products_options_id'].'", text:"'.$row['products_options_name'].'"}';
              ///$js_select2_cache_values[$row['options_values_id']]='attributesValues['.$row['options_values_id'].']={id:"'.$row['options_values_id'].'", text:"'.$row['options_values_name'].'"}';
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
                  for($i = ($num_options-1); $i >= 0; $i--) {
                    //$res .= $counters[$i]." ";
                    //$res .= $attributes_data[$options_ids[$i]][$counters[$i]]['options_values_name'] . " - ";
                    $markerArray['OPTION_NAME'] = $attributes_data[$options_ids[$i]][$counters[$i]]['products_options_name'];
                    $markerArray['OPTION_VALUE'] = $attributes_data[$options_ids[$i]][$counters[$i]]['options_values_name'];
                    $content_options .= $reference->cObj->substituteMarkerArray($subparts['item_option'], $markerArray, '###|###');

                    $variant_name .= $attributes_data[$options_ids[$i]][$counters[$i]]['options_values_name']." - ";
                  }
                  //$res .= "\n";

                  $markerArray = array();
                  $markerArray['LABEL_VARIANT_NAME'] = $variant_name;
                  $markerArray['LABEL_PRICE'] = 'Price';
                  $markerArray['LABEL_STOCK'] = 'Stock';
                  $markerArray['LABEL_SKU'] = 'SKU';
                  $markerArray['PRICE'] = '120.0';
                  $markerArray['STOCK'] = '10';
                  $markerArray['SKU'] = 'sku-variant-test';

                  $content_item_variant = $reference->cObj->substituteMarkerArray($subparts['item_variant'], $markerArray, '###|###');
                  $content_item_variant = $reference->cObj->substituteSubpart($content_item_variant, 'ITEM_OPTION', $content_options);

                  $content_variants .= $content_item_variant;

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


              $markerArray = array();
              $markerArray['LABEL_HEADING_TAB_VARIANTS'] = 'VARIANTS';

              $content = $reference->cObj->substituteMarkerArray($subparts['template'], $markerArray, '###|###');
              $content = $reference->cObj->substituteSubpart($content, 'ITEM_VARIANT', $content_variants);


              foreach ($options_data as $option_id=>$option_name) {

                if (!isset($group_row_type) || $group_row_type=='even_group_row') {
                  $group_row_type='odd_group_row';
                } else {
                  $group_row_type='even_group_row';
                }



                foreach ($attributes_data[$option_id] as $attribute_data) {
                  if (!isset($item_row_type) || $item_row_type=='even_item_row') {
                    $item_row_type='odd_item_row';
                  } else {
                    $item_row_type='even_item_row';
                  }


                  // create and insert variant into db
                  $variant = array(
                    'product_id' => $attribute_data['products_id'],
                    'product_attribute_id' => $attribute_data['products_attributes_id'],
                    'option_id' => $attribute_data['options_id'],
                    'option_value_id' => $attribute_data['options_values_id'],
                    'variant_price' => 0.0,
                    'variant_stock' => 0,
                    'variant_sku' => '',
                  );
                  //$variant = ;

                  $insertArray=array();
                  /*$insertArray['product_id'] = ;
                  $insertArray['product_attribute_id'] = ;
                  $insertArray['option_id'] = ;
                  $insertArray['options_values_id'] = ;
                  $insertArray['variant_price'] = ;
                  $insertArray['variant_stock'] = ;
                  $insertArray['variant_sku'] = ;*/


                  $insertArray['cruser_id']=$GLOBALS['TSFE']->fe_user->user['uid'];

                  xdebug_break();

                  //$query=$GLOBALS['TYPO3_DB']->INSERTquery('tx_msvariants_domain_model_variants', $variant);
                  //$res=$GLOBALS['TYPO3_DB']->sql_query($query);

                  //$GLOBALS['TYPO3_DB']->debugOutput = true;
                  $res.=$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_msvariants_domain_model_variants', $variant);

                  ///$prodid=$GLOBALS['TYPO3_DB']->sql_insert_id(); // last inserteed raw id


                  // generate html for variant






                  $variants_tab_block.='<div class="wrap-attributes-item '.$item_row_type.'" id="item_product_attribute_'.$attribute_data['products_attributes_id'].'" rel="'.$attribute_data['products_attributes_id'].'">';
                  $variants_tab_block.='<table>';
                  $variants_tab_block.='<tr class="option_row">';
                  $variants_tab_block.='<td class="product_attribute_option">';
                  $variants_tab_block.='<input type="hidden" name="tx_multishop_pi1[options][]" id="option_'.$attribute_data['products_attributes_id'].'" class="product_attribute_options" value="'.$option_id.'" style="width:200px" />';
                  $variants_tab_block.='<input type="hidden" name="tx_multishop_pi1[is_manual_options][]" id="manual_option_'.$attribute_data['products_attributes_id'].'" value="0" />';
                  $variants_tab_block.='<input type="hidden" name="tx_multishop_pi1[pa_id][]" value="'.$attribute_data['products_attributes_id'].'" />';
                  /*$variants_tab_block.='<select name="tx_multishop-pi1[options][]" id="option_'.$attribute_data['products_attributes_id'].'" class="product_attribute_options">';
                  $variants_tab_block.='<option value="">'.$reference->pi_getLL('admin_label_choose_option').'</option>';
                  // fetch attributes options
                  $str=$GLOBALS ['TYPO3_DB']->SELECTquery('*', // SELECT ...
                    'tx_multishop_products_options', // FROM ...
                    'language_id = 0', // WHERE.
                    '', // GROUP BY...
                    'sort_order', // ORDER BY...
                    '' // LIMIT ...
                  );
                  $qry=$GLOBALS ['TYPO3_DB']->sql_query($str);
                  while (($row2=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry))!=false) {
                    if ($row2['products_options_id']==$option_id) {
                      $variants_tab_block.='<option value="'.$row2['products_options_id'].'" selected="selected">'.$row2['products_options_name'].'</option>';
                    } else {
                      $variants_tab_block.='<option value="'.$row2['products_options_id'].'">'.$row2['products_options_name'].'</option>';
                    }
                  }
                  $variants_tab_block.='</select>';*/
                  $variants_tab_block.='</td>';
                  $variants_tab_block.='<td class="product_attribute_value">';
                  $variants_tab_block.='<input type="hidden" name="tx_multishop_pi1[attributes][]" id="attribute_'.$attribute_data['products_attributes_id'].'" class="product_attribute_values" value="'.$attribute_data['options_values_id'].'" style="width:200px" />';
                  $variants_tab_block.='<input type="hidden" name="tx_multishop_pi1[is_manual_attributes][]" id="manual_attributes_'.$attribute_data['products_attributes_id'].'" value="0" />';
                  /*$variants_tab_block.='<select name="tx_multishop_pi1[attributes][]" id="attribute_'.$attribute_data['products_attributes_id'].'" class="product_attribute_values">';
                  $variants_tab_block.='<option value="">'.$reference->pi_getLL('admin_label_choose_attribute').'</option>';
                  // fetch values
                  $str2=$GLOBALS ['TYPO3_DB']->SELECTquery('optval.*', // SELECT...
                    'tx_multishop_products_options_values as optval, tx_multishop_products_options_values_to_products_options as optval2opt', // FROM...
                    'optval2opt.products_options_id = '.$option_id.' and optval2opt.products_options_values_id = optval.products_options_values_id and optval.language_id = 0', // WHERE...
                    '', // GROUP BY...
                    'optval2opt.sort_order', // ORDER BY...
                    '' // LIMIT...
                  );
                  $qry2=$GLOBALS ['TYPO3_DB']->sql_query($str2);
                  while (($row3=$GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry2))!=false) {
                    if ($row3['products_options_values_id']==$attribute_data['options_values_id']) {
                      $variants_tab_block.='<option value="'.$row3['products_options_values_id'].'" selected="selected">'.$row3['products_options_values_name'].'</option>';
                    } else {
                      $variants_tab_block.='<option value="'.$row3['products_options_values_id'].'">'.$row3['products_options_values_name'].'</option>';
                    }
                  }
                  $variants_tab_block.='</select>';*/
                  $variants_tab_block.='</td>';
                  $variants_tab_block.='<td class="product_attribute_prefix">';
                  $variants_tab_block.='<select name="tx_multishop_pi1[prefix][]">';
                  $variants_tab_block.='<option value="">&nbsp;</option>';
                  $variants_tab_block.='<option value="+"'.($attribute_data['price_prefix']=='+' ? ' selected="selected"' : '').'>+</option>';
                  $variants_tab_block.='<option value="-"'.($attribute_data['price_prefix']=='-' ? ' selected="selected"' : '').'>-</option>';
                  $variants_tab_block.='</select>';
                  //$variants_tab_block.='<input type="text" name="tx_multishop_pi1[prefix][]" value="'.$attribute_data['price_prefix'].'" />';
                  $variants_tab_block.='</td>';
                  // recalc price to display
                  $attributes_tax=mslib_fe::taxDecimalCrop(($attribute_data['options_values_price']*$product_tax_rate)/100);
                  $attribute_price_display=mslib_fe::taxDecimalCrop($attribute_data['options_values_price'], 2, false);
                  $attribute_price_display_incl=mslib_fe::taxDecimalCrop($attribute_data['options_values_price']+$attributes_tax, 2, false);
                  $variants_tab_block.='<td>
                        <div class="msAttributesField">'.mslib_fe::currency().' <input type="text" id="display_name" name="display_name" class="msAttributesPriceExcludingVat" value="'.$attribute_price_display.'"><label for="display_name">'.$reference->pi_getLL('excluding_vat').'</label></div>
                        <div class="msAttributesField">'.mslib_fe::currency().' <input type="text" name="display_name" id="display_name" class="msAttributesPriceIncludingVat" value="'.$attribute_price_display_incl.'"><label for="display_name">'.$reference->pi_getLL('including_vat').'</label></div>
                        <div class="msAttributesField hidden"><input type="hidden" name="tx_multishop_pi1[price][]" value="'.$attribute_data['options_values_price'].'" /></div>
                      </td>';
                  $variants_tab_block.='<td class="product_attribute_price"><input type="button" value="'.htmlspecialchars($reference->pi_getLL('delete')).'" class="msadmin_button delete_product_attributes"></td>';
                  $variants_tab_block.='</tr>';
                  $variants_tab_block.='</table>';
                  $variants_tab_block.='</div>';
                }
                $variants_tab_block.='</div><div class="add_new_attributes"><input type="button" class="msadmin_button add_new_attributes_values" value="'.$reference->pi_getLL('admin_add_new_value').' [+]" rel="'.$option_id.'" /></div>';
                $variants_tab_block.='</li>';
              }
              $variants_tab_block.='</ul></td>';
              $variants_tab_block.='</tr>';
            }
          }
          ///$count_js_cache_options=count($js_select2_cache_options);
          ///$count_js_cache_values=count($js_select2_cache_values);
          ///if ($count_js_cache_options) {
          ///  $js_select2_cache.=implode(";\n", $js_select2_cache_options);
          ///}
          ///if ($count_js_cache_values) {
          ///  if ($count_js_cache_options) {
          ///    $js_select2_cache.=";\n";
          ///  }
          ///  $js_select2_cache.=implode(";\n", $js_select2_cache_values).";\n";
          ///}
        }
        ///$js_select2_cache.='</script>';
        ///if (!empty($js_select2_cache)) {
        ///  $GLOBALS['TSFE']->additionalHeaderData['js_select2_cache']=$js_select2_cache;
        ///}
        $variants_tab_block.='<tr id="add_attributes_holder">
            <td colspan="5">&nbsp;</td>
        </tr>';
        $variants_tab_block.='<tr id="add_attributes_button">
            <td colspan="5" align="right"><input id="addAttributes" type="button" class="msadmin_button" value="'.$reference->pi_getLL('admin_add_new_attribute').' [+]"></td>
        </tr>
        </table>
        <script>
        $(document).on("keyup", ".msAttributesPriceExcludingVat", function() {
          productPrice(true, $(this));
        });
        $(document).on("keyup", ".msAttributesPriceIncludingVat", function() {
          productPrice(false, $(this));
        });
        </script>
        ';






    // display editing form for variants



/*

    $sql_pa=$GLOBALS ['TYPO3_DB']->SELECTquery('popt.required,popt.products_options_id, popt.products_options_name, popt.listtype, patrib.*', // SELECT ...
      'tx_multishop_products_options popt, tx_multishop_products_attributes patrib', // FROM ...
      "patrib.products_id='".$product['products_id']."' and popt.language_id = '0' and patrib.options_id = popt.products_options_id", // WHERE.
      '', // GROUP BY...
      'patrib.sort_order_option_name, patrib.sort_order_option_value', // ORDER BY...
      '' // LIMIT ...
    );
    $qry_pa=$GLOBALS ['TYPO3_DB']->sql_query($sql_pa);

    if ($product['products_id']) {
      if ($GLOBALS['TYPO3_DB']->sql_num_rows($qry_pa)>0) {
        $ctr=1;
        $options_data=array();
        $variants_data=array();
        while (($row=$GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_pa))!=false) {
          $row['options_values_name']=mslib_fe::getNameOptions($row['options_values_id']);
          $options_data[$row['products_options_id']]=$row['products_options_name'];
          $attributes_data[$row['products_options_id']][]=$row;
          // js cache
          ///$js_select2_cache_options[$row['products_options_id']]='attributesOptions['.$row['products_options_id'].']={id:"'.$row['products_options_id'].'", text:"'.$row['products_options_name'].'"}';
          ///$js_select2_cache_values[$row['options_values_id']]='attributesValues['.$row['options_values_id'].']={id:"'.$row['options_values_id'].'", text:"'.$row['options_values_name'].'"}';
        }
        if (count($options_data)) {
          $variants_tab_block.='<tr id="product_attributes_content_row">';
          $variants_tab_block.='<td colspan="5"><ul id="products_attributes_items">';
          foreach ($options_data as $option_id=>$option_name) {
            if (!isset($group_row_type) || $group_row_type=='even_group_row') {
              $group_row_type='odd_group_row';
            } else {
              $group_row_type='even_group_row';
            }
            $variants_tab_block.='<li id="products_attributes_item_'.$option_id.'" alt="'.$option_name.'" class="products_attributes_item '.$group_row_type.'">
            <span class="option_name">'.$option_name.' <a href="#" class="items_wrapper_folded">unfold</a></span>
            <div class="items_wrapper">
            ';
            foreach ($attributes_data[$option_id] as $attribute_data) {
              if (!isset($item_row_type) || $item_row_type=='even_item_row') {
                $item_row_type='odd_item_row';
              } else {
                $item_row_type='even_item_row';
              }


              // create and insert variant into db
              $variant = array(
                'product_id' => $attribute_data['products_id'],
                'product_attribute_id' => $attribute_data['products_attributes_id'],
                'option_id' => $attribute_data['options_id'],
                'option_value_id' => $attribute_data['options_values_id'],
                'variant_price' => 0.0,
                'variant_stock' => 0,
                'variant_sku' => '',
              );
              //$variant = ;

              $insertArray=array();
              /*$insertArray['product_id'] = ;
              $insertArray['product_attribute_id'] = ;
              $insertArray['option_id'] = ;




*/

    $v = mslib_fe::getProductOptions($params['product']['products_id']);
    foreach($v as $w) {
      $variants_tab_block .= $w[0];
    }



    $params['plugins_extra_tab']['tabs_header'][] = '<li><a href="#product_variants">VARIANTS</a></li>';
    //$params['plugins_extra_tab']['tabs_content'][] =
    //  '<div style="display:none;" id="product_variants" class="tab_content">'.$res.'</div';
    $params['plugins_extra_tab']['tabs_content'][] = $content;
  }


}


?>
