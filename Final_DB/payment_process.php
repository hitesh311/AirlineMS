<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['flight_id']) && isset($_POST['num_tickets']) && isset($_POST['selected_seats']) && isset($_POST['total_payment']) && isset($_POST['price_per_ticket']) && isset($_POST['payment_method']) && isset($_POST['card_number'])) {

    $flightID = $_POST['flight_id'];
    $numTickets = $_POST['num_tickets'];
    $selectedSeats = explode(",", $_POST['selected_seats']); 
    $totalPayment = $_POST['total_payment'];
    $pricePerTicket = $_POST['price_per_ticket'];
    $paymentMethod = $_POST['payment_method'];
    $cardNumber = $_POST['card_number'];

    function generatePNR() {
        return str_pad(mt_rand(1, 999999999), 10, '0', STR_PAD_LEFT);
    }

    $host = "localhost";
    $username = "root";
    $password_db = "";
    $database = "db";

    $conn = new mysqli($host, $username, $password_db, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $generatedPNR = '';

    $insertPayment = $conn->prepare("INSERT INTO payment (PNRNUMBER, FlightId, Payment_Amount, Payment_Status) VALUES (?, ?, ?, 1)");

    if (!$insertPayment) {
        die("Error in payment prepared statement: " . $conn->error);
    }

    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>FAST Airlines - Booking Successful</title>
        <style>
            /* Your CSS styles go here */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background: url('254361.png') center/cover no-repeat fixed;
                color: white;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            header {
                background-color: #333;
                color: white;
                text-align: left;
                padding: 1rem;
                width: 100%;
                position: fixed;
                top: 0;
            }

            .logo {
                font-size: 2rem;
                color: white;
                margin-left: 20px;
            }

            footer {
                background-color: #333;
                color: white;
                text-align: center;
                padding: 1rem 0;
                width: 100%;
                position: fixed;
                bottom: 0;
            }

            h2 {
                color: #4caf50;
            }

            p {
                font-size: 18px;
                margin-top: 10px;
            }

            table {
                width: 70%;
                margin-top: 20px;
                border-collapse: collapse;
                border: 1px solid white;
                color: white;
            }

            th, td {
                padding: 10px;
                border: 1px solid white;
            }

            button {
                background-color: #4caf50;
                color: white;
                padding: 10px 20px;
                border: none;
                cursor: pointer;
                font-size: 16px;
                border-radius: 5px;
                margin-top: 20px;
                transition: background-color 0.3s ease;
            }

            button:hover {
                background-color: #45a049;
            }

            /* Adjusted styles for background image */
            html, body {
                height: 100%;
                margin: 0;
            }

            body {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background-size: cover;
            }
        </style>
    </head>
    <body>
        <header>
            <div class='logo'>FAST Airlines</div>
        </header>
        <h2>Ticket Booked Successfully!</h2>
    ";

    echo "<table>";
    echo "<tr><th>PNR Number</th><th>Seat Number</th></tr>";

    for ($i = 1; $i <= $numTickets; $i++) {
        $insertPassenger = $conn->prepare("INSERT INTO passenger (PNRnumber, FlightId, Payment, Booked_Ticket) VALUES (?, ?, ?, ?)");
        $insertPayment = $conn->prepare("INSERT INTO payment (PNRNUMBER, FlightId, Payment_Amount,Payment_Date,Payment_Status) VALUES (?, ?, ?,?,1)");
        $generatedPNR = generatePNR();

        $insertPassenger->bind_param("sids", $generatedPNR, $flightID, $pricePerTicket, $selectedSeats[$i-1]);
        $paymentDate = date('Y-m-d H:i:s'); // Current date in Y-m-d format
        $insertPayment->bind_param("ssds", $generatedPNR, $flightID, $pricePerTicket,$paymentDate);
        $insertPayment->execute();

        if ($insertPassenger->execute()) {
            echo "<tr><td>$generatedPNR</td><td>{$selectedSeats[$i-1]}</td></tr>";
        } else {
            echo "Error inserting passenger data.";
        }
        
        
        $insertPayment->close();
           

        $insertPassenger->close();

        $insertSeat = $conn->prepare("INSERT INTO seat (FlightId, Seat_Number, Seat_Status) VALUES (?, ?, 1)");

        $insertSeat->bind_param("ii", $flightID, $selectedSeats[$i-1]);

        if (!$insertSeat->execute()) {
            die("Error inserting seat data: " . $insertSeat->error);
        }

        $insertSeat->close();
    }

    echo "</table>";

   // $insertPayment->bind_param("sdi", $generatedPNR, $flightID, $totalPayment);

    
        echo "
            <h3>Thank you for using our service.</h3>
            <button onclick='goBack()'>Go Back</button>
            <footer>
                <div class='footer-content'>
                    <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
                </div>
            </footer>
            <script>
                function goBack() {
                    window.location.href = 'ALogin.php';
                }
            </script>
        </body>
        </html>
        ";
    

    //$insertPayment->close();

    $conn->close();

} else {
    
    echo "Error: Insufficient data provided.";
}
?>
