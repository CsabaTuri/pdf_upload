<?php

require_once('mail.php');

header('Content-Type: application/json');
header('Content-Type: text/html; charset=utf-8');

if (!isset($_FILES['pdf-file']) || $_FILES['pdf-file']['error'] !== 0) {
    die('Hiba történt a fájl feltöltése közben!');
}

try {

    $file = $_FILES['pdf-file']['tmp_name'];

    if (!file_exists(__DIR__ . '/pdf')) {
        mkdir(__DIR__ . '/pdf', 0777, true);
    }

    if (!file_exists(__DIR__ . '/decrypt')) {
        mkdir(__DIR__ . '/decrypt', 0777, true);
    }

    exec('qpdf --decrypt ' . $file . ' ' . __DIR__ . '/decrypt/decrypt.pdf');
    exec('python3 split_pdf.py' . ' ' . __DIR__ . '/decrypt/decrypt.pdf' . ' ' . __DIR__ . '/pdf/', $output, $return);

    $dir = __DIR__ . '/pdf/';
    $dh  = opendir($dir);
    $i   = 0;
    while (false !== ($filename = readdir($dh))) {
        if($filename == '.' || $filename == '..') continue;
        $email =  'email' .  $i . '@email.com';
        $path  = __DIR__ . '/pdf/' . $filename;
        $mail = new Mail();
        $to = $i . '@email.com';
        $subject = 'PDF fájl feltöltve';
        $message = 'A következő PDF fájlt tölthette fel: ' . $filename;
        $from = 'server@email.com';
        $mail->sendMail($to, $subject, $message, $from, $path);
        $i++;
    }

    closedir($dh);

    array_map('unlink', glob(__DIR__ . '/pdf/*.*'));
    rmdir(__DIR__ . '/pdf');

    array_map('unlink', glob(__DIR__ . '/decrypt/*.*'));
    rmdir(__DIR__ . '/decrypt');

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
