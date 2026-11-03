@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/jobs-style.css') }}">
@endpush

@section('content')
    <div class="jobs-hero">
        <h1 class="jobs-title-giant">Discover Openings</h1>
        <p class="search-subtext">Precision recruitment, no compromises. Powered by Chally AI.</p>
    </div>

    <form method="get" action="{{ route('jobs.index') }}" id="job-filters-form">
        <div class="filter-bar-premium">
            <div class="filter-group relative">
                <label class="flex items-center gap-1">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Job Title
                </label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="e.g. Lead Designer"
                    class="brutalist-input-subtle" onchange="this.form.submit()">
            </div>
            <div class="filter-group relative">
                <label class="flex items-center gap-1">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                    Location
                </label>
                <input type="text" name="location" value="{{ request('location') }}" placeholder="Global"
                    class="brutalist-input-subtle" onchange="this.form.submit()">
            </div>
            <div class="filter-group relative">
                <label class="flex items-center gap-1">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    Job Type
                </label>
                <select name="job_type" class="brutalist-input-subtle" onchange="this.form.submit()">
                    <option value="">Any Schedule</option>
                    @foreach(\App\Enums\JobType::cases() as $type)
                        <option value="{{ $type->value }}" {{ request('job_type') === $type->value ? 'selected' : '' }}>
                            {{ str_replace('_', '-', ucfirst($type->value)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group relative">
                <label class="flex items-center gap-1">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                        </path>
                    </svg>
                    Experience
                </label>
                <select name="experience_level" class="brutalist-input-subtle" onchange="this.form.submit()">
                    <option value="">Any Level</option>
                    @foreach(\App\Enums\ExperienceLevel::cases() as $level)
                        <option value="{{ $level->value }}" {{ request('experience_level') === $level->value ? 'selected' : '' }}>
                            {{ $level->value }} Years
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group relative">
                <label class="flex items-center gap-1">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Min Salary
                </label>
                <input type="number" name="min_salary" value="{{ request('min_salary') }}" placeholder="e.g. 5000000"
                    class="brutalist-input-subtle" onchange="this.form.submit()">
            </div>
            <div class="filter-group relative">
                <button type="button" 
                    onclick="const inp = document.getElementById('top-choice-input'); inp.value = inp.value === '1' ? '0' : '1'; this.form.submit();"
                    class="flex gap-2 items-center {{ request('top_choice') === '1' ? 'bg-accent text-white' : 'bg-surface text-accent border-accent' }} px-6 py-4 font-mono text-xs font-bold uppercase tracking-widest border-2 shadow-[4px_4px_0_0_black] hover:translate-y-[2px] transition-all group">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    {{ request('top_choice') === '1' ? '✓ AI Picks Active' : 'AI Job Picks' }}
                </button>
                <input type="hidden" name="top_choice" id="top-choice-input" value="{{ request('top_choice', '0') }}">
            </div>
        </div>
    </form>

    <div class="job-list-full-width">
        <div class="job-list-area">
            @if($aiRecommendationsPending ?? false)
                <div class="bg-surface border-2 border-accent shadow-[6px_6px_0_0_black] p-6 mb-6 flex items-center gap-4">
                    <div class="h-6 w-6 border-4 border-accent border-t-transparent animate-spin flex-shrink-0"></div>
                    <div>
                        <p class="font-mono font-bold uppercase text-xs tracking-wider text-accent">AI Picks are being generated</p>
                        <p class="font-serif italic text-text-muted text-sm mt-1">Chally is analyzing your profile against all open positions. Showing all jobs in the meantime — check back in a moment.</p>
                    </div>
                </div>
            @endif
            @forelse ($jobs as $j)
                @php
                    $salaryDisplay = !empty($j->min_salary) ? 'IDR ' . number_format($j->min_salary / 1000000, 1) . 'M+' : ($j->salary_range ?: 'Competitive');
                    $isSaved = in_array($j->id, $savedJobIds);
                    $isApplied = in_array($j->id, $appliedJobIds);
                    $isTopAiJob = in_array($j->id, $topJobRecommendationIds ?? []);
                @endphp
                <div class="job-card-premium" onclick="window.location.href='{{ route('jobs.show', $j->id) }}'">
                    <div class="job-main-info">
                        <h2 class="job-role-title">{{ $j->title }}</h2>
                        <div class="job-company-line">{{ $j->creator->name ?? 'Company' }}</div>
                        @if($isTopAiJob)
                            <div class="inline-flex items-center gap-2 bg-accent text-white px-3 py-1 mt-2 font-mono text-[10px] font-bold uppercase tracking-widest">
                                Top Job Match by Chally AI
                            </div>
                        @endif
                        <div class="job-meta-line">
                            <span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24" stroke-linejoin="miter">
                                    <path d="M12 21l-7-7V3h14v11l-7 7z"></path>
                                    <circle cx="12" cy="9" r="2" fill="currentColor"></circle>
                                </svg>
                                {{ $j->location ?: 'Remote' }}
                            </span>
                            <span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24" stroke-linejoin="miter">
                                    <rect x="3" y="3" width="18" height="18"></rect>
                                    <path d="M12 8v4h4"></path>
                                </svg>
                                {{ $j->job_type ? str_replace('_', '-', ucfirst($j->job_type->value)) : 'Full-time' }}
                            </span>
                            @if($isApplied)
                                <span class="text-accent flex items-center gap-1">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24" stroke-linejoin="miter">
                                        <path d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                    Applied
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right flex flex-col items-end gap-3 relative z-10">
                        <div class="salary-tag-premium">{{ $salaryDisplay }}</div>
                        <form method="post"
                            action="{{ $isSaved ? route('user.jobs.unsave', $j->id) : route('user.jobs.save', $j->id) }}"
                            style="width: 100%; margin: 10px 0 0 0; display: flex; align-items: end; justify-content: end;"
                            onclick="event.stopPropagation()">
                            @csrf
                            <button type="submit" class="hover:text-accent transition-colors save-btn">
                                @if ($isSaved)
                                    <svg width="24" height="24" fill="var(--color-accent)" stroke="var(--color-accent)"
                                        stroke-width="2.5" viewBox="0 0 24 24" stroke-linejoin="miter">
                                        <path d="M5 2h14v20l-7-6-7 6V2z"></path>
                                    </svg>
                                @else
                                    <svg width="24" height="24" fill="none" stroke="var(--color-accent)" stroke-width="2.5"
                                        viewBox="0 0 24 24" stroke-linejoin="miter">
                                        <path d="M5 2h14v20l-7-6-7 6V2z"></path>
                                    </svg>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-secondary p-12 text-center border-4 border-black shadow-[8px_8px_0_0_black]">
                    <h3 class="font-black text-2xl mb-2 text-text">No matches found</h3>
                    <p class="text-text-muted font-bold uppercase tracking-tight">Try adjusting your filters or search keywords.
                    </p>
                </div>
            @endforelse
            <div class="mt-12">
                {{ $jobs->appends(request()->query())->links('components.premium-pagination') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('GSAP animation triggered for jobs index');

            // Animation for hero and filters
            gsap.from(".jobs-hero > *", { opacity: 0, x: -40, stagger: 0.2, duration: 1, ease: "power4.out" });
            gsap.from(".filter-bar-premium", { opacity: 0, y: 20, duration: 1, ease: "power4.out", delay: 0.3 });

            // Explicit fromTo for job cards to ensure visibility
            const cards = document.querySelectorAll('.job-card-premium');
            console.log('Found ' + cards.length + ' cards to animate');

            if (cards.length > 0) {
                gsap.fromTo(".job-card-premium",
                    { opacity: 0, y: 30 },
                    { opacity: 1, y: 0, stagger: 0.1, duration: 1, ease: "power4.out", delay: 0.6 }
                );
            }

            gsap.from(".ai-card-premium", { opacity: 0, x: 40, duration: 1, ease: "power4.out", delay: 0.8 });
        });
    </script>
@endpush