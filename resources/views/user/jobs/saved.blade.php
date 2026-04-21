@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/jobs-style.css') }}">
    <style>
        .saved-hero { margin-bottom: 60px; }
        .saved-title-giant { font-size: 64px; font-weight: 800; letter-spacing: -3px; color: var(--color-text); line-height: 1; margin-bottom: 16px; }
        .saved-subtext { font-size: 18px; font-weight: 600; color: var(--color-text-muted); }
    </style>
@endpush

@section('content')
<div class="saved-hero gsap-reveal">
    <h1 class="saved-title-giant">Saved Assets</h1>
    <p class="saved-subtext">Positions flagged for future intelligence processing.</p>
</div>

<div class="job-list-area">
    @if ($savedJobs->isEmpty())
        <div class="bg-secondary p-12 text-center border-2 border-dashed border-border gsap-reveal">
            <h3 class="font-black text-2xl mb-2">No saved items</h3>
            <p class="text-text-muted font-bold">Your flagged positions will appear here for review.</p>
            <a href="{{ route('jobs.index') }}" class="inline-block mt-6 bg-accent text-surface px-6 py-3 font-black uppercase tracking-widest border-2 border-black shadow-[4px_4px_0_black] hover:translate-y-[2px] transition-all">Explore Boards</a>
        </div>
    @else
        <div id="jobs-container">
            @foreach ($savedJobs as $j)
                @php
                    $salaryDisplay = !empty($j->min_salary) ? 'IDR ' . number_format($j->min_salary / 1000000, 1) . 'M+' : ($j->salary_range ?: 'Competitive');
                    $isApplied = in_array($j->id, $appliedJobIds);
                @endphp
                <div class="job-card-premium gsap-reveal" onclick="window.location.href='{{ route('jobs.show', $j->id) }}'">
                    <div class="job-main-info">
                        <h2 class="job-role-title">{{ $j->title }}</h2>
                        <div class="job-company-line">{{ $j->creator->name }}</div>
                        <div class="job-meta-line">
                            <span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linejoin="miter">
                                    <path d="M12 21l-7-7V3h14v11l-7 7z"></path>
                                    <circle cx="12" cy="9" r="2" fill="currentColor"></circle>
                                </svg>
                                {{ $j->location ?: 'Remote' }}
                            </span>
                            <span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linejoin="miter">
                                    <rect x="3" y="3" width="18" height="18"></rect>
                                    <path d="M12 8v4h4"></path>
                                </svg>
                                {{ str_replace('_', '-', ucfirst($j->job_type->value)) }}
                            </span>
                            @if($isApplied)
                                <span class="text-accent flex items-center gap-1">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linejoin="miter">
                                        <path d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                    Applied
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right flex flex-col items-end gap-3 relative z-10">
                        <div class="salary-tag-premium">{{ $salaryDisplay }}</div>
                        <form method="post" action="{{ route('user.jobs.unsave', $j->id) }}" onclick="event.stopPropagation()" class="flex items-end">
                            @csrf
                            <button type="submit" class="hover:text-accent transition-colors save-btn">
                                <svg width="24" height="24" fill="var(--color-accent)" stroke="var(--color-accent)" stroke-width="2.5" viewBox="0 0 24 24" stroke-linejoin="miter">
                                    <path d="M5 2h14v20l-7-6-7 6V2z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $savedJobs->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.from(".gsap-reveal", {
            opacity: 0,
            y: 30,
            stagger: 0.1,
            duration: 1,
            ease: "power4.out"
        });
    });
</script>
@endpush
