<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$from = $to = "";
$tableContent = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = $_POST["from"];
    $to = $_POST["to"];

    // SQL query to fetch data based on user input
    $sql = "SELECT * FROM planes WHERE Departure_Airport = '$from' AND Arrival_Airport = '$to'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $tableContent .= "<table border='1'>";
        $tableContent .= "<tr><th>FlightId</th><th>FlightNo</th><th>Departure_Airport</th><th>Arrival_Airport</th><th>Departure_Date_Time</th><th>Arrival_Date_Time</th><th>Aircraft_Id</th><th>Available_Seats</th><th>Ticket_Price</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $tableContent .= "<tr>";
            $tableContent .= "<td>" . $row["FlightId"] . "</td>";
            $tableContent .= "<td>" . $row["FlightNo"] . "</td>";
            $tableContent .= "<td>" . $row["Departure_Airport"] . "</td>";
            $tableContent .= "<td>" . $row["Arrival_Airport"] . "</td>";
            $tableContent .= "<td>" . $row["Departure_Date_Time"] . "</td>";
            $tableContent .= "<td>" . $row["Arrival_Date_Time"] . "</td>";
            $tableContent .= "<td>" . $row["Aircraft_Id"] . "</td>";
            $tableContent .= "<td>" . $row["Available_Seats"] . "</td>";
            $tableContent .= "<td>" . $row["Ticket_Price"] . "</td>";
            $tableContent .= "</tr>";
        }

        $tableContent .= "</table>";
    } else {
        $tableContent = "No flights found from $from to $to";
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

        .menu-links {
            margin-top: 30px;
            margin-bottom: 20px;
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
            width: 150px;
            padding: 10px;
            margin-bottom: 15px;
            background-color: transparent;
            font-size: 16px;
            margin-right: 10px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
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
            <label for="from">From:</label>
            <input type="text" name="from" value="<?php echo $from; ?>" required>

            <label for="to">To:</label>
            <input type="text" name="to" value="<?php echo $to; ?>" required>

            <input type="submit" value="Search Flights">
        </form>

        <?php echo $tableContent; ?>

        <a href="project.html" class="go-back-btn">Go Back</a>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
