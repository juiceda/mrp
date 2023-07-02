<?php
// Establish a connection to the MySQL database
$servername = "localhost";
$username = "mrpdbuser";
$password = "madhavrampankaj";
$dbname = "mrpdb";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the loyalty settings for the retailer
$sqlSettings = "SELECT * FROM `loyaltySettings` WHERE `retailerId` = '101'";
$resultSettings = $conn->query($sqlSettings);

$rowSettings = $resultSettings->fetch_assoc();
$rewardPercentage = $rowSettings['reward'];

// Close the database connection
$conn->close();
?>

<html>
<head>
  <title>Sale Order Entry</title>
  <style>
    /* Your CSS styles here */
  </style>
  <script>
    function calculateRewards() {
      var orderValue = parseFloat(document.getElementById("order_value").value);
      var rewardPercentage = <?php echo $rowSettings['reward']; ?>;
      var rewardPoints = orderValue * (rewardPercentage / 100); // Calculate reward points based on the reward percentage from loyalty settings
      var redeemPoints = 10; // Set redeem points

      document.getElementById("reward_points").value = rewardPoints.toFixed(2); // Display reward points
      document.getElementById("redeem_points").value = redeemPoints.toFixed(2); // Display redeem points
    }
  </script>
</head>
<body>
  <h1>Sale Order Entry</h1>
  <form action="process.php" method="POST">
    <label for="customer_id">Customer ID:</label>
    <input type="text" name="customer_id" id="customer_id" required>

    <input type="hidden" name="retailer_id" value="101">

    <label for="order_value">Order Value:</label>
    <input type="number" name="order_value" id="order_value" min="0" required oninput="calculateRewards()">

    <label for="reward_points">Reward Points:</label>
    <input type="text" name="reward_points" id="reward_points" readonly>

    <label for="redeem_points">Redeem Points:</label>
    <input type="text" name="redeem_points" id="redeem_points" readonly>

    <input type="submit" value="Submit">
  </form>
</body>
</html>
