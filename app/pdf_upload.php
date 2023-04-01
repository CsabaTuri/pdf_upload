<?php

use TonchikTm\PdfToHtml\Pdf;

require_once('vendor/tecnickcom/tcpdf/tcpdf.php');
require_once('vendor/autoload.php');
require_once('mail.php');

header('Content-Type: application/json');
header('Content-Type: text/html; charset=utf-8');

if (!isset($_FILES['pdf-file']) || $_FILES['pdf-file']['error'] !== 0) {
    die('Hiba történt a fájl feltöltése közben!');
}

try {

    $file = $_FILES['pdf-file']['tmp_name'];

    $pdf = new Pdf($file, [
        'pdftohtml_path' => '/usr/bin/pdftohtml',
        'pdfinfo_path' => '/usr/bin/pdfinfo',
        'clearAfter' => false,
        'removeOutputDir' => false,
        'outputDir' => __DIR__ . '/temp/',
        'generate' => [
            'ignoreImages' => true,
        ],
        'html' => [
            'inlineCss' => true,
            'inlineImages' => true,
            'onlyContent' => true,
        ]
    ]);

    if (!file_exists(__DIR__ . '/pdf')) {
        mkdir(__DIR__ . '/pdf', 0777, true);
    }
    
    foreach ($pdf->getHtml()->getAllPages() as $i => $page) {
        $name = '';
        $email = '';
        $dom = new DOMDocument();
        $dom->loadHTML($page);
        $element = $dom->getElementsByTagName('p')->item(0);
        if ($element->getAttribute('class') === 'ft00') {
            $text = $element->textContent;
            $name = utf8_decode($text);
        }
        $element = $dom->getElementsByTagName('p')->item(2);
        if ($element->getAttribute('class') === 'ft02') {
            $text = $element->textContent;
            $email = utf8_decode($text);
        }
        $tcpdf = new TCPDF();
        $tcpdf->AddPage();
        $tcpdf->writeHTML($page);
        $filename = $name . '.pdf';
        $path = __DIR__ . '/pdf/' . $filename;
        $tcpdf->Output($path, 'F');
        $mail = new Mail();
        $to = $email;
        $subject = 'PDF fájl feltöltve';
        $message = 'A következő PDF fájlt tölthette fel: ' . $filename;
        $from = 'server@email.com';
        $mail->sendMail($to, $subject, $message, $from, $path);
    }

    array_map('unlink', glob(__DIR__ . '/pdf/*.*'));
    rmdir(__DIR__ . '/pdf');

    $return = array(
        'status' => 200,
        'message' => $i . ' email elküldve küldve'
    );
    http_response_code(200);
} catch (Exception $e) {
    $return = array(
        'status' => 400
    );
    http_response_code(400);
}

print_r(json_encode($return));
