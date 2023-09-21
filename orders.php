<?php 
// Get orders from Bol.com API
function bol_com_orders($api_key, $api_secret){
    // Get the access token
    $token_url = 'https://login.bol.com/token?grant_type=client_credentials';
    // $api_username = '67dda2cd-9fda-4114-a439-b3f785071771';
    // $api_password = ')YoIpUBGgmfEZjveX4HE@dSR0LGZq6G4ur8tTQuOb8bX37MMd55i5gHOXm@xbYLB';
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
        $order_url = "https://api.bol.com/retailer/shipments/invoices/requests";
        $order_data = wp_remote_get($order_url, array(
            'headers' => array(
                "Authorization" => "Bearer $access_token",
                "Accept" => "application/vnd.retailer.V10+json"
            )
        ));

        $order_output = json_decode(wp_remote_retrieve_body($order_data), true);
        $orders = $order_output['invoiceRequests'];
        return $orders;
    }else{
        return false;
    }
}

// Submenu order page 
function bol_com_api_orders_page() {
    echo '<div class="wrap">';
    echo '<h2 class="my-5">Orders</h2>';

    // if(isset($_POST['generate_invoice'])){
    //     echo "<pre>";
    //     print_r($_POST);
    //     echo "</pre>";
    // }

    $api_data = json_decode(get_option('bol_com_api_settings'), true);

    if (!empty($api_data)) {
        foreach ($api_data as $index => $api) {
            $api_key = $api['api_key'];
            $api_secret = $api['api_secret'];
            $orders = bol_com_orders($api_key, $api_secret);

            echo "<h3>API " . ($index+1) . "</h3>";

            if($orders){ 
                echo '<table class="wp-list-table widefat fixed striped">';
                echo '<thead><tr><th>Order Number</th><th>Invoice Number</th><th>Name</th><th>Type</th><th>Date</th><th>Amount</th><th>Status</th><th>Generate Invoice</th><th>Upload Invoice</th></tr></thead>';
                echo '<tbody>';
                foreach($orders as $order){

                    echo "<form method='post' action=''>";
                    $order_number = $order['orderId'];
                    $invoice_number = $order['shipmentId'];
                    $name = $order['billingDetails']['firstName'] . ' ' . $order['billingDetails']['surname'];
                    $type = 'Private';
                    $date = $order['statusTransitions'][0]['statusDateTime'];
                    // convert the date into this format: 12.02.2023 10:00 am
                    $date = date('d-m-Y h:i a', strtotime($date));
                    $amount = $order['products'][0]['unitPrice'];
                    $status = $order['statusTransitions'][0]['status'];
                    $generated_invoice_url = "";

                    echo '<input type="hidden" name="order_number" value="' . $order_number . '">';
                    echo '<input type="hidden" name="invoice_number" value="' . $invoice_number . '">';
                    echo '<input type="hidden" name="name" value="' . $name . '">';
                    echo '<input type="hidden" name="type" value="' . $type . '">';
                    echo '<input type="hidden" name="date" value="' . $date . '">';
                    echo '<input type="hidden" name="amount" value="' . $amount . '">';
                    echo '<input type="hidden" name="status" value="' . $status . '">';
                    echo '<input type="hidden" name="generated_invoice_url" value="' . $generated_invoice_url . '">';

        
                    echo '<tr>';
                    echo '<td>' . $order_number . '</td>';
                    echo '<td>' . $invoice_number . '</td>';
                    echo '<td>' . $name . '</td>';
                    echo '<td>Private</td>';
                    echo '<td>' . $date . '</td>';
                    echo '<td>€ ' . $amount . '</td>';
                    echo '<td>' . $status . '</td>';
                    echo '<td><button class="fancy-button" name="generate_invoice">Generate Invoice</button></td>';
                    echo '<td><button class="fancy-button" name="upload_invoice">Upload Invoice</button></td>';
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