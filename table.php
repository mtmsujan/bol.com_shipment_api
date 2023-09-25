<?php 

// Create order table in database
function bol_com_plugin_activate() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'bol_com_orders';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        api_number varchar(20) NOT NULL,
        order_number varchar(50) NOT NULL,
        invoice_number varchar(50) NOT NULL,
        name varchar(255) NOT NULL,
        type varchar(50) NOT NULL,
        order_date varchar(50) NOT NULL,
        product_details text NULL,
        billing_details text NULL,
        shipment_date varchar(50) NOT NULL,
        amount decimal(10, 2) NOT NULL,
        status varchar(50) NOT NULL,
        generated_invoice_url varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Delete order table from database
function bol_com_plugin_deactivate() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'bol_com_orders';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}