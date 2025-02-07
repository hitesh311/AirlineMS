<?php
$planeInfoTable = "";
$goBackUrl = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['from']) && isset($_POST['to'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];

    $host = "localhost";
    $username = "root";
    $password_db = "";
    $database = "db";

    $conn = new mysqli($host, $username, $password_db, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = $conn->prepare("SELECT p.*, COUNT(s.SeatId) AS BookedSeats, fs.Status
                        FROM planes p
                        LEFT JOIN seat s ON p.FlightId = s.FlightId
                        LEFT JOIN flight_shedule fs ON p.FlightId = fs.FlightId
                        WHERE p.Departure_Airport=? AND p.Arrival_Airport=?
                        GROUP BY p.FlightId");
    $query->bind_param("ss", $from, $to);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $planeInfoTable = "<table>
            <thead>
                <tr>
                    <th>Departure Date and Time</th>
                    <th>Arrival Date and Time</th>
                    <th>Available Seats</th>
                    <th>Ticket Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>";

            while ($row = $result->fetch_assoc()) {
                $departureDateTime = $row['Departure_Date_Time'];
                $arrivalDateTime = $row['Arrival_Date_Time'];
                $totalSeats = $row['Available_Seats']; 
                $ticketPrice = $row['Ticket_Price'];
                $flightID = $row['FlightId'];
                $bookedSeats = $row['BookedSeats'];
                $flightStatus = $row['Status'];
            
                $availableSeats = $totalSeats - $bookedSeats;
            
                $planeInfoTable .= "<tr>
                    <td>$departureDateTime</td>
                    <td>$arrivalDateTime</td>
                    <td>$availableSeats</td>
                    <td>$ticketPrice</td>
                    <td>";

                // Check if the flight status is not 1 (assuming 1 means active)
                if ($flightStatus != 1) {
                    $planeInfoTable .= "<form method='post' action='rprocess.php'>
                            <input type='hidden' name='flight_id' value='$flightID'>
                            <button type='submit'>Book Now</button>
                        </form>";
                }

                $planeInfoTable .= "</td>
                </tr>";
            }            

        $planeInfoTable .= "</tbody></table>";

        $goBackUrl = $_SERVER['PHP_SELF'];
    } else {
        $planeInfoTable = "<div id='no-flights'>No flights available for the specified locations. Please try again.</div>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST Airlines - Book a Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        header {
            background-color: #333;
            padding: 1rem;
        }

        .logo {
            font-size: 2rem;
            color: white;
        }

        .book-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-top: 0;
            background: url('254361.png') center/cover no-repeat;
            min-height: 100vh;
            color: white;
            position: relative;
        }

        .book-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        #no-flights {
            margin-top: 20px;
            font-size: 16px;
        }

        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 80%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        .go-back-button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        .go-back-button:hover {
            background-color: #555;
        }

        .footer-content {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
        }

        #booking-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        #booking-form input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            background-color: transparent;
            border: 3px solid #333;
            color: #333;
            box-sizing: border-box;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        #booking-form button {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        #booking-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">FAST Airlines</div>
    </header>

    <div class="book-container">
        <?php if ($planeInfoTable) : ?>
            <?php echo $planeInfoTable; ?>
            <form id="go-back-form" method="post" action="<?php echo $goBackUrl; ?>">
                <button type="submit" class="go-back-button">Go Back</button>
            </form>
        <?php else : ?>
            <h2>Book a Ticket</h2>
            <form id="booking-form" method="post" action="">
                <label for="from">From:</label>
                <input type="text" id="from" name="from" required>
                <label for="to">To:</label>
                <input type="text" id="to" name="to" required>
                <button type="submit">Check Flights</button>
            </form>
            <form id="go-back-form-start" method="get" action="ALogin.php">
                <button type="submit" class="go-back-button">Go Back</button>
            </form>
        <?php endif; ?>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
