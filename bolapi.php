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

