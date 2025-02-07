<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Plane</title>
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

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
        }


        .goback-btn {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .goback-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">FAST Airlines</div>
    </header>

    <h2>Update Plane</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli('localhost', 'root', '', 'db');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $flightIdToUpdate = $_POST["flightIdToUpdate"];

        $sqlFetch = "SELECT * FROM planes WHERE FlightId = '$flightIdToUpdate'";
        $resultFetch = $conn->query($sqlFetch);

        if ($resultFetch->num_rows > 0) {
            $row = $resultFetch->fetch_assoc();

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateButton"])) {
                // Update button is clicked

                $flightNo = $_POST["flightNo"];
                $departureAirport = $_POST["departureAirport"];
                $arrivalAirport = $_POST["arrivalAirport"];
                $departureDateTime = $_POST["departureDateTime"];
                $arrivalDateTime = $_POST["arrivalDateTime"];
                $aircraftId = $_POST["aircraftId"];
                $availableSeats = $_POST["availableSeats"];
                $ticketPrice = $_POST["ticketPrice"];

                // Add validation checks
                if ($availableSeats < 0 || $ticketPrice < 0) {
                    echo "<p class='message'>Available Seats and Ticket Price cannot be less than 0.</p>";
                } elseif (strtotime($arrivalDateTime) < strtotime($departureDateTime)) {
                    echo "<p class='message'>Arrival Date and Time cannot be less than Departure Date and Time.</p>";
                } else {
                    $sqlUpdate = "UPDATE planes SET 
                        FlightNo='$flightNo', 
                        Departure_Airport='$departureAirport', 
                        Arrival_Airport='$arrivalAirport', 
                        Departure_Date_Time='$departureDateTime', 
                        Arrival_Date_Time='$arrivalDateTime', 
                        Aircraft_Id='$aircraftId', 
                        Available_Seats='$availableSeats', 
                        Ticket_Price='$ticketPrice' 
                        WHERE FlightId='$flightIdToUpdate'";

                    $resultUpdate = $conn->query($sqlUpdate);

                    if ($resultUpdate) {
                        echo "<p class='message'>Plane updated successfully. Redirecting...</p>";
                        header("refresh:2;url=update.php");
                        exit(); 
                    } else {
                        echo "<p class='message'>Error updating plane: " . $conn->error . "</p>";
                    }
                }
            }
            ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="flightIdToUpdate" value="<?php echo $flightIdToUpdate; ?>">
                FlightId: <input type="text" name="flightId" value="<?php echo $row["FlightId"]; ?>" readonly><br>
                FlightNo: <input type="text" name="flightNo" value="<?php echo $row["FlightNo"]; ?>" required><br>
                Departure Airport: <input type="text" name="departureAirport" value="<?php echo $row["Departure_Airport"]; ?>" required><br>
                Arrival Airport: <input type="text" name="arrivalAirport" value="<?php echo $row["Arrival_Airport"]; ?>" required><br>
                Departure Date Time: <input type="datetime-local" name="departureDateTime" value="<?php echo date('Y-m-d\TH:i', strtotime($row["Departure_Date_Time"])); ?>" required><br>
                Arrival Date Time: <input type="datetime-local" name="arrivalDateTime" value="<?php echo date('Y-m-d\TH:i', strtotime($row["Arrival_Date_Time"])); ?>" required><br>
                Aircraft Id: <input type="text" name="aircraftId" value="<?php echo $row["Aircraft_Id"]; ?>" required><br>
                Available Seats: <input type="number" name="availableSeats" value="<?php echo $row["Available_Seats"]; ?>" required><br>
                Ticket Price: <input type="number" name="ticketPrice" value="<?php echo $row["Ticket_Price"]; ?>" required><br>
                <input type="submit" name="updateButton" value="Update Plane">
            </form>
            <?php
        } else {
            echo "<p class='message'>Plane with Flight Id $flightIdToUpdate not found. Please enter another Flight Id.</p>";
            ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                Enter Flight Id to Update: <input type="text" name="flightIdToUpdate" required>
                <input type="submit" value="Search">
                <a href='achoice.html' class='message'><input type='button' class='goback-btn' value='Go Back'></a>
            </form>
            <?php
        }

        $conn->close();
    } else {
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Enter Flight Id to Update: <input type="text" name="flightIdToUpdate" required>
            <input type="submit" value="Search">
            <a href='achoice.html' class='message'><input type='button' class='goback-btn' value='Go Back'></a>
        </form>
        <?php
    }
    ?>
</body>

</html>
