<?php
$conn = new mysqli('localhost', 'root', '', 'db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function performCheckIn($conn, $pnrNumber)
{
    $query = "SELECT PassengerId, FlightId FROM passenger WHERE PNRnumber = '$pnrNumber'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $passengerId = $row['PassengerId'];
        $flightId = $row['FlightId'];

        $checkinDateTime = date('Y-m-d H:i:s');
        $checkinStatus = 1;

        $insertQuery = "INSERT INTO checkin (PassengerId, FlightId, CheckIn_Date_Time, CheckIn_Status) 
                        VALUES ('$passengerId', '$flightId', '$checkinDateTime', '$checkinStatus')";

        $insertResult = $conn->query($insertQuery);

        if ($insertResult) {
            return "Check-in successful!";
        } else {
            return "Check-in failed. Please try again.";
        }
    } else {
        return "Error fetching data. Please try again.";
    }
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["pnrNumber"])) {
        $pnrNumber = $_POST["pnrNumber"];
        $message = performCheckIn($conn, $pnrNumber);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST Airlines - Check In</title>
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

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 2rem;
        }

        .admin-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-top: 0;
            background: url('254361.png') center/cover no-repeat;
            min-height: 100vh;
            color: white;
            position: relative;
            padding: 20px;
        }

        .admin-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .action-button {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-bottom: 10px;
        }

        .action-button:hover {
            background-color: #45a049;
        }

        .go-back-button {
            background-color: #333;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-bottom: 10px;
        }

        .go-back-button:hover {
            background-color: #555;
        }

        .input-box {
            width: 300px;
            padding: 10px;
            border: 2px solid #333;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: transparent;
        }

        .success-message {
            color: white;
            font-weight: bold;
            margin-top: 10px;
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
        </nav>
    </header>

    <div class="admin-container">
        <h2>Check In</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="pnrNumber">Enter PNR Number:</label>
            <input type="text" id="pnrNumber" name="pnrNumber" class="input-box" required>
            <button type="submit" class="action-button">Check In</button>
        </form>

        <?php
        if ($message) {
            echo "<p class='success-message'>$message</p>";
        }
        ?>
        <button onclick="window.location.href='ALogin.php'" class="go-back-button">Go Back</button>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
