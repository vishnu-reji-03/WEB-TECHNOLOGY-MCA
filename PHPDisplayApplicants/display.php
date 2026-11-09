
<?php

$conn = mysqli_connect("localhost", "root", "root", "applicant_db");

if (!$conn) {
    die("Connection failed");
}

$sql = "SELECT * FROM applicants";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Applicant Details</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>

    <h2>Applicant Details</h2>

    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile Number</th>
            <th>Username</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['mobile']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "</tr>";

        }

        mysqli_close($conn);

        ?>

    </table>

</body>
</html>
