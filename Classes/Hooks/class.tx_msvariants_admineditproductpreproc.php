<?php

class tx_msvariants_admineditproductpreproc {

  function adminEditProductPreProc(&$params, &$reference) {


    // TODO: this config should go somewhere else
    $this->msvariants['image_paths']['variants']['dir_images'] = 'uploads/tx_msvariants/images';  
    $this->msvariants['image_paths']['variants']['dir_variants'] = 'uploads/tx_msvariants/images/variants';  
    $this->msvariants['image_paths']['variants']['original'] = 'uploads/tx_msvariants/images/variants/original';  
    $this->msvariants['image_paths']['variants']['normal'] = 'uploads/tx_msvariants/images/variants/normal';  
    $this->msvariants['image_paths']['variants']['50'] = 'uploads/tx_msvariants/images/variants/50';  
    $this->msvariants['image_paths']['variants']['100'] = 'uploads/tx_msvariants/images/variants/100';  
    $this->msvariants['image_paths']['variants']['200'] = 'uploads/tx_msvariants/images/variants/200';  
    $this->msvariants['image_paths']['variants']['300'] = 'uploads/tx_msvariants/images/variants/300';  
    foreach($this->msvariants['image_paths']['variants'] as $path) {
      error_log('path: '.$ref->DOCUMENT_ROOT.$path);
      if (!is_dir($ref->DOCUMENT_ROOT.$path)) {
        t3lib_div::mkdir($ref->DOCUMENT_ROOT.$path);
      }
    }


    //----------------------------------------
    // VARIANTS TAB
    //----------------------------------------

    // product Attribute
    //if (!$reference->ms['MODULES']['DISABLE_PRODUCT_VARIANTS_TAB_IN_EDITOR']) {

    $product = $params['product'];

    // Get HTML template file
    if ($reference->conf['edit_variants_tmpl_path']) {
     $template=$reference->cObj->fileResource($reference->conf['edit_variants_tmpl_path']);
   } 
   else {
     $template=$reference->cObj->fileResource(t3lib_extMgm::siteRelPath('msvariants').'Templates/edit_variants.tmpl');
   }

    // Extract the subparts from the template
   $subparts=array();
   $subparts['template']=$reference->cObj->getSubpart($template, '###TEMPLATE###');
   $subparts['item_variant']=$reference->cObj->getSubpart($subparts['template'], '###ITEM_VARIANT###');
   $subparts['item_option']=$reference->cObj->getSubpart($subparts['template'], '###ITEM_OPTION###');


    // Get variants from db
    $sql_pa=$GLOBALS ['TYPO3_DB']->SELECTquery('vars.variant_id, vars.product_id, vars.variant_price, vars.variant_stock, vars.variant_sku, vattrs.attribute_id, vattrs.option_id, vattrs.option_value_id', // SELECT ...
      'tx_msvariants_domain_model_variants vars, tx_msvariants_domain_model_variantsattributes vattrs', // FROM ...
      "vars.product_id='".$product['products_id']."' and vars.variant_id=vattrs.variant_id", // WHERE.  // TODO should we take into consideration the language popt.language_id = 0?
      '', // GROUP BY...
      '', // ORDER BY...
      '' // LIMIT ...
      );

    $qry_pa=$GLOBALS ['TYPO3_DB']->sql_query($sql_pa);



        // Generate HTML for variants tab block if there are variants

    if ($product['products_id']) {

      if ($GLOBALS['TYPO3_DB']->sql_num_rows($qry_pa)>0) {

        $variants_data = array();
        $variants_attributes_data = array();
        while (($row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_pa)) != false) {
          if (!$variants_data[$row['variant_id']]) {
            $variants_data[$row['variant_id']] = array(
              'variant_id' => $row['variant_id'],
              'product_id' => $row['product_id'],
              'variant_price' => $row['variant_price'],
              'variant_stock' => $row['variant_stock'],
              'variant_sku' => $row['variant_sku'],
              );
          }

          $row['option_name'] = mslib_fe::getRealNameOptions($row['option_id']);
          $row['option_value_name'] = mslib_fe::getNameOptions($row['option_value_id']);
          $variants_attributes_data[$row['variant_id']][] = $row;
        }


            // Generate HTML for variants
        foreach($variants_data as $variant) {

          $variant_name = '';

              // generate HTML for variant's attributes
          $content_options = '';
          $markerArray = array();
          foreach($variants_attributes_data[$variant['variant_id']] as $variant_attribute) {

            $markerArray['OPTION_NAME'] = $variant_attribute['option_name'];
            $markerArray['OPTION_VALUE'] = $variant_attribute['option_value_name'];
            $content_options .= $reference->cObj->substituteMarkerArray($subparts['item_option'], $markerArray, '###|###');

            $variant_name .= $variant_attribute['option_value_name']." - ";
          }

              // generate HTML for variant details
          $markerArray = array();
          $markerArray['LABEL_VARIANT_NAME'] = $variant_name;
          $markerArray['VARIANT_ID'] = $variant['variant_id'];
          $markerArray['PRICE'] = $variant['variant_price'];
          $markerArray['STOCK'] = $variant['variant_stock'];
          $markerArray['SKU'] = $variant['variant_sku'];


          $content_item_variant = $reference->cObj->substituteMarkerArray($subparts['item_variant'], $markerArray, '###|###');
          $content_item_variant = $reference->cObj->substituteSubpart($content_item_variant, 'ITEM_OPTION', $content_options);

          $content_variants .= $content_item_variant;
        }


            // Generate HTML for variants tab block
        $markerArray = array();
        $markerArray['LABEL_HEADING_TAB_VARIANTS'] = 'VARIANTS';
          $markerArray['LABEL_PRICE'] = 'Price';
          $markerArray['LABEL_STOCK'] = 'Stock';
          $markerArray['LABEL_SKU'] = 'SKU';

        $content = $reference->cObj->substituteMarkerArray($subparts['template'], $markerArray, '###|###');
        $content = $reference->cObj->substituteSubpart($content, 'ITEM_VARIANT', $content_variants);
      }
      else {
        $markerArray = array();
        $markerArray['LABEL_HEADING_TAB_VARIANTS'] = 'VARIANTS';
                  $markerArray['LABEL_PRICE'] = 'Price';
          $markerArray['LABEL_STOCK'] = 'Stock';
          $markerArray['LABEL_SKU'] = 'SKU';

        $content = $reference->cObj->substituteMarkerArray($subparts['template'], $markerArray, '###|###');
        $content = $reference->cObj->substituteSubpart($content, 'ITEM_VARIANT', '');
      }

    }
    else {
      $markerArray = array();
      $markerArray['LABEL_HEADING_TAB_VARIANTS'] = 'VARIANTS';
                $markerArray['LABEL_PRICE'] = 'Price';
          $markerArray['LABEL_STOCK'] = 'Stock';
          $markerArray['LABEL_SKU'] = 'SKU';

      $content = $reference->cObj->substituteMarkerArray($subparts['template'], $markerArray, '###|###');
      $content = $reference->cObj->substituteSubpart($content, 'ITEM_VARIANT', '');
    }


