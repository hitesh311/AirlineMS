<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Plane</title>
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
        }

        header {
            background-color: #333;
            color: white;
            padding: 1rem 0;
        }

        h2 {
            text-align: center;
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

        input {
            width: calc(100% - 20px);
            padding: 10px;
            font-size: 16px;
            background-color: transparent;
            border: 3px solid #333;
            color: white;
            margin-bottom: 10px;
            box-sizing: border-box;
            border-radius: 5px;
        }

        input[type="submit"],
        input[type="button"] {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli('localhost', 'root', '', 'db');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $flightIdToRemove = $_POST["flightIdToRemove"];

        $sqlFetch = "SELECT * FROM planes WHERE FlightId = '$flightIdToRemove'";
        $resultFetch = $conn->query($sqlFetch);

        if ($resultFetch->num_rows > 0) {
            $row = $resultFetch->fetch_assoc();

            if (isset($_POST["remove"])) {
                $flightIdToRemove = $_POST["flightIdToRemove"];

                $deleteSql = "DELETE FROM planes WHERE FlightId = '$flightIdToRemove'";

                if ($conn->query($deleteSql) === TRUE) {
                    echo "<p class='message'>Plane with ID $flightIdToRemove removed. Redirecting...</p>";
                    header("refresh:3;url=remove.php");  // Redirects to the search page (remove.php)
                    exit; 
                } else {
                    echo "<p class='message'>Error removing record: " . $conn->error . "</p>";
                }
            }
            ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="flightIdToRemove" value="<?php echo $flightIdToRemove; ?>">
                <label for="flightId">Flight ID:</label>
                <input type="text" name="flightId" value="<?php echo $row["FlightId"]; ?>" readonly><br>
                <label for="flightNo">Flight Number:</label>
                <input type="text" name="flightNo" value="<?php echo $row["FlightNo"]; ?>" readonly><br>
                <label for="departureAirport">Departure Airport:</label>
                <input type="text" name="departureAirport" value="<?php echo $row["Departure_Airport"]; ?>" readonly><br>
                <label for="arrivalAirport">Arrival Airport:</label>
                <input type="text" name="arrivalAirport" value="<?php echo $row["Arrival_Airport"]; ?>" readonly><br>
                <label for="departureDateTime">Departure Date Time:</label>
                <input type="text" name="departureDateTime" value="<?php echo $row["Departure_Date_Time"]; ?>" readonly><br>
                <label for="arrivalDateTime">Arrival Date Time:</label>
                <input type="text" name="arrivalDateTime" value="<?php echo $row["Arrival_Date_Time"]; ?>" readonly><br>
                <label for="aircraftId">Aircraft ID:</label>
                <input type="text" name="aircraftId" value="<?php echo $row["Aircraft_Id"]; ?>" readonly><br>
                <label for="availableSeats">Available Seats:</label>
                <input type="text" name="availableSeats" value="<?php echo $row["Available_Seats"]; ?>" readonly><br>
                <label for="ticketPrice">Ticket Price:</label>
                <input type="text" name="ticketPrice" value="<?php echo $row["Ticket_Price"]; ?>" readonly><br>

                <div>
                    <input type="submit" name="remove" value="Remove">
                    <input type="button" value="Go Back" onclick="history.go(-1);">
                </div>
            </form>
            <?php
        } else {
            echo "<p class='message'>Flight ID $flightIdToRemove not found. Redirecting...</p>";
            header("refresh:3;url=remove.php");  
            exit; 
        }

        $conn->close();
    } else {
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Enter Flight ID to Remove: <input type="text" name="flightIdToRemove" required>
            <input type="submit" value="Search">
            <a href='achoice.html' class='message'><input type='button' value='Go Back'></a>
        </form>
        <?php
    }
    ?>
</body>

</html>
