<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST Airlines - Flight Status</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('254361.png') center/cover no-repeat;
            color: white;
            position: relative;
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

        .form-container {
            margin: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-size: 1.2rem;
            margin-right: 1rem;
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
            margin-top: 1rem;
        }

        .go-back-btn:hover {
            background-color: #555;
        }

        input {
            font-size: 1rem;
            padding: 0.5rem;
        }

        input[type="text"] {
            background-color: transparent;
            border: 1px solid white;
            border-radius: 5px;
            margin-bottom: 1rem;
            color: white;
            padding: 0.5rem;
        }

        input[type="submit"],
        .go-back-btn {
            margin-top: 1rem; 
            display: inline-block;
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
            margin-right: 1rem; 
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        .flight-details {
            margin-top: 2rem;
            font-size: 1.2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            color: white;
        }

        th,
        td {
            border: 1px solid white;
            padding: 0.5rem;
            text-align: left;
        }

        th {
            background-color: #555;
        }

        .footer-content {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
            position: fixed;
            bottom: 0;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">FAST Airlines</div>
            <ul>
                <li><a href="project.php">Home</a></li>
                <li><a href="login.php">Login / Sign-up</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="admin.html">Admin</a></li>
            </ul>
        </nav>
    </header>

    <div class="form-container">
        <h2>Flight Status</h2>
        <form method="POST" action="">
            <label for="flightId">Flight ID:</label>
            <input type="text" name="flightId" required>
            <br> 
            <input type="submit" value="Check Status">
            <a href="project.html" class="go-back-btn">Go Back</a>
        </form>

        <div class="flight-details">
            <?php
            
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "db";

            $conn = new mysqli($servername, $username, $password, $dbname);

          
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

      
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $flightId = $_POST["flightId"];

                // SQL query to fetch flight details
                $sql = "SELECT FlightId, Departure_Date_Time, Arrival_Date_Time, Status FROM flight_shedule WHERE FlightId = $flightId";
                $result = $conn->query($sql);

               
                if ($result !== false && $result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Flight ID</th><th>Departure Date and Time</th><th>Arrival Date and Time</th><th>Status</th></tr>";
                    $row = $result->fetch_assoc();
                    echo "<tr>";
                    echo "<td>" . $row["FlightId"] . "</td>";
                    echo "<td>" . $row["Departure_Date_Time"] . "</td>";
                    echo "<td>" . $row["Arrival_Date_Time"] . "</td>";
                    echo "<td>" . ($row["Status"] == 0 ? "Still Not Fly" : "Fly") . "</td>";
                    echo "</tr>";
                    echo "</table>";
                } else {
                    echo "Flight details not found for the provided Flight ID.";
                }
            }

 
            $conn->close();
            ?>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
