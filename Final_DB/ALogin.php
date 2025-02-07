<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST Airlines - Admin Panel</title>
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
        }

        .admin-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .action-button-container {
            display: flex;
            margin-bottom: 10px;
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
            margin-right: 10px;
        }

        .action-button:hover {
            background-color: #45a049;
        }

        .logout-button {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background-color: transparent;
            border: 2px solid white;
            cursor: pointer;
            border-radius: 50%;
            padding: 15px;
            font-size: 20px;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #45a049;
        }

        .logout-text {
            font-size: 16px;
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
        <h2>Welcome</h2>
        <div class="action-button-container">
            <a href="uview.php" class="action-button">View Planes</a>
            <a href="book.php" class="action-button">Book a Ticket</a>
            <a href="checkin.php" class="action-button">Check In</a>
            <a href="cancel.php" class="action-button">Cancel a Ticket</a>
        </div>
        <button class="logout-button" onclick="location.href='login.php'"><span class="logout-text">Logout</span></button>
    </div>
    
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