    $params['plugins_extra_tab']['tabs_header'][] = '<li><a href="#product_variants">VARIANTS</a></li>';
    //$params['plugins_extra_tab']['tabs_content'][] =
    //  '<div style="display:none;" id="product_variants" class="tab_content">'.$res.'</div';
    $params['plugins_extra_tab']['tabs_content'][] = $content;



    //----------------------------------------
    // VARIANTS IMAGES TAB
    //----------------------------------------

    // product Attribute
    //if (!$reference->ms['MODULES']['DISABLE_PRODUCT_VARIANTS_TAB_IN_EDITOR']) {

    // Get HTML template file
    if ($reference->conf['edit_variants_images_tmpl_path']) {
      $template=$reference->cObj->fileResource($reference->conf['edit_variants_images_tmpl_path']);
    }
    else {
      $template=$reference->cObj->fileResource(t3lib_extMgm::siteRelPath('msvariants').'Templates/edit_variants_images.tmpl');
    }

    // Extract the subparts from the template
    $subparts=array();
    $subparts['template']=$reference->cObj->getSubpart($template, '###TEMPLATE###');
    $subparts['item_variant_images']=$reference->cObj->getSubpart($subparts['template'], '###ITEM_VARIANT_IMAGES###');
    $subparts['item_option']=$reference->cObj->getSubpart($subparts['template'], '###ITEM_OPTION###');


