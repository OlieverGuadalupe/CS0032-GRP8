<?php
session_start();
if (!isset($_SESSION['logged_in'])) { header('Location: login.php'); exit; }
require_once 'db.php';

// In a real production app, these values would be fetched from a logs table or a monitoring API.
// For this case study, we simulate the current system state.
$metrics = [
    'avg_response_time' => 1.2, // seconds
    'peak_memory' => 184,       // MB
    'query_latency' => 450,     // ms
    'convergence_time' => 8.5,   // seconds
    'error_rate' => 0.02        // 2%
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Performance Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">System Health & Performance</h2>
        
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6>Avg Response Time</h6>
                    <h3 class="text-primary"><?= $metrics['avg_response_time'] ?>s</h3>
                    <small class="text-muted">Target: < 2.0s</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6>Memory Usage</h6>
                    <h3 class="<?= $metrics['peak_memory'] > 200 ? 'text-danger' : 'text-success' ?>">
                        <?= $metrics['peak_memory'] ?>MB
                    </h3>
                    <small class="text-muted">Limit: 256MB</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6>Query Latency</h6>
                    <h3 class="text-warning"><?= $metrics['query_latency'] ?>ms</h3>
                    <small class="text-muted">Database Health</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6>Error Rate</h6>
                    <h3 class="text-success"><?= $metrics['error_rate'] * 100 ?>%</h3>
                    <small class="text-muted">Last 24 Hours</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-4">
                    <h5>Clustering Convergence Time (Trend)</h5>
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h5>Resource Allocation</h5>
                    <canvas id="memoryPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Line Chart for Convergence Trends
        new Chart(document.getElementById('performanceChart'), {
            type: 'line',
            data: {
                labels: ['Run 1', 'Run 2', 'Run 3', 'Run 4', 'Run 5'],
                datasets: [{
                    label: 'Seconds to Converge',
                    data: [12, 10, 8.5, 9, 7.8],
                    borderColor: '#0d6efd',
                    tension: 0.3
                }]
            }
        });

        // Pie Chart for Memory Limit
        new Chart(document.getElementById('memoryPieChart'), {
            type: 'doughnut',
            data: {
                labels: ['Used Memory', 'Available'],
                datasets: [{
                    data: [<?= $metrics['peak_memory'] ?>, <?= 256 - $metrics['peak_memory'] ?>],
                    backgroundColor: ['#dc3545', '#e9ecef']
                }]
            }
        });
    </script>
</body>
</html>