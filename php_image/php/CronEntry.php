<?php
session_start();

// Define your password here
$correct_password =  $_ENV['SE_VNC_PASSWORD'];


// Helper functions for crontab operations
function isValidCronJob($cron_job) {
    $allowed_commands = ['sv_run', 'w1_run', 'w2_run', 'w3_run', 'sw_run'];
    // Split the cron job into parts
    $parts = preg_split('/\s+/', $cron_job);
    
    // Ensure there are at least 6 parts (minute, hour, day of month, month, day of week, command)
    if (count($parts) < 6) {
        return false;
    }

    // Extract the command part
    $command = end($parts);

    // Validate the command
    if (!in_array($command, $allowed_commands)) {
        return false;
    }

    // Validate the cron schedule check if the first five parts are numbers, asterisks, or ranges
    foreach (array_slice($parts, 0, 5) as $part) {
        if (!preg_match('/^(\*|\d+|\d+\-\d+|\/\d+|\*\/\d+)$/', $part)) {
            return false;
        }
    }

    return true;
}

function getCurrentCrontab() {
    $output = [];
    exec('sudo crontab -l', $output, $return_var);
    if ($return_var === 1) {
        return []; // No crontab set
    }
    return $output;
}

function saveCrontab($crontab) {
    $tmp_file = tempnam(sys_get_temp_dir(), 'crontab');
    file_put_contents($tmp_file, implode(PHP_EOL, $crontab) . PHP_EOL);
    exec("sudo crontab $tmp_file");
    unlink($tmp_file);
}

function addCronJob($new_job) {
    if (isValidCronJob($new_job)) {
        $crontab = getCurrentCrontab();
        if (!in_array($new_job, $crontab)) {
            $crontab[] = $new_job;
            saveCrontab($crontab);
            echo "<p>Cron job added successfully.</p>";
        } else {
            echo "<p>Cron job already exists.</p>";
        }
    } else {
        echo "<p>Invalid cron job format or command used.</p>";
    }
}

function removeCronJob($job_to_remove) {
    $crontab = getCurrentCrontab();
    $crontab = array_filter($crontab, function($job) use ($job_to_remove) {
        return trim($job) !== trim($job_to_remove);
    });
    saveCrontab($crontab);
    echo "<p>Cron job removed successfully.</p>";
}

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' && !empty($_POST['cron_job'])) {
            addCronJob(trim($_POST['cron_job']));
        } elseif ($_POST['action'] === 'remove' && !empty($_POST['remove_cron_job'])) {
            removeCronJob(trim($_POST['remove_cron_job']));
        }
    }

    if (isset($_POST['password']) && $_POST['password'] === $correct_password) {
        $_SESSION['authenticated'] = true;
    } else {
        $error = "Invalid password. Please try again.";
    }
}

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Crontab</title>
</head>
<body>
    <h1>Edit Crontab</h1>
    <form method="post">
        <h2>Add a New Cron Job</h2>
        <label for="cron_job">Cron Job:</label>
        <input type="text" id="cron_job" name="cron_job" placeholder="* * * * * cmd">
        <button type="submit" name="action" value="add">Add Cron Job</button>
        
        <h2>Remove an Existing Cron Job</h2>
        <label for="remove_cron_job">Cron Job:</label>
        <input type="text" id="remove_cron_job" name="remove_cron_job" placeholder="* * * * * cmd">
        <button type="submit" name="action" value="remove">Remove Cron Job</button>
    </form>

    <h2>Current Crontab</h2>
    <pre><?php echo htmlspecialchars(implode("\n", getCurrentCrontab())); ?></pre>
</body>
</html>

<?php
} else {
?>

<!DOCTYPE html>
<html lang="en">
<body>
    <h1>Please enter the password to continue</h1>
    <form method="post">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Submit</button>
    </form>

    <?php
    // Display an error message if the password is incorrect
    if (isset($error)) {
        echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>';
    }
    ?>

</body>
</html>

<?php
}
?>