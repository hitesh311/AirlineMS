<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Plane</title>
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

        input[type="datetime-local"] {
            margin-bottom: 10px;
        }

        input[type="submit"],
        input[type="button"] {
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        input[type="button"] {
            background-color: #f44336;
            color: white;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="button"]:hover {
            background-color: #d32f2f;
        }

        .message {
            text-align: center;
        }
    </style>
</head>

<body>

<?php
    $flightId = $flightNo = $departureAirport = $arrivalAirport = $departureDateTime = $arrivalDateTime = $aircraftId = $availableSeats = $ticketPrice = "";
    $errorMsg = "";
    $successMsg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli('localhost', 'root', '', 'db');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $flightId = isset($_POST["flightId"]) ? $_POST["flightId"] : "";
        $flightNo = isset($_POST["flightNo"]) ? $_POST["flightNo"] : "";
        $departureAirport = isset($_POST["departureAirport"]) ? $_POST["departureAirport"] : "";
        $arrivalAirport = isset($_POST["arrivalAirport"]) ? $_POST["arrivalAirport"] : "";
        $departureDateTime = isset($_POST["departureDateTime"]) ? $_POST["departureDateTime"] : "";
        $arrivalDateTime = isset($_POST["arrivalDateTime"]) ? $_POST["arrivalDateTime"] : "";
        $aircraftId = isset($_POST["aircraftId"]) ? $_POST["aircraftId"] : "";
        $availableSeats = isset($_POST["availableSeats"]) ? $_POST["availableSeats"] : "";
        $ticketPrice = isset($_POST["ticketPrice"]) ? $_POST["ticketPrice"] : "";

        // Check if AircraftId exists in the Craft table
        $checkAircraftId = "SELECT AircraftId FROM aircraft WHERE AircraftId = '$aircraftId'";
        $resultAircraft = $conn->query($checkAircraftId);

        if ($resultAircraft->num_rows == 0) {
            $errorMsg = "Error: AircraftId '$aircraftId' does not exist. Please enter a valid AircraftId.";
        } elseif (intval($availableSeats) < 0) {
            $errorMsg = "Error: Invalid input for Available Seats. Please enter a non-negative value.";
        } elseif (intval($ticketPrice) < 0) {
            $errorMsg = "Error: Invalid input for Ticket Price. Please enter a non-negative value.";
        } else {
            if ($departureDateTime >= $arrivalDateTime) {
                $errorMsg = "Error: Invalid input for Arrival Date Time. It should be after the Departure Date Time.";
            }
            
            $checkFlightId = "SELECT FlightId FROM planes WHERE FlightId = '$flightId'";
            $result = $conn->query($checkFlightId);

            if ($result->num_rows > 0) {
                $errorMsg = "Error: FlightId '$flightId' already exists. Please choose a different FlightId.";
            }
        }

        if (empty($errorMsg)) {
            $sql = "INSERT INTO planes (FlightId, FlightNo, Departure_Airport, Arrival_Airport, Departure_Date_Time, Arrival_Date_Time, Aircraft_Id, Available_seats, Ticket_Price)
                    VALUES ('$flightId', '$flightNo', '$departureAirport', '$arrivalAirport', '$departureDateTime', '$arrivalDateTime', '$aircraftId', '$availableSeats', '$ticketPrice')";

            if ($conn->query($sql) === TRUE) {
                $scheduleSql = "INSERT INTO flight_shedule (FlightId, Departure_Date_Time, Arrival_Date_Time, Status)
                                VALUES ('$flightId', '$departureDateTime', '$arrivalDateTime', 0)";

                if ($conn->query($scheduleSql) === TRUE) {
                    header("Refresh: 3; URL=add.php");
                    echo "<p class='message'>Plane added successfully. Redirecting...</p>";
                    exit;
                } else {
                    $errorMsg = "Error: " . $scheduleSql . "<br>" . $conn->error;
                }
            } else {
                $errorMsg = "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        $conn->close();
    }
?>


    <header>
        <div class="logo">FAST Airlines</div>
    </header>

    <h2>Add Plane</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errorMsg)) {
        echo "<p class='message'>Plane added successfully. Redirecting...</p>";
    } else {
        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
        echo "FlightId: <input type='text' name='flightId' value='$flightId' required><br>";
        echo "FlightNo: <input type='text' name='flightNo' value='$flightNo' required><br>";
        echo "Departure Airport: <input type='text' name='departureAirport' value='$departureAirport' required><br>";
        echo "Arrival Airport: <input type='text' name='arrivalAirport' value='$arrivalAirport' required><br>";
        echo "Departure Date Time: <input type='datetime-local' name='departureDateTime' value='$departureDateTime' required><br>";
        echo "Arrival Date Time: <input type='datetime-local' name='arrivalDateTime' value='$arrivalDateTime' required><br>";
        echo "Aircraft Id: <input type='text' name='aircraftId' value='$aircraftId' required><br>";
        echo "Available Seats: <input type='number' name='availableSeats' value='$availableSeats' required><br>";
        echo "Ticket Price: <input type='number' name='ticketPrice' value='$ticketPrice' required><br>";
        echo "<input type='submit' value='Add Plane'>";
        echo "</form>";
        echo "<a href='achoice.html' class='message' style='display: inline-block; margin-top: 10px;'><input type='button' value='Go Back' style='background-color: #4caf50; color: white; padding: 12px 20px; font-size: 16px; border: none; cursor: pointer; border-radius: 5px; transition: background-color 0.3s ease;'></a>";  
    }

    if (!empty($errorMsg)) {
        echo "<p class='message'>$errorMsg</p>";
    }
    ?>
</body>

</html>