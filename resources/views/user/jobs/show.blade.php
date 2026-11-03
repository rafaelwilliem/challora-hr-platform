@extends('layouts.app')

@push('styles')
<style>
    .job-show-hero {
        margin-bottom: 48px;
        padding-bottom: 36px;
        border-bottom: 1px solid #1d1d1d;
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
        font-size: 11px;
        color: #888888;
        text-decoration: none;
        margin-bottom: 28px;
        transition: color 0.2s;
    }
    .back-btn-brutalist:hover {
        color: #FF4D30;
    }
    .job-title-giant {
        font-family: var(--font-display);
        font-size: clamp(2.5rem, 5vw, 4.25rem);
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 0.95;
        color: #ffffff;
        margin-bottom: 20px;
    }
    .company-badge-premium {
        font-family: var(--font-body);
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 20px;
        font-weight: 400;
        color: #9c9c9c;
        margin-bottom: 28px;
    }
    .company-icon-frame {
        font-family: var(--font-display);
        font-style: normal;
        width: 40px;
        height: 40px;
        background: #090909;
        color: #FF4D30;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
        border: 1px solid #262626;
    }
    .job-meta-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .meta-pill {
        font-family: var(--font-mono);
        background: #090909;
        border: 1px solid #262626;
        padding: 6px 14px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #b8b8b8;
    }
    .job-layout-premium {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 64px;
        align-items: start;
    }
    .content-section-premium {
        margin-bottom: 48px;
        padding-bottom: 40px;
        border-bottom: 1px solid #181818;
    }
    .content-section-premium:last-child {
        border-bottom: none;
    }
    .section-headline {
        font-family: var(--font-display);
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.02em;
        text-transform: uppercase;
        color: #ffffff;
        margin-bottom: 20px;
        position: relative;
    }
    .section-headline::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 32px;
        height: 2px;
        background: #FF4D30;
    }
    .rich-text-premium {
        font-family: var(--font-body);
        font-size: 18px;
        line-height: 1.75;
        color: #b8b8b8;
        font-weight: 400;
    }
    .rich-text-premium ul {
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }
    .rich-text-premium li {
        margin-bottom: 14px;
        display: flex;
        gap: 12px;
    }
    .rich-text-premium li::before {
        font-family: var(--font-mono);
        content: '→';
        color: #FF4D30;
        font-weight: 700;
        flex-shrink: 0;
    }
    .sidebar-card-premium {
        background: #090909;
        border: 1px solid #1d1d1d;
        padding: 36px;
        position: sticky;
        top: 110px;
        border-radius: 0;
    }
    .sidebar-salary {
        font-family: var(--font-display);
        font-size: 32px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.03em;
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 1px solid #181818;
        line-height: 1;
    }
    .sidebar-stats {
        margin-bottom: 32px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .stat-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid #141414;
    }
    .stat-row label {
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #777777;
    }
    .stat-row span {
        font-family: var(--font-mono);
        font-size: 12px;
        font-weight: 600;
        color: #E5E5DF;
    }
    .apply-btn-giant {
        font-family: var(--font-mono);
        display: block;
        width: 100%;
        background: #ECECE7;
        color: #000000;
        text-align: center;
        padding: 16px 24px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        border: 1px solid #ECECE7;
        border-radius: 0 !important;
        transition: all 0.18s ease;
        text-decoration: none;
        margin-bottom: 12px;
        cursor: pointer;
    }
    .apply-btn-giant:hover {
        background: #FF4D30;
        border-color: #FF4D30;
        color: #ffffff;
    }
    .btn-secondary-shadow {
        background: transparent;
        color: #ECECE7;
        border: 1px solid #333333;
    }
    .btn-secondary-shadow:hover {
        background: #171717;
        border-color: #777777;
        color: #ffffff;
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
