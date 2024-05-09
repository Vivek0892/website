<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Instantiate PHPMailer
    $mail = new PHPMailer(true);
    print_r($mail->Body);

    try {
        
        // Server settings
        $mail->SMTPDebug = false;                     
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'vivekh.harikumar@gmail.com';                     
        $mail->Password   = 'utqr qene cezc rsua';                               
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;                                  

        // Recipients
        $mail->setFrom('vivekh.harikumar@gmail.com', 'Vivek');       
        $mail->addAddress('vivekh.harikumar@gmail.com', 'Vivek');    
        
        // Content
        $mail->isHTML(true);                                      
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body    = "Name: $name <br> Email: $email <br> Message: $message";

        // Send email
        $mail->send();
        echo 'Message has been sent successfully!';
        print_r($mail->Body);
    } catch (Exception $e) {
        print_r($mail->Body);
    }
}
?>
