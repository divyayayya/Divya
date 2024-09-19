<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Check</title>
</head>
<body>
    <h1 style='display: inline-block; margin-right: 20px;'>Sign In</h1></br>
    <form action="process_login.php" method="post">
        <label for="userID">Enter User ID:</label>

        <input type="text" id="userID" name="userID" value="<?php echo isset($_GET['userID']) ? htmlspecialchars($_GET['userID']) : ''; ?>" />

        <input type="submit" value="Check User">

        <?php
        $_SESSION = [];
        if (isset($_GET['error']) && $_GET['error'] === 'true') {
            echo '<p style="color: red;">User ID does not exist. Please re-enter UserID.</p>';
        }
        ?>
    </form>
</body>
</html>