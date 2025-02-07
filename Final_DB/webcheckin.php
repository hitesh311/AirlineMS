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
$passengerDetails = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pnrNumber = $_POST["pnrNumber"];

    $sql = "SELECT p.PassengerId, p.FlightId, p.Booked_Ticket, c.CheckIn_Status
            FROM passenger p
            LEFT JOIN checkin c ON p.PassengerId = c.PassengerId
            WHERE p.PNRnumber = '$pnrNumber'";

    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        $passengerDetails = "<table border='1'>";
        $passengerDetails .= "<tr><th>Passenger ID</th><th>Flight ID</th><th>Booked Ticket</th><th>Check-in Status</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $passengerDetails .= "<tr>";
            $passengerDetails .= "<td>" . $row["PassengerId"] . "</td>";
            $passengerDetails .= "<td>" . $row["FlightId"] . "</td>";
            $passengerDetails .= "<td>" . $row["Booked_Ticket"] . "</td>";
            
            if ($row["CheckIn_Status"] == 1) {
                $passengerDetails .= "<td>Checked-in</td>";
            } else {
                $passengerDetails .= "<td>Not Checked-in</td>";
            }

            $passengerDetails .= "</tr>";
        }

        $passengerDetails .= "</table>";
    } else {
        $passengerDetails = "No seat exists for the provided PNR number.";
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

        input[type="text"] {
            width: 300px;
            padding: 10px;
            margin-bottom: 15px;
            background-color: transparent;
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

        .passenger-details {
            margin-top: 20px;
            text-align: left;
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
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <div class="hero-content">
        <form method="POST" action="">
            <label for="pnrNumber">Enter PNR Number:</label>
            <input type="text" name="pnrNumber" value="<?php echo $pnrNumber; ?>" required>
            <input type="submit" value="Check-in Status">
        </form>

        <div class="passenger-details">
            <?php echo $passengerDetails; ?>
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