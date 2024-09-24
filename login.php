<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Check</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('images/office2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: grayscale(100%);
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 22px;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            display: none; /* Hides the label, using placeholders instead */
        }

        input[type="text"], input[type="password"] {
            padding: 15px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 16px;
            background-color: #f5f5f5;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input[type="submit"] {
            padding: 15px;
            background-image: linear-gradient(to right, #ff416c, #ff4b2b);
            color: #fff;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            width: 50%;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-image: linear-gradient(to right, #ff4b2b, #ff416c);
        }

        .error-message {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Account Login</h1>
        <form action="process_login.php" method="post">
            <input type="text" id="userID" name="userID" placeholder="User ID" required />
            <input type="submit" value="Login">
            <?php
            $_SESSION = [];
            if (isset($_GET['error']) && $_GET['error'] === 'true') {
                echo '<p class="error-message">User ID does not exist. Please re-enter User ID.</p>';
            }
            ?>
        </form>
    </div>
</body>

</html>
