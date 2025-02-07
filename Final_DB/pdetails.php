<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['flight_id']) && 
        isset($_POST['num_tickets']) && 
        isset($_POST['selected_seats'])
    ) {
        $flightID = $_POST['flight_id'];
        $numTickets = $_POST['num_tickets'];
        $selectedSeats = $_POST['selected_seats'];

        $host = "localhost";
        $username = "root";
        $password_db = "";
        $database = "db";

        $conn = new mysqli($host, $username, $password_db, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

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

?>

        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>FAST Airlines - Passenger Details</title>
            <style>
                /* Your CSS styles go here */
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background: url('254361.png') center/cover no-repeat fixed;
                    color: white;
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
                }

                label {
                    font-weight: bold;
                    display: block;
                    margin: 10px 0;
                }

                input, .seat-number {
                    padding: 5px;
                    font-size: 14px;
                    width: 50%;
                    box-sizing: border-box;
                    background: transparent;
                    color: white;
                    border: 1px solid white;
                    border-radius: 5px;
                    margin-bottom: 10px;
                    display: inline-block;
                }

                .seat-number {
                    background: transparent;
                    color: white;
                    padding: 5px;
                    border: 1px solid white;
                    border-radius: 5px;
                    margin-bottom: 10px;
                    display: inline-block;
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
                <div class='logo'>FAST Airlines</div>
            </header>
            <div class='form-container'>
                <h2>Passenger Details</h2>
                <form method='post' action='payment.php' onsubmit='return validateForm()'>
                    <input type='hidden' name='flight_id' value='<?php echo $flightID; ?>'>
                    <input type='hidden' name='num_tickets' value='<?php echo $numTickets; ?>'>
                    <input type='hidden' name='selected_seats' value='<?php echo implode(",", $selectedSeats); ?>'>
                    
                    <?php for ($i = 1; $i <= $numTickets; $i++) : ?>
                        <label>First Name:
                            <input type='text' name='fname[]' placeholder='Enter your first name' required>
                        </label>
                        <label>Last Name:
                            <input type='text' name='lname[]' placeholder='Enter your last name' required>
                        </label>
                        <label>Email:
                            <input type='email' name='email[]' placeholder='Enter your email' required>
                        </label>
                        <!-- Display seat number for each passenger -->
                        <div class='seat-number'>Seat Number: <?php echo $selectedSeats[$i - 1]; ?></div>
                        <br><br>
                    <?php endfor; ?>

                    <button type='submit'>Proceed to Payment</button>
                </form>
            </div>
            <footer>
                <div class='footer-content'>
                    <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
                </div>
            </footer>
        </body>
        </html>
        <?php

        $conn->close();

    } else {
        echo "Error: Insufficient data provided.";
        echo "flight_id: " . (isset($_POST['flight_id']) ? $_POST['flight_id'] : 'Not set') . ", num_tickets: " . (isset($_POST['num_tickets']) ? $_POST['num_tickets'] : 'Not set') . ", selected_seats: " . (isset($_POST['selected_seats']) ? $_POST['selected_seats'] : 'Not set');
    }
} else {
    echo "Error: Invalid request.";
}
?>
