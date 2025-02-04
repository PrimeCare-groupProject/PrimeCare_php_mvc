
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require __DIR__ . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'PHPMailer.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Exception.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'SMTP.php';

/**
 * Sends an email using PHPMailer.
 *
 * @param string $to The recipient's email address.
 * @param string $subject The subject of the email.
 * @param string $body The HTML body of the email.
 * @param string $from The sender's email address. Default is 'support.primecare@gmail.com'.
 * @param string $fromName The sender's name. Default is 'PrimeCare Support Team'.
 * @param string|null $imagePath The path to an image to embed in the email. Default is null.
 * 
 * @return array An associative array with 'error' and 'message' keys indicating the status of the email sending.
 * 
 * @throws \PHPMailer\PHPMailer\Exception If there is an error in sending the email.
 */

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
