<?php
// Helper functions for crontab operations
function getCurrentCrontab() {
    $output = [];
    exec('crontab -l', $output, $return_var);
    if ($return_var === 1) {
        return []; // No crontab set
    }
    return $output;
}

function saveCrontab($crontab) {
    $tmp_file = tempnam(sys_get_temp_dir(), 'crontab');
    file_put_contents($tmp_file, implode(PHP_EOL, $crontab) . PHP_EOL);
    exec("crontab $tmp_file");
    unlink($tmp_file);
}

function addCronJob($new_job) {
    $crontab = getCurrentCrontab();
    if (!in_array($new_job, $crontab)) {
        $crontab[] = $new_job;
        saveCrontab($crontab);
        echo "<p>Cron job added successfully.</p>";
    } else {
        echo "<p>Cron job already exists.</p>";
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
}
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
        <input type="text" id="cron_job" name="cron_job" placeholder="* * * * * /path/to/script.sh">
        <button type="submit" name="action" value="add">Add Cron Job</button>
        
        <h2>Remove an Existing Cron Job</h2>
        <label for="remove_cron_job">Cron Job:</label>
        <input type="text" id="remove_cron_job" name="remove_cron_job" placeholder="* * * * * /path/to/script.sh">
        <button type="submit" name="action" value="remove">Remove Cron Job</button>
    </form>

    <h2>Current Crontab</h2>
    <pre><?php echo htmlspecialchars(implode("\n", getCurrentCrontab())); ?></pre>
</body>
</html>