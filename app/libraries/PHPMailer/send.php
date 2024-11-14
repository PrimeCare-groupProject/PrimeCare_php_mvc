<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require __DIR__ . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'PHPMailer.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Exception.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'SMTP.php';


function sendMail($to, $subject, $body, $from = 'support.primecare@gmail.com', $fromName = 'PrimeCare Support Team', $imagePath = null) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = 'wvedmund@gmail.com'; // SMTP username
        $mail->Password   = 'xrslfcozhskheuwp'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        if ($imagePath) {
            // $imagePath2 = ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $imagePath;
            // $mail->AddEmbeddedImage($imagePath2,'1', 'logo');
        }

        $mail->send();
        $status['error'] = false;
        $status['message'] = 'Message has been sent';
    } catch (Exception $e) {
        $status['error'] = true;
        $status['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return $status;
}
