<?php
/*
Plugin Name: Bol.com API
Plugin URI: http://imjol.com
Description: Bol.com API integration with WordPress
Version: 1.0
Author: Md Toriqul Mowla Sujan
Author URI: http://imjol.com
License: GPLv2 or later
Text Domain: bolapi
*/

// include the orders and sync page
include_once('table.php');
include_once('pdf-generator.php');
include_once('orders.php');
include_once('sync.php');

// external css and js files
function enqueue_custom_styles() {
    // enqueue a css file in this plugin 
    wp_enqueue_style('bolapi-style', plugins_url('css/style.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'enqueue_custom_styles');

// Create order table in database
register_activation_hook(__FILE__, 'bol_com_plugin_activate');
// Delete order table from database
register_deactivation_hook(__FILE__, 'bol_com_plugin_deactivate');


// Adding a menu page in Dashboard menu
function bol_com_api_menu() {
    // Add menu page for Bol.com API
    add_menu_page(
        'Bol.com API',
        'Bol.com API',
        'manage_options',
        'bol_com_api_menu',
        'bol_com_api_menu_page'
    );

    // Add submenu page for Orders
    add_submenu_page(
        'bol_com_api_menu', // Parent menu slug
        'Orders', // Page title
        'Orders', // Menu title
        'manage_options',
        'bol_com_api_orders', // Submenu slug
        'bol_com_api_orders_page' // Callback function to display Orders page
    );

    // Add submenu page for Orders
    add_submenu_page(
        'bol_com_api_menu', // Parent menu slug
        'Sync Orders', // Page title
        'Sync Orders', // Menu title
        'manage_options',
        'bol_com_api_sync', // Submenu slug
        'bol_com_api_sync_page' // Callback function to display Orders page
    );

    // Add submenu page for Orders
    add_submenu_page(
        'bol_com_api_menu', // Parent menu slug
        'Modify Invoice', // Page title
        'Modify Invoice', // Menu title
        'manage_options',
        'bol_com_modify_invoice', // Submenu slug
        'bol_com_api_modify_invoice' // Callback function to modify invoice fields 
    );
}
add_action('admin_menu', 'bol_com_api_menu');

// Save the API settings
function save_bol_com_api_settings() {
    if (isset($_POST['save-settings'])) {
        $api_data = array();

        if (isset($_POST['api_key']) && isset($_POST['api_secret'])) {
            $api_keys = $_POST['api_key'];
            $api_secrets = $_POST['api_secret'];

            foreach ($api_keys as $key => $value) {
                $api_data[] = array(
                    'api_key' => $value,
                    'api_secret' => $api_secrets[$key]
                );
            }
        }

        // Save the data as JSON in wp_options
        update_option('bol_com_api_settings', json_encode($api_data));
    }
}

// Display the menu page with the repeatable fields and save the data
function bol_com_api_menu_page() {
    save_bol_com_api_settings();
    $api_data = get_option('bol_com_api_settings');
    $api_data = json_decode($api_data, true);
    ?>
    <div class="wrap">
        <h2>Bol.com API Settings</h2>
        <form method="post" action="">
            <div id="api-fields">
                <!-- Fields will be added here -->
                <?php
                if (!empty($api_data)) {
                    foreach ($api_data as $key => $value) {
                        ?>
                        <div class="api-section">
                            <h2>Bol.com API <?php echo $key + 1; ?></h2>
                            <label>API Key</label>
                            <input type="text" class="fancy-input" name="api_key[]" placeholder="API Key" value="<?php echo $value['api_key']; ?>" />
                            <label>API Secret</label>
                            <input type="text" class="fancy-input" name="api_secret[]" placeholder="API Secret" value="<?php echo $value['api_secret']; ?>" />
                            <button class="remove-field">Remove</button>
                            <hr>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <button class="fancy-button" id="add-new">Add New</button>
            <button type="submit" class="fancy-button" name="save-settings">Save</button>
        </form>
    </div>
    <script>
        jQuery(document).ready(function($) {
            var count = 1;

            $("#add-new").click(function(e) {
                e.preventDefault();
                var newField = '<div class="api-section"><h2>New API Settings</h2><label>API Key</label><input type="text" class="fancy-input" name="api_key[]" placeholder="API Key" /> <label>API Secret</label><input type="text" class="fancy-input" name="api_secret[]" placeholder="API Secret" /> <button class="bol-button remove-field">Remove</button><hr></div>';
                $("#api-fields").append(newField);
            });

            $("#api-fields").on("click", ".remove-field", function() {
                $(this).parent().remove();
            });
        });
    </script>
    <?php
}

// add some fields to modify the hardcoded texts of the invoice 
function bol_com_api_modify_invoice(){
    $invoice_title = get_option('invoice_title') == '' ? 'Factuur' : get_option('invoice_title');
    $invoice_order_date = get_option('invoice_order_date') == '' ? 'Orderdatum' : get_option('invoice_order_date');
    $invoice_order_number = get_option('invoice_order_number') == '' ? 'Ordernummer Bol' : get_option('invoice_order_number');
    $invoice_date = get_option('invoice_date') == '' ? 'Factuurdatum' : get_option('invoice_date');
    $invoice_number = get_option('invoice_number') == '' ? 'Factuurnummer' : get_option('invoice_number');
    $invoice_due_date = get_option('invoice_due_date') == '' ? 'Vervaldatum' : get_option('invoice_due_date');
    $invoice_paid_date = get_option('invoice_paid_date') == '' ? 'n.v.t. (betaald via Bol.com)' : get_option('invoice_paid_date');
    $invoice_product_name = get_option('invoice_product_name') == '' ? 'Productnaam' : get_option('invoice_product_name');
    $invoice_product_amount = get_option('invoice_product_amount') == '' ? 'Aantal' : get_option('invoice_product_amount');
    $invoice_product_price = get_option('invoice_product_price') == '' ? 'Prijs' : get_option('invoice_product_price');
    $invoice_product_tax = get_option('invoice_product_tax') == '' ? 'BTW' : get_option('invoice_product_tax');
    $invoice_product_subtotal = get_option('invoice_product_subtotal') == '' ? 'Subtotaal excl. BTW' : get_option('invoice_product_subtotal');
    $invoice_product_total = get_option('invoice_product_total') == '' ? 'Totaal excl. BTW' : get_option('invoice_product_total');
    $invoice_product_total_tax = get_option('invoice_product_total_tax') == '' ? 'Totaal incl. BTW' : get_option('invoice_product_total_tax');
    $invoice_whatsapp = get_option('invoice_whatsapp') == '' ? 'Whatsapp' : get_option('invoice_whatsapp');
    $invoice_whatsapp_number = get_option('invoice_whatsapp_number') == '' ? '+31 (0) 683 926 724' : get_option('invoice_whatsapp_number');
    $invoice_chamber_of_commerce = get_option('invoice_chamber_of_commerce') == '' ? 'KvK' : get_option('invoice_chamber_of_commerce');
    $invoice_chamber_of_commerce_number = get_option('invoice_chamber_of_commerce_number') == '' ? '83863966' : get_option('invoice_chamber_of_commerce_number');
    $invoice_phone_number = get_option('invoice_phone_number') == '' ? '+31 (0) 180 700 209' : get_option('invoice_phone_number');
    $invoice_vat_number = get_option('invoice_vat_number') == '' ? 'NL003883640B02' : get_option('invoice_vat_number');
    $invoice_bank = get_option('invoice_bank') == '' ? 'Bank' : get_option('invoice_bank');
    $invoice_bank_number = get_option('invoice_bank_number') == '' ? 'NL89KNAB0411483749' : get_option('invoice_bank_number');
    $invoice_bank_bic = get_option('invoice_bank_bic') == '' ? 'BIC' : get_option('invoice_bank_bic');
    $invoice_bank_bic_number = get_option('invoice_bank_bic_number') == '' ? 'KNABNL2H' : get_option('invoice_bank_bic_number');
    $invoice_address = get_option('invoice_address') == '' ? 'Cornelis Trooststraat 15 - 2923 CE - Krimpen aan den IJssel - Nederland' : get_option('invoice_address');

    // save the settings
    if (isset($_POST['save-settings'])) {
        $invoice_title = $_POST['invoice_title'];
        $invoice_order_date = $_POST['invoice_order_date'];
        $invoice_order_number = $_POST['invoice_order_number'];
        $invoice_date = $_POST['invoice_date'];
        $invoice_number = $_POST['invoice_number'];
        $invoice_due_date = $_POST['invoice_due_date'];
        $invoice_paid_date = $_POST['invoice_paid_date'];
        $invoice_product_name = $_POST['invoice_product_name'];
        $invoice_product_amount = $_POST['invoice_product_amount'];
        $invoice_product_price = $_POST['invoice_product_price'];
        $invoice_product_tax = $_POST['invoice_product_tax'];
        $invoice_product_subtotal = $_POST['invoice_product_subtotal'];
        $invoice_product_total = $_POST['invoice_product_total'];
        $invoice_product_total_tax = $_POST['invoice_product_total_tax'];
        $invoice_whatsapp = $_POST['invoice_whatsapp'];
        $invoice_whatsapp_number = $_POST['invoice_whatsapp_number'];
        $invoice_chamber_of_commerce = $_POST['invoice_chamber_of_commerce'];
        $invoice_chamber_of_commerce_number = $_POST['invoice_chamber_of_commerce_number'];
        $invoice_phone_number = $_POST['invoice_phone_number'];
        $invoice_vat_number = $_POST['invoice_vat_number'];
        $invoice_bank = $_POST['invoice_bank'];
        $invoice_bank_number = $_POST['invoice_bank_number'];
        $invoice_bank_bic = $_POST['invoice_bank_bic'];
        $invoice_bank_bic_number = $_POST['invoice_bank_bic_number'];
        $invoice_address = $_POST['invoice_address'];
        update_option('invoice_title', $invoice_title);
        update_option('invoice_order_date', $invoice_order_date);
        update_option('invoice_order_number', $invoice_order_number);
        update_option('invoice_date', $invoice_date);
        update_option('invoice_number', $invoice_number);
        update_option('invoice_due_date', $invoice_due_date);
        update_option('invoice_paid_date', $invoice_paid_date);
        update_option('invoice_product_name', $invoice_product_name);
        update_option('invoice_product_amount', $invoice_product_amount);
        update_option('invoice_product_price', $invoice_product_price);
        update_option('invoice_product_tax', $invoice_product_tax);
        update_option('invoice_product_subtotal', $invoice_product_subtotal);
        update_option('invoice_product_total', $invoice_product_total);
        update_option('invoice_product_total_tax', $invoice_product_total_tax);
        update_option('invoice_whatsapp', $invoice_whatsapp);
        update_option('invoice_whatsapp_number', $invoice_whatsapp_number);
        update_option('invoice_chamber_of_commerce', $invoice_chamber_of_commerce);
        update_option('invoice_chamber_of_commerce_number', $invoice_chamber_of_commerce_number);
        update_option('invoice_phone_number', $invoice_phone_number);
        update_option('invoice_vat_number', $invoice_vat_number);
        update_option('invoice_bank', $invoice_bank);
        update_option('invoice_bank_number', $invoice_bank_number);
        update_option('invoice_bank_bic', $invoice_bank_bic);
        update_option('invoice_bank_bic_number', $invoice_bank_bic_number);
        update_option('invoice_address', $invoice_address);
        // add a success message 
        echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully.</p></div>';
    }


    ?>
    <div style="padding: 5px 15px;">
        <h1>Modify Invoice</h1>
        <form method="post" action="">
            <div id="api-fields">
                <!-- Fields will be added here -->
                <div class="api-section">
                <label>Factuur</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_title"
                    placeholder="Factuur"
                    value="<?php echo $invoice_title; ?>"
                />

                <hr />
                <label>Orderdatum</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_order_date"
                    placeholder="Orderdatum"
                    value="<?php echo $invoice_order_date; ?>"
                />

                <hr />
                <label>Ordernummer Bol</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_order_number"
                    placeholder="Ordernummer Bol"
                    value="<?php echo $invoice_order_number; ?>"
                />

                <hr />
                <label>Factuurdatum</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_date"
                    placeholder="Factuurdatum"
                    value="<?php echo $invoice_date; ?>"
                />

                <hr />
                <label>Factuurnummer</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_number"
                    placeholder="Factuurnummer"
                    value="<?php echo $invoice_number; ?>"
                />

                <hr />
                <label>Vervaldatum</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_due_date"
                    placeholder="Vervaldatum"
                    value="<?php echo $invoice_due_date; ?>"
                />

                <hr />
                <label>n.v.t. (betaald via Bol.com)</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_paid_date"
                    placeholder="n.v.t. (betaald via Bol.com)"
                    value="<?php echo $invoice_paid_date; ?>"
                />

                <hr />
                <label>Productnaam</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_product_name"
                    placeholder="Productnaam"
                    value="<?php echo $invoice_product_name; ?>"
                />

                <hr />
                <label>Aantal</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_product_amount"
                    placeholder="Aantal"
                    value="<?php echo $invoice_product_amount; ?>"
                />

                <hr />
                <label>Prijs</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_product_price"
                    placeholder="Prijs"
                    value="<?php echo $invoice_product_price; ?>"
                />

                <hr />
                <label>BTW</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_product_tax"
                    placeholder="BTW"
                    value="<?php echo $invoice_product_tax; ?>"
                />

                <hr />
                <label>Subtotaal excl. BTW</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_product_subtotal"
                    placeholder="Subtotaal excl. BTW"
                    value="<?php echo $invoice_product_subtotal; ?>"
                />

                <hr />
                <label>Totaal excl. BTW</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_product_total"
                    placeholder="Totaal excl. BTW"
                    value="<?php echo $invoice_product_total; ?>"
                />

                <hr />
                <label>Totaal incl. BTW</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_product_total_tax"
                    placeholder="Totaal incl. BTW"
                    value="<?php echo $invoice_product_total_tax; ?>"
                />

                <hr />
                <label>Whatsapp</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_whatsapp"
                    placeholder="Whatsapp"
                    value="<?php echo $invoice_whatsapp; ?>"
                />

                <hr />
                <label>+31 (0) 683 926 724</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_whatsapp_number"
                    placeholder="+31 (0) 683 926 724"
                    value="<?php echo $invoice_whatsapp_number; ?>"
                />

                <hr />
                <label>KvK</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_chamber_of_commerce"
                    placeholder="KvK"
                    value="<?php echo $invoice_chamber_of_commerce; ?>"
                />

                <hr />
                <label>83863966</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_chamber_of_commerce_number"
                    placeholder="83863966"
                    value="<?php echo $invoice_chamber_of_commerce_number; ?>"
                />

                <hr />
                <label>+31 (0) 180 700 209</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_phone_number"
                    placeholder="+31 (0) 180 700 209"
                    value="<?php echo $invoice_phone_number; ?>"
                />

                <hr />
                <label>NL003883640B02</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_vat_number"
                    placeholder="NL003883640B02"
                    value="<?php echo $invoice_vat_number; ?>"
                />

                <hr />
                <label>Bank</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_bank"
                    placeholder="Bank"
                    value="<?php echo $invoice_bank; ?>"
                />

                <hr />
                <label>NL89KNAB0411483749</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_bank_number"
                    placeholder="NL89KNAB0411483749"
                    value="<?php echo $invoice_bank_number; ?>"
                />

                <hr />
                <label>BIC</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_bank_bic"
                    placeholder="BIC"
                    value="<?php echo $invoice_bank_bic; ?>"
                />

                <hr />
                <label>KNABNL2H</label>
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_bank_bic_number"
                    placeholder="KNABNL2H"
                    value="<?php echo $invoice_bank_bic_number; ?>"
                />

                <hr />
                <label
                    >Cornelis Trooststraat 15 - 2923 CE - Krimpen aan den IJssel -
                    Nederland</label
                >
                <input
                    type="text"
                    class="fancy-input"
                    name="invoice_address"
                    placeholder="Cornelis Trooststraat 15 - 2923 CE - Krimpen aan den IJssel - Nederland"
                    value="<?php echo $invoice_address; ?>"
                />

                <hr />
                <hr />
                </div>
            </div>
            <button type="submit" class="fancy-button" name="save-settings">Save</button>
        </form>
    </div>

    <?php 
}