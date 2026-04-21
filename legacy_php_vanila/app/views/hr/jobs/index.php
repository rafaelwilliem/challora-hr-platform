<?php
$stats = $stats ?? ['total' => 0, 'accepted' => 0, 'rejected' => 0, 'pending' => 0];
$totalJobs        = (int) ($totalJobs ?? 0);
$totalApplicants  = (int) ($stats['total'] ?? 0);
$accepted         = (int) ($stats['accepted'] ?? 0);
$rejected         = (int) ($stats['rejected'] ?? 0);
$pending          = (int) ($stats['pending'] ?? 0);
$topRegions       = $topRegions ?? [];
$monthlyTrend     = $monthlyTrend ?? [];
$jobsByApplicants = $jobsByApplicants ?? [];
$sortApplicants   = $sortApplicants ?? 'desc';

$hiringRate          = $totalApplicants > 0 ? (int) round(($accepted / $totalApplicants) * 100) : 0;
$callbackRate        = $totalApplicants > 0 ? (int) round((($pending + $accepted) / $totalApplicants) * 100) : 0;
$offerAcceptanceRate = ($accepted + $rejected) > 0 ? (int) round(($accepted / ($accepted + $rejected)) * 100) : 0;

$trendLabels = $trendTotal = $trendAccepted = [];
foreach ($monthlyTrend as $m) {
    $trendLabels[]   = $m['month_label'];
    $trendTotal[]    = (int) $m['total'];
    $trendAccepted[] = (int) $m['accepted'];
}

