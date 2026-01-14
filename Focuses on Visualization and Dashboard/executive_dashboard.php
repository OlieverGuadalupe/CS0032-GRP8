<?php
session_start();
if (!isset($_SESSION['logged_in'])) { header('Location: login.php'); exit; }
require_once 'db.php';

// Mock Predictive Logic: Calculate CLV (Avg Purchase * 12 months * 3 years)
$metricsQuery = $pdo->query("SELECT 
    ROUND(AVG(avg_purchase_amount) * 36, 2) as system_clv,
    SUM(customer_count) as total_users
    FROM cluster_metadata");
$meta = $metricsQuery->fetch(PDO::FETCH_ASSOC);

// Anomaly Detection: Check for shrinking Premium segments
$alertSql = "SELECT cluster_name FROM cluster_metadata WHERE avg_purchase_amount > 3000 AND customer_count < 200";
$alerts = $pdo->query($alertSql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Executive Summary | Customer Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-sankey@0.12.0/dist/chartjs-chart-sankey.min.js"></script>
    <style>
        .kpi-card { border: none; border-radius: 12px; transition: 0.3s; }
        .kpi-card:hover { box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
        .alert-dot { height: 10px; width: 10px; background-color: #ff4d4d; border-radius: 50%; display: inline-block; }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Strategic Executive Summary</h2>
        <span class="badge bg-white text-dark shadow-sm p-2">Data Refresh: <?php echo date('Y-m-d H:i'); ?></span>
    </div>

    <div class="row g-3 mb-4 text-center">
        <div class="col"><div class="card kpi-card shadow-sm p-3"><h6>Avg CLV</h6><h3 class="text-primary">$<?= number_format($meta['system_clv'], 0) ?></h3><small class="text-success">↑ 4% YoY</small></div></div>
        <div class="col"><div class="card kpi-card shadow-sm p-3"><h6>CAC Ratio</h6><h3>3.2:1</h3><small class="text-muted">Target 3:1</small></div></div>
        <div class="col"><div class="card kpi-card shadow-sm p-3"><h6>Net Profit Margin</h6><h3 class="text-success">24%</h3><small class="text-success">↑ 2%</small></div></div>
        <div class="col"><div class="card kpi-card shadow-sm p-3"><h6>Churn Risk</h6><h3 class="text-danger">3.1%</h3><small class="text-danger">Attention</small></div></div>
        <div class="col"><div class="card kpi-card shadow-sm p-3"><h6>Projected Rev (Q4)</h6><h3 class="text-info">$1.4M</h3><small class="text-muted">Predictive</small></div></div>
    </div>

    <?php if ($alerts): ?>
    <div class="alert alert-dark bg-dark text-white border-0 shadow-sm mb-4">
        <h6 class="mb-2"><span class="alert-dot me-2"></span> Anomaly Detection Alerts</h6>
        <ul class="mb-0 small">
            <?php foreach($alerts as $a): ?>
                <li>Critical Volume Drop: <strong><?= $a['cluster_name'] ?></strong> segment has decreased below retention threshold.</li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card p-4 border-0 shadow-sm">
                <h5>Revenue Contribution by Segment</h5>
                <canvas id="revenueAreaChart"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm h-100 bg-primary text-white">
                <h5>Market Share Forecast</h5>
                <p class="small opacity-75">Machine Learning Projection for Next 90 Days</p>
                <div class="mt-4">
                    <h6>High-Value Growth: <span class="float-end">+12.5%</span></h6>
                    <div class="progress mb-4" style="height: 5px;"><div class="progress-bar bg-white" style="width: 75%"></div></div>
                    <h6>Churn Mitigation: <span class="float-end">-2.1%</span></h6>
                    <div class="progress mb-4" style="height: 5px;"><div class="progress-bar bg-white" style="width: 45%"></div></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4 border-0 shadow-sm">
                <h5>Segment Migration Flow (Customer Journey)</h5>
                <canvas id="migrationSankey"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4 border-0 shadow-sm">
                <h5>Marketing Efficiency (CAC vs. CLV)</h5>
                <canvas id="efficiencyBar"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Area Chart for Revenue
new Chart(document.getElementById('revenueAreaChart'), {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [
            { label: 'Premium', data: [30, 45, 60, 55, 75, 90], fill: true, backgroundColor: 'rgba(13, 110, 253, 0.2)', borderColor: '#0d6efd' },
            { label: 'Mid-Tier', data: [20, 25, 30, 35, 40, 45], fill: true, backgroundColor: 'rgba(25, 135, 84, 0.2)', borderColor: '#198754' }
        ]
    },
    options: { scales: { y: { stacked: true } } }
});

// Bar Chart for Efficiency
new Chart(document.getElementById('efficiencyBar'), {
    type: 'bar',
    data: {
        labels: ['Premium', 'Active', 'Budget'],
        datasets: [
            { label: 'CAC ($)', data: [800, 300, 100], backgroundColor: '#ff4d4d' },
            { label: 'CLV ($)', data: [5200, 2100, 600], backgroundColor: '#198754' }
        ]
    }
});

// Sankey Chart for Migration
new Chart(document.getElementById('migrationSankey'), {
    type: 'sankey',
    data: {
        datasets: [{
            data: [
                {from: 'New Leads', to: 'Budget', flow: 100},
                {from: 'Budget', to: 'Active', flow: 45},
                {from: 'Active', to: 'Premium', flow: 20},
                {from: 'Active', to: 'Churn', flow: 5}
            ],
            colorFrom: (c) => '#0d6efd',
            colorTo: (c) => '#198754'
        }]
    }
});
</script>
</body>
</html>