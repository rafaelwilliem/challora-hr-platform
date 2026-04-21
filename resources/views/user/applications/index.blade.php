@extends('layouts.app')

@push('styles')
<style>
    .brutalist-title {
        font-size: 64px;
        font-weight: 900;
        letter-spacing: -4px;
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
        padding: 32px 32px;
        border: 4px solid black;
        box-shadow: 8px 8px 0 black;
        margin-bottom: 24px;
        background-color: var(--color-surface);
        transition: all 0.1s;
    }
    .brutalist-job-row:hover {
        transform: translate(-4px, -4px);
        box-shadow: 12px 12px 0 var(--color-accent);
    }

    .brutalist-job-name {
        font-size: 28px;
        font-weight: 900;
        color: var(--color-text);
        text-decoration: none;
        letter-spacing: -1.5px;
        text-transform: uppercase;
    }

    .brutalist-job-desc {
        opacity: 0.6;
    }

    .brutalist-job-row:hover .brutalist-job-name {
        color: var(--color-accent);
    }

    .brutalist-job-row:hover {
        background-color: rgba(255, 255, 255, 0.02);
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
        gap: 8px;
    }

    .brutalist-status {
        font-size: 14px;
        font-weight: 900;
        color: black;
        background: var(--color-accent);
        border: 2px solid black;
        padding: 4px 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .brutalist-meta {
        font-size: 14px;
        color: var(--color-text-muted);
        font-weight: 500;
    }

    .brutalist-pill {
        padding: 6px 12px;
        border: 2px solid black;
        background: black;
        color: white;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
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
<div class="lowercase">
    <h1 class="brutalist-title">applied jobs</h1>

    <div class="brutalist-job-list">
        @if ($applications->isEmpty())
            <div class="py-10 text-xl text-gray-500 font-medium">
                no applications yet.
            </div>
        @else
            @foreach ($applications as $a)
                <div class="brutalist-job-row" style="cursor: pointer;"
                    onclick="window.location.href='{{ route('jobs.show', $a->job_id) }}'">
                    <div class="brutalist-job-left">
                        <div class="brutalist-job-info">
                            <a href="{{ route('jobs.show', $a->job_id) }}"
                                class="brutalist-job-name">{{ $a->job->title }}</a>
                            <span class="brutalist-job-desc"
                                style="font-size: 20px; margin-top: 4px;">{{ $a->job->short_description ?: 'no description available.' }}</span>
                        </div>
                    </div>
                    <div class="brutalist-job-right">
                        <div class="brutalist-status" style="font-size: 24px; color: var(--color-text);">
                            {{ ucfirst($a->status->value) }}
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="brutalist-meta">
                                {{ $a->job->creator->name }} / {{ $a->job->location ?: 'remote' }}
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
</div>
@endsection
