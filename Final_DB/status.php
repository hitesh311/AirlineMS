<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Status</title>
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
            width: calc(68% - 20px);
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 3px solid #555;
            border-radius: 5px;
            background-color: transparent;
        }

        button[type="submit"],
        button[type="button"] {
            background-color: #4caf50;
            color: white;
            padding: 12px 12px;
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
    $flightId = "";
    $errorMsg = "";
    $successMsg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli('localhost', 'root', '', 'db');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $flightId = isset($_POST["flightId"]) ? $_POST["flightId"] : "";

        if (empty($flightId)) {
            $errorMsg = "Error: Flight ID is required.";
        } else {
            $checkFlightStatus = "SELECT Status FROM flight_shedule WHERE FlightId = '$flightId'";
            $flightStatusResult = $conn->query($checkFlightStatus);

            if ($flightStatusResult->num_rows > 0) {
                $flightStatusData = $flightStatusResult->fetch_assoc();
                $flightStatus = $flightStatusData['Status'];

                if ($flightStatus == 0) {
                    $updateFlightStatus = "UPDATE flight_shedule SET Status = 1 WHERE FlightId = '$flightId'";
                    $conn->query($updateFlightStatus);
                    $successMsg = "Status updated successfully.";
                } else {
                    $errorMsg = "Error: The plane has already flown. Status cannot be updated.";
                }
            } else {
                $errorMsg = "Error: Flight ID not found.";
            }
        }

        $conn->close();
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="flightId">Flight ID:</label>
        <input type="text" name="flightId" value="<?php echo $flightId; ?>" required>
        <button type="submit">Update Status</button>
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
            window.location.href = "achoice.html";
        }
    </script>
</body>

</html>
