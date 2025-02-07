<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Aircraft</title>
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

        input[type="number"] {
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
    $aircraftId = $aircraftNumber = $aircraftType = $totalSeats = $manufacturer = $model = "";
    $errorMsg = "";
    $successMsg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli('localhost', 'root', '', 'db');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $aircraftId = isset($_POST["aircraftId"]) ? $_POST["aircraftId"] : "";
        $aircraftNumber = isset($_POST["aircraftNumber"]) ? $_POST["aircraftNumber"] : "";
        $aircraftType = isset($_POST["aircraftType"]) ? $_POST["aircraftType"] : "";
        $totalSeats = isset($_POST["totalSeats"]) ? $_POST["totalSeats"] : "";
        $manufacturer = isset($_POST["manufacturer"]) ? $_POST["manufacturer"] : "";
        $model = isset($_POST["model"]) ? $_POST["model"] : "";

        if (intval($totalSeats) < 0 || intval($aircraftId) < 0 || intval($aircraftNumber) < 0) {
            $errorMsg = "Error: Invalid input. Please enter non-negative values for Aircraft ID, Aircraft Number, and Total Seats.";
        } elseif (empty($aircraftId) || empty($aircraftNumber) || empty($aircraftType) || empty($totalSeats) || empty($manufacturer) || empty($model)) {
            $errorMsg = "Error: All fields are required.";
        } else {
            
            $checkAircraftId = "SELECT * FROM aircraft WHERE AircraftId = '$aircraftId'";
            $result = $conn->query($checkAircraftId);

            if ($result->num_rows > 0) {
                $errorMsg = "Error: Aircraft ID already exists. Please enter a unique Aircraft ID.";
            } else {
            
            $sql = "INSERT INTO aircraft (AircraftId, Aircraft_Number, Aircraft_Type, Total_Seats, Manufacturer, Model)
                        VALUES ('$aircraftId', '$aircraftNumber', '$aircraftType', '$totalSeats', '$manufacturer', '$model')";

                if ($conn->query($sql) === TRUE) {
                    header("Refresh: 3; URL=craft.php");
                    echo "<p class='message'>Aircraft added successfully. Redirecting...</p>";
                    exit;
                } else {
                    $errorMsg = "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }

        $conn->close();
    }
?>
    <h2>Add Aircraft</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errorMsg)) {
        echo "<p class='message'>Aircraft added successfully. Redirecting...</p>";
    } else {
        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
        echo "Aircraft ID: <input type='text' name='aircraftId' value='$aircraftId' required><br>";
        echo "Aircraft Number: <input type='text' name='aircraftNumber' value='$aircraftNumber' required><br>";
        echo "Aircraft Type: <input type='text' name='aircraftType' value='$aircraftType' required><br>";
        echo "Total Seats: <input type='number' name='totalSeats' value='$totalSeats' required><br>";
        echo "Manufacturer: <input type='text' name='manufacturer' value='$manufacturer' required><br>";
        echo "Model: <input type='number' name='model' value='$model' required><br>";
        echo "<input type='submit' value='Add Aircraft'>";
        echo "</form>";
        echo "<a href='achoice.html' class='message' style='display: inline-block; margin-top: 10px;'><input type='button' value='Go Back' style='background-color: #4caf50; color: white; padding: 12px 20px; font-size: 16px; border: none; cursor: pointer; border-radius: 5px; transition: background-color 0.3s ease;'></a>";
    }

    if (!empty($errorMsg)) {
        echo "<p class='message'>$errorMsg</p>";
    }
    ?>
</body>

</html>
