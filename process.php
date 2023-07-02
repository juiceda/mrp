<?php
// Establish a connection to the MySQL database
$servername = "localhost";
$username = "mrpdbuser";
$password = "madhavrampankaj";
$dbname = "mrpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the form data
$customerID = $_POST['customer_id'];
$retailerID = $_POST['retailer_id'];
$rewardPoints = $_POST['reward_points'];
$redeemPoints = $_POST['redeem_points'];
$orderValue = $_POST['order_value'];

// Get the current balance from the ledger table
$sqlBalance = "SELECT `balance` FROM `ledger` WHERE `customerId` = '$customerID' AND `retailerId` = '$retailerID'";
$resultBalance = $conn->query($sqlBalance);

if ($resultBalance->num_rows > 0) {
    $rowBalance = $resultBalance->fetch_assoc();
    $currentBalance = $rowBalance['balance'];
    $finalBalance = $currentBalance + $rewardPoints;

    // Update the ledger table with the final balance
    $sqlLedger = "UPDATE `ledger` SET `balance` = '$finalBalance' WHERE `customerId` = '$customerID' AND `retailerId` = '$retailerID'";
} else {
    // Handle the case where no previous ledger entry exists
    $finalBalance = $rewardPoints;

    // Insert a new entry into the ledger table
    $sqlLedger = "INSERT INTO `ledger` (`customerId`, `retailerId`, `balance`, `status`)
            VALUES ('$customerID', '$retailerID', '$finalBalance', 'active')";
}

// Insert the data into the "transaction" table
$sqlTransaction = "INSERT INTO `transaction` (`customerId`, `retailerId`, `rewardPoints`, `redeemPoints`, `orderValue`) 
        VALUES ('$customerID', '$retailerID', '$rewardPoints', '$redeemPoints', '$orderValue')";

if ($conn->query($sqlTransaction) === TRUE && $conn->query($sqlLedger) === TRUE) {
    // Close the database connection
    $conn->close();

    // Redirect to the retailerHome.html page
    header("Location: retailerHome.php");
    exit;
} else {
    // Display a warning message if there's an error
    echo "Error: " . $conn->error;
    $conn->close();
}
?>
