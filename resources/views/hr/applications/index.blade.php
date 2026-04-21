@extends('layouts.hr')

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
        font-size: 64px;
        font-weight: 800;
        letter-spacing: -4px;
        line-height: 1;
        color: var(--color-text);
    }
    .hr-subtitle {
        font-size: 18px;
        font-weight: 700;
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
        font-size: 11px;
        font-weight: 800;
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
        font-size: 20px;
        font-weight: 800;
        color: var(--color-text);
        letter-spacing: -0.5px;
    }
    .applicant-contact {
        font-size: 14px;
        font-weight: 600;
        color: var(--color-text-muted);
    }
    .status-badge-premium {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        font-weight: 800;
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
        font-weight: 800;
        text-transform: uppercase;
        font-size: 11px;
        cursor: pointer;
        box-shadow: 4px 4px 0 black;
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
        <form action="{{ route('hr.applications.index') }}" method="GET" class="flex gap-2">
            <select name="status" class="action-select-premium" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach(\App\Enums\ApplicationStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ ucfirst($status->value) }}
                    </option>
                @endforeach
            </select>
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
                            <div class="applicant-contact">{{ $a->user->email }}</div>
                        </td>
                        <td>
                            <div class="font-bold uppercase text-xs tracking-widest text-accent">{{ $a->job->title }}</div>
                            <div class="text-[10px] font-bold text-text-muted mt-1">{{ $a->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            <span class="status-badge-premium status-{{ $a->status->value }}">
                                {{ $a->status->value }}
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                @if($a->user->cv_path)
                                    <a href="{{ route('download.file', ['type' => 'cv', 'id' => $a->user->id]) }}" class="premium-btn-icon" title="CV">
                                        <i class="bi bi-file-earmark-person"></i>
                                    </a>
                                @endif
                                @if($a->user->diploma_path)
                                    <a href="{{ route('download.file', ['type' => 'diploma', 'id' => $a->user->id]) }}" class="premium-btn-icon" title="Ijazah">
                                        <i class="bi bi-mortarboard"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td class="text-right">
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
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-8">
        {{ $applications->links() }}
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
    });
</script>
@endpush
