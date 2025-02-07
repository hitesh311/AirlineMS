<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['flight_id'])) {
    $flightID = $_POST['flight_id'];

    $host = "localhost";
    $username = "root";
    $password_db = "";
    $database = "db";

    $conn = new mysqli($host, $username, $password_db, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch reserved seats for the flight
$reservedSeatQuery = $conn->prepare("SELECT Seat_Number FROM seat WHERE FlightId = ? AND Seat_Status = 1");
$reservedSeatQuery->bind_param("i", $flightID);
$reservedSeatQuery->execute();
$reservedSeatResult = $reservedSeatQuery->get_result();

$reservedSeats = [];
while ($seatRow = $reservedSeatResult->fetch_assoc()) {
    $reservedSeats[] = $seatRow['Seat_Number'];
}

$reservedSeatQuery->close();


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['num_tickets'])) {
        $numTickets = $_POST['num_tickets'];

        // Check if the form is submitted with selected seats
        if (isset($_POST['selected_seats'])) {
            $selectedSeats = json_decode($_POST['selected_seats']);

            // Insert data into the "seat" table for each selected seat
            $insertSeat = $conn->prepare("INSERT INTO seat (FlightId, Seat_Number, Seat_Status) VALUES (?, ?, 1)");

            if (!$insertSeat) {
                die("Prepare failed: " . $conn->error);
            }

            $insertSeat->bind_param("iis", $flightID, $seatNumber);

            // Generate a random PNR number (6-9 digits)
            $pnrNumber = mt_rand(100000, 999999999);

            foreach ($selectedSeats as $seatNumber) {
                $insertSeat->execute();
                if ($insertSeat->errno) {
                    die("Execute failed: " . $insertSeat->error);
                }
            }

            // Close the prepared statement
            $insertSeat->close();

            // Redirect to payment.php after booking seats
            header("Location: pdetails.php?flight_id=$flightID&num_tickets=$numTickets&selected_seats=$selectedSeats");
            exit();
        }
    }

    // Fetch available seats
    $query = $conn->prepare("SELECT Available_Seats FROM planes WHERE FlightId = ?");
    $query->bind_param("i", $flightID);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $availableSeats = $row['Available_Seats'];

        // Close the prepared statement
        $query->close();
    } else {
        echo "Error: Unable to fetch available seats.";
        $conn->close();
        exit();
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Error: Flight ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST Airlines - Select Seat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('254361.png') center/cover no-repeat fixed;
            color: white;
        }

        header {
            background-color: #333;
            padding: 1rem;
        }

        .logo {
            font-size: 2rem;
            color: white;
        }

        .form-container {
            text-align: center;
            margin-top: 50px;
        }

        label {
            font-weight: bold;
        }

        input {
            padding: 10px;
            font-size: 16px;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
            position: relative;
            bottom: 0;
        }

        .seat-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        label {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">FAST Airlines</div>
    </header>
    <div class="form-container">
        <h2>Select Seats</h2>
        <form method="post" action="payment.php" onsubmit="return validateForm()">
            <input type="hidden" name="flight_id" value="<?php echo $flightID; ?>">
            <label for="num_tickets">Number of Tickets:</label>
            <input type="number" name="num_tickets" id="num_tickets" min="1" max="<?php echo $availableSeats; ?>" required>
            <br>
            <button type="button" onclick="showAvailableSeats()">Show Available Seats</button>
            <div id="availableSeats" style="display: none;">
                <label for="selected_seats">Select Seats:</label>
                <div class="seat-container">
                <?php
    for ($i = 1; $i <= $availableSeats; $i++) {
        if (!in_array($i, $reservedSeats)) {  // Only display if the seat is not reserved
            echo "<label for='seat$i'><input type='checkbox' name='selected_seats[]' value='$i' id='seat$i'> Seat $i </label>";
            if (($i - 1) % 5 === 0) {
                echo "<br>";
            }
        }
    }
    ?>
                </div>
            </div>
            <br>
            <div style="text-align: center;">
                <button type="submit" id="bookTicketsBtn">Proceed to Payment</button>
            </div>
        </form>
    </div>
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function validateForm() {
            var numTickets = document.getElementById('num_tickets').value;
            var checkboxes = document.querySelectorAll('input[name="selected_seats[]"]:checked');
            var selectedSeats = [];

            checkboxes.forEach(function (checkbox) {
                selectedSeats.push(checkbox.value);
            });

            if (selectedSeats.length !== parseInt(numTickets)) {
                alert('Please select exactly ' + numTickets + ' seats.');
                return false;
            }

            // Set the value of the hidden input for selected seats
            document.querySelector('input[name="selected_seats"]').value = JSON.stringify(selectedSeats);

            return true;
        }

        function showAvailableSeats() {
            var numTickets = document.getElementById('num_tickets').value;
            var checkboxes = document.querySelectorAll('input[name="selected_seats[]"]');
            
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].disabled = false;
            }

            document.getElementById('availableSeats').style.display = 'block';
        }
    </script>
</body>
</html>
