@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/jobs-style.css') }}">
    <style>
        .brutalist-title {
            font-family: var(--font-display);
            font-size: 64px;
            font-weight: 800;
            letter-spacing: -3.5px;
            color: var(--color-text);
            margin-bottom: 60px;
            padding-bottom: 24px;
            border-bottom: 8px solid black;
            text-transform: uppercase;
        }

        .brutalist-job-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 28px 32px;
            border: 1px solid #1d1d1d;
            margin-bottom: 16px;
            background-color: #090909;
            transition: all 0.2s ease;
        }

        .brutalist-job-row:hover {
            background-color: #111111;
            border-color: #383838;
        }

        .brutalist-job-name {
            font-family: var(--font-display);
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: -0.02em;
            transition: color 0.2s ease;
        }

        .brutalist-job-desc {
            font-family: var(--font-body);
            font-style: italic;
            color: #9c9c9c;
            font-size: 16px;
        }

        .brutalist-job-row:hover .brutalist-job-name {
            color: #FF4D30;
        }

        .brutalist-job-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .brutalist-job-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .brutalist-job-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }

        .brutalist-status {
            font-family: var(--font-mono);
            font-size: 10px;
            font-weight: 700;
            color: #FF4D30;
            background: rgba(255, 77, 48, 0.08);
            border: 1px solid rgba(255, 77, 48, 0.4);
            padding: 5px 12px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .brutalist-meta {
            font-family: var(--font-mono);
            font-size: 11px;
            color: #888888;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .brutalist-pill {
            font-family: var(--font-mono);
            padding: 4px 10px;
            border: 1px solid #262626;
            background: #070707;
            color: #9c9c9c;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .saved-hero {
            margin-bottom: 60px;
        }

        .saved-title-giant {
            font-family: var(--font-display);
            font-size: 64px;
            font-weight: 800;
            letter-spacing: -3px;
            color: var(--color-text);
            line-height: 1;
            margin-bottom: 14px;
        }

        .saved-subtext {
            font-family: var(--font-body);
            font-style: italic;
            font-size: 20px;
            font-weight: 400;
            color: var(--color-text-muted);
        }

        @media (max-width: 768px) {
            .brutalist-job-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .brutalist-job-right {
                align-items: flex-start;
                flex-direction: row;
                justify-content: space-between;
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="saved-hero gsap-reveal">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#171717] pb-3 mb-6">
            <div class="c-release-tag mb-0">
                <span class="w-2 h-2 bg-[#FF4D30]"></span>
                <span>CHALLORA // APPLICATION TRACKER</span>
            </div>
            <div class="c-mono text-[11px] text-[#777]">CANDIDATE PIPELINE</div>
        </div>
        <h1 class="saved-title-giant">Applied Jobs</h1>
        <p class="saved-subtext">Real-time status of positions you've applied for.</p>
    </div>

    <div class="job-list-area">
        @if ($applications->isEmpty())
            <div class="bg-[#090909] p-16 text-center border border-[#1d1d1d] gsap-reveal">
                <h3 class="c-title-card text-2xl mb-2 text-white uppercase">No applications yet</h3>
                <p class="c-motto text-base">Your active applications will appear here.</p>
                <a href="{{ route('jobs.index') }}" class="c-btn-install mt-6">Explore Jobs &rarr;</a>
            </div>
        @else
            @foreach ($applications as $a)
                <div class="brutalist-job-row" style="cursor: pointer;"
                    onclick="window.location.href='{{ route('jobs.show', $a->job_id) }}'">
                    <div class="brutalist-job-left">
                        <div class="brutalist-job-info">
                            <a href="{{ route('jobs.show', $a->job_id) }}" class="brutalist-job-name">{{ $a->job->title }}</a>
                            <span class="brutalist-job-desc"
                                style="margin-top: 4px;">{{ $a->job->short_description ?: 'No description available.' }}</span>
                        </div>
                    </div>
                    <div class="brutalist-job-right">
                        <div class="brutalist-status">
                            {{ ucfirst($a->status->value) }}
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="brutalist-meta">
                                {{ $a->job->creator->name }} // {{ $a->job->location ?: 'remote' }}
                            </div>
                            <span class="brutalist-pill">applied: {{ $a->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="mt-8">
        {{ $applications->links() }}
    </div>
@endsection