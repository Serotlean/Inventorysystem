<?php
// Include the necessary files and database connection
include 'includes/config.php';

// Handle form submission for logging mortality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['log_mortality'])) {
    $fish_id = $_POST['fish_id'];
    $cause_of_death = $_POST['cause_of_death'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO mortality_records (fish_id, cause_of_death, notes) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $fish_id, $cause_of_death, $notes);

    if ($stmt->execute()) {
        echo "Mortality logged successfully.";
        // Update fish status to Dead
        $update_sql = "UPDATE fish SET health_status = 'Dead' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('i', $fish_id);
        $update_stmt->execute();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch the fish data to show in the form
$sql = "SELECT * FROM fish WHERE health_status != 'Dead'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Mortality</title>
</head>
<body>
    <h1>Track Fish Mortality</h1>

    <form method="POST">
        <label for="fish_id">Fish ID:</label>
        <select name="fish_id" required>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
        </select><br><br>

        <label for="cause_of_death">Cause of Death:</label>
        <input type="text" name="cause_of_death" required><br><br>

        <label for="notes">Notes:</label>
        <textarea name="notes" rows="4" cols="50" placeholder="Any symptoms before death, environment conditions, etc."></textarea><br><br>

        <button type="submit" name="log_mortality">Log Mortality</button>
    </form>
</body>
</html>
