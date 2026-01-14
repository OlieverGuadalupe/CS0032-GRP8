<?php
// anomaly_alerts.php

// Mock data: In a real app, this would come from your SQL Database
$current_high_value_revenue = 45000; 
$previous_week_revenue = 55000; // Previous baseline

// Logic: Detect a "Sudden Drop" (Anomaly)
// Trigger if revenue drops by more than 15%
$drop_threshold = 0.15;
$percentage_change = ($previous_week_revenue - $current_high_value_revenue) / $previous_week_revenue;

$is_anomaly = ($percentage_change > $drop_threshold);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task 4: Anomaly Detection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">System Monitor: High-Value Segment</h5>
        </div>
        <div class="card-body">
            <?php if ($is_anomaly): ?>
                <div class="alert alert-danger d-flex align-items-center p-4 mb-0" role="alert">
                    <div class="display-6 me-3">⚠️</div>
                    <div>
                        <h4 class="alert-heading fw-bold">Anomaly Detected!</h4>
                        <p class="mb-0">Sudden <strong><?php echo round($percentage_change * 100); ?>% drop</strong> in High-Value Segment revenue detected compared to last week.</p>
                        <hr>
                        <button class="btn btn-danger btn-sm">Generate Migration Report</button>
                        <button class="btn btn-outline-secondary btn-sm">Acknowledge Alert</button>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-success mb-0">
                    ✅ Segment migration is within normal variance (+/- 5%).
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>