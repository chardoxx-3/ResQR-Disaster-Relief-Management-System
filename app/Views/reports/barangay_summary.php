<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

:root {
  --green-deep: #4a7a26;
  --green-mid: #77BC3F;
  --green-light: #99d15f;
  --orange-deep: #c96b10;
  --orange-mid: #F58220;
  --amber: #f5a623;
  --bg: #f8fafc;
  --surface: #ffffff;
  --surface2: #f0fdf4;
  --text-1: #1e293b;
  --text-2: #334155;
  --text-3: #64748b;
  --border: #e2e8f0;
  --radius: 14px;
}

* { box-sizing: border-box; }
body { font-family: 'Outfit', sans-serif; background: var(--bg); }

.page-header {
  background: var(--surface);
  border-radius: var(--radius) var(--radius) 0 0;
  padding: 20px 24px;
  border: 1px solid var(--border);
  border-bottom: none;
}

.page-header h4 {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--text-1);
}

.page-header p {
  margin: 5px 0 0;
  font-size: 0.7rem;
  color: var(--text-3);
}

/* Filter Bar */
.filter-bar {
  background: var(--surface2);
  border: 1px solid var(--border);
  border-top: none;
  padding: 16px 24px;
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  align-items: flex-end;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.filter-group label {
  font-size: 0.65rem;
  font-weight: 600;
  color: var(--text-2);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.filter-select {
  padding: 8px 28px 8px 12px;
  border: 1.5px solid var(--border);
  border-radius: 8px;
  font-size: 0.75rem;
  font-family: 'Outfit', sans-serif;
  background: var(--surface);
  color: var(--text-1);
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2364748b'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 10px center;
}

.btn-primary-c {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 8px 18px;
  font-size: 0.75rem;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  font-family: 'Outfit', sans-serif;
}

.btn-outline-c {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: transparent;
  color: var(--text-2);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 8px 16px;
  font-size: 0.75rem;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-outline-c:hover {
  border-color: var(--green-mid);
  color: var(--green-deep);
  background: #e8f5ee;
}

/* KPI Cards Grid */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
  padding: 20px 24px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-top: none;
}

.kpi-card {
  background: linear-gradient(135deg, #ffffff, var(--surface2));
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 16px;
  transition: transform 0.2s, box-shadow 0.2s;
}

.kpi-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}

.kpi-label {
  font-size: 0.6rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  color: var(--text-3);
  margin-bottom: 8px;
}

.kpi-value {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--green-deep);
  line-height: 1.2;
}

.kpi-sub {
  font-size: 0.65rem;
  color: var(--text-3);
  margin-top: 5px;
}

/* Chart Row */
.chart-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 20px;
  padding: 0 24px 20px;
}

.chart-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.chart-header {
  padding: 14px 18px;
  background: var(--bg);
  border-bottom: 1px solid var(--border);
}

.chart-header h5 {
  margin: 0;
  font-size: 0.8rem;
  font-weight: 700;
  color: var(--text-1);
}

.chart-header p {
  margin: 4px 0 0;
  font-size: 0.65rem;
  color: var(--text-3);
}

.chart-body {
  padding: 16px;
  min-height: 280px;
}

canvas {
  max-height: 250px;
  width: 100%;
}

/* Vulnerable Stats Grid */
.vulnerable-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 16px;
}

.vuln-card {
  background: linear-gradient(135deg, #fff8f0, #fff);
  border: 1px solid #ffe0b3;
  border-radius: 12px;
  padding: 16px;
  text-align: center;
}

.vuln-icon {
  font-size: 1.8rem;
  margin-bottom: 8px;
}

.vuln-number {
  font-size: 1.6rem;
  font-weight: 800;
  color: var(--orange-deep);
}

.vuln-label {
  font-size: 0.7rem;
  color: var(--text-2);
  font-weight: 500;
}

/* Income Analysis */
.income-stats {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 16px;
  padding-top: 12px;
  border-top: 1px solid var(--border);
}

.income-average {
  text-align: center;
  padding: 8px 16px;
  background: var(--surface2);
  border-radius: 10px;
}

.income-average .label {
  font-size: 0.65rem;
  color: var(--text-3);
}

.income-average .value {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--green-deep);
}

