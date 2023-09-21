<?php 

// Submenu Order Sync Page
function bol_com_api_sync_page() {
    echo '<div class="wrap">';
    echo '<h2>Sync your orders</h2>';


    if( isset( $_POST['sync-orders'] ) ){

        $api_data = json_decode(get_option('bol_com_api_settings'), true);

        if (!empty($api_data)) {
            foreach ($api_data as $index => $api) {
                $api_key = $api['api_key'];
                $api_secret = $api['api_secret'];
                $orders = bol_com_orders($api_key, $api_secret);

                if($orders){ 

                    foreach($orders as $order){
                        $api_number = $index+1;
                        $order_number = $order['orderId'];
                        $invoice_number = $order['shipmentId'];
                        $name = $order['billingDetails']['firstName'] . ' ' . $order['billingDetails']['surname'];
                        $type = 'Private';
                        $date = $order['statusTransitions'][0]['statusDateTime'];
                        // convert the date into this format: 12.02.2023 10:00 am
                        // $date = date('d-m-Y h:i a', strtotime($date));
                        $amount = $order['products'][0]['unitPrice'];
                        $status = $order['statusTransitions'][0]['status'];
                        $generated_invoice_url = "";
            
                        // Save the order in database
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'bol_com_orders';
                        // check if the order already exists
                        $order_exists = $wpdb->get_results("SELECT * FROM $table_name WHERE order_number = '$order_number'");
                        if($order_exists){
                            continue;
                        }else{
                            $wpdb->insert(
                                $table_name,
                                array(
                                    'api_number' => $api_number,
                                    'order_number' => $order_number,
                                    'invoice_number' => $invoice_number,
                                    'name' => $name,
                                    'type' => $type,
                                    'date' => $date,
                                    'amount' => $amount,
                                    'status' => $status,
                                    'generated_invoice_url' => $generated_invoice_url
                                )
                            );
                        }
                        
                    }


                }
            }
        } else {
            echo '<p>No API data found.</p>';
        }

        echo '<div class="notice notice-success is-dismissible"><p>Orders synced successfully.</p></div>';
    }

    ?>
    <form method="post" class="sync-form" action="">
        <button type="submit" class="fancy-button" name="sync-orders">Sync Orders</button>
    </form>
    <?php
    echo '</div>';
}