<?php

require_once 'dompdf/lib/html5lib/Parser.php';
require_once 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

use Dompdf\Dompdf;
use Dompdf\Options;

if(!isset($_POST['body'])){
    echo '{"status":"failed"}';
}
else {

    $body = $_POST['body'];
    $size = $_POST['size'];

    $options = new Options();

    $options->set('defaultFont', 'ms mincho');

    $dompdf = new Dompdf($options);

    include("html.php");

    $dompdf->loadHtml($body, 'UTF-8');

    $dompdf->setPaper($size, 'portrait');

    $dompdf->render();

    $output = $dompdf->output();

    $pdfid = 'pdf-'.uniqid().'.pdf';

    file_put_contents('print_log/'.$pdfid, $output );

    echo '{"status":"success", "url": "'.$pdfid.'"}';
}

exit();