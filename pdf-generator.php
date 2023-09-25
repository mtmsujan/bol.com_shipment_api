<?php
require 'vendor/autoload.php'; // Autoload Dompdf

use Dompdf\Dompdf;

add_action('asdfasdf', function(){
    $outputDirectory = __DIR__ . '/invoices/';
    if (!file_exists($outputDirectory)) {
        mkdir($outputDirectory, 0755, true);
    }
    $dompdf = new Dompdf();
    $html = file_get_contents(__DIR__ . '/sample.html');
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $outputFileName = __DIR__ .'/invoices/invoice.pdf';
    file_put_contents($outputFileName, $dompdf->output());

    echo "PDF generated and saved as '$outputFileName'";
});

?>
