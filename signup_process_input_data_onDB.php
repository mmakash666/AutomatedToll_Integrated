<?php
include('connection.php');

 if(isset($_POST['submit'])) {
    // Get form data when the user typed in the form field
    $nid = $_POST["nid"];
    $username = $_POST["username"];
    $card_details = $_POST["card_details"];
    $bank_details = $_POST["bank_details"];
    $amount = $_POST["amount"];
    $license_plate = $_POST["license_plate"];
    $email = $_POST["email"];
    $password = $_POST['password'];

    



    // Prepare and bind SQL statement to insert the datas onto the database
    $stmt = $conn->prepare("INSERT INTO carowner (nid, username, cardetails, bankdetails, amount, licence, email,password) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
    $stmt->bind_param("ssssssss", $nid, $username, $card_details, $bank_details, $amount, $license_plate, $email,$password);

    
    if ($stmt->execute()) {
        //if the input data is successfully loaded then this will execute
        //writing a form in html to show the data
        header("Location:Thank_you.php");
        exit();




    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
