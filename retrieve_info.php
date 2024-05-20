<?php
// Include PHPMailer library
require 'vendor\phpmailer\phpmailer\PHPMailer\src\PHPMailer.php';
require 'vendor\phpmailer\phpmailer\PHPMailer\src\SMTP.php';
require 'vendor\phpmailer\phpmailer\PHPMailer\src\Exception.php';



// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Path to PHPMailer autoload.php file


// Include database connection file
include ('connection.php');

// Initialize variables
$message = 'MG';
$showForm = true;

// Check if form is submitted
if(isset($_POST["sub"])) {
    // Get license plate from form
    $license_plate = $_POST['license_plate'];

    // Query to select car owner details by license plate
    $sql = "SELECT * FROM carowner WHERE BINARY licence = '$license_plate'";
   
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Extracting information from the database
            $user_name = $row["username"];
            $mail_sentTo = $row["email"];
            $current_amount = $row["amount"];

            // Setting the deduction amount
            $deduction_amount = 50;

            // Calculating amount after deduction
            $after_deduct = $current_amount - $deduction_amount;

            // Update amount in the database
            $licence_plate = $row["licence"];
            $sql_update = "UPDATE carowner SET amount = $after_deduct WHERE BINARY licence = '$licence_plate'";

            if ($conn->query($sql_update) === TRUE) {
                // Sending HTML email notification
                try {
                    // Create a new PHPMailer instance
                    $mail = new PHPMailer(true);

                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = ''; // email address
                    $mail->Password = ''; // Gmail password generated on the google account app password section
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('', '');
                    $mail->addAddress($mail_sentTo);

                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = 'Toll plaza';
                    $mail->Body = "<p>Dear $user_name,<br><br>We are writing to inform you about a recent toll deduction from your account associated with license plate number $license_plate. The deducted amount from your wallet was $deduction_amount. Your current wallet balance is $after_deduct. Please note that this balance reflects the remaining amount after the recent toll deduction.<br><br>If you have any questions or concerns regarding this deduction or your wallet balance, please don't hesitate to contact us at mmakash666@gmail.com.<br><br>Thank you for your attention to this matter.<br><br>Best regards,<br>Mahbub Mokaddes Akash<br>Director of Toll plaza</p>";

                    // Send email
                    $mail->send();
                    $message = 'Email has been sent successfully!';
                    $showForm = false; // Hide the form after processing
                } catch (Exception $e) {
                    $message = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $message = "Error updating record: " . $conn->error;
            }
        }
    } else {
        $message = "No car owner found with the provided license plate.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deduction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .amount {
            font-size: 42px;
            color: #ff5c5c;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .message {
            font-size: 20px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 15px 30px;
            font-size: 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php if ($showForm): ?>
        
        <form method="post" action="">
            <label for="license_plate">License Plate:</label>
            <input type="text" name="license_plate" id="license_plate" required>
            <button type="submit" name="sub">Submit</button>
        </form>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    <?php else: ?>
        
        <div class="container">
            <h1>Amount Deducted</h1>
            <div class="amount">50 tk</div>
            <div class="message"><?php echo $message; ?></div>
            <a href="index.html" class="btn">Go Back</a>
        </div>
    <?php endif; ?>
</body>
</html>
