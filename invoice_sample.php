<?php 

// base64 encoded logo image
function base_64_encode_image($image_url){
    // Get the image data from the URL
    $image_data = file_get_contents($image_url);
    $headers = get_headers($image_url, 1);
    if ($headers !== false && isset($headers['Content-Type'])) {
        $mime_type = $headers['Content-Type'];
        $image_data = file_get_contents($image_url);
        if ($image_data !== false) {
            $base64_image = base64_encode($image_data);
            if ($base64_image !== false) {
                $data_uri = "data:$mime_type;base64,$base64_image";
                return $data_uri;
            } else {
                return "";
            }
        } else {
            return "";
        }
    } else {
        return "";
    }
}

// invoice sample code instead of sample.html 
function invoice_sample_html(){
    $invoice_title = get_option('invoice_title') == '' ? 'Factuur' : get_option('invoice_title');
    $company_name = get_option('company_name') == '' ? 'Whiteboardmaster' : get_option('company_name');
    $company_logo = get_option('company_logo') == '' ? 'https://whiteboardmaster.shop/wp-content/uploads/2023/10/whiteboardmaster.png' : get_option('company_logo');
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
    $invoice_phone_title = get_option('invoice_phone_title') == '' ? 'Telefoon' : get_option('invoice_phone_title');
    $invoice_phone_number = get_option('invoice_phone_number') == '' ? '+31 (0) 180 700 209' : get_option('invoice_phone_number');
    $invoice_vat_number = get_option('invoice_vat_number') == '' ? 'NL003883640B02' : get_option('invoice_vat_number');
    $invoice_bank = get_option('invoice_bank') == '' ? 'Bank' : get_option('invoice_bank');
    $invoice_bank_number = get_option('invoice_bank_number') == '' ? 'NL89KNAB0411483749' : get_option('invoice_bank_number');
    $invoice_bank_bic = get_option('invoice_bank_bic') == '' ? 'BIC' : get_option('invoice_bank_bic');
    $invoice_bank_bic_number = get_option('invoice_bank_bic_number') == '' ? 'KNABNL2H' : get_option('invoice_bank_bic_number');
    $invoice_address = get_option('invoice_address') == '' ? 'Cornelis Trooststraat 15 - 2923 CE - Krimpen aan den IJssel - Nederland' : get_option('invoice_address');
    ob_start(); ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>(anonymous)</title>
        <meta name="author" content="(anonymous)" />
        <meta name="description" content="(unspecified)" />
        <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
            font-family: arial;
            letter-spacing: 2px;
        }
        body {
            padding: 25px;
        }
        .s1 {
            color: #59cddf;
            font-family: Helvetica;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 30pt;
        }
        .s2 {
            color: black;
            font-family: Helvetica;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 8pt;
        }
        .s3 {
            color: #59cddf;
            font-family: Helvetica;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 8pt;
        }
        p {
            color: black;
            font-family: Helvetica;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 8pt;
            margin: 0pt;
        }
        .s4 {
            color: black;
            font-family: Helvetica;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 8pt;
            vertical-align: 6pt;
        }
        .s5 {
            color: #a8a8a8;
            font-family: Helvetica;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 8pt;
        }
        .s6 {
            color: black;
            font-family: Helvetica;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 14px;
        }
        .s7 {
            color: #59cddf;
            font-family: Helvetica;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }
        .s8 {
            color: black;
            font-family: Helvetica;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }
        .s9 {
            color: #59cddf;
            font-family: Helvetica;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 9pt;
        }
        table,
        tbody {
            vertical-align: top;
            overflow: visible;
        }
        </style>
    </head>
    <body>
        <p
        class="s1"
        style="
            padding-top: 10pt;
            padding-left: 6pt;
            text-indent: 0pt;
            text-align: left;
            font-family: Helvetica;
        "
        >
        <?php echo $invoice_title; ?>
        </p>
        <p style="text-indent: 0pt; text-align: left"><br /></p>
        <table
        style="border-collapse: collapse; margin-left: 6.5pt; width: 100%"
        cellspacing="0"
        >
        <tr style="height: 16pt">
            <td style="width: 223pt">
            <p
                class="s2"
                style="
                padding-top: 1pt;
                padding-left: 2pt;
                padding-bottom: 3pt;
                text-indent: 0pt;
                text-align: left;
                font-weight: bold;
                "
            >
                {{companyName}}
            </p>
            </td>
            <td style="width: 338pt" colspan="2">
            <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
        </tr>
        <tr style="height: 16pt">
            <td style="width: 223pt">
            <p
                class="s2"
                style="
                padding-top: 1pt;
                padding-left: 2pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                {{name}}
            </p>
            </td>
            <td style="width: 338pt" colspan="2">
            <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
        </tr>
        <tr style="height: 20pt">
            <td style="width: 223pt">
            <p
                class="s2"
                style="
                padding-top: 4pt;
                padding-left: 2pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                {{streetName}} {{houseNumber}}
            </p>
            </td>
            <td style="width: 183pt">
            <p
                class="s3"
                style="
                padding-top: 3pt;
                padding-left: 70pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                <?php echo $invoice_order_date; ?>:
            </p>
            </td>
            <td style="width: 155pt">
            <p
                class="s2"
                style="
                padding-top: 3pt;
                padding-left: 1pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                {{orderPlacedDateTime}}
            </p>
            </td>
        </tr>
        <tr style="height: 20pt">
            <td style="width: 223pt">
            <p
                class="s2"
                style="
                padding-top: 4pt;
                padding-left: 2pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                {{zipCode}} {{city}}
            </p>
            </td>
            <td style="width: 183pt">
            <p
                class="s3"
                style="
                padding-top: 3pt;
                padding-left: 70pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                <?php echo $invoice_order_number; ?>:
            </p>
            </td>
            <td style="width: 155pt">
            <p
                class="s2"
                style="
                padding-top: 3pt;
                padding-left: 1pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                {{orderId}}
            </p>
            </td>
        </tr>
        <tr style="height: 20pt">
            <td style="width: 223pt">
            <p
                class="s2"
                style="
                padding-top: 4pt;
                padding-left: 2pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                {{countryCode}}
            </p>
            </td>
            <td style="width: 183pt">
            <p
                class="s3"
                style="
                padding-top: 3pt;
                padding-left: 70pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                <?php echo $invoice_date; ?>:
            </p>
            </td>
            <td style="width: 155pt">
            <p
                class="s2"
                style="
                padding-top: 3pt;
                padding-left: 1pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                {{shipmentDateTime}}
            </p>
            </td>
        </tr>
        <tr style="height: 30pt">
            <td style="width: 223pt">
            <p style="text-indent: 0pt; text-align: left"><br /></p>
            </td>
            <td style="width: 183pt">
            <p
                class="s3"
                style="
                padding-top: 3pt;
                padding-left: 70pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                <?php echo $invoice_number; ?>:
            </p>
            <p
                class="s3"
                style="
                padding-top: 5pt;
                padding-left: 70pt;
                text-indent: 0pt;
                line-height: 9pt;
                text-align: left;
                "
            >
                <?php echo $invoice_due_date; ?>:
            </p>
            </td>
            <td style="width: 155pt">
            <p
                class="s2"
                style="
                padding-top: 3pt;
                padding-left: 1pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                {{invoiceNumber}}
            </p>
            <p
                class="s2"
                style="
                padding-top: 5pt;
                padding-left: 1pt;
                text-indent: 0pt;
                line-height: 9pt;
                text-align: left;
                "
            >
                <?php echo $invoice_paid_date; ?>
            </p>
            </td>
        </tr>
        </table>

        <table
        style="
            margin-top: 50px;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        "
        >
        <thead>
            <tr>
            <th
                style="
                text-align: left;
                border-bottom: 2px solid rgb(210, 209, 209);
                padding-bottom: 15px;
                font-size: 10px;
                font-family: Helvetica;
                width: 50%;
                "
            >
                <?php echo $invoice_product_name; ?>
            </th>
            <th
                style="
                text-align: left;
                border-bottom: 2px solid rgb(210, 209, 209);
                padding-bottom: 15px;
                font-size: 10px;
                font-family: Helvetica;
                width: 10%;
                "
            >
                <?php echo $invoice_product_amount; ?>
            </th>
            <th
                style="
                text-align: left;
                border-bottom: 2px solid rgb(210, 209, 209);
                padding-bottom: 15px;
                font-size: 10px;
                font-family: Helvetica;
                width: 10%;
                "
            >
                <?php echo $invoice_product_price; ?>
            </th>
            <th
                style="
                text-align: left;
                border-bottom: 2px solid rgb(210, 209, 209);
                padding-bottom: 15px;
                font-size: 10px;
                font-family: Helvetica;
                width: 10%;
                "
            >
                <?php echo $invoice_product_tax; ?>
            </th>
            <th
                style="
                text-align: left;
                border-bottom: 2px solid rgb(210, 209, 209);
                padding-bottom: 15px;
                font-size: 10px;
                font-family: Helvetica;
                width: 20%;
                "
            >
                <?php echo $invoice_product_subtotal; ?>
            </th>
            </tr>
        </thead>
        <tbody>
            {{orderItems}}
        </tbody>
        </table>

        <table
        style="border-collapse: collapse; margin-left: 5pt; width: 100%"
        cellspacing="0"
        >
        <tr style="height: 21pt">
            <td style="width: 476pt">
            <p
                class="s5"
                style="
                padding-top: 5pt;
                padding-left: 351pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                <?php echo $invoice_product_total; ?>
            </p>
            </td>
            <td style="width: 82pt">
            <p
                class="s6"
                style="
                padding-top: 5pt;
                padding-right: 5pt;
                text-indent: 0pt;
                text-align: right;
                "
            >
                € {{total_excluding_vat}}
            </p>
            </td>
        </tr>
        <tr style="height: 22pt">
            <td style="width: 476pt">
            <p
                class="s5"
                style="
                padding-top: 7pt;
                padding-left: 351pt;
                text-indent: 0pt;
                text-align: left;
                "
            >
                <?php echo $invoice_product_tax; ?> ({{vat_percentage}}%)
            </p>
            </td>
            <td style="width: 82pt">
            <p
                class="s6"
                style="
                padding-top: 7pt;
                padding-right: 5pt;
                text-indent: 0pt;
                text-align: right;
                "
            >
                € {{total_btw}}
            </p>
            </td>
        </tr>
        <tr style="height: 17pt">
            <td style="width: 476pt">
            <p
                class="s6"
                style="
                padding-top: 7pt;
                padding-left: 351pt;
                text-indent: 0pt;
                line-height: 8pt;
                text-align: left;
                "
            >
                <?php echo $invoice_product_total_tax; ?>
            </p>
            </td>
            <td style="width: 82pt">
            <p
                class="s6"
                style="
                padding-top: 7pt;
                padding-right: 5pt;
                text-indent: 0pt;
                line-height: 8pt;
                text-align: right;
                "
            >
                € {{total_including_vat}}
            </p>
            </td>
        </tr>
        </table>
        <p style="text-indent: 0pt; text-align: left"><br /></p>
        <p style="text-indent: 0pt; text-align: left"><br /></p>
        <table style="margin-top: 450px; width: 100%">
        <tr>
            <td>
            <img
                width="180"
                height="134"
                src="<?php echo base_64_encode_image($company_logo); ?>"
            />
            </td>
            <td>
            <table
                style="border-collapse: collapse; margin-top: 20px; width: 100%"
                cellspacing="0"
            >
                <tr>
                <td style="width: 10%">
                    <p
                    class="s7"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: justify;
                    "
                    >
                    <?php echo $invoice_whatsapp; ?>:
                    </p>
                </td>
                <td style="width: 50%">
                    <p
                    class="s8"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        padding-right: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: left;
                    "
                    >
                    <span style="color: #000"><?php echo $invoice_whatsapp_number; ?></span>
                    </p>
                </td>
                <td style="width: 10%">
                    <p
                    class="s7"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: justify;
                    "
                    >
                    <?php echo $invoice_chamber_of_commerce; ?>:
                    </p>
                </td>
                <td style="width: 30%">
                    <p
                    class="s8"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        padding-right: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: left;
                    "
                    >
                    <span style="color: #000"><?php echo $invoice_chamber_of_commerce_number; ?></span>
                    </p>
                </td>
                </tr>
                <tr>
                <td style="width: 10%">
                    <p
                    class="s7"
                    style="
                        padding-top: 3pt;
                        padding-left: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: justify;
                    "
                    >
                    <?php echo $invoice_phone_title; ?>:
                    </p>
                </td>
                <td style="width: 50%">
                    <p
                    class="s8"
                    style="
                        padding-top: 3pt;
                        padding-left: 2pt;
                        padding-right: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: left;
                    "
                    >
                    <span style="color: #000"><?php echo $invoice_phone_number; ?></span>
                    </p>
                </td>
                <td style="width: 10%">
                    <p
                    class="s7"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: justify;
                    "
                    >
                    <?php echo $invoice_product_tax; ?>:
                    </p>
                </td>
                <td style="width: 30%">
                    <p
                    class="s8"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        padding-right: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: left;
                    "
                    >
                    <span style="color: #000"><?php echo $invoice_vat_number; ?></span>
                    </p>
                </td>
                </tr>

                <tr>
                <td style="width: 10%">
                    <p
                    class="s7"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: justify;
                    "
                    ></p>
                </td>
                <td style="width: 50%">
                    <p
                    class="s8"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        padding-right: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: left;
                    "
                    >
                    <span style="color: #000"></span>
                    </p>
                </td>
                <td style="width: 10%">
                    <p
                    class="s7"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: justify;
                    "
                    >
                    <?php echo $invoice_bank; ?>:
                    </p>
                </td>
                <td style="width: 30%">
                    <p
                    class="s8"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        padding-right: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: left;
                    "
                    >
                    <span style="color: #000"><?php echo $invoice_bank_number; ?></span>
                    </p>
                </td>
                </tr>
                <tr>
                <td style="width: 10%">
                    <p
                    class="s7"
                    style="
                        padding-top: 3pt;
                        padding-left: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: justify;
                    "
                    ></p>
                </td>
                <td style="width: 50%">
                    <p
                    class="s8"
                    style="
                        padding-top: 3pt;
                        padding-left: 2pt;
                        padding-right: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: left;
                    "
                    >
                    <span style="color: #000"></span>
                    </p>
                </td>
                <td style="width: 10%">
                    <p
                    class="s7"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: justify;
                    "
                    >
                    <?php echo $invoice_bank_bic; ?>:
                    </p>
                </td>
                <td style="width: 30%">
                    <p
                    class="s8"
                    style="
                        padding-top: 2pt;
                        padding-left: 2pt;
                        padding-right: 2pt;
                        text-indent: 0pt;
                        line-height: 12pt;
                        text-align: left;
                    "
                    >
                    <span style="color: #000"><?php echo $invoice_bank_bic_number; ?></span>
                    </p>
                </td>
                </tr>
            </table>
            <p
                class="s9"
                style="padding-top: 6pt; text-indent: 0pt; text-align: center"
            >
                <?php echo $invoice_address; ?>
            </p>
            </td>
        </tr>
        </table>
    </body>
    </html>

    <?php return ob_get_clean();
}