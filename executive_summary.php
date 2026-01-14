<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Dashboard | Visionary Corp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --sidebar-bg: #1e293b; --card-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        body { background-color: #f1f5f9; font-family: 'Segoe UI', system-ui, sans-serif; }
        .sidebar { background: var(--sidebar-bg); min-height: 100vh; color: white; }
        .nav-link { color: #94a3b8; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.1); border-radius: 8px; }
        .stat-card { border: none; border-radius: 12px; box-shadow: var(--card-shadow); transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .chart-container { background: white; border-radius: 12px; padding: 20px; box-shadow: var(--card-shadow); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar py-4 px-3">
            <h4 class="fw-bold mb-4 px-2">Visionary AI</h4>
            <ul class="nav flex-column gap-2">
                <li class="nav-item"><a class="nav-link active" href="#">Executive Summary</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Segment Analysis</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Predictive Tools</a></li>
            </ul>
        </nav>

        <main class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-0">Executive Dashboard</h2>
                    <p class="text-muted">Real-time performance & predictive insights</p>
                </div>
                <button class="btn btn-primary shadow-sm">Download Quarterly Report</button>
            </div>

            <div class="row g-3 mb-4">
                <div class="col">
                    <div class="card stat-card p-3">
                        <small class="text-muted fw-bold">REVENUE</small>
                        <h3 class="fw-bold text-primary mb-0">$1,240,000</h3>
                        <small class="text-success">↑ 12.5% vs LW</small>
                    </div>
                </div>
                <div class="col">
                    <div class="card stat-card p-3">
                        <small class="text-muted fw-bold">CAC</small>
                        <h3 class="fw-bold mb-0">$42.10</h3>
                        <small class="text-danger">↓ 2.1% (Saving)</small>
                    </div>
                </div>
                <div class="col">
                    <div class="card stat-card p-3">
                        <small class="text-muted fw-bold">RETENTION</small>
                        <h3 class="fw-bold mb-0">94.2%</h3>
                        <small class="text-success">Stable</small>
                    </div>
                </div>
                <div class="col">
                    <div class="card stat-card p-3">
                        <small class="text-muted fw-bold">CHURN</small>
                        <h3 class="fw-bold mb-0">1.8%</h3>
                        <small class="text-success">Low Risk</small>
                    </div>
                </div>
                <div class="col">
                    <div class="card stat-card p-3">
                        <small class="text-muted fw-bold">LTV</small>
                        <h3 class="fw-bold mb-0">$850</h3>
                        <small class="text-primary">↑ 4% growth</small>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="chart-container">
                        <h6 class="fw-bold text-muted mb-4">REVENUE BY SEGMENT OVER TIME (HISTORICAL vs PREDICTED)</h6>
                        <canvas id="revenueChart" height="150"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="chart-container">
                        <h6 class="fw-bold text-muted mb-4">CAC BY SEGMENT</h6>
                        <canvas id="cacChart" height="315"></canvas>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="chart-container">
                        <h6 class="fw-bold text-muted mb-4">CUSTOMER SEGMENT MIGRATION (MONTHLY FLOW)</h6>
                        <canvas id="migrationChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    // 1. Revenue Chart (Line)
    const ctxRev = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRev, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun (Pred)'],
            datasets: [{
                label: 'High-Value',
                data: [450, 480, 460, 510, 550, 600],
                borderColor: '#0d6efd',
                fill: false,
                tension: 0.4
            }, {
                label: 'Standard',
                data: [300, 310, 340, 330, 350, 380],
                borderColor: '#6c757d',
                fill: false,
                tension: 0.4
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // 2. CAC Chart (Bar)
    const ctxCac = document.getElementById('cacChart').getContext('2d');
    new Chart(ctxCac, {
        type: 'bar',
        data: {
            labels: ['High-Value', 'Standard', 'At-Risk'],
            datasets: [{
                label: 'Cost ($)',
                data: [85, 35, 12],
                backgroundColor: ['#0d6efd', '#6c757d', '#dc3545'],
                borderRadius: 8
            }]
        }
    });

    // 3. Migration Chart (Horizontal Bar)
    const ctxMig = document.getElementById('migrationChart').getContext('2d');
    new Chart(ctxMig, {
        type: 'bar',
        indexAxis: 'y',
        data: {
            labels: ['Standard → High-Value', 'High-Value → At-Risk', 'At-Risk → Churn'],
            datasets: [{
                label: 'Customers Moved',
                data: [450, 120, 85],
                backgroundColor: '#20c997',
                borderRadius: 5
            }]
        }
    });
</script>

</body>
</html>