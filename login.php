<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = '<script>alert("Invalid Username Or Password")</script>';
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = '<script>alert("Invalid Username Or Password")</script>';
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>User | Login</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
</head>

<body>
    <?php
    if (!empty($login_err)) {
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>
    <div id="particles-js"></div>
    <div class="center">
        <h1>Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="txt_field">
                <input type="text" required name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span></span>
                <label>Username</label>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="txt_field">
                <input type="password" required name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span></span>
                <label>Password</label>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="pass">Forgot Password?</div>
            <input type="submit" value="Login">
            <div class="signup_link">
                Not a member? <a href="./register.php">Signup</a>
            </div>
        </form>
    </div>
</body>
<script>
    $(document).ready(function() {
        function disableBack() {
            window.history.forward()
        }

        window.onload = disableBack();
        window.onpageshow = function(evt) {
            if (evt.persisted) disableBack()
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="https://threejs.org/examples/js/libs/stats.min.js"></script>
<script src="assets/js/background.js"></script>

</html>