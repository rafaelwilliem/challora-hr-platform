@extends('layouts.app')

@push('styles')
<style>
    .job-show-hero {
        margin-bottom: 60px;
        position: relative;
    }
    .back-btn-brutalist {
        font-family: var(--font-mono);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-size: 12px;
        color: var(--color-text-muted);
        text-decoration: none;
        margin-bottom: 32px;
        transition: color 0.2s;
    }
    .back-btn-brutalist:hover {
        color: var(--color-accent);
    }
    .job-title-giant {
        font-family: var(--font-display);
        font-size: 68px;
        font-weight: 800;
        letter-spacing: -3.5px;
        line-height: 0.92;
        color: var(--color-text);
        margin-bottom: 24px;
    }
    .company-badge-premium {
        font-family: var(--font-body);
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 22px;
        font-weight: 500;
        color: var(--color-text);
        margin-bottom: 40px;
    }
    .company-icon-frame {
        font-family: var(--font-display);
        font-style: normal;
        width: 48px;
        height: 48px;
        background: var(--color-text);
        color: var(--color-surface);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 20px;
        border: 2px solid black;
        box-shadow: 4px 4px 0 var(--color-accent);
    }
    .job-meta-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .meta-pill {
        font-family: var(--font-mono);
        background: var(--color-accent);
        border: 2px solid black;
        padding: 8px 16px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: black;
    }
    .job-layout-premium {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 80px;
        align-items: start;
    }
    .content-section-premium {
        margin-bottom: 60px;
    }
    .section-headline {
        font-family: var(--font-display);
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -1px;
        margin-bottom: 24px;
        position: relative;
    }
    .section-headline::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 40px;
        height: 4px;
        background: var(--color-accent);
    }
    .rich-text-premium {
        font-family: var(--font-body);
        font-size: 19px;
        line-height: 1.75;
        color: #c8c8c8;
        font-weight: 400;
    }
    .rich-text-premium ul {
        list-style: none;
        padding: 0;
        margin-top: 24px;
    }
    .rich-text-premium li {
        margin-bottom: 16px;
        display: flex;
        gap: 12px;
    }
    .rich-text-premium li::before {
        font-family: var(--font-mono);
        content: '→';
        color: var(--color-accent);
        font-weight: 700;
        flex-shrink: 0;
    }
    .sidebar-card-premium {
        background: var(--color-surface);
        border: 4px solid black;
        padding: 40px;
        position: sticky;
        top: 120px;
        box-shadow: 12px 12px 0 black;
        border-radius: 0;
    }
    .sidebar-salary {
        font-family: var(--font-display);
        font-size: 38px;
        font-weight: 800;
        color: var(--color-accent);
        letter-spacing: -1.5px;
        margin-bottom: 32px;
        line-height: 1;
    }
    .sidebar-stats {
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .stat-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
    }
    .stat-row label {
        font-family: var(--font-mono);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--color-text-muted);
    }
    .stat-row span {
        font-family: var(--font-sans);
        font-weight: 700;
        color: var(--color-text);
    }
    .apply-btn-giant {
        font-family: var(--font-mono);
        display: block;
        width: 100%;
        background: var(--color-accent);
        color: var(--color-surface);
        text-align: center;
        padding: 20px 24px;
        font-size: 14px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        border: 2px solid black;
        border-radius: 0 !important;
        box-shadow: 6px 6px 0 black;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        margin-bottom: 16px;
        cursor: pointer;
    }
    .apply-btn-giant:hover {
        transform: translate(-2px, -2px);
        box-shadow: 10px 10px 0 black;
    }
    .btn-secondary-shadow {
        background: var(--color-surface);
        color: var(--color-text);
        box-shadow: 4px 4px 0 black;
    }
    .btn-secondary-shadow:hover {
        box-shadow: 6px 6px 0 black;
    }

    @media (max-width: 1024px) {
        .job-layout-premium { grid-template-columns: 1fr; gap: 40px; }
        .job-title-giant { font-size: 48px; }
        .sidebar-card-premium { position: static; }
    }
</style>
@endpush

