@extends('layouts.app')

@push('styles')
<style>
    .hr-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #090909;
        padding: 28px 32px;
        margin-bottom: 24px;
        border: 1px solid #1d1d1d;
    }
    .hr-header-text {
        font-family: var(--font-body);
        font-style: italic;
        font-size: 17px;
        color: #b8b8b8;
        font-weight: 400;
    }
    .btn-orange {
        background-color: #ECECE7;
        color: #000;
        padding: 12px 22px;
        border-radius: 0;
        font-family: var(--font-mono);
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        border: 1px solid #ECECE7;
        transition: all 0.18s ease;
    }
    .btn-orange:hover {
        background-color: #FF4D30;
        border-color: #FF4D30;
        color: #fff;
    }
    .btn-dark {
        background-color: transparent;
        color: #ECECE7;
        padding: 12px 22px;
        border-radius: 0;
        font-family: var(--font-mono);
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-size: 11px;
        text-decoration: none;
        border: 1px solid #333333;
        transition: all 0.18s ease;
    }
    .btn-dark:hover {
        background-color: #171717;
        border-color: #777777;
        color: #fff;
    }

    .kpi-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid #1d1d1d;
        border-left: 1px solid #1d1d1d;
        margin-bottom: 24px;
    }
    .kpi-card {
        background-color: #090909;
        border-right: 1px solid #1d1d1d;
        border-bottom: 1px solid #1d1d1d;
        border-radius: 0;
        padding: 28px 24px;
        display: flex;
        flex-direction: column;
        transition: background 0.2s ease;
    }
    .kpi-card:hover {
        background-color: #111111;
    }
    .kpi-title {
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 700;
        color: #FF4D30;
        letter-spacing: 0.12em;
        margin-bottom: 14px;
        text-transform: uppercase;
    }
    .kpi-val {
        font-family: var(--font-display);
        font-size: 40px;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        margin-bottom: 8px;
        letter-spacing: -0.03em;
    }
    .kpi-sub {
        font-family: var(--font-body);
        font-style: italic;
        font-size: 14px;
        color: #9c9c9c;
        margin-bottom: 16px;
    }
    .live-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 0;
        background-color: #070707;
        border: 1px solid #262626;
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #b8b8b8;
        width: max-content;
        margin-top: auto;
    }
    .dot { width: 6px; height: 6px; border-radius: 0; }
    .dot.green { background-color: #22c55e; }
    .dot.yellow { background-color: #eab308; }
    .dot.red { background-color: #ef4444; }

    .chart-row {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    .panel-card {
        background-color: #090909;
        border: 1px solid #1d1d1d;
        border-radius: 0;
        padding: 28px;
    }
    .panel-header {
        font-family: var(--font-mono);
        font-size: 11px;
        font-weight: 700;
        color: #888888;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #181818;
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
    .funnel-num { font-family: var(--font-display); font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 4px; letter-spacing: -0.02em; }
    .funnel-lbl { font-family: var(--font-body); font-style: italic; font-size: 13px; color: #d1d5db; font-weight: 400; }
    .funnel-pct { font-family: var(--font-mono); font-size: 11px; color: #6b7280; margin-top: 4px; }

    .region-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-family: var(--font-mono);
    }
    .region-bar-bg {
        flex: 1;
        height: 6px;
        background-color: #374151;
        border-radius: 0;
        margin: 0 16px;
        overflow: hidden;
    }
    .region-bar-fill {
        height: 100%;
        background-color: #f97316;
        border-radius: 0;
    }

    .table-container {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .table-container th {
        text-align: left;
        color: #9ca3af;
        font-family: var(--font-mono);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
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
        border-radius: 0;
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
</style>
@endpush

@section('content')
<div class="hr-header-bar">
    <div>
        <div class="hr-header-text">
            <b class="text-white">{{ $stats->pending }}</b> applicants awaiting review &nbsp;·&nbsp; <b class="text-white">{{ $totalJobs }}</b> active listings
        </div>
        <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
            <span class="uppercase font-bold tracking-wider">Recruiter Invite Code:</span>
            <code id="hr-invite-code-display" class="px-2 py-1 bg-black border border-gray-700 text-orange-400 font-mono font-bold select-all">{{ $hrInviteCode }}</code>
            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('hr-invite-code-display').innerText); this.innerText='Copied!'; setTimeout(()=>this.innerText='Copy', 1500);" class="text-[11px] uppercase font-bold text-white hover:text-orange-400 underline cursor-pointer bg-transparent border-0">Copy</button>
            <form method="post" action="{{ route('hr.invite_code.regenerate') }}" class="inline">
                @csrf
                <button type="submit" class="text-[11px] uppercase font-bold text-gray-400 hover:text-white underline cursor-pointer bg-transparent border-0">Regenerate</button>
            </form>
        </div>
    </div>
    <div class="flex gap-3 items-center">
        <a href="{{ route('hr.applications.index') }}" class="btn-orange">
            Review Applicants &rarr;
        </a>
        <a href="{{ route('hr.jobs.create') }}" class="btn-dark">
            + Create Listing
        </a>
    </div>
</div>

<div class="kpi-row">
    <div class="kpi-card">
        <div class="kpi-title">// 01 &middot; TOTAL APPLICANTS</div>
        <div class="kpi-val">{{ $stats->total }}</div>
        <div class="kpi-sub">{{ $totalJobs }} listings posted</div>
        <div class="live-badge"><span class="dot green"></span> Live</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-title">// 02 &middot; ACCEPTED</div>
        <div class="kpi-val">{{ $stats->accepted }}</div>
        <div class="kpi-sub">Hiring Rate: {{ $stats->total > 0 ? round(($stats->accepted / $stats->total) * 100) : 0 }}%</div>
        <div class="live-badge"><span class="dot green"></span> Live</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-title">// 03 &middot; UNDER REVIEW</div>
        <div class="kpi-val">{{ $stats->pending }}</div>
        <div class="kpi-sub">Review Pipeline</div>
        <div class="live-badge"><span class="dot yellow"></span> Live</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-title">// 04 &middot; REJECTED</div>
        <div class="kpi-val">{{ $stats->rejected }}</div>
        <div class="kpi-sub">Selection Efficiency</div>
        <div class="live-badge"><span class="dot red"></span> Live</div>
    </div>
</div>

<div class="chart-row">
    <div class="panel-card">
        <div class="panel-header">// APPLICANT TREND (6 MONTHS)</div>
        <div style="height: 220px; width: 100%;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
    <div class="panel-card">
        <div class="panel-header">// APPLICANT STATUS</div>
        <div style="display: flex; align-items: center; justify-content: center; height: 220px;">
            <div style="position: relative; width: 140px; height: 140px;">
                <canvas id="donutChart"></canvas>
            </div>
            <div style="margin-left: 40px; display: flex; flex-direction: column; gap: 12px; font-size: 12px; flex: 1; font-family: var(--font-mono);">
                <div class="flex justify-between items-center text-gray-300">
                    <span class="flex items-center gap-2"><div class="w-2 h-2 bg-yellow-500"></div> Under Review</span>
                    <span class="font-bold text-white">{{ $stats->pending }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-300">
                    <span class="flex items-center gap-2"><div class="w-2 h-2 bg-green-500"></div> Accepted</span>
                    <span class="font-bold text-white">{{ $stats->accepted }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-300">
                    <span class="flex items-center gap-2"><div class="w-2 h-2 bg-red-500"></div> Rejected</span>
                    <span class="font-bold text-white">{{ $stats->rejected }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="funnel-row">
    <div class="panel-card">
        <div class="panel-header">// RECRUITING FUNNEL</div>
        <div class="funnel-stats">
            <div class="funnel-item">
                <div class="funnel-num">{{ $stats->total }}</div>
                <div class="funnel-lbl">Applications</div>
                <div class="funnel-pct">100%</div>
            </div>
            <div class="funnel-item">
                <div class="funnel-num">{{ $stats->pending }}</div>
                <div class="funnel-lbl">Under Review</div>
                <div class="funnel-pct">{{ $stats->total > 0 ? round(($stats->pending / $stats->total) * 100) : 0 }}%</div>
            </div>
            <div class="funnel-item">
                <div class="funnel-num">{{ $stats->accepted }}</div>
                <div class="funnel-lbl">Hired</div>
                <div class="funnel-pct">{{ $stats->total > 0 ? round(($stats->accepted / $stats->total) * 100) : 0 }}%</div>
            </div>
        </div>
    </div>
    <div class="panel-card">
        <div class="flex justify-between items-center mb-6 border-b border-[#181818] pb-3">
            <div class="panel-header" style="margin: 0; padding: 0; border: 0;">// TOP APPLICANT REGIONS</div>
            <a href="{{ route('hr.applications.index') }}" class="c-mono text-xs text-gray-400 hover:text-white">View all &rarr;</a>
        </div>
        <div class="flex flex-col gap-3">
            @foreach ($topRegions as $r)
                @php $maxR = $topRegions->max('total') ?: 1; @endphp
                <div class="region-item text-xs text-gray-300">
                    <div class="w-32 truncate" title="{{ $r->region }}">{{ $r->region ?: 'Unknown' }}</div>
                    <div class="region-bar-bg">
                        <div class="region-bar-fill" style="width: {{ round(($r->total / $maxR) * 100) }}%"></div>
                    </div>
                    <div class="w-6 text-right font-bold text-white">{{ $r->total }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="panel-card">
    <div class="flex justify-between items-center mb-6 border-b border-[#181818] pb-3">
        <div class="panel-header" style="margin: 0; padding: 0; border: 0;">// LISTINGS BY APPLICANTS</div>
        <div class="c-mono text-xs text-[#FF4D30] font-bold flex items-center gap-1">
            TOP RANKED
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="table-container">
            <thead>
                <tr>
                    <th>#</th>
                    <th>POSITION</th>
                    <th>TYPE</th>
                    <th>DEADLINE</th>
                    <th class="text-right">APPLICANTS</th>
                    <th class="text-right">ACCEPTED</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobsByApplicants as $jb)
                    <tr>
                        <td class="text-gray-500">{{ $loop->iteration }}</td>
                        <td>
                            <div class="font-bold text-white">{{ $jb->title }}</div>
                            <div class="text-gray-500 text-xs mt-1">{{ $jb->location ?: 'Remote' }}</div>
                        </td>
                        <td><span class="tag-orange">{{ str_replace('_', '-', ucfirst($jb->job_type->value)) }}</span></td>
                        <td class="text-gray-400">{{ $jb->deadline ? $jb->deadline->format('d M Y') : 'N/A' }}</td>
                        <td class="text-right font-bold text-white text-lg">{{ $jb->applicant_count }}</td>
                        <td class="text-right font-bold text-green-500 text-lg">{{ $jb->accepted_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Trend Chart
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyTrend->pluck('month_label')) !!},
                datasets: [
                    { label: 'Total Applicants', data: {!! json_encode($monthlyTrend->pluck('total')) !!}, borderColor: '#f97316', backgroundColor: '#f97316', tension: 0.4, borderWidth: 3, pointRadius: 4, pointBackgroundColor: '#f97316' },
                    { label: 'Accepted', data: {!! json_encode($monthlyTrend->pluck('accepted')) !!}, borderColor: '#22c55e', backgroundColor: '#22c55e', tension: 0.4, borderWidth: 3, pointRadius: 4, pointBackgroundColor: '#22c55e' }
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
                    data: [{{ $stats->pending }}, {{ $stats->accepted }}, {{ $stats->rejected }}],
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
@endpush
