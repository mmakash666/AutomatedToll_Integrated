<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Info</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
       
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            max-width: 400px;
            width: 100%;
        }
        .input-container {
            margin-bottom: 1.5rem;
        }
        .btn {
            display: inline-block;
            background-color: #4caf50;
            color: #fff;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container">
        <div class="form-container bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6">User Info</h2>
            
                <?php
                include('connection.php');

                if(isset($_POST["sign_sub"])) {
                    $licence = $_POST['lpnumber'];
                    $sql = "SELECT nid, username, cardetails, bankdetails, amount, email FROM carowner WHERE BINARY licence = '$licence'";
                    $result = $conn->query($sql);

                    if($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $nid = $row["nid"];
                            $card_details = $row["cardetails"];
                            $bank_details = $row["bankdetails"];
                            $amount = $row["amount"];
                            $email = $row["email"];

                            // Display user info
                            echo "<div class='input-container'>
                                    <label for='nid' class='block text-gray-700 mb-1'>NID:</label>
                                    <input type='text' id='nid' name='nid' value='$nid' readonly class='input-field'>
                                  </div>";

                            echo "<div class='input-container'>
                                    <label for='card_details' class='block text-gray-700 mb-1'>Card Details:</label>
                                    <input type='text' id='card_details' name='card_details' value='$card_details' readonly class='input-field'>
                                  </div>";
                            echo "<div class='input-container'>
                                    <label for='bank_details' class='block text-gray-700 mb-1'>Bank Details:</label>
                                    <input type='text' id='bank_details' name='bank_details' value='$bank_details' readonly class='input-field'>
                                  </div>";
                            echo "<div class='input-container'>
                                    <label for='amount' class='block text-gray-700 mb-1'>Amount:</label>
                                    <input type='text' id='amount' name='amount' value='$amount' readonly class='input-field'>
                                  </div>";
                            echo "<div class='input-container'>
                                    <label for='email' class='block text-gray-700 mb-1'>Email:</label>
                                    <input type='email' id='email' name='email' value='$email' readonly class='input-field'>
                                  </div>";
                        }
                    }
                }
                ?>
            
            <a href ="index.html" class="btn mt-4">Back</a>
        </div>
    </div>
</body>
</html>
