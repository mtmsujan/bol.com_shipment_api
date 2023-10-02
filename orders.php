<?php 
// upload invoice to bol.com
function upload_invoice($api_key, $api_secret, $shipment_id, $invoice_url){
    // Get the access token
    $token_url = 'https://login.bol.com/token?grant_type=client_credentials';
    $basic_auth = base64_encode($api_key . ':' . $api_secret);
    $token_data = wp_remote_post($token_url, array(
        'headers' => array(
            "Authorization" => "Basic $basic_auth"
        )
    ));
    $token_output = json_decode(wp_remote_retrieve_body($token_data), true);
    $access_token = $token_output['access_token'];

    // Upload the invoice
    if($access_token){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.bol.com/retailer/shipments/invoices/' . $shipment_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('invoice'=> new CURLFILE($invoice_url, 'application/pdf', 'invoice.pdf')),
        CURLOPT_HTTPHEADER => array(
            'Accept: application/vnd.retailer.V10+json',
            'Authorization: Bearer ' . $access_token
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $response_json = json_decode($response, true);

        if( isset($response_json['status']) && $response_json['status'] == 'PENDING' ){
            // Update the order status in the database
            global $wpdb;
            $table_name = $wpdb->prefix . 'bol_com_orders';
            $wpdb->update($table_name, array('status' => 'INVOICE_UPLOADED'), array('invoice_number' => $shipment_id));

            return true;
        }else{
            return false;
        }

    }else{
        return false;
    }
}

// Get orders from Bol.com API
function bol_com_orders($api_key, $api_secret){
    // Get the access token
    $token_url = 'https://login.bol.com/token?grant_type=client_credentials';
    $basic_auth = base64_encode($api_key . ':' . $api_secret);
    $token_data = wp_remote_post($token_url, array(
        'headers' => array(
            "Authorization" => "Basic $basic_auth"
        )
    ));
    $token_output = json_decode(wp_remote_retrieve_body($token_data), true);
    $access_token = $token_output['access_token'];

    // Get the orders
    if($access_token){
        $order_url = "https://api.bol.com/retailer/shipments";
        $order_data = wp_remote_get($order_url, array(
            'headers' => array(
                "Authorization" => "Bearer $access_token",
                "Accept" => "application/vnd.retailer.V9+json"
            )
        ));

        $order_output = json_decode(wp_remote_retrieve_body($order_data), true);
        $orders = $order_output['shipments'];
        return $orders;
    }else{
        return false;
    }
}

// single shipment details
function single_shipment_details( $access_token, $shipment_id ){
    if($access_token){
        $shipment = "https://api.bol.com/retailer/shipments/" . $shipment_id;
        $shipment_data = wp_remote_get($shipment, array(
            'headers' => array(
                "Authorization" => "Bearer $access_token",
                "Accept" => "application/vnd.retailer.V9+json"
            )
        ));

        $shipment_details = json_decode(wp_remote_retrieve_body($shipment_data), true);
        return $shipment_details;
    }else{
        return false;
    }
}

// Submenu order page 
function bol_com_api_orders_page() {

    echo '<div class="wrap">';
    echo '<h2 class="my-5">Orders</h2>';

    $api_data = json_decode(get_option('bol_com_api_settings'), true);
    if (!empty($api_data)) {
        foreach ($api_data as $index => $api) {

            if(isset($_REQUEST['upload_invoice'])){
                $api_key = $api['api_key'];
                $api_secret = $api['api_secret'];
                $shipment_id = $_REQUEST['shipment_id'];
                $invoice_url = $_REQUEST['invoice_url'];
        
                $upload_invoice = upload_invoice($api_key, $api_secret, $shipment_id, $invoice_url);

                if($upload_invoice){
                    echo '<div class="notice notice-success is-dismissible"><p>Invoice uploaded successfully.</p></div>';
                }else{
                    echo '<div class="notice notice-error is-dismissible"><p>Something went wrong. Please try again.</p></div>';
                }
            }
            
            echo "<h3>Bol.com API " . ($index+1) . ": " . $api['api_name'] . "</h3>";

            // get orders from the table in the database
            global $wpdb;
            $table_name = $wpdb->prefix . 'bol_com_orders';
            // $orders = $wpdb->get_results("SELECT * FROM $table_name WHERE api_number = " . ($index+1), ARRAY_A);
            // orders based on shipping date (newest first)
            $orders = $wpdb->get_results("SELECT * FROM $table_name WHERE api_number = " . ($index+1) . " ORDER BY shipment_date DESC", ARRAY_A);

            if($orders){ 
                echo '<table class="wp-list-table widefat fixed striped">';
                echo '<thead><tr><th>Order Number</th><th>Invoice Number</th><th>Name</th><th>Order Date</th><th>Shipping Date</th><th>Amount</th><th>Status</th><th>Download Invoice</th><th>Upload Invoice</th></tr></thead>';
                echo '<tbody>';
                foreach($orders as $order){

                    echo "<form method='post' action=''>";
                    $order_number = $order['order_number'];
                    $invoice_number = $order['invoice_number'];
                    $name = $order['name'];
                    $type = $order['type'];
                    $order_date = $order['order_date'];
                    $shipping_date = $order['shipment_date'];
                    $amount = $order['amount'];
                    $status = $order['status'];
                    $generated_invoice_url = $order['generated_invoice_url'];

                    echo '<input type="hidden" name="order_number" value="' . $order_number . '">';
                    echo '<input type="hidden" name="invoice_number" value="' . $invoice_number . '">';
                    echo '<input type="hidden" name="name" value="' . $name . '">';
                    echo '<input type="hidden" name="type" value="' . $type . '">';
                    echo '<input type="hidden" name="order_date" value="' . $order_date . '">';
                    echo '<input type="hidden" name="amount" value="' . $amount . '">';
                    echo '<input type="hidden" name="status" value="' . $status . '">';
                    echo '<input type="hidden" name="generated_invoice_url" value="' . $generated_invoice_url . '">';

        
                    echo '<tr>';
                    echo '<td>' . $order_number . '</td>';
                    echo '<td>' . $invoice_number . '</td>';
                    echo '<td>' . $name . '</td>';
                    echo '<td>' . $order_date . '</td>';
                    echo '<td>' . $shipping_date . '</td>';
                    echo '<td>€ ' . number_format($amount, 2, ',', '') . '</td>';
                    echo '<td>' . $status . '</td>';
                    echo '<td><button type="submit" class="fancy-button" name="generate_invoice" onclick="window.open(\'' . $generated_invoice_url . '\', \'_blank\'); return false;">Download Invoice</button></td>';
                    echo '<td><form method="POST" action=""><input type="hidden" name="shipment_id" value="'.$invoice_number.'" /><input type="hidden" name="invoice_url" value="'.$generated_invoice_url.'" /><button type="submit" class="fancy-button" name="upload_invoice">Upload Invoice</button></form></td>';
                    echo '</tr>';
                    echo "</form>";
                }
                echo '</tbody>';
                echo '</table>';
            }else{
                echo '<p>No orders found.</p>';
            }
        }
    } else {
        echo '<p>No API data found.</p>';
    }

    echo '</div>';
}