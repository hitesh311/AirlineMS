<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST Airlines - Sign Up</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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

        .signup-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-top: 0;
            background: url('254361.png') center/cover no-repeat;
            min-height: 100vh;
            color: white;
            position: relative;
            padding-bottom: 60px;
        }

        .signup-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        #signup-form {
            width: 300px;
            text-align: left;
        }

        #signup-form .form-group {
            margin-bottom: 15px;
        }

        #signup-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        #signup-form input[type="text"],
        #signup-form input[type="email"],
        #signup-form input[type="password"],
        #signup-form textarea {
            width: calc(100% - 20px);
            padding: 10px;
            font-size: 16px;
            background-color: transparent;
            border: 2px solid #333;
            color: white;
            margin-bottom: 10px;
            box-sizing: border-box;
            resize: none;
            height: 40px;
        }

        .signup-button {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .signup-button:hover {
            background-color: #45a049;
        }

        .login-link {
            margin-top: 20px;
            color: white;
            font-size: 20px;
        }

        .login-link a {
            color: #edede9;
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .footer {
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
                <li><a href="login.html">Login / Sign-up</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="admin.html">Admin</a></li>
            </ul>
        </nav>
    </header>

    <div class="signup-container">
        <h2>Create an Account</h2>

        <?php
        function handleFormSubmission()
        {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $contact_no = $_POST['contact_no'];
            $address = $_POST['address'];
            $password = $_POST['password'];
            $re_password = $_POST['re_password'];

            if ($password !== $re_password) {
                echo '<div class="error-message">Error: Passwords do not match.</div>';
            } else {
                $host = "localhost";
                $username = "root";
                $password_db = "";
                $database = "db";

                $conn = new mysqli($host, $username, $password_db, $database);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $emailCheckQuery = "SELECT * FROM accounts WHERE Email = '$email'";
                $emailResult = $conn->query($emailCheckQuery);

                if ($emailResult->num_rows > 0) {
                    echo '<div class="error-message">Error: Email is already in use.</div>';
                } else {
                    $contactNoCheckQuery = "SELECT * FROM accounts WHERE Contact_No = '$contact_no'";
                    $contactNoResult = $conn->query($contactNoCheckQuery);

                    if ($contactNoResult->num_rows > 0) {
                        echo '<div class="error-message">Error: Contact number is already in use.</div>';
                    } else {
                        $sql = "INSERT INTO accounts (First_Name, Last_Name, Email, Contact_No, Address, Password, Re_Password) VALUES ('$first_name', '$last_name', '$email', '$contact_no', '$address', '$password', '$re_password')";

                        if ($conn->query($sql) === TRUE) {
                            header("Refresh: 3; URL = login.php");
                            echo "<p class='message'>Account Created Successfully. Redirecting...</p>";
                            exit();
                        } else {
                            echo '<div class="error-message">Error: ' . $sql . '<br>' . $conn->error . '</div>';
                        }
                    }
                }

                $conn->close();
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            handleFormSubmission();
        }
        ?>

        <form id="signup-form" method="post" action="">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contact_no">Contact Number:</label>
                <input type="text" id="contact_no" name="contact_no" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="re_password">Re-enter Password:</label>
                <input type="password" id="re_password" name="re_password" required>
            </div>
            <button type="submit" class="signup-button">Sign Up</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Log In</a>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2023 FAST Airlines. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
