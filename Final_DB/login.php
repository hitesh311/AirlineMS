<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $host = "localhost";
    $username = "root";
    $password_db = "";
    $database = "db";

    $conn = new mysqli($host, $username, $password_db, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM accounts WHERE Email='$email' AND Password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        header("Location: ALogin.php");
        exit();
    } else {
        $error_message = 'Invalid email or password. Please try again.';
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST Airlines - Login</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0; 
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
        .login-container { 
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
        .login-container h2 {
            font-size: 24px; 
            margin-bottom: 20px; 
        }
        #login-form { 
            width: 300px; 
            text-align: left; 
        }
        #login-form label, 
        #login-form input { 
            display: block; 
            margin-bottom: 10px; 
        }
        #login-form input[type="text"], 
        #login-form input[type="password"] { 
            width: calc(100% - 20px); 
            padding: 10px; 
            font-size: 16px; 
            background-color: transparent; 
            border: 3px solid #333; 
            color: #333; 
        }
        #login-form button { 
            background-color: #4caf50; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            cursor: pointer; 
            font-size: 16px; 
            border-radius: 5px; 
            transition: background-color 0.3s ease; 
        }
        #login-form button:hover { 
            background-color: #45a049; 
        }
        #login-message { 
            margin-top: 10px; 
            font-size: 14px; 
            color: red; 
        }
        .create-account-link { 
            margin-top: 20px; 
        }
        .create-account-link a { 
            color: #edede9; 
            text-decoration: underline; 
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

    <div class="login-container">
        <h2>Login</h2>

        <form id="login-form" method="post" action="">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <?php if(isset($error_message)) echo '<div id="login-message">'.$error_message.'</div>'; ?>
            <button type="submit">Login</button>
        </form>

        <div class="create-account-link">
            Don't have an account? <a href="signup.php"> Create an Account</a>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>