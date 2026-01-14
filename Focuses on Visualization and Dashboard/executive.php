<?php
session_start();
// Security Check: Ensure user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Executive Strategic Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-sankey@0.12.0/dist/chartjs-chart-sankey.min.js"></script>
</head>
<body class="bg-light">

    <div class="container-fluid py-4">
        <header class="pb-3 mb-4 border-bottom d-flex justify-content-between">
            <span class="fs-4 fw-bold">Executive Strategic Insights</span>
            <a href="index.php" class="btn btn-outline-secondary">Back to Operations</a>
        </header>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 p-4">
                    <h5 class="card-title">Revenue by Segment Over Time (Trend)</h5>
                    <canvas id="revenueAreaChart"></canvas>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-4 h-100">
                    <h5 class="card-title">Marketing ROI Efficiency (CAC vs CLV)</h5>
                    <canvas id="efficiencyBarChart"></canvas>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm border-0 p-4">
                    <h5 class="card-title">Customer Segment Migration Flow</h5>
                    <div style="height: 400px;">
                        <canvas id="migrationSankeyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        /** 1. Revenue by Segment Over Time (Stacked Area Chart) **/
        
        const revenueCtx = document.getElementById('revenueAreaChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'High-Income Premium',
                        data: [45000, 48000, 52000, 61000, 65000, 72000],
                        fill: true,
                        backgroundColor: 'rgba(13, 110, 253, 0.3)',
                        borderColor: '#0d6efd',
                        tension: 0.4
                    },
                    {
                        label: 'Mid-Tier Moderate',
                        data: [30000, 32000, 31000, 35000, 38000, 40000],
                        fill: true,
                        backgroundColor: 'rgba(25, 135, 84, 0.3)',
                        borderColor: '#198754',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { title: { display: false } },
                scales: { y: { stacked: true, beginAtZero: true } }
            }
        });

        /** 2. Customer Acquisition Cost (CAC) vs. CLV (Grouped Bar Chart) **/
        const efficiencyCtx = document.getElementById('efficiencyBarChart').getContext('2d');
        new Chart(efficiencyCtx, {
            type: 'bar',
            data: {
                labels: ['Premium', 'Mid-Tier', 'Budget'],
                datasets: [
                    {
                        label: 'CAC (Cost)',
                        data: [1200, 450, 180],
                        backgroundColor: '#dc3545'
                    },
                    {
                        label: 'CLV (Value)',
                        data: [8500, 3200, 950],
                        backgroundColor: '#198754'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        /** 3. Segment Migration (Sankey Diagram) **/
        
        const migrationCtx = document.getElementById('migrationSankeyChart').getContext('2d');
        new Chart(migrationCtx, {
            type: 'sankey',
            data: {
                datasets: [{
                    label: 'Customer Flow',
                    data: [
                        {from: 'New Leads', to: 'Budget', flow: 1000},
                        {from: 'Budget', to: 'Mid-Tier', flow: 250},
                        {from: 'Mid-Tier', to: 'Premium', flow: 75},
                        {from: 'Premium', to: 'Churn', flow: 10},
                        {from: 'Budget', to: 'Churn', flow: 300}
                    ],
                    colorFrom: (c) => '#6c757d',
                    colorTo: (c) => '#0d6efd'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>