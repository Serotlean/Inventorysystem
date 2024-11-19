<?php
// Include the necessary files and database connection
include 'includes/config.php';

// Fetch health status and mortality data
$sql_health = "SELECT health_status, COUNT(*) AS count FROM fish GROUP BY health_status";
$sql_mortality = "SELECT cause_of_death, COUNT(*) AS count FROM mortality_records GROUP BY cause_of_death";

$health_result = $conn->query($sql_health);
$mortality_result = $conn->query($sql_mortality);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health and Mortality Reports</title>
</head>
<body>
    <h1>Fish Health and Mortality Reports</h1>

    <h2>Fish Health Status</h2>
    <table border="1">
        <tr>
            <th>Health Status</th>
            <th>Count</th>
        </tr>
        <?php while ($row = $health_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['health_status']; ?></td>
                <td><?php echo $row['count']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <h2>Mortality Report</h2>
    <table border="1">
        <tr>
            <th>Cause of Death</th>
            <th>Count</th>
        </tr>
        <?php while ($row = $mortality_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['cause_of_death']; ?></td>
                <td><?php echo $row['count']; ?></td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>
