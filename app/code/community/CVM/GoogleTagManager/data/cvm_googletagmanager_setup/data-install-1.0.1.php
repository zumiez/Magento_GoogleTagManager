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
                var product_id = $j(this).attr('data-prodid');
                if (typeof dataLayer != 'undefined') {
                    dataLayer.push({
                        'event' : 'product-click',
                        'sku' : product_id
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

// CMS BLOCK pdp_datalayer

ob_start(); ?>
    <script>
        document.observe("dom:loaded", function() {
            $('zumiez-add-to-bag').observe('click', function(event){
                var form_data_array = $('product_addtocart_form').serialize(true);
                var product_id = form_data_array.product;
                var product_qty = form_data_array.qty;
                if (typeof dataLayer != 'undefined') {
                    dataLayer.push({
                        'event': 'add-to-cart',
                        'sku': product_id,
                        'quantity': product_qty
                    });
                }
            });
        });
    </script>

<?php
$contents = ob_get_clean();
$aStores = array(0);
$block_id = 'pdp_datalayer';
Mage::getModel('cms/block')
    ->load($block_id)
    ->setTitle('PDP DataLayer')
    ->setIdentifier($block_id)
    ->setIsActive(true)
    ->setStores($aStores)
    ->setContent($contents)
    ->save();


// CMS BLOCK cart_datalayer

ob_start(); ?>
    <script>
        document.observe("dom:loaded", function() {
            $$('.removeFromCart').invoke('observe','click', function(event){
                event.preventDefault();
                var href = $(this).href;
                if (typeof dataLayer != 'undefined') {
                    dataLayer.push({
                        'event' : 'remove-from-cart',
                        'productId' : this.dataset.sku,
                        'productName' : this.dataset.name
                    });
                }
                setTimeout(function(){
                    // short delay to allow tracking to complete
                    window.open(href,"_self")
                }, 200);
            })
        });
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

// CMS BLOCK checkout_datalayer

ob_start(); ?>

<script>
    document.observe("dom:loaded", function() {
        if (typeof dataLayer != 'undefined') {
            // shipping step 1a
            var initialShippingRadio = $$('input:checked[type="radio"][name="shipping:form"]').pluck('value')[0];
            dataLayer.push({
                'event': 'view-shipping',
                'shippingOption': initialShippingRadio
            });

            $$('input[name="shipping:form"]').each(
                function (el) {
                    el.observe('click', function () {
                        dataLayer.push({
                            'event': 'shipping-option-update',
                            'shippingOption': this.value
                        });
                    });
                });

            // shipping step 1b

            $('co-shipping-method-form').on('change', '.radio', function (event) {
                var shippingMethodTitle = $$('input:checked[type=radio][name=shipping_method]')[0].dataset.title;
                var shippingMethodDescription = $$('input:checked[type=radio][name=shipping_method]')[0].dataset.description;
                var fullShippingDescription = shippingMethodTitle + ' ' + shippingMethodDescription;
                dataLayer.push({
                    'event': 'shipping-rate-option-update',
                    'shippingOption': fullShippingDescription
                });
            });

            // billing step 2
         /*   var initialBillingRadio  = $$('input:checked[type="radio"][name="payment[method]"]').pluck('value')[0];
            if (initialBillingRadio == 'cybersource_soap') initialBillingRadio = 'Credit Card';
            if (initialBillingRadio == 'paypal_express') initialBillingRadio = 'Paypal';
            dataLayer.push({
                'event': 'view-billing',
                'billingOption': initialBillingRadio
            });
        */
            $$('input[name="payment[method]"]').each(
                function (el) {
                    el.observe('click', function () {
                        var billingChoice = this.value;
                        if (billingChoice == 'cybersource_soap') billingChoice = 'Credit Card';
                        if (billingChoice == 'paypal_express') billingChoice = 'Paypal';

                        dataLayer.push({
                            'event': 'billing-option-update',
                            'billingOption': billingChoice
                        });
                    });
                }
            );
        }
    });
</script>


<?php
$contents = ob_get_clean();
$aStores = array(0);
$block_id = 'checkout_datalayer';
Mage::getModel('cms/block')
    ->load($block_id)
    ->setTitle('Checkout DataLayer')
    ->setIdentifier($block_id)
    ->setIsActive(true)
    ->setStores($aStores)
    ->setContent($contents)
    ->save();

$installer->endSetup();