$jobTypeLabels = ['full_time' => 'Full Time', 'part_time' => 'Part Time', 'freelance' => 'Freelance', 'volunteer' => 'Volunteer', 'internship' => 'Internship'];
?>
<style>
    /* UI matched exactly to uploaded image */
    body {
        background-color: #121212 !important; /* Dark background */
        color: #e5e7eb !important;
        font-family: 'Inter', sans-serif !important;
    }
    
    .hr-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #1a1a1a;
        padding: 24px 32px;
        margin-bottom: 24px;
        border-radius: 8px;
        border: 1px solid #2d2d2d;
    }
    .hr-header-text {
        font-size: 15px;
        color: #d1d5db;
        font-weight: 500;
    }
    .btn-orange {
        background-color: #f97316;
        color: #fff;
        padding: 10px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        border: 1px solid #f97316;
        transition: opacity 0.2s;
    }
    .btn-orange:hover {
        opacity: 0.9;
        color: #fff;
    }
    .btn-dark {
        background-color: transparent;
        color: #d1d5db;
        padding: 10px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        border: 1px solid #4b5563;
        transition: background 0.2s;
    }
    .btn-dark:hover {
        background-color: #374151;
        color: #fff;
    }

    .kpi-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    .kpi-card {
        background-color: #1a1a1a;
        border: 1px solid #2d2d2d;
        border-radius: 8px;
        padding: 24px;
        display: flex;
        flex-direction: column;
    }
    .kpi-title {
        font-size: 12px;
        font-weight: 700;
        color: #9ca3af;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        text-transform: uppercase;
    }
    .kpi-val {
        font-size: 36px;
        font-weight: 700;
        color: #fff;
        line-height: 1;
        margin-bottom: 8px;
    }
    .kpi-sub {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 12px;
    }
    .live-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        background-color: rgba(255,255,255,0.05);
        border: 1px solid #374151;
        font-size: 11px;
        font-weight: 600;
        color: #fff;
        width: max-content;
    }
    .dot { width: 6px; height: 6px; border-radius: 50%; }
    .dot.green { background-color: #22c55e; box-shadow: 0 0 6px #22c55e; }
    .dot.yellow { background-color: #eab308; box-shadow: 0 0 6px #eab308; }
    .dot.red { background-color: #ef4444; box-shadow: 0 0 6px #ef4444; }

    .chart-row {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    .panel-card {
        background-color: #1a1a1a;
        border: 1px solid #2d2d2d;
        border-radius: 8px;
        padding: 24px;
    }
    .panel-header {
        font-size: 12px;
        font-weight: 700;
        color: #9ca3af;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    
    .funnel-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    .funnel-stats {
        display: flex;
        justify-content: space-between;
        margin-top: 16px;
    }
    .funnel-item {
        display: flex;
        flex-direction: column;
    }
    .funnel-num { font-size: 32px; font-weight: 700; color: #fff; margin-bottom: 4px; }
    .funnel-lbl { font-size: 12px; color: #d1d5db; font-weight: 500; }
    .funnel-pct { font-size: 11px; color: #6b7280; margin-top: 4px; }

    .region-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .region-bar-bg {
        flex: 1;
        height: 6px;
        background-color: #374151;
        border-radius: 4px;
        margin: 0 16px;
        overflow: hidden;
    }
    .region-bar-fill {
        height: 100%;
        background-color: #f97316;
        border-radius: 4px;
    }

    .table-container {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .table-container th {
        text-align: left;
        color: #9ca3af;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #374151;
    }
    .table-container td {
        padding: 16px;
        border-bottom: 1px solid #2d2d2d;
        color: #e5e7eb;
        vertical-align: middle;
    }
    .table-container tr:last-child td {
        border-bottom: none;
    }
    .tag-orange {
        background-color: rgba(249, 115, 22, 0.1);
        color: #f97316;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
</style>

<div class="hr-header-bar">
    <div class="hr-header-text">
        Ada <b class="text-white"><?= $pending ?></b> pelamar yang belum diproses &nbsp;·&nbsp; <b class="text-white"><?= $totalJobs ?></b> lowongan aktif
    </div>
    <div class="flex gap-3 items-center">
        <a href="<?= BASE_URL ?>/hr/applications" class="btn-orange">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Lihat Pelamar Belum Diproses
        </a>
        <a href="<?= BASE_URL ?>/hr/jobs/create" class="btn-dark">
            + Buat Lowongan
        </a>
    </div>
</div>

<div class="kpi-row">
    <div class="kpi-card" style="border-top: 3px solid #f97316;">
        <div class="kpi-title">TOTAL PELAMAR</div>
        <div class="kpi-val"><?= $totalApplicants ?></div>
        <div class="kpi-sub"><?= $totalJobs ?> lowongan diposting</div>
        <div class="live-badge"><span class="dot green"></span> Live</div>
    </div>
    <div class="kpi-card" style="border-top: 3px solid #22c55e;">
        <div class="kpi-title">DITERIMA</div>
        <div class="kpi-val"><?= $accepted ?></div>
        <div class="kpi-sub">Hiring Rate: <?= $hiringRate ?>%</div>
        <div class="live-badge"><span class="dot green"></span> Live</div>
    </div>
    <div class="kpi-card" style="border-top: 3px solid #1a1a1a;">
        <div class="kpi-title">DALAM PROSES</div>
        <div class="kpi-val"><?= $pending ?></div>
        <div class="kpi-sub">Callback Rate: <?= $callbackRate ?>%</div>
        <div class="live-badge"><span class="dot yellow"></span> Live</div>
    </div>
    <div class="kpi-card" style="border-top: 3px solid #1a1a1a;">
        <div class="kpi-title">DITOLAK</div>
        <div class="kpi-val"><?= $rejected ?></div>
        <div class="kpi-sub">Offer Acceptance: <?= $offerAcceptanceRate ?>%</div>
        <div class="live-badge"><span class="dot red"></span> Live</div>
    </div>
</div>

<div class="chart-row">
    <div class="panel-card">
        <div class="panel-header">TREN PELAMAR (6 BULAN)</div>
        <div style="height: 220px; width: 100%;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
    <div class="panel-card">
        <div class="panel-header">STATUS PELAMAR</div>
        <div style="display: flex; align-items: center; justify-content: center; height: 220px;">
            <div style="position: relative; width: 140px; height: 140px;">
                <canvas id="donutChart"></canvas>
            </div>
            <div style="margin-left: 40px; display: flex; flex-direction: column; gap: 12px; font-size: 12px; flex: 1;">
                <div class="flex justify-between items-center text-gray-300">
                    <span class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-yellow-500"></div> Dalam Proses</span>
                    <span class="font-bold text-white"><?= $pending ?></span>
                </div>
                <div class="flex justify-between items-center text-gray-300">
                    <span class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-green-500"></div> Diterima</span>
                    <span class="font-bold text-white"><?= $accepted ?></span>
                </div>
                <div class="flex justify-between items-center text-gray-300">
                    <span class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-red-500"></div> Ditolak</span>
                    <span class="font-bold text-white"><?= $rejected ?></span>
                </div>
                <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-700">
                    <span class="flex items-center gap-2 font-bold text-accent-500"><div class="w-2 h-2 rounded-full bg-orange-500"></div> Total</span>
                    <span class="font-bold text-accent-500"><?= $totalApplicants ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="funnel-row">
    <div class="panel-card">
        <div class="panel-header">RECRUITING FUNNEL</div>
        <div class="funnel-stats">
            <div class="funnel-item">
                <div class="funnel-num"><?= $totalApplicants ?></div>
                <div class="funnel-lbl">Applications</div>
                <div class="funnel-pct">100%</div>
            </div>
            <div class="funnel-item">
                <div class="funnel-num"><?= $pending ?></div>
                <div class="funnel-lbl">Under Review</div>
                <div class="funnel-pct"><?= $totalApplicants > 0 ? round(($pending / $totalApplicants) * 100) : 0 ?>%</div>
            </div>
            <div class="funnel-item">
                <div class="funnel-num"><?= $accepted ?></div>
                <div class="funnel-lbl">Hired</div>
                <div class="funnel-pct"><?= $hiringRate ?>%</div>
            </div>
        </div>
    </div>
    <div class="panel-card">
        <div class="flex justify-between items-center mb-6">
            <div class="panel-header" style="margin: 0;">TOP DAERAH PELAMAR</div>
            <a href="<?= BASE_URL ?>/hr/applications" class="text-xs text-gray-400 hover:text-white">Lihat semua ></a>
        </div>
        <div class="flex flex-col gap-3">
            <?php foreach (array_slice($topRegions, 0, 3) as $r): ?>
                <?php $maxR = max(array_column($topRegions, 'total') ?: [1]); ?>
                <div class="region-item text-xs text-gray-300">
                    <div class="w-32 truncate" title="<?= e($r['region']) ?>"><?= e($r['region']) ?></div>
                    <div class="region-bar-bg">
                        <div class="region-bar-fill" style="width: <?= round(($r['total'] / $maxR) * 100) ?>%"></div>
                    </div>
                    <div class="w-6 text-right font-bold text-white"><?= (int)$r['total'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="panel-card">
    <div class="flex justify-between items-center mb-6">
        <div class="panel-header" style="margin: 0;">LOWONGAN BERDASARKAN PELAMAR</div>
        <div class="text-xs text-accent-500 font-bold flex items-center gap-1">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
            TERTINGGI
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="table-container">
            <thead>
                <tr>
                    <th>#</th>
                    <th>POSISI</th>
                    <th>TIPE</th>
                    <th>DEADLINE</th>
                    <th class="text-right">PELAMAR</th>
                    <th class="text-right">DITERIMA</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach (array_slice($jobsByApplicants, 0, 5) as $jb): ?>
                    <tr>
                        <td class="text-gray-500"><?= $rank++ ?></td>
                        <td>
                            <div class="font-bold text-white"><?= e($jb['title']) ?></div>
                            <div class="text-gray-500 text-xs mt-1"><?= e($jb['location'] ?: 'Remote') ?></div>
                        </td>
                        <td><span class="tag-orange"><?= e($jobTypeLabels[$jb['job_type'] ?? ''] ?? 'Full Time') ?></span></td>
                        <td class="text-gray-400">31 dec 2026</td> <!-- Placeholder, database table job might not have deadline -->
                        <td class="text-right font-bold text-white text-lg"><?= (int)$jb['applicant_count'] ?></td>
                        <td class="text-right font-bold text-green-500 text-lg"><?= (int)$jb['accepted_count'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Trend Chart
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($trendLabels) ?>,
                datasets: [
                    { label: 'Total Pelamar', data: <?= json_encode($trendTotal) ?>, borderColor: '#f97316', backgroundColor: '#f97316', tension: 0.4, borderWidth: 3, pointRadius: 4, pointBackgroundColor: '#f97316' },
                    { label: 'Diterima', data: <?= json_encode($trendAccepted) ?>, borderColor: '#22c55e', backgroundColor: '#22c55e', tension: 0.4, borderWidth: 3, pointRadius: 4, pointBackgroundColor: '#22c55e' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: {
                        position: 'bottom',
                        labels: { color: '#9ca3af', boxWidth: 10, boxHeight: 10, usePointStyle: true, padding: 20 }
                    }
                },
                scales: {
                    x: { grid: { color: '#2d2d2d', drawBorder: false }, ticks: { color: '#9ca3af' } },
                    y: { grid: { color: '#2d2d2d', drawBorder: false }, ticks: { color: '#9ca3af', stepSize: 1 }, beginAtZero: true }
                }
            }
        });

        // Donut Chart
        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [<?= $pending ?>, <?= $accepted ?>, <?= $rejected ?>],
                    backgroundColor: ['#eab308', '#22c55e', '#ef4444'],
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } }
            }
        });
    });
</script>
