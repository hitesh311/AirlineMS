<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $pnrNumber = "";
    $bookingDetails = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pnrNumber = $_POST["pnrNumber"];

        $sql = "SELECT p.PassengerId, p.FlightId, p.Booked_Ticket,
            pl.Departure_Airport, pl.Arrival_Airport, pl.Departure_Date_Time, pl.Arrival_Date_Time
            FROM passenger p
            INNER JOIN planes pl ON p.FlightId = pl.FlightId
            WHERE p.PNRnumber = '$pnrNumber'";

        $result = $conn->query($sql);

        if ($result !== false) {
            if ($result->num_rows > 0) {
                $bookingDetails = "<table border='1'>";
                $bookingDetails .= "<tr><th>Passenger ID</th><th>Flight ID</th><th>Booked Ticket</th>
                                    <th>Departure Airport</th><th>Arrival Airport</th><th>Departure Date & Time</th>
                                    <th>Arrival Date & Time</th></tr>";

                while ($row = $result->fetch_assoc()) {
                    $bookingDetails .= "<tr>";
                    $bookingDetails .= "<td>" . $row["PassengerId"] . "</td>";
                    $bookingDetails .= "<td>" . $row["FlightId"] . "</td>";
                    $bookingDetails .= "<td>" . $row["Booked_Ticket"] . "</td>";
                    $bookingDetails .= "<td>" . $row["Departure_Airport"] . "</td>";
                    $bookingDetails .= "<td>" . $row["Arrival_Airport"] . "</td>";
                    $bookingDetails .= "<td>" . $row["Departure_Date_Time"] . "</td>";
                    $bookingDetails .= "<td>" . $row["Arrival_Date_Time"] . "</td>";
                    $bookingDetails .= "</tr>";
                }

                $bookingDetails .= "</table>";
            } else {
                $bookingDetails = "No booking details found for the provided PNR number.";
            }
        } else {
            $bookingDetails = "Error retrieving booking details. Please try again.";
        }
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST Airlines</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #333;
            color: white;
            padding: 1rem 0;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 2rem;
        }

        .logo {
            font-size: 1.5rem;
        }

        ul {
            list-style: none;
            display: flex;
        }

        li {
            margin: 0 1rem;
        }

        a {
            color: white;
            text-decoration: none;
        }

        .hero-content {
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

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        label {
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: 300px;
            padding: 10px;
            background-color: transparent;
            margin-bottom: 15px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 12px 30px;
            font-size: 18px;
            cursor: pointer;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        .booking-details {
            margin-top: 20px;
            text-align: left;
        }

        .booking-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .go-back-btn {
            background-color: #333;
            color: white;
            padding: 12px 30px;
            font-size: 18px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .go-back-btn:hover {
            background-color: #555;
        }

        th {
            background-color: #333;
            color: white;
        }

        .footer-content {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">FAST Airlines</div>
            <ul>
                <li><a href="project.html">Home</a></li>
                <li><a href="login.php">Login / Sign-up</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="admin.html">Admin</a></li>
            </ul>
        </nav>
    </header>

    <div class="hero-content">
        <form method="POST" action="">
            <label for="pnrNumber">Enter PNR Number:</label>
            <input type="text" name="pnrNumber" value="<?php echo $pnrNumber; ?>" required>
            <input type="submit" value="View My Booking">
        </form>

        <div class="booking-details">
            <?php echo $bookingDetails; ?>
        </div>

        <a href="project.html" class="go-back-btn">Go Back</a>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
