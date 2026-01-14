<?php
// Initialize variables so they don't cause "null" errors
$prediction_result = "";
$error_message = "";
$confidence = "";

if (isset($_POST['run_forecast'])) {
    $current_rev = (float)$_POST['current_rev'];
    $growth_rate = (float)$_POST['growth_rate'];

    // Use escapeshellarg for security
    $arg1 = escapeshellarg($current_rev);
    $arg2 = escapeshellarg($growth_rate);

    // EXECUTION: Try 'python' first, then 'python3'
    $command = "python predict.py $arg1 $arg2 2>&1";
    $output = shell_exec($command);
    
    $data = json_decode($output, true);

    if (is_array($data) && isset($data['status']) && $data['status'] === 'success') {
        $prediction_result = number_format($data['forecast'], 2);
        $confidence = $data['confidence'];
    } else {
        $error_message = "System Error: " . ($data['message'] ?? "Python not found or script failed. Output: $output");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Executive Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; padding-top: 50px; }
        .exec-card { border: none; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .exec-header { background: #1a1d20; color: white; border-radius: 12px 12px 0 0 !important; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card exec-card">
                <div class="card-header exec-header p-3">
                    <h5 class="mb-0">Executive Revenue Forecaster</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Current Monthly Revenue ($)</label>
                                <input type="number" name="current_rev" class="form-control" value="50000" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Target Growth (e.g. 0.05)</label>
                                <input type="number" step="0.01" name="growth_rate" class="form-control" value="0.05" required>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" name="run_forecast" class="btn btn-dark w-100 py-2">Generate Forecast</button>
                            </div>
                        </div>
                    </form>

                    <?php if ($prediction_result): ?>
                        <div class="mt-4 p-4 bg-light border-start border-primary border-4 rounded">
                            <small class="text-uppercase text-muted fw-bold">Projected Revenue (Next Month)</small>
                            <h1 class="display-5 text-primary fw-bold">$<?php echo $prediction_result; ?></h1>
                            <span class="badge bg-success">Confidence: <?php echo $confidence; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger mt-4">
                            <strong>Note:</strong> <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>