<?php 

use Dompdf\Dompdf;

// upload invoice to bol.com while syncing
function upload_invoice_while_syncing($access_token, $shipment_id, $invoice_url){

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

// sync all orders
function sync_all_orders(){
    $api_data = json_decode(get_option('bol_com_api_settings'), true);
    if (!empty($api_data)) {
        foreach ($api_data as $index => $api) {
            $api_key = $api['api_key'];
            $api_secret = $api['api_secret'];
            $orders = bol_com_orders($api_key, $api_secret);

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

            $invoice_requests = get_invoice_requests($access_token);

            if($orders){ 
                foreach($orders as $order){
                    $single_request = getRequestByShipmentId($invoice_requests, $order['shipmentId']);
                    $invoice_status = isset($single_request['statusTransitions']) ? $single_request['statusTransitions'][0]['status'] : 'SHIPPED';

                    $single_shipment = single_shipment_details( $access_token, $order['shipmentId']);
                    $single_order = get_single_order_details( $access_token, $order['order']['orderId']);
                    $orderItems = $single_order['orderItems'];
                    $api_number = $index+1;
                    $order_number = $order['order']['orderId'];
                    $invoice_number = $order['shipmentId'];
                    $name = $single_shipment['billingDetails']['firstName'] . ' ' . $single_shipment['billingDetails']['surname'];
                    $type = 'Private';
                    $order_date = $single_shipment['order']['orderPlacedDateTime'];
                    $order_date = date('d-m-Y h:i a', strtotime($order_date));
                    $shipment_date = $single_shipment['shipmentDateTime'];
                    $shipment_date = date('d-m-Y h:i a', strtotime($shipment_date));
                    $product_details = $single_shipment['shipmentItems'];
                    $billing_details = $single_shipment['billingDetails'];
                    $amount = $single_shipment['shipmentItems'][0]['unitPrice'];
                    $status =  'SHIPPED';
                    $generated_invoice_url = "";

                    // Generate the invoice
                    $invoice_details = array(
                        'id' => $api_number,
                        'order_number' => $order_number,
                        'invoice_number' => $invoice_number,
                        'name' => $name,
                        'type' => $type,
                        'order_date' => $order_date,
                        'product_details' => $orderItems,
                        'billing_details' => $billing_details, 
                        'shipment_date' => $shipment_date,
                        'amount' => $amount,
                        'status' => $status,
                        'generated_invoice_url' => $generated_invoice_url
                    );

                    $generated_invoice_url = generate_invoice($invoice_details);

                    // order total 
                    $total_inc_vat = 0;
                    foreach($orderItems as $product){
                        $product_quantity = $product['quantity'];
                        $product_price = $product['unitPrice'];
                        $product_vat = 21;
                        $product_total = $product_price * $product_quantity;
                        $total_inc_vat += $product_total;
                    }
        
                    // Save the order in database
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'bol_com_orders';
                    // check if the order already exists
                    $order_exists = $wpdb->get_results("SELECT * FROM $table_name WHERE order_number = '$order_number'");
                    if($order_exists){
                        if($order_exists[0]->status == "SHIPPED"){
                            upload_invoice_while_syncing($access_token, $order_exists[0]->invoice_number, $order_exists[0]->generated_invoice_url);
                        }
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
                                'order_date' => $order_date,
                                'product_details' => json_encode($orderItems),
                                'billing_details' => json_encode($billing_details),
                                'shipment_date' => $shipment_date,
                                'amount' => number_format($total_inc_vat, 2, '.', ''),
                                'status' => $invoice_status,
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
}

// shcedule cron job to sync all orders 
function custom_cron_schedule($schedules) {
    $schedules['1_minute'] = array(
        'interval' => 60, // 1 minute in seconds
        'display'  => __('Every Minute')
    );
    return $schedules;
}
add_filter('cron_schedules', 'custom_cron_schedule');

function schedule_custom_cron() {
    if (!wp_next_scheduled('sync_orders_through_cron_job')) {
        wp_schedule_event(time(), '1_minute', 'sync_orders_through_cron_job');
    }
}
add_action('init', 'schedule_custom_cron');
add_action('admin_init', 'schedule_custom_cron');

add_action('sync_orders_through_cron_job', 'sync_all_orders');



// date_format
function correct_date_format($input_date){
    $date_object = DateTime::createFromFormat('d-m-Y h:i A', $input_date);
    $formatted_date = $date_object->format('d-m-Y');
    return $formatted_date;
}

// get single order details
function get_single_order_details( $access_token, $order_id ){
    if($access_token){
        $order = "https://api.bol.com/retailer/orders/" . $order_id;
        $order_data = wp_remote_get($order, array(
            'headers' => array(
                "Authorization" => "Bearer $access_token",
                "Accept" => "application/vnd.retailer.V9+json"
            )
        ));

        $order_details = json_decode(wp_remote_retrieve_body($order_data), true);
        return $order_details;
    }else{
        return false;
    }
}

// get invoice requests 
function get_invoice_requests($access_token){
    if($access_token){
        $invoice_requests = "https://api.bol.com/retailer/shipments/invoices/requests";
        $request_data = wp_remote_get($invoice_requests, array(
            'headers' => array(
                "Authorization" => "Bearer $access_token",
                "Accept" => "application/vnd.retailer.V10+json"
            )
        ));

        $invoice_request_details = json_decode(wp_remote_retrieve_body($request_data), true)['invoiceRequests'];
        return $invoice_request_details;
    }else{
        return false;
    }
}

function getRequestByShipmentId($invoiceRequests, $shipmentId) {
    foreach ($invoiceRequests as $request) {
        if (isset($request['shipmentId']) && $request['shipmentId'] === $shipmentId) {
            return $request;
        }
    }
    
    // Return null if no request with the specified shipment ID is found
    return null;
}

// generate invoices
function generate_invoice($invoice_details){
    $order_number = isset($invoice_details['order_number']) ? $invoice_details['order_number'] : '';
    $invoice_number = isset($invoice_details['invoice_number']) ? $invoice_details['invoice_number'] : '';
    $name = isset($invoice_details['name']) ? $invoice_details['name'] : '';
    $type = isset($invoice_details['type']) ? $invoice_details['type'] : '';
    $order_date = isset($invoice_details['order_date']) ? $invoice_details['order_date'] : '';
    $order_date = correct_date_format($order_date);
    $shipment_date = isset($invoice_details['shipment_date']) ? $invoice_details['shipment_date'] : '';
    $shipment_date = correct_date_format($shipment_date);
    $product_details = isset($invoice_details['product_details']) ? $invoice_details['product_details'] : '';
    $billing_details = isset($invoice_details['billing_details']) ? $invoice_details['billing_details'] : '';
    $amount = isset($invoice_details['amount']) ? $invoice_details['amount'] : '';
    $status = isset($invoice_details['status']) ? $invoice_details['status'] : '';
    $generated_invoice_url = isset($invoice_details['generated_invoice_url']) ? $invoice_details['generated_invoice_url'] : '';
    $streetName = isset($billing_details['streetName']) ? $billing_details['streetName'] : '';
    $houseNumber = isset($billing_details['houseNumber']) ? $billing_details['houseNumber'] : '';
    $zipCode = isset($billing_details['zipCode']) ? $billing_details['zipCode'] : '';
    $city = isset($billing_details['city']) ? $billing_details['city'] : '';
    $countryCode = isset($billing_details['countryCode']) && $billing_details['countryCode'] != 'NL' ? $billing_details['countryCode'] : 'Nederland';
    $vat_percentage = 21;

    $orderItems = '';
    $total_inc_vat = 0;
    foreach($product_details as $product){
        $product_title = $product['product']['title'];
        $product_quantity = $product['quantity'];
        $product_price = $product['unitPrice'];
        $product_total = $product_price * $product_quantity;
        $total_inc_vat += $product_total;
    }

    $total_vat = $total_inc_vat * ($vat_percentage / 100);
    $total_excluding_vat = $total_inc_vat - $total_vat;
    $total_excluding_vat = number_format($total_excluding_vat, 2, ',', ' ');
    $total_vat = number_format($total_vat, 2, ',', ' ');

    $total_inc_vat = number_format($total_inc_vat, 2, ',', ' ');

    foreach($product_details as $product){
        $product_title = $product['product']['title'];
        $product_quantity = $product['quantity'];
        $product_price = $product['unitPrice'];
        $product_total = $product_price * $product_quantity;
        $orderItems .= '<tr
        style="
          border-bottom-style: solid;
          border-bottom-width: 2px;
          border-bottom-color: rgb(210, 209, 209);
        "
      >
        <td style="padding: 10px 0; font-size: 14px; font-family: Helvetica">
          ' . $product_title . '
        </td>
        <td style="padding: 10px 0; font-size: 14px; font-family: Helvetica">
          ' . $product_quantity . '
        </td>
        <td style="padding: 10px 0; font-size: 14px; font-family: Helvetica">
          € ' . number_format($product_price, 2, ',', ' ') . '
        </td>
        <td style="padding: 10px 0; font-size: 14px; font-family: Helvetica">
          ' . $vat_percentage . '%
        </td>
        <td
          style="
            text-align: right;
            font-size: 14px;
            font-family: Helvetica;
            padding: 10px 0;
          "
        >
          € ' . number_format($product_total, 2, ',', ' ') . '
        </td>
      </tr>';
    }

    $outputDirectory = __DIR__ . '/invoices/';
    if (!file_exists($outputDirectory)) {
        mkdir($outputDirectory, 0755, true);
    }

    // current year 
    $current_year = date('Y');
    
    // get last id value from wp_bol_com_orders table
    global $wpdb;
    $table_name = $wpdb->prefix . 'bol_com_orders';
    $last_id = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC LIMIT 1", ARRAY_A);
    $invoice_id = isset($last_id[0]['id']) ? $last_id[0]['id'] + 1 : 1;


    $dompdf = new Dompdf();
    $html = file_get_contents(__DIR__ . '/sample.html');
    $html = str_replace('{{name}}', $name, $html);
    $html = str_replace('{{streetName}}', $streetName, $html);
    $html = str_replace('{{houseNumber}}', $houseNumber, $html);
    $html = str_replace('{{orderPlacedDateTime}}', $order_date, $html);
    $html = str_replace('{{zipCode}}', $zipCode, $html);
    $html = str_replace('{{city}}', $city, $html);
    $html = str_replace('{{orderId}}', $order_number, $html);
    $html = str_replace('{{countryCode}}', $countryCode, $html);
    $html = str_replace('{{shipmentDateTime}}', $shipment_date, $html);
    $html = str_replace('{{invoiceNumber}}', $current_year . '-0000' . $invoice_id, $html);
    $html = str_replace('{{orderItems}}', $orderItems, $html);
    $html = str_replace('{{total_excluding_vat}}', $total_excluding_vat, $html);
    $html = str_replace('{{vat_percentage}}', $vat_percentage, $html);
    $html = str_replace('{{total_btw}}', $total_vat, $html);
    $html = str_replace('{{total_including_vat}}', $total_inc_vat, $html);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $outputFileName = __DIR__ .'/invoices/'.$order_number.'.pdf';
    file_put_contents($outputFileName, $dompdf->output());

    $plugin_basename = plugin_basename(__FILE__);
    $plugin_directory = plugin_dir_path($plugin_basename);
    $plugin_url = plugins_url('', $plugin_basename);
    return $plugin_url . '/invoices/'.$order_number.'.pdf';
}

// Submenu Order Sync Page
function bol_com_api_sync_page() {
    echo '<div class="wrap">';
    echo '<h2>Sync your orders</h2>';

    if( isset( $_POST['sync-orders'] ) ){
        sync_all_orders();
        echo '<div class="notice notice-success is-dismissible"><p>Orders synced successfully.</p></div>';
    }

    ?>
    <form method="post" class="sync-form" action="">
        <button type="submit" class="fancy-button" name="sync-orders">Sync Orders</button>
    </form>
    <?php
    echo '</div>';
}