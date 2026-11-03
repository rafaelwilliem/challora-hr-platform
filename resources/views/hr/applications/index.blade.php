@extends('layouts.app')

@push('styles')
<style>
    .hr-header-premium {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 60px;
        border-bottom: 2px solid var(--color-border);
        padding-bottom: 32px;
    }
    .hr-title-giant {
        font-family: var(--font-display);
        font-size: 64px;
        font-weight: 800;
        letter-spacing: -4px;
        line-height: 1;
        color: var(--color-text);
        text-transform: uppercase;
    }
    .hr-subtitle {
        font-family: var(--font-body);
        font-style: italic;
        font-size: 18px;
        font-weight: 400;
        color: var(--color-text-muted);
        margin-top: 12px;
    }
    .hr-card-table {
        background: var(--color-surface);
        border: 4px solid black;
        box-shadow: 10px 10px 0 black;
        overflow: hidden;
        border-radius: 0;
    }
    .ax-premium-table {
        width: 100%;
        border-collapse: collapse;
    }
    .ax-premium-table thead th {
        background: var(--color-secondary);
        padding: 24px;
        text-align: left;
        font-family: var(--font-mono);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--color-text-muted);
        border-bottom: 2px solid var(--color-border);
    }
    .ax-premium-table tbody td {
        padding: 32px 24px;
        border-bottom: 1px solid var(--color-border);
        vertical-align: middle;
    }
    .applicant-name {
        font-family: var(--font-display);
        font-size: 20px;
        font-weight: 800;
        color: var(--color-text);
        letter-spacing: -0.5px;
    }
    .applicant-contact {
        font-family: var(--font-mono);
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-muted);
    }
    .status-badge-premium {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        font-family: var(--font-mono);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        border: 2px solid black;
    }
    .status-accepted { background: #4ade80; color: black; }
    .status-rejected { background: #f87171; color: black; }
    .status-pending { background: #fbbf24; color: black; }
    
    .action-select-premium {
        background: var(--color-surface);
        border: 4px solid black;
        padding: 12px 16px;
        font-family: var(--font-mono);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        cursor: pointer;
        box-shadow: 4px 4px 0 black;
        border-radius: 0;
    }
    .action-select-premium:focus {
        border-color: var(--color-accent);
        outline: none;
    }
    .premium-btn-icon {
        background: black;
        color: white;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        box-shadow: 4px 4px 0 var(--color-accent);
        text-decoration: none;
        transition: all 0.1s;
    }
    .premium-btn-icon:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0 var(--color-accent);
    }
</style>
@endpush

@section('content')
<div class="hr-header-premium">
    <div class="gsap-reveal">
        <h1 class="hr-title-giant">Talent Pipeline</h1>
        <p class="hr-subtitle">Review and manage candidate applications across all postings.</p>
    </div>
    <div class="flex gap-4 gsap-reveal">
        <form action="{{ route('hr.applications.index') }}" method="GET" class="flex gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-black uppercase text-text-muted">Job Posting</label>
                <select name="job_id" class="action-select-premium" onchange="this.form.submit()">
                    <option value="">All Positions</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                            {{ $job->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-black uppercase text-text-muted">Status</label>
                <select name="status" class="action-select-premium" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach(\App\Enums\ApplicationStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ ucfirst($status->value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-black uppercase text-text-muted">Sort by Rating</label>
                <select name="sort_rating" class="action-select-premium" onchange="this.form.submit()">
                    <option value="">Newest First</option>
                    <option value="high" {{ request('sort_rating') === 'high' ? 'selected' : '' }}>High to Low</option>
                    <option value="low" {{ request('sort_rating') === 'low' ? 'selected' : '' }}>Low to High</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-black uppercase text-text-muted">Show</label>
                <select name="per_page" class="action-select-premium" onchange="this.form.submit()">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Rows</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Rows</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Rows</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Rows</option>
                </select>
            </div>
        </form>
    </div>
</div>

@if ($applications->isEmpty())
    <div class="py-24 text-center bg-secondary border-2 border-dashed border-border gsap-reveal">
        <i class="bi bi-person-slash text-6xl text-border mb-6 block"></i>
        <h3 class="font-black text-3xl">No Applicants Found</h3>
        <p class="text-text-muted font-bold mt-2">Try adjusting your filters or wait for new submissions.</p>
    </div>
@else
    <div class="hr-card-table gsap-reveal">
        <table class="ax-premium-table">
            <thead>
                <tr>
                    <th>Candidate</th>
                    <th>Applied For</th>
                    <th>AI Score</th>
                    <th>Status</th>
                    <th>Files</th>
                    <th class="text-right">Intelligence Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $a)
                    <tr>
                        <td>
                            <div class="applicant-name">{{ $a->user->name }}</div>
                            @if(($topApplicationId ?? null) === $a->id)
                                <div class="text-xs font-black uppercase text-accent mt-1">Top Candidate</div>
                            @endif
                            <div class="applicant-contact">{{ $a->user->email }}</div>
                        </td>
                        <td>
                            <div class="font-bold uppercase text-xs tracking-widest text-accent">{{ $a->job->title }}</div>
                            <div class="text-xs font-bold text-text-muted mt-1">{{ $a->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            @if($a->aiScore && $a->aiScore->status === 'completed')
                                    <div class="font-black text-xl">{{ number_format($a->aiScore->score_total / 10, 1) }}/10</div>
                                    <div class="text-xs font-bold uppercase text-text-muted mt-1">{{ $a->aiScore->core_strength }}</div>
                                    <form method="post" action="{{ route('hr.applications.ai_refresh', $a->id) }}" class="mt-1">
                                        @csrf
                                        <button type="submit" class="text-[9px] font-black uppercase text-accent hover:opacity-70 transition-opacity underline">re-rate</button>
                                    </form>
                                @elseif($a->aiScore && $a->aiScore->status === 'failed')
                                <form method="post" action="{{ route('hr.applications.ai_refresh', $a->id) }}">
                                    @csrf
                                    <button type="submit"
                                        class="bg-accent text-white font-black text-[10px] uppercase tracking-widest px-3 py-2 border-2 border-black shadow-[3px_3px_0_black] hover:translate-y-[1px] transition-all whitespace-nowrap">
                                        ⚠ AI Failed — Retry
                                    </button>
                                </form>
                            @elseif($a->aiScore && in_array($a->aiScore->status, ['processing', 'pending']))
                                <div class="flex flex-col items-center" data-app-id="{{ $a->id }}">
                                    <div class="h-6 w-6 border-4 border-accent border-t-transparent rounded-full animate-spin"></div>
                                    <span class="text-yellow-600 font-bold text-xs uppercase mt-2 animate-pulse">Analyzing...</span>
                                </div>
                            @else
                                {{-- No score yet — show Rate button --}}
                                <form method="post" action="{{ route('hr.applications.ai_refresh', $a->id) }}" class="rate-form">
                                    @csrf
                                    <button type="submit" class="bg-accent text-white font-black text-[10px] uppercase tracking-widest px-3 py-2 border-2 border-black shadow-[3px_3px_0_black] hover:translate-y-[1px] transition-all whitespace-nowrap">
                                        ★ Rate it Chally
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge-premium status-{{ $a->status->value }}">
                                {{ $a->status->value }}
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                @if($a->cv_path || $a->user->cv_path)
                                    <a href="{{ route('view.document', ['type' => 'cv', 'id' => $a->id]) }}" class="premium-btn-icon" title="View CV">
                                        <i class="bi bi-file-earmark-person"></i>
                                    </a>
                                @endif
                                @if($a->diploma_path || $a->user->diploma_path)
                                    <a href="{{ route('view.document', ['type' => 'diploma', 'id' => $a->id]) }}" class="premium-btn-icon" title="View Diploma">
                                        <i class="bi bi-mortarboard"></i>
                                    </a>
                                @endif
                                <a href="{{ route('hr.applications.berkas', $a->id) }}" class="premium-btn-icon" title="View Candidate Profile" style="box-shadow: 4px 4px 0 #FF4D30; border-color: #FF4D30;">
                                    <i class="bi bi-person-lines-fill"></i>
                                </a>
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="flex justify-end items-center gap-4">
                            <form method="post" action="{{ route('hr.applications.ai_refresh', $a->id) }}">
                                @csrf
                                <button class="action-select-premium" type="submit">Refresh AI</button>
                            </form>
                            <form method="post" action="{{ route('hr.applications.status', $a->id) }}" class="flex justify-end items-center gap-4">
                                @csrf
                                <select name="status" class="action-select-premium" onchange="this.form.submit()">
                                    @foreach(\App\Enums\ApplicationStatus::cases() as $status)
                                        <option value="{{ $status->value }}" {{ $a->status === $status ? 'selected' : '' }}>
                                            Mark as {{ ucfirst($status->value) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-8">
        {{ $applications->appends(request()->query())->links('components.premium-pagination') }}
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.from(".gsap-reveal", {
            opacity: 0,
            y: 30,
            stagger: 0.15,
            duration: 1,
            ease: "power4.out"
        });
        
        gsap.from(".ax-premium-table tbody tr", {
            opacity: 0,
            x: -20,
            stagger: 0.05,
            duration: 0.8,
            ease: "power3.out",
            delay: 0.4
        });

        // ── AI Status Polling ──────────────────────────────────────────────
        // For every row that has a spinner, poll the status endpoint every 3s
        // and update the cell live without a page reload.
        const pollingIntervals = {};

        function renderScoreCell(cell, data, appId) {
            const score   = data.score;
            const summary = data.summary;
            const csrfToken = document.querySelector('meta[name=csrf-token]')?.content || '';

            if (score.status === 'completed') {
                cell.innerHTML = `
                    <div class="font-black text-xl">${(score.score_total / 10).toFixed(1)}/10</div>
                    <div class="text-xs font-bold uppercase text-text-muted mt-1">${score.core_strength || ''}</div>`;
            } else if (score.status === 'failed') {
                cell.innerHTML = `
                    <form method="post" action="/hr/applications/${appId}/ai-refresh">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="bg-accent text-white font-black text-[10px] uppercase tracking-widest px-3 py-2 border-2 border-black shadow-[3px_3px_0_black] hover:translate-y-[1px] transition-all whitespace-nowrap">
                            ⚠ AI Failed — Retry
                        </button>
                    </form>`;
            } else {
                // Still processing — keep spinner
                cell.innerHTML = `
                    <div class="flex flex-col items-center">
                        <div class="h-6 w-6 border-4 border-accent border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-yellow-600 font-bold text-xs uppercase mt-2 animate-pulse">Analyzing...</span>
                    </div>`;
            }
        }

        function pollApplication(appId, scoreCell) {
            if (pollingIntervals[appId]) return; // already polling

            pollingIntervals[appId] = setInterval(async () => {
                try {
                    const res  = await fetch(`/hr/applications/${appId}/ai-status`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) return;
                    const data = await res.json();

                    const done = (data.score.status === 'completed' || data.score.status === 'failed');
                    renderScoreCell(scoreCell, data, appId);

                    if (done) {
                        clearInterval(pollingIntervals[appId]);
                        delete pollingIntervals[appId];
                    }
                } catch (e) {
                    // network error — keep trying
                }
            }, 3000);
        }

        // Find all rows with a spinner (processing state) and start polling
        document.querySelectorAll('.ax-premium-table tbody tr').forEach(row => {
            const spinner = row.querySelector('.animate-spin');
            if (!spinner) return;

            const scoreCell = spinner.closest('td');
            const berkasLink = row.querySelector('a[href*="/berkas"]');
            if (!berkasLink) return;

            const match = berkasLink.href.match(/\/applications\/(\d+)\/berkas/);
            if (!match) return;

            const appId = match[1];
            pollApplication(appId, scoreCell);
        });
    });
</script>
@endpush