/* Table Section */
.table-section {
  padding: 0 24px 24px;
}

.table-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.table-header {
  padding: 14px 18px;
  background: var(--bg);
  border-bottom: 1px solid var(--border);
}

.table-header h5 {
  margin: 0;
  font-size: 0.8rem;
  font-weight: 700;
}

.table-scroll {
  overflow-x: auto;
}

.mini-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.7rem;
}

.mini-table th {
  padding: 10px 12px;
  text-align: left;
  font-weight: 600;
  color: var(--text-2);
  border-bottom: 1px solid var(--border);
  background: var(--surface);
}

.mini-table td {
  padding: 8px 12px;
  border-bottom: 1px solid var(--border);
  color: var(--text-2);
}

.mini-table tr:last-child td {
  border-bottom: none;
}

.badge-stat {
  display: inline-block;
  padding: 2px 8px;
  border-radius: 20px;
  font-size: 0.6rem;
  font-weight: 600;
}

.badge-green { background: #e8f5ee; color: #4a7a26; }
.badge-orange { background: #fef0e8; color: #c96b10; }
.badge-amber { background: #fff8e8; color: #c08000; }
.badge-blue { background: #e8f0fe; color: #1a56a0; }

/* Empty State */
.empty-state {
  text-align: center;
  padding: 60px 24px;
  color: var(--text-3);
}

.empty-state i {
  font-size: 3rem;
  opacity: 0.3;
  margin-bottom: 12px;
}

@media (max-width: 768px) {
  .kpi-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; padding: 16px; }
  .chart-row { grid-template-columns: 1fr; padding: 0 16px 16px; }
  .filter-bar { padding: 12px 16px; flex-direction: column; align-items: stretch; }
  .page-header { padding: 14px 16px; }
}

/* Split chart card: left = chart, right = info */
.chart-body-split {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px;
  min-height: 280px;
}

.chart-body-split .chart-canvas-wrap {
  flex: 0 0 55%;
  max-width: 55%;
}

.chart-body-split .chart-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.info-stat {
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 10px 14px;
}

.info-stat .info-label {
  font-size: 0.6rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--text-3);
  margin-bottom: 3px;
}

.info-stat .info-value {
  font-size: 1.2rem;
  font-weight: 800;
  color: var(--text-1);
}

.info-stat .info-pct {
  font-size: 0.65rem;
  color: var(--text-3);
}
</style>

<div class="page-wrap">
  
  <!-- Header -->
  <div class="page-header">
    <h4><i class="fa-solid fa-chart-pie me-2" style="color: var(--green-mid);"></i> Barangay Summary Report</h4>
    <p>Comprehensive demographic and socio-economic statistics by barangay</p>
  </div>

<!-- Filter Bar -->
  <div class="filter-bar">
    <div class="filter-group">
      <label><i class="fa-solid fa-location-dot"></i> Select Barangay</label>
      <select id="barangaySelect" class="filter-select" style="min-width: 220px;">
        <option value="">-- Select Barangay --</option>
        <?php foreach($allBarangays as $b): ?>
          <option value="<?= htmlspecialchars($b) ?>" <?= ($selectedBarangay == $b) ? 'selected' : '' ?>>
            <?= htmlspecialchars($b) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="filter-group">
      <label><i class="fa-solid fa-road"></i> Street</label>
      <select id="streetSelect" class="filter-select" style="min-width: 180px;" <?= empty($streets) ? 'disabled' : '' ?>>
        <option value="">All Streets</option>
        <?php foreach($streets ?? [] as $s): ?>
          <option value="<?= htmlspecialchars($s) ?>" <?= ($selectedStreet ?? '') == $s ? 'selected' : '' ?>>
            <?= htmlspecialchars($s) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="filter-group">
      <label>&nbsp;</label>
<div class="filter-group">
    <label>&nbsp;</label>
    <div style="display:flex; gap:8px;">
        <button id="viewReportBtn" class="btn-primary-c">
            <i class="fa-solid fa-chart-line"></i> Generate Report
        </button>
        <button id="printReportBtn" class="btn-outline-c">
            <i class="fa-solid fa-print"></i> Print PDF
        </button>
    </div>
</div>
    </div>
  </div>

  <?php if(empty($selectedBarangay)): ?>
    <div class="empty-state">
      <i class="fa-solid fa-chart-simple"></i>
      <p>Please select a barangay to view the summary report</p>
    </div>
  <?php elseif($totalFamilies == 0): ?>
    <div class="empty-state">
      <i class="fa-solid fa-users-slash"></i>
      <p>No data available for the selected barangay</p>
    </div>
  <?php else: ?>

  <!-- KPI CARDS -->
  <div class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-label"><i class="fa-solid fa-house-user"></i> Total Families</div>
      <div class="kpi-value"><?= number_format($totalFamilies) ?></div>
      <div class="kpi-sub">Registered households</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label"><i class="fa-solid fa-users"></i> Total Residents</div>
      <div class="kpi-value"><?= number_format($totalResidents) ?></div>
      <div class="kpi-sub">Including family members</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label"><i class="fa-solid fa-people-arrows"></i> Avg Family Size</div>
      <div class="kpi-value"><?= $avgFamilySize ?></div>
      <div class="kpi-sub">Members per household</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label"><i class="fa-solid fa-calendar-day"></i> Registered Today</div>
      <div class="kpi-value"><?= number_format($registeredToday) ?></div>
      <div class="kpi-sub">New families today</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label"><i class="fa-solid fa-calendar-week"></i> This Month</div>
      <div class="kpi-value"><?= number_format($registeredThisMonth) ?></div>
      <div class="kpi-sub"><?= date('F Y') ?></div>
    </div>
  </div>

  <!-- ROW 2: Gender + 4Ps Charts -->
  <div class="chart-row">
    <div class="chart-card">
      <div class="chart-header">
        <h5><i class="fa-solid fa-venus-mars"></i> Gender Distribution (All Individuals)</h5>
<p>Male vs Female — heads & family members</p>
        <p>Male vs Female breakdown</p>
      </div>
      <div class="chart-body-split">
        <div class="chart-canvas-wrap">
          <canvas id="genderChart"></canvas>
        </div>
        <div class="chart-info">
          <div class="info-stat">
            <div class="info-label" style="color:#4a7a26;">● Male</div>
            <div class="info-value"><?= number_format($maleCount) ?></div>
            <div class="info-pct"><?= $malePercent ?>% of all individuals</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#F58220;">● Female</div>
            <div class="info-value"><?= number_format($femaleCount) ?></div>
            <div class="info-pct"><?= $femalePercent ?>% of all individuals</div>
          </div>
          <div class="info-stat">
            <div class="info-label">Total Counted</div>
            <div class="info-value"><?= number_format($totalGenderCount) ?></div>
            <div class="info-pct">Heads + family members</div>
          </div>
        </div>
      </div>
    </div>
    <div class="chart-card">
      <div class="chart-header">
        <h5><i class="fa-solid fa-hand-holding-heart"></i> 4Ps Beneficiary Status</h5>
        <p>Pantawid Pamilyang Pilipino Program</p>
      </div>
      <div class="chart-body-split">
        <div class="chart-canvas-wrap">
          <canvas id="fourPsChart"></canvas>
        </div>
        <div class="chart-info">
          <div class="info-stat">
            <div class="info-label" style="color:#4a7a26;">● 4Ps Beneficiary</div>
            <div class="info-value"><?= number_format($fourPsCount) ?></div>
            <div class="info-pct"><?= $fourPsPercent ?>% of families</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#64748b;">● Non-4Ps</div>
            <div class="info-value"><?= number_format($nonFourPsCount) ?></div>
            <div class="info-pct"><?= round(100 - $fourPsPercent, 1) ?>% of families</div>
          </div>
          <div class="info-stat">
            <div class="info-label">Total Families</div>
            <div class="info-value"><?= number_format($totalFamilies) ?></div>
            <div class="info-pct">Registered households</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ROW 3: Civil Status + Age Group -->
  <div class="chart-row">
    <div class="chart-card">
      <div class="chart-header">
        <h5><i class="fa-solid fa-ring"></i> Civil Status</h5>
        <p>Distribution by marital status</p>
      </div>
      <div class="chart-body-split">
        <div class="chart-canvas-wrap">
          <canvas id="civilStatusChart"></canvas>
        </div>
        <div class="chart-info">
          <div class="info-stat">
            <div class="info-label" style="color:#4a7a26;">● Single</div>
            <div class="info-value"><?= number_format($civilStatus['single']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($civilStatus['single'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#77BC3F;">● Married</div>
            <div class="info-value"><?= number_format($civilStatus['married']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($civilStatus['married'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#F58220;">● Widowed</div>
            <div class="info-value"><?= number_format($civilStatus['widowed']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($civilStatus['widowed'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#f5a623;">● Separated</div>
            <div class="info-value"><?= number_format($civilStatus['separated']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($civilStatus['separated'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
        </div>
      </div>
    </div>
    <div class="chart-card">
      <div class="chart-header">
        <h5><i class="fa-solid fa-calendar-alt"></i> Age Group Distribution</h5>
        <p>Family heads by age bracket</p>
      </div>
      <div class="chart-body-split">
        <div class="chart-canvas-wrap">
          <canvas id="ageGroupChart"></canvas>
        </div>
        <div class="chart-info">
          <div class="info-stat">
            <div class="info-label" style="color:#4a7a26;">● 18–30</div>
            <div class="info-value"><?= number_format($ageGroups['18-30']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($ageGroups['18-30'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#77BC3F;">● 31–45</div>
            <div class="info-value"><?= number_format($ageGroups['31-45']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($ageGroups['31-45'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#F58220;">● 46–60</div>
            <div class="info-value"><?= number_format($ageGroups['46-60']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($ageGroups['46-60'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#f5a623;">● Senior (60+)</div>
            <div class="info-value"><?= number_format($ageGroups['senior']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($ageGroups['senior'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ROW 4: Income Analysis -->
  <div class="chart-row">
    <div class="chart-card">
      <div class="chart-header">
        <h5><i class="fa-solid fa-coins"></i> Monthly Income Analysis</h5>
        <p>Distribution by income range</p>
      </div>
      <div class="chart-body-split">
        <div class="chart-canvas-wrap">
          <canvas id="incomeChart"></canvas>
        </div>
        <div class="chart-info">
          <div class="info-stat">
            <div class="info-label" style="color:#f5a623;">● ₱0–₱5k</div>
            <div class="info-value"><?= number_format($incomeRanges['₱0-₱5k']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($incomeRanges['₱0-₱5k'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#F58220;">● ₱5k–₱10k</div>
            <div class="info-value"><?= number_format($incomeRanges['₱5k-₱10k']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($incomeRanges['₱5k-₱10k'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#77BC3F;">● ₱10k–₱20k</div>
            <div class="info-value"><?= number_format($incomeRanges['₱10k-₱20k']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($incomeRanges['₱10k-₱20k'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
          <div class="info-stat">
            <div class="info-label" style="color:#4a7a26;">● ₱20k+</div>
            <div class="info-value"><?= number_format($incomeRanges['₱20k+']) ?></div>
            <div class="info-pct"><?= $totalFamilies > 0 ? round(($incomeRanges['₱20k+'] / $totalFamilies) * 100, 1) : 0 ?>%</div>
          </div>
        </div>
      </div>
    </div>
    <div class="chart-card">
      <div class="chart-header">
        <h5><i class="fa-solid fa-handcuffs"></i> Vulnerable Members Summary</h5>
        <p>Count of vulnerable individuals</p>
      </div>
      <div class="chart-body">
        <div class="vulnerable-grid">
          <div class="vuln-card">
            <div class="vuln-icon">👴</div>
            <div class="vuln-number"><?= number_format($vulnerableStats['older_persons']) ?></div>
            <div class="vuln-label">Older Persons</div>
          </div>
          <div class="vuln-card">
            <div class="vuln-icon">🤰</div>
            <div class="vuln-number"><?= number_format($vulnerableStats['pregnant']) ?></div>
            <div class="vuln-label">Pregnant Women</div>
          </div>
          <div class="vuln-card">
            <div class="vuln-icon">🍼</div>
            <div class="vuln-number"><?= number_format($vulnerableStats['lactating']) ?></div>
            <div class="vuln-label">Lactating</div>
          </div>
          <div class="vuln-card">
            <div class="vuln-icon">♿</div>
            <div class="vuln-number"><?= number_format($vulnerableStats['pwd']) ?></div>
            <div class="vuln-label">PWD</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Streets data from PHP - make sure this is defined before it's used
const streetsByBarangay = <?= json_encode($streetsByBarangay ?? []) ?>;
const currentStreet = '<?= htmlspecialchars($selectedStreet ?? '') ?>';

// Chart instances to destroy before recreating
let genderChart, fourPsChart, civilStatusChart, ageGroupChart, incomeChart, ownershipChart, shelterChart;

// Initialize charts if data exists
<?php if(!empty($selectedBarangay) && $totalFamilies > 0): ?>

// Gender Chart (Doughnut)
const genderCtx = document.getElementById('genderChart').getContext('2d');
genderChart = new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: ['Male', 'Female'],
        datasets: [{
            data: [<?= $maleCount ?>, <?= $femaleCount ?>],
            backgroundColor: ['#4a7a26', '#F58220'],
            borderWidth: 0,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 10 } } },
            tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} (${((ctx.raw / <?= $totalFamilies ?>) * 100).toFixed(1)}%)` } }
        }
    }
});

// 4Ps Chart (Pie)
const fourPsCtx = document.getElementById('fourPsChart').getContext('2d');
fourPsChart = new Chart(fourPsCtx, {
    type: 'pie',
    data: {
        labels: ['4Ps Beneficiary', 'Non-4Ps'],
        datasets: [{
            data: [<?= $fourPsCount ?>, <?= $nonFourPsCount ?>],
            backgroundColor: ['#F58220', '#4a7a26'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 10 } } },
            tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} (${((ctx.raw / <?= $totalFamilies ?>) * 100).toFixed(1)}%)` } }
        }
    }
});

// Civil Status Chart (Pie)
const civilCtx = document.getElementById('civilStatusChart').getContext('2d');
civilStatusChart = new Chart(civilCtx, {
    type: 'pie',
    data: {
        labels: ['Single', 'Married', 'Widowed', 'Separated'],
        datasets: [{
            data: [<?= $civilStatus['single'] ?>, <?= $civilStatus['married'] ?>, <?= $civilStatus['widowed'] ?>, <?= $civilStatus['separated'] ?>],
            backgroundColor: ['#4a7a26', '#77BC3F', '#F58220', '#f5a623'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 10 } } }
        }
    }
});

// Age Group Chart (Bar)
const ageCtx = document.getElementById('ageGroupChart').getContext('2d');
ageGroupChart = new Chart(ageCtx, {
    type: 'bar',
    data: {
        labels: ['18-30', '31-45', '46-60', 'Senior (60+)'],
        datasets: [{
            label: 'Number of Family Heads',
            data: [<?= $ageGroups['18-30'] ?>, <?= $ageGroups['31-45'] ?>, <?= $ageGroups['46-60'] ?>, <?= $ageGroups['senior'] ?>],
            backgroundColor: '#77BC3F',
            borderRadius: 6,
            barPercentage: 0.65
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: (ctx) => `${ctx.raw} families (${((ctx.raw / <?= $totalFamilies ?>) * 100).toFixed(1)}%)` } }
        },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Number of Families', font: { size: 10 } } },
            x: { title: { display: true, text: 'Age Group', font: { size: 10 } } }
        }
    }
});

// Income Chart (Bar)
const incomeCtx = document.getElementById('incomeChart').getContext('2d');
incomeChart = new Chart(incomeCtx, {
    type: 'bar',
    data: {
        labels: ['₱0-₱5k', '₱5k-₱10k', '₱10k-₱20k', '₱20k+'],
        datasets: [{
            label: 'Number of Families',
            data: [<?= $incomeRanges['₱0-₱5k'] ?>, <?= $incomeRanges['₱5k-₱10k'] ?>, <?= $incomeRanges['₱10k-₱20k'] ?>, <?= $incomeRanges['₱20k+'] ?>],
            backgroundColor: '#f5a623',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: (ctx) => `${ctx.raw} families` } }
        },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Number of Families' } }
        }
    }
});

<?php endif; ?>

// Populate streets when barangay changes - LIKE IMAGE REPORT
function populateStreets(barangay) {
    const streetSel = document.getElementById('streetSelect');
    
    if (barangay && streetsByBarangay[barangay]?.length > 0) {
        streetSel.innerHTML = '<option value="">All Streets</option>';
        streetsByBarangay[barangay].forEach(street => {
            const opt = document.createElement('option');
            opt.value = street;
            opt.textContent = street;
            if (street === currentStreet) {
                opt.selected = true;
            }
            streetSel.appendChild(opt);
        });
        streetSel.disabled = false;
        streetSel.style.opacity = '1';
        streetSel.style.cursor = 'pointer';
    } else {
        streetSel.innerHTML = '<option value="">All Streets</option>';
        streetSel.disabled = true;
        streetSel.style.opacity = '.4';
        streetSel.style.cursor = 'not-allowed';
    }
}

// Trigger when barangay changes
document.getElementById('barangaySelect').addEventListener('change', function () {
    const barangay = this.value;
    populateStreets(barangay);
});

// Initial population on page load
document.addEventListener('DOMContentLoaded', function() {
    const initialBarangay = document.getElementById('barangaySelect').value;
    if (initialBarangay) {
        populateStreets(initialBarangay);
    }
});

// Generate Report button - LIKE IMAGE REPORT
document.getElementById('viewReportBtn').addEventListener('click', function () {
    const barangay = document.getElementById('barangaySelect').value;
    const street = document.getElementById('streetSelect').value;
    
    if (!barangay) { 
        alert('Please select a barangay'); 
        return; 
    }
    
    let url = '/reports/barangay-summary';
    const params = [];
    if (barangay) params.push('barangay=' + encodeURIComponent(barangay));
    if (street) params.push('street=' + encodeURIComponent(street));
    if (params.length) url += '?' + params.join('&');
    
    window.location.href = url;
});

// Print PDF button
document.getElementById('printReportBtn').addEventListener('click', function () {
    const barangay = document.getElementById('barangaySelect').value;
    const street = document.getElementById('streetSelect').value;
    
    if (!barangay) { 
        alert('Please select a barangay first'); 
        return; 
    }
    
    let url = '/reports/barangay-summary/print';
    const params = [];
    if (barangay) params.push('barangay=' + encodeURIComponent(barangay));
    if (street) params.push('street=' + encodeURIComponent(street));
    if (params.length) url += '?' + params.join('&');
    
    window.open(url, '_blank');
});
</script>

<?= $this->endSection() ?>