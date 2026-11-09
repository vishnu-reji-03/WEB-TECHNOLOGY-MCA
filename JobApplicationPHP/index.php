
<?php

$conn = mysqli_connect("localhost", "root", "root", "studentDetails");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$message = "";
$messageType = "";

$name = "";
$email = "";
$mobile = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form values
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate name using regular expression
    if (!preg_match("/^[A-Za-z ]{2,100}$/", $name)) {

        $message = "Invalid name. Use only letters and spaces.";
        $messageType = "error";

    // Validate email
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Invalid email address.";
        $messageType = "error";

    // Validate mobile number using regular expression
    } elseif (!preg_match("/^[6-9][0-9]{9}$/", $mobile)) {

        $message = "Enter a valid 10-digit mobile number.";
        $messageType = "error";

    // Validate username
    } elseif (strlen($username) < 3) {

        $message = "Username must contain at least 3 characters.";
        $messageType = "error";

    // Validate password
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/", $password)) {

        $message = "Password must have at least 8 characters, one uppercase letter, one lowercase letter and one number.";
        $messageType = "error";

    } else {

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert applicant details using prepared statement
        $sql = "INSERT INTO StudDetails (name, email, mobile, username, password)
        VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $name,
            $email,
            $mobile,
            $username,
            $hashedPassword
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "Application submitted successfully!";
            $messageType = "success";

            // Clear form values after successful submission
            $name = "";
            $email = "";
            $mobile = "";
            $username = "";

        } else {

            $message = "Error submitting application.";
            $messageType = "error";
        }

        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Application Form</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            width: 400px;
            margin: 40px auto;
            padding: 25px;
            background-color: white;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .success {
            color: green;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Job Application Form</h2>

    <?php
    if ($message != "") {
        echo "<p class='$messageType'>" . htmlspecialchars($message) . "</p>";
    }
    ?>

    <form method="POST" action="">

        <label>Name</label>
        <input type="text" name="name"
               value="<?php echo htmlspecialchars($name); ?>"
               required>

        <label>Email</label>
        <input type="email" name="email"
               value="<?php echo htmlspecialchars($email); ?>"
               required>

        <label>Mobile Number</label>
        <input type="text" name="mobile"
               value="<?php echo htmlspecialchars($mobile); ?>"
               required>

        <label>Username</label>
        <input type="text" name="username"
               value="<?php echo htmlspecialchars($username); ?>"
               required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Submit Application</button>

    </form>

</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