@section('content')
<div class="job-show-hero">
    <a href="{{ route('jobs.index') }}" class="back-btn-brutalist">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Boards
    </a>
    <h1 class="job-title-giant">{{ $job->title }}</h1>
    
    <div class="company-badge-premium">
        <div class="company-icon-frame">
            {{ substr($job->creator->name, 0, 1) }}
        </div>
        <span>{{ $job->creator->name }}</span>
    </div>

    <div class="job-meta-pills">
        <span class="meta-pill">{{ str_replace('_', '-', ucfirst($job->job_type->value)) }}</span>
        <span class="meta-pill">{{ $job->location ?: 'Remote Allowed' }}</span>
        <span class="meta-pill">{{ $job->experience_level->value }} Tier</span>
        <span class="meta-pill">Verified by AI</span>
    </div>
</div>

<div class="job-layout-premium">
    <div class="main-content">
        <div class="content-section-premium">
            <h3 class="section-headline">Opportunity Overview</h3>
            <div class="rich-text-premium">
                {!! nl2br(e($job->description)) !!}
            </div>
        </div>

        @if($job->skills_json)
            <div class="content-section-premium">
                <h3 class="section-headline">Required Skills</h3>
                <div class="rich-text-premium">
                    <ul>
                        @foreach($job->skills_json as $skill)
                            <li>{{ $skill }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if($job->benefits_json)
            <div class="content-section-premium">
                <h3 class="section-headline">Benefits</h3>
                <div class="rich-text-premium">
                    <ul>
                        @foreach($job->benefits_json as $benefit)
                            <li>{{ $benefit }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <aside class="sidebar-area">
        <div class="sidebar-card-premium">
            <div class="sidebar-salary">
                {{ !empty($job->min_salary) ? 'IDR ' . number_format($job->min_salary / 1000000, 1) . 'M+' : ($job->salary_range ?: 'Competitive') }}
            </div>
            
            <div class="sidebar-stats">
                <div class="stat-row">
                    <label>Company</label>
                    <span>{{ $job->creator->name }}</span>
                </div>
                <div class="stat-row">
                    <label>Location</label>
                    <span>{{ $job->location ?: 'Remote' }}</span>
                </div>
                <div class="stat-row">
                    <label>Experience</label>
                    <span>{{ $job->experience_level->value }}</span>
                </div>
                <div class="stat-row">
                    <label>Deadline</label>
                    <span>{{ $job->deadline ? $job->deadline->format('d M Y') : 'Open' }}</span>
                </div>
            </div>

            @auth
                @if(auth()->user()->role === \App\Enums\UserRole::USER)
                    @if ($isApplied)
                        <div class="apply-btn-giant btn-secondary-shadow text-center opacity-50 cursor-not-allowed">Application Sent</div>
                    @else
                        <form method="post" action="{{ route('user.jobs.apply', $job->id) }}">
                            @csrf
                            <button type="submit" class="apply-btn-giant">Apply for Position</button>
                        </form>
                    @endif

                    <form method="post" action="{{ $isSaved ? route('user.jobs.unsave', $job->id) : route('user.jobs.save', $job->id) }}">
                        @csrf
                        <button type="submit" class="apply-btn-giant btn-secondary-shadow">
                            {{ $isSaved ? 'Remove from Saved' : 'Save for Later' }}
                        </button>
                    </form>
                @else
                    <div class="bg-secondary p-4 border-2 border-black font-bold text-center">
                        Viewing as HR Moderator
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" class="apply-btn-giant">Sign in to Apply</a>
            @endauth
        </div>
    </aside>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.from(".job-show-hero > *", { opacity: 0, y: 30, stagger: 0.1, duration: 1, ease: "power4.out" });
        gsap.from(".main-content > *", { opacity: 0, y: 30, stagger: 0.15, duration: 1, ease: "power4.out", delay: 0.3 });
        gsap.from(".sidebar-card-premium", { opacity: 0, scale: 0.95, duration: 0.8, ease: "back.out(1.7)", delay: 0.5 });
    });
</script>
@endpush
