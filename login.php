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
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Slight transparency for contrast */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        input[type="text"] {
            padding: 10px;
            width: 100%;
            max-width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
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
        <h1>Sign In</h1>
        <form action="process_login.php" method="post">
            <label for="userID">Enter User ID:</label>
            <input type="text" id="userID" name="userID" value="<?php echo isset($_GET['userID']) ? htmlspecialchars($_GET['userID']) : ''; ?>" required />

            <input type="submit" value="Sign In">

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
