<?php
/**
 * * Adding CMS blocks to add js for the dataLayer component
 */
/* @var $this Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
ob_start(); ?>

<script>
    jQuery(document).ready(function($j){

        // FOR QUICKSHOP
        $j('body').on('click', '#zumiez-quick-add-to-bag', function(event){

            var form_data_array = $j('#product_addtocart_form').serializeArray();

            if ( form_data_array[3] !=undefined ) {
                var qs_prod_sku = form_data_array[0].value;
                var qs_prod_qty = form_data_array[3].value;

                dataLayer.push({
                    'event' : 'add-to-cart',
                    'sku' : qs_prod_sku,
                    'quantity' : qs_prod_qty
                });
            }

        });

        // FOR CATEGORY PAGE PRODUCTS
        $j('.item a').on('click', function(event){
            event.preventDefault();

            var href = $j(this).attr('href');
            var target = $j(this).attr('target');
            var image_el = $j(this).children('img');
            var img_class = $j(image_el).attr('class');
            if (!img_class) return;

            var prod_sku = img_class.match(/[\d]+$/)[0];

            if (typeof dataLayer != 'undefined') {
                dataLayer.push({
                    'event' : 'product-click',
                    'sku' : prod_sku
                });
            }
            // need slight delay to ensure data gets sent before page reloads
            setTimeout(function() {
                window.open(href,(!target?"_self":target));
            },300);
        });

    });
</script>

<?php
$contents = ob_get_clean();
$aStores = array(0);
$block_id = 'category_datalayer';

Mage::getModel('cms/block')
    ->load($block_id)
    ->setTitle('Category/Quickshop DataLayer')
    ->setIdentifier($block_id)
    ->setIsActive(true)
    ->setStores($aStores)
    ->setContent($contents)
    ->save();

$installer->endSetup();
