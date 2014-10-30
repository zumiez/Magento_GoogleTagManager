<?php
/**
 * * Adding CMS blocks to add js for the dataLayer component
 */
/* @var $this Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

// CMS BLOCK category_datalayer
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

// CMS BLOCK cart_datalayer

ob_start(); ?>

    <script>
        (function(){
            var remove_element = document.querySelector('.removeFromCart');
            $$('.removeFromCart').invoke('observe','click', function(event){
                event.preventDefault();
                var href = $j(this).attr('href');
                var target = $j(this).attr('target');
                if (typeof dataLayer != 'undefined') {
                    dataLayer.push({
                        'event' : 'remove-from-cart',
                        'productId' : this.dataset.sku,
                        'productName' : this.dataset.name
                    });
                }
                setTimeout(function(){
                    // short delay to allow tracking to complete
                    window.open(href,(!target?"_self":target));
                }, 200);
            })
        })();
    </script>

<?php
$contents = ob_get_clean();
$aStores = array(0);
$block_id = 'cart_datalayer';
Mage::getModel('cms/block')
    ->load($block_id)
    ->setTitle('Cart DataLayer')
    ->setIdentifier($block_id)
    ->setIsActive(true)
    ->setStores($aStores)
    ->setContent($contents)
    ->save();

$installer->endSetup();
