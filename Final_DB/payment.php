<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['flight_id']) && isset($_POST['num_tickets']) && isset($_POST['selected_seats'])) {
    $flightID = $_POST['flight_id'];
    $numTickets = $_POST['num_tickets'];
    
    if(is_array($_POST['selected_seats'])){
        $selectedSeats = $_POST['selected_seats'];
    } else {
        $selectedSeats = explode(",", $_POST['selected_seats']);
    } 
    $host = "localhost";
    $username = "root";
    $password_db = "";
    $database = "db";

    $conn = new mysqli($host, $username, $password_db, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    function generatePNR() {
        return str_pad(mt_rand(1, 999999999), 10, '0', STR_PAD_LEFT);
    }

    // Retrieve ticket price from the "planes" table based on flight ID
    $queryPrice = $conn->prepare("SELECT Ticket_Price FROM planes WHERE FlightId = ?");
    $queryPrice->bind_param("i", $flightID);
    $queryPrice->execute();
    $resultPrice = $queryPrice->get_result();

    if ($resultPrice->num_rows > 0) {
        $rowPrice = $resultPrice->fetch_assoc();
        $pricePerTicket = $rowPrice['Ticket_Price'];
    } else {
        $pricePerTicket = 10000.00;
    }

    $totalPayment = $numTickets * $pricePerTicket;
    $queryPrice->close();

    $conn->close();

} else {
    echo "Error: Insufficient data provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST Airlines - Payment</title>
    <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background: url('254361.png') center/cover no-repeat fixed;
                color: white;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
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
                flex: 1; 
            }

            label {
                font-weight: bold;
                display: block;
                margin: 10px 0;
            }

            input, select {
                padding: 8px;
                font-size: 14px;
                margin-bottom: 15px;
                width: 70%;
                box-sizing: border-box;
                background: transparent; 
                color: white;
                border: 1px solid white;
                border-radius: 5px;
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
            }
        </style>
</head>
<body>
    <header>
        <div class="logo">FAST Airlines</div>
    </header>
    <div class="form-container">
        <h2>Payment Details</h2>
        <form method="post" action="payment_process.php">
            <input type="hidden" name="flight_id" value="<?php echo $flightID; ?>">
            <input type="hidden" name="num_tickets" value="<?php echo $numTickets; ?>">
            <input type="hidden" name="selected_seats" value="<?php echo implode(",", $selectedSeats); ?>">
            <input type="hidden" name="total_payment" value="<?php echo $totalPayment; ?>">
            <input type="hidden" name="price_per_ticket" value="<?php echo $pricePerTicket; ?>">

            <label>Total Payment</label>
            <p>Total Payment: $<?php echo $totalPayment; ?></p>

            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" onchange="showCardNumberField()" required>
                <option value="">Select Payment Method</option>
                <option value="credit_card" style="color: black;">Credit Card</option>
                <option value="debit_card" style="color: black;">Debit Card</option>
            </select>

            <div id="cardNumberField" style="display: none;">
                <label for="card_number">Card/Account Number</label>
                <input type="text" name="card_number" id="card_number" placeholder="Enter Card/Account Number" required oninput="showPaymentButtons()">
            </div>

            <div id="confirmPaymentButtons" style="display: none;">
                <label for="confirm_payment">Confirm Payment</label>
                <button type="submit">Accept Payment</button>
                <button type="button" onclick="declinePayment()">Decline Payment</button>
            </div>
        </form>
    </div>
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function showCardNumberField() {
            var paymentMethod = document.getElementById('payment_method').value;
            var cardNumberField = document.getElementById('cardNumberField');
            var confirmPaymentButtons = document.getElementById('confirmPaymentButtons');

            if (paymentMethod === 'credit_card' || paymentMethod === 'debit_card') {
                cardNumberField.style.display = 'block';
                confirmPaymentButtons.style.display = 'none';
            } else {
                cardNumberField.style.display = 'none';
                confirmPaymentButtons.style.display = 'none';
            }
        }

        function showPaymentButtons() {
            var cardNumber = document.getElementById('card_number').value;
            var confirmPaymentButtons = document.getElementById('confirmPaymentButtons');

            if (cardNumber && cardNumber.length >= 10 && cardNumber.length <= 13) {
                confirmPaymentButtons.style.display = 'block';
            } else {
                confirmPaymentButtons.style.display = 'none';
            }
        }

        function declinePayment() {
            alert('Payment declined. Redirecting to the homepage.');
            window.location.href = 'ALogin.php'; 
        }
    </script>

</body>
</html>
