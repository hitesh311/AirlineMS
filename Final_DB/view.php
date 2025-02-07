<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Planes</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            color: white;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        input[type="button"] {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        input[type="button"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">FAST Airlines</div>
    </header>

    <h2>View Planes</h2>

    <?php
  
    $conn = new mysqli('localhost', 'root', '', 'db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

 
    $sql = "SELECT * FROM planes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Flight ID</th>";
        echo "<th>Flight Number</th>";
        echo "<th>Departure Airport</th>";
        echo "<th>Arrival Airport</th>";
        echo "<th>Departure Date Time</th>";
        echo "<th>Arrival Date Time</th>";
        echo "<th>Aircraft ID</th>";
        echo "<th>Available Seats</th>";
        echo "<th>Ticket Price</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['FlightId']}</td>";
            echo "<td>{$row['FlightNo']}</td>";
            echo "<td>{$row['Departure_Airport']}</td>";
            echo "<td>{$row['Arrival_Airport']}</td>";
            echo "<td>{$row['Departure_Date_Time']}</td>";
            echo "<td>{$row['Arrival_Date_Time']}</td>";
            echo "<td>{$row['Aircraft_Id']}</td>";
            echo "<td>{$row['Available_Seats']}</td>";
            echo "<td>{$row['Ticket_Price']}</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No data available in the 'planes' table.</p>";
    }

    $conn->close();
    ?>

    <input type="button" value="Go Back" onclick="location.href='achoice.html';">
</body>

</html>
