<?php

include 'database.php';

if (!($conn instanceof mysqli)) {
    die("Database connection is not properly initialized.");
}


if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}


$firstname = $surname = $middleinitial = $email = $password = $confirm_password = "";
$firstname_err = $surname_err = $middleinitial_err = $email_err = $password_err = $confirm_password_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["firstname"]))) {
        $firstname_err = "Please enter your first name.";
    } else {
        $firstname = trim($_POST["firstname"]);
    }


    if (empty(trim($_POST["surname"]))) {
        $surname_err = "Please enter your surname.";
    } else {
        $surname = trim($_POST["surname"]);
    }


    if (empty(trim($_POST["middleinitial"]))) {
        $middleinitial_err = "Please enter your middle initial.";
    } elseif (strlen(trim($_POST["middleinitial"])) > 1) {
        $middleinitial_err = "Middle initial must be a single character.";
    } else {
        $middleinitial = trim($_POST["middleinitial"]);
    }
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {

        $sql = "SELECT id FROM teachers WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = htmlspecialchars(trim($_POST["email"]));
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $email_err = "This email is already registered.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                error_log("Database error: " . $stmt->error, 3, "error_log.txt");
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (!empty($password_err) || ($password != $confirm_password)) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    if (!$firstname_err && !$surname_err && !$middleinitial_err && !$email_err && !$password_err && !$confirm_password_err) {
        $sql = "INSERT INTO teachers (firstname, surname, middleinitial, email, password) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssss", $param_firstname, $param_surname, $param_middleinitial, $param_email, $param_password);
            $param_firstname = $firstname;
            $param_surname = $surname;
            $param_middleinitial = $middleinitial;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash password
            if ($stmt->execute()) {
                header("location: login_teacher.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Teacher</title>
    <link rel="stylesheet" href="style.css">
    <style>
        h1 {
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <h1>REGISTER AS TEACHER</h1>
    <form action="register_teacher.php" method="post">
        <div>
            <label>First Name</label>
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>">
            <span><?php echo $firstname_err; ?></span>
        </div>
        <div>
            <label>Surname</label>
            <input type="text" name="surname" value="<?php echo htmlspecialchars($surname); ?>">
            <span><?php echo $surname_err; ?></span>
        </div>
        <div>
            <label>Middle Initial</label>
            <input type="text" name="middleinitial" value="<?php echo htmlspecialchars($middleinitial); ?>">
            <span><?php echo $middleinitial_err; ?></span>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span><?php echo $email_err; ?></span>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password">
            <span><?php echo $confirm_password_err; ?></span>
        </div>
        <p>Already have an account? <a href="login_teacher.php">Log In</a></p>
        <div>
            <input type="submit" value="Register">
        </div>
        <p>Already have account? <a href="login_teacher.php">Log In</a></p>
    </form>
</body>
</html>