    // Get variants from db
    $sql_pa=$GLOBALS ['TYPO3_DB']->SELECTquery('vars.variant_id, vars.product_id, vars.variant_price, vars.variant_stock, vars.variant_sku, vars.image1, vars.image2, vars.image3, vars.image4, vars.image5, vattrs.attribute_id, vattrs.option_id, vattrs.option_value_id', // SELECT ...
      'tx_msvariants_domain_model_variants vars, tx_msvariants_domain_model_variantsattributes vattrs', // FROM ...
      "vars.product_id='".$product['products_id']."' and vars.variant_id=vattrs.variant_id", // WHERE.  // TODO should we take into consideration the language popt.language_id = 0?
      '', // GROUP BY...
      '', // ORDER BY...
      '' // LIMIT ...
      );

    $qry_pa=$GLOBALS ['TYPO3_DB']->sql_query($sql_pa);


    // Generate HTML for variants tab block if there are variants

    if ($product['products_id']) {

      if ($GLOBALS['TYPO3_DB']->sql_num_rows($qry_pa)>0) {

        $variants_data = array();
        $variants_attributes_data = array();
        while (($row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_pa)) != false) {
          if (!$variants_data[$row['variant_id']]) {
            $variants_data[$row['variant_id']] = array(
              'variant_id' => $row['variant_id'],
              'product_id' => $row['product_id'],
              'variant_price' => $row['variant_price'],
              'variant_stock' => $row['variant_stock'],
              'variant_sku' => $row['variant_sku'],
              'image1' => $row['image1'],
              'image2' => $row['image2'],
              'image3' => $row['image3'],
              'image4' => $row['image4'],
              'image5' => $row['image5']
              );
          }

          $row['option_name'] = mslib_fe::getRealNameOptions($row['option_id']);
          $row['option_value_name'] = mslib_fe::getNameOptions($row['option_value_id']);
          $variants_attributes_data[$row['variant_id']][] = $row;
        }


            // Generate HTML for variants
        $nVariant = 0;
        $currColor = '';
        $newColor = false;
        foreach($variants_data as $variant) {

          $nVariant++;
          $variant_name = 'Variant: ';

              // generate HTML for variant's attributes
          $content_options = '';
          $markerArray = array();
          foreach($variants_attributes_data[$variant['variant_id']] as $variant_attribute) {

            if (strtoupper($variant_attribute['option_name']) == 'COLOR') {
              if ($variant_attribute['option_value_name'] != $currColor) {
                $currColor = $variant_attribute['option_value_name'];
                $newColor = true;
              }
              else {
                $newColor = false;
              }
            }

            //$markerArray['OPTION_NAME'] = $variant_attribute['option_name'];
            //$markerArray['OPTION_VALUE'] = $variant_attribute['option_value_name'];
            $markerArray['OPTION_NAME'] = '';
            $markerArray['OPTION_VALUE'] = '';

            $content_options .= $reference->cObj->substituteMarkerArray($subparts['item_option'], $markerArray, '###|###');

            $variant_name .= $variant_attribute['option_value_name']." - ";

          }

              // generate HTML for variant details
          $markerArray = array();

          //$markerArray['LABEL_VARIANT_NAME'] = $variant_name;
          //$markerArray['LABEL_SKU'] = 'SKU'
          //$markerArray['SKU'] = $variant['variant_sku'];
          $markerArray['LABEL_VARIANT_NAME'] = $newColor ? 'Color: '.$currColor : '';
          $markerArray['LABEL_SKU'] = '';
          $markerArray['SKU'] = '';

          $markerArray['VARIANT_ID'] = $variant['variant_id'];


          // images tab

          $images_tab_block='';
          for ($x=0; $x<$reference->ms['MODULES']['NUMBER_OF_PRODUCT_IMAGES']; $x++) {
//            $i=$x;
//            if ($i==0) {
//              $i='';
//            }
            $i = $x+1;
            $images_tab_block.='
            <div class="account-field" id="msEditProductInputImage_'.$i.'"'.(!$newColor ? 'style="display:none;"' : '').'>
              <label for="variants_image_'.$variant['variant_id'].'_'.$i.'">'.$reference->pi_getLL('admin_image').' '.$i.'</label>
              <div id="variants_image_'.$variant['variant_id'].'_'.$i.'">
                <noscript>
                  <input name="variants_image_'.$variant['variant_id'].'_'.$i.'" type="file" />
                </noscript>
              </div>
              <input name="ajax_variants_image_'.$variant['variant_id'].'_'.$i.'" id="ajax_variants_image_'.$variant['variant_id'].'_'.$i.'" type="hidden" value="" />';
              if ($_REQUEST['action']=='edit_product' and $variant['image'.$i]) {

//                $images_tab_block.='<img src="'.$folder.mslib_befe::getImagePath($variant['image'.$i], 'products', '50').'">';
                $filename = $variant['image'.$i];
                $images_tab_block.='<img src="'.$this->msvariants['image_paths']['variants']['50'].'/'.mslib_befe::getImagePrefixFolder($filename).'/'.$filename.'">';

                $images_tab_block.=' <a href="'.mslib_fe::typolink(',2002', '&tx_multishop_pi1[page_section]=admin_ajax&cid='.$_REQUEST['cid'].'&pid='.$_REQUEST['pid'].'&action=edit_product&delete_image=variants_image_'.$variant['variant_id'].'_'.$i).'" onclick="return confirm(\'Are you sure?\')"><img src="'.$reference->FULL_HTTP_URL_MS.'templates/images/icons/delete2.png" border="0" alt="'.$reference->pi_getLL('admin_delete_image').'"></a>';
              }
              $images_tab_block.='</div>';
            }

            $images_tab_block.='<script>
            jQuery(document).ready(function($) {';
            for ($x=0; $x<$reference->ms['MODULES']['NUMBER_OF_PRODUCT_IMAGES']; $x++) {
//              $i=$x;
//              if ($i==0) {
//                $i='';
//              }
              $i = $x+1;
              $images_tab_block.='
              var products_name=$("#products_name_0").val();
              var uploader'.$i.' = new qq.FileUploader({
                element: document.getElementById(\'variants_image_'.$variant['variant_id'].'_'.$i.'\'),
                action: \''.mslib_fe::typolink(',2002', '&tx_multishop_pi1[page_section]=custom_page').'\',
                params: {
                  products_name: products_name,
                  file_type: \'variants_image\'
                },
                template: \'<div class="qq-uploader">\' +
                \'<div class="qq-upload-drop-area"><span>'.$reference->pi_getLL('admin_label_drop_files_here_to_upload').'</span></div>\' +
                \'<div class="qq-upload-button">'.addslashes(htmlspecialchars($reference->pi_getLL('choose_image'))).'</div>\' +
                \'<ul class="qq-upload-list"></ul>\' +
                \'</div>\',
                onComplete: function(id, fileName, responseJSON){
                  var filenameServer = responseJSON[\'filename\'];
                  $("#ajax_variants_image_'.$variant['variant_id'].'_'.$i.'").val(filenameServer);
                },
                debug: false
              });';
            }
            $images_tab_block.='
            $(\'#products_name_0\').change(function() {
              var products_name=$("#products_name_0").val();';
              for ($x=0; $x<$reference->ms['MODULES']['NUMBER_OF_PRODUCT_IMAGES']; $x++) {
//                  $i=$x;
//                  if ($i==0) {
//                    $i='';
//                  }
                $i = $x+1;
                $images_tab_block.='
                uploader'.$i.'.setParams({
                 products_name: products_name,
                 file_type: \'products_image'.$i.'\'
               });';
             }
             $images_tab_block.='
           });
         });
       </script>';

       $markerArray['IMAGE_CODE'] = $images_tab_block;


       $content_item_variant_images = $reference->cObj->substituteMarkerArray($subparts['item_variant_images'], $markerArray, '###|###');
       $content_item_variant_images = $reference->cObj->substituteSubpart($content_item_variant_images, 'ITEM_OPTION', $content_options);

       $content_variants_images .= $content_item_variant_images;
     }


            // Generate HTML for variants tab block
     $markerArray = array();
     $markerArray['LABEL_HEADING_TAB_VARIANTS_IMAGES'] = 'VARIANTS IMAGES';

     $content2 = $reference->cObj->substituteMarkerArray($subparts['template'], $markerArray, '###|###');
     $content2 = $reference->cObj->substituteSubpart($content2, 'ITEM_VARIANT_IMAGES', $content_variants_images);


   }
 }


 $params['plugins_extra_tab']['tabs_header'][] = '<li><a href="#product_variants_images">VARIANTS IMAGES</a></li>';
    //$params['plugins_extra_tab']['tabs_content'][] =
    //  '<div style="display:none;" id="product_variants" class="tab_content">'.$res.'</div';
 $params['plugins_extra_tab']['tabs_content'][] = $content2;




}


}

?>
