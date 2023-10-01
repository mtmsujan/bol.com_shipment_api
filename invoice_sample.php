<?php 

// invoice sample code instead of sample.html 
function invoice_sample_html(){
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
                src="data:image/png;base64,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"
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