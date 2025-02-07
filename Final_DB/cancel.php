<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            background-image: url('254361.png');
            background-size: cover;
            background-position: center top;
            color: white;
            position: relative;
            min-height: 100vh;
        }

        header {
            background-color: #333;
            color: white;
            padding: 1rem 0;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
            background-color: transparent;
            padding: 20px;
            border-radius: 10px;
            margin-top: 50px;
            color: white;
        }

        label {
            display: inline-block;
            margin-bottom: 8px;
            width: 30%;
        }

        input {
            width: 68%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 3px solid #555; 
            border-radius: 5px;
            background-color: transparent;
            outline: none; 
        }

        input:focus {
            border-color: #45a049; 
        }


        button[type="submit"],
        button[type="button"] {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
            position: absolute;
            bottom: 0;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">FAST Airlines</div>
        </nav>
    </header>
    <?php
$pnrNumber = "";
$errorMsg = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli('localhost', 'root', '', 'db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $pnrNumber = isset($_POST["pnrNumber"]) ? $_POST["pnrNumber"] : "";

    if (empty($pnrNumber)) {
        $errorMsg = "Error: PNR Number is required.";
    } else {
        // Fetch flightId and seatNumber from the passenger table
        $getSeatInfo = "SELECT FlightId, Booked_Ticket FROM passenger WHERE PNRnumber = '$pnrNumber'";
        $result = $conn->query($getSeatInfo);

        if ($result->num_rows > 0) {
            $seatData = $result->fetch_assoc();
            $flightId = $seatData['FlightId'];
            $seatNumber = $seatData['Booked_Ticket'];

            // Delete from seat table
            $deleteSeat = "DELETE FROM seat WHERE FlightId = '$flightId' AND Seat_Number = '$seatNumber'";
            $conn->query($deleteSeat);

            // Delete from passenger table
            $deletePassenger = "DELETE FROM passenger WHERE PNRnumber = '$pnrNumber'";
            $conn->query($deletePassenger);

            // Update available seats in planes table
            $updateAvailableSeats = "UPDATE planes SET Available_Seats = Available_Seats + 1 WHERE FlightId = '$flightId'";
            $conn->query($updateAvailableSeats);

            $successMsg = "Ticket canceled successfully. Available seats updated.";
        } else {
            $errorMsg = "Error: Unable to fetch details from the passenger table.";
        }
    }

    $conn->close();
}
?>

<!-- Rest of your HTML code remains unchanged -->

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="pnrNumber">PNR Number:</label>
        <input type="text" name="pnrNumber" value="<?php echo $pnrNumber; ?>" required>
        <button type="submit">Cancel Ticket</button>
        <button type="button" onclick="goBack()">Go Back</button>
    </form>

    <?php
    if (!empty($errorMsg)) {
        echo "<p class='message'>$errorMsg</p>";
    }

    if (!empty($successMsg)) {
        echo "<p class='message'>$successMsg</p>";
    }
    ?>

    <footer>
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function goBack() {
            window.location.href = "ALogin.php";
        }
    </script>
</body>
</html>