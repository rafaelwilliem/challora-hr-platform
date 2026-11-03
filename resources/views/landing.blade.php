@extends('layouts.app')

@push('styles')
<style>
    /* ── CHALLORA HR DESIGN SYSTEM ── */
    :root {
        --c-accent: #FF4D30;
        --c-accent-hover: #e03e00;
        --c-black: #070707;
        --c-dark-surface: #0e0e0e;
        --c-border: #1d1d1d;
        --c-border-subtle: #171717;
        --c-white: #FFFFFF;
        --c-cream: #E5E5DF;
        --c-gray-mid: #9E9E9E;
        --c-gray-dark: #555555;
        --nav-height: 80px;

        /* Typography Hierarchy */
        --font-display: "Space Grotesk", "Poppins", -apple-system, BlinkMacSystemFont, sans-serif;
        --font-body: "Newsreader", "Georgia", "Cambria", serif;
        --font-mono: "JetBrains Mono", ui-monospace, SFMono-Regular, monospace;
    }

    body, html {
        background-color: var(--c-black);
        color: var(--c-cream);
        font-family: var(--font-body) !important;
        -webkit-font-smoothing: antialiased;
    }

    /* ── 1. Big Titles: Sans-Serif ── */
    .c-title-display {
        font-family: var(--font-display) !important;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 0.95;
    }

    .c-title-section {
        font-family: var(--font-display) !important;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1.1;
    }

    .c-title-card {
        font-family: var(--font-display) !important;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    /* ── 2. Main Font: Serif ── */
    .c-serif {
        font-family: var(--font-body) !important;
    }

    .c-motto {
        font-family: var(--font-body) !important;
        font-style: italic;
        color: #9c9c9c;
        font-size: clamp(1.2rem, 2.2vw, 1.55rem);
        line-height: 1.45;
    }

    .c-body-text {
        font-family: var(--font-body) !important;
        color: #b8b8b8;
        font-size: 1.05rem;
        line-height: 1.7;
    }

    /* ── 3. Mini Text & Decoration: Monospace ── */
    .c-mono {
        font-family: var(--font-mono) !important;
        letter-spacing: 0.06em;
    }

    /* ── Pure Information Hero ── */
    .c-hero-pure {
        min-height: calc(100vh - var(--nav-height));
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        background-color: var(--c-black);
        border-bottom: 1px solid var(--c-border);
        padding-top: calc(var(--nav-height) + 3.5rem);
        padding-bottom: 5rem;
    }

    .c-hero-inner {
        max-width: 1360px;
        margin: 0 auto;
        padding: 0 3rem;
    }

    /* Kicker & Tags */
    .c-release-tag {
        font-family: var(--font-mono) !important;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #777777;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 1.75rem;
    }

    /* Feature Breakdown Grid */
    .c-feature-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border: 1px solid var(--c-border);
        background: #090909;
        margin-top: 3.5rem;
    }

    .c-feature-box {
        padding: 1.75rem 1.75rem;
        border-right: 1px solid var(--c-border);
        display: flex;
        flex-direction: column;
        transition: background 0.2s ease;
    }
    .c-feature-box:last-child {
        border-right: none;
    }
    .c-feature-box:hover {
        background: #111111;
    }

    /* Zero-Radius Buttons */
    .c-btn-install {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.95rem 2.25rem;
        background-color: #ECECE7;
        color: #000000;
        font-family: var(--font-mono) !important;
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        text-decoration: none;
        border-radius: 0px !important;
        border: 1px solid #ECECE7;
        transition: all 0.18s ease;
        cursor: pointer;
    }
    .c-btn-install:hover {
        background-color: var(--c-accent);
        border-color: var(--c-accent);
        color: #ffffff;
        box-shadow: 4px 4px 0px rgba(255, 255, 255, 0.2);
    }

    .c-btn-source {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.95rem 2.25rem;
        background-color: transparent;
        color: #ECECE7;
        font-family: var(--font-mono) !important;
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        text-decoration: none;
        border-radius: 0px !important;
        border: 1px solid #333333;
        transition: all 0.18s ease;
        cursor: pointer;
    }
    .c-btn-source:hover {
        background-color: #171717;
        border-color: #777777;
        color: #ffffff;
    }

    /* Marquee */
    .c-marquee {
        background-color: #0c0c0c;
        color: #777777;
        padding: 0.85rem 0;
        overflow: hidden;
        white-space: nowrap;
        font-family: var(--font-mono) !important;
        font-size: 0.85rem;
        text-transform: uppercase;
        border-bottom: 1px solid var(--c-border);
        border-top: 1px solid var(--c-border);
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        letter-spacing: 0.1em;
    }
    .c-marquee span {
        display: inline-block;
        animation: c-scroll 38s linear infinite;
    }
    @keyframes c-scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* Section Containers */
    .c-section {
        padding: 6.5rem 3rem;
        max-width: 1360px;
        margin: 0 auto;
        width: 100%;
    }

    .c-grid-solutions {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid var(--c-border);
        border-left: 1px solid var(--c-border);
        margin-top: 3.5rem;
    }

    .c-solution-card {
        border-right: 1px solid var(--c-border);
        border-bottom: 1px solid var(--c-border);
        padding: 2.75rem 2rem;
        display: flex;
        flex-direction: column;
        background: #090909;
        position: relative;
        border-radius: 0px !important;
        transition: all 0.22s ease;
    }

    .c-solution-card:hover {
        background: #111111;
        border-color: #383838;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .c-feature-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .c-feature-box:nth-child(2) {
            border-right: none;
        }
        .c-feature-box:nth-child(1), .c-feature-box:nth-child(2) {
            border-bottom: 1px solid var(--c-border);
        }
        .c-grid-solutions {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .c-hero-inner {
            padding: 0 1.25rem;
        }
        .c-hero-pure {
            padding-top: calc(var(--nav-height) + 2rem);
            padding-bottom: 3.5rem;
        }
        .c-feature-grid {
            grid-template-columns: 1fr;
        }
        .c-feature-box {
            border-right: none;
            border-bottom: 1px solid var(--c-border);
        }
        .c-grid-solutions {
            grid-template-columns: 1fr;
        }
        .c-section {
            padding: 4rem 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="flex flex-col w-full bg-[#070707] text-[#E5E5DF] selection:bg-[#FF4D30] selection:text-white">

    <!-- 01 // PURE INFORMATION HERO SECTION -->
    <section class="c-hero-pure">
        <div class="c-hero-inner">

            <!-- Top Tagline & Status -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#1c1c1c] pb-4 mb-8">
                <div class="c-release-tag mb-0">
                    <span class="w-2 h-2 bg-[#FF4D30]"></span>
                    <span>CHALLORA // HR &amp; TALENT PLATFORM</span>
                </div>
                <div class="c-mono text-[11px] text-gray-500 flex items-center gap-2">
                    <span class="text-gray-400">CORE ATS</span>
                    <span>/</span>
                    <span>RECRUITMENT INTELLIGENCE</span>
                </div>
            </div>

            <!-- Big Title (Sans-Serif) -->
            <h1 class="c-title-display text-white text-6xl sm:text-7xl md:text-8xl lg:text-9xl mb-4">
                Challora HR
            </h1>

            <!-- Motto / Sub-headline (Italic Serif) -->
            <p class="c-motto max-w-3xl mb-8">
                A focused talent acquisition platform — matching candidate potential with company growth through objective intelligence.
            </p>

            <!-- Product Purpose (Clean Body Serif) -->
            <div class="max-w-3xl mb-10 space-y-4">
                <p class="c-body-text text-base sm:text-lg">
                    Challora HR streamlines the end-to-end recruitment lifecycle. Candidates get a clear, transparent application process with instant profile coaching, while hiring teams gain automated resume evaluation, candidate scorecards, and visual stage pipelines in a single, focused workspace.
                </p>
            </div>

            <!-- Action Area (Zero-Radius Buttons) -->
            <div class="flex flex-wrap items-center gap-4 mb-12">
                <a href="{{ route('jobs.index') }}" class="c-btn-install">
                    BROWSE OPEN POSITIONS &rarr;
                </a>
                @guest
                <a href="{{ route('login') }}" class="c-btn-source">
                    RECRUITER ACCESS
                </a>
                @else
                <a href="{{ auth()->user()->isAdmin() ? route('hr.dashboard') : route('user.applications.index') }}" class="c-btn-source">
                    GO TO DASHBOARD
                </a>
                @endguest
            </div>

            <!-- Pure Information Breakdown Grid (The 4 Pillars of Challora HR) -->
            <div class="c-feature-grid">
                <div class="c-feature-box">
                    <span class="c-mono text-[10px] text-[#FF4D30] font-bold tracking-widest uppercase mb-2">// 01 &middot; SCREENING</span>
                    <h3 class="font-sans font-bold text-white text-base mb-1.5">AI Candidate Match</h3>
                    <p class="c-serif text-xs text-gray-400 leading-relaxed">
                        Evaluates skills and experience contextually against job requirements, bypassing superficial keyword tricks.
                    </p>
                </div>

                <div class="c-feature-box">
                    <span class="c-mono text-[10px] text-[#FF4D30] font-bold tracking-widest uppercase mb-2">// 02 &middot; WORKFLOW</span>
                    <h3 class="font-sans font-bold text-white text-base mb-1.5">Pipeline Stages</h3>
                    <p class="c-serif text-xs text-gray-400 leading-relaxed">
                        Track candidate progression with clarity: Applied &rarr; Screening &rarr; Interview &rarr; Offered &rarr; Hired.
                    </p>
                </div>

                <div class="c-feature-box">
                    <span class="c-mono text-[10px] text-[#FF4D30] font-bold tracking-widest uppercase mb-2">// 03 &middot; DOSSIERS</span>
                    <h3 class="font-sans font-bold text-white text-base mb-1.5">Decision Scorecards</h3>
                    <p class="c-serif text-xs text-gray-400 leading-relaxed">
                        Structured summaries providing hiring managers with quick, objective snapshots of applicant suitability.
                    </p>
                </div>

                <div class="c-feature-box">
                    <span class="c-mono text-[10px] text-[#FF4D30] font-bold tracking-widest uppercase mb-2">// 04 &middot; COACHING</span>
                    <h3 class="font-sans font-bold text-white text-base mb-1.5">Applicant Coaching</h3>
                    <p class="c-serif text-xs text-gray-400 leading-relaxed">
                        Gives applicants constructive profile recommendations to highlight real strengths for future applications.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- 02 // SUBTLE MONO MARQUEE -->
    <div class="c-marquee">
        <span>
            ✦ APPLICANT TRACKING &nbsp;&nbsp;&nbsp;&nbsp; // &nbsp;&nbsp;&nbsp;&nbsp; CANDIDATE MATCHING &nbsp;&nbsp;&nbsp;&nbsp; // &nbsp;&nbsp;&nbsp;&nbsp; OBJECTIVE EVALUATION &nbsp;&nbsp;&nbsp;&nbsp; // &nbsp;&nbsp;&nbsp;&nbsp; STRUCTURED PIPELINES &nbsp;&nbsp;&nbsp;&nbsp; // &nbsp;&nbsp;&nbsp;&nbsp; CHALLORA HR PLATFORM &nbsp;&nbsp;&nbsp;&nbsp;
            ✦ APPLICANT TRACKING &nbsp;&nbsp;&nbsp;&nbsp; // &nbsp;&nbsp;&nbsp;&nbsp; CANDIDATE MATCHING &nbsp;&nbsp;&nbsp;&nbsp; // &nbsp;&nbsp;&nbsp;&nbsp; OBJECTIVE EVALUATION &nbsp;&nbsp;&nbsp;&nbsp; // &nbsp;&nbsp;&nbsp;&nbsp; STRUCTURED PIPELINES &nbsp;&nbsp;&nbsp;&nbsp; // &nbsp;&nbsp;&nbsp;&nbsp; CHALLORA HR PLATFORM &nbsp;&nbsp;&nbsp;&nbsp;
        </span>
    </div>

    <!-- 03 // CAPABILITIES SECTION -->
    <section class="c-section">
        <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-[#202020] pb-6 gap-4">
            <div>
                <span class="c-mono text-xs text-[#FF4D30] font-bold tracking-widest uppercase mb-2 block">// CAPABILITY MATRIX</span>
                <h2 class="c-title-section text-white text-3xl sm:text-4xl md:text-5xl">RECRUITMENT INTELLIGENCE</h2>
            </div>
            <p class="c-body-text max-w-md">
                Designed to make recruitment transparent, structured, and free of administrative bottlenecks.
            </p>
        </div>
        
        <div class="c-grid-solutions">
            <!-- Module 01 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 01</span>
                    <span class="c-mono text-[10px] text-gray-400 border border-[#262626] px-2 py-0.5 uppercase tracking-wider">PARSING</span>
                </div>
                <h3 class="c-title-card text-white text-xl mb-3 group-hover:text-[#FF4D30] transition-colors">CV Evaluation</h3>
                <p class="c-body-text text-sm mb-8 flex-1">
                    Extracts skills, work trajectories, and project contributions semantically to surface genuine qualifications without manual spreadsheet tracking.
                </p>
                <div class="mt-auto pt-4 border-t border-[#181818]">
                    <a href="{{ route('jobs.index') }}" class="c-mono text-xs text-[#FF4D30] font-bold hover:underline flex items-center justify-between">
                        <span>EXPLORE ROLES</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Module 02 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 02</span>
                    <span class="c-mono text-[10px] text-gray-400 border border-[#262626] px-2 py-0.5 uppercase tracking-wider">PIPELINE</span>
                </div>
                <h3 class="c-title-card text-white text-xl mb-3 group-hover:text-[#FF4D30] transition-colors">Applicant Tracking</h3>
                <p class="c-body-text text-sm mb-8 flex-1">
                    Visual pipelines allow HR coordinators to transition applicants across stages with immediate status updates and centralized candidate records.
                </p>
                <div class="mt-auto pt-4 border-t border-[#181818]">
                    <a href="{{ route('register') }}" class="c-mono text-xs text-[#FF4D30] font-bold hover:underline flex items-center justify-between">
                        <span>JOIN AS RECRUITER</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Module 03 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 03</span>
                    <span class="c-mono text-[10px] text-gray-400 border border-[#262626] px-2 py-0.5 uppercase tracking-wider">ASSESSMENT</span>
                </div>
                <h3 class="c-title-card text-white text-xl mb-3 group-hover:text-[#FF4D30] transition-colors">Candidate Dossiers</h3>
                <p class="c-body-text text-sm mb-8 flex-1">
                    Automated candidate dossiers synthesize resume data, portfolio evidence, and skill breakdowns to give interviewers an objective summary.
                </p>
                <div class="mt-auto pt-4 border-t border-[#181818]">
                    <a href="{{ route('register') }}" class="c-mono text-xs text-[#FF4D30] font-bold hover:underline flex items-center justify-between">
                        <span>VIEW WORKFLOW</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Module 04 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 04</span>
                    <span class="c-mono text-[10px] text-gray-400 border border-[#262626] px-2 py-0.5 uppercase tracking-wider">COACHING</span>
                </div>
                <h3 class="c-title-card text-white text-xl mb-3 group-hover:text-[#FF4D30] transition-colors">Applicant Feedback</h3>
                <p class="c-body-text text-sm mb-8 flex-1">
                    Candidates receive actionable guidance on CV gaps, portfolio improvements, and skill positioning to strengthen their career trajectory.
                </p>
                <div class="mt-auto pt-4 border-t border-[#181818]">
                    <a href="{{ route('register') }}" class="c-mono text-xs text-[#FF4D30] font-bold hover:underline flex items-center justify-between">
                        <span>LEARN MORE</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 04 // LIVE OPEN POSITIONS (SHARP BRUTALIST GRID) -->
    <section class="c-section pt-0">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-[#202020] pb-4 mb-8 gap-4">
            <div>
                <span class="c-mono text-xs text-[#FF4D30] font-bold tracking-widest uppercase mb-1 block">// ACTIVE REQUISITIONS</span>
                <h2 class="c-title-section text-white text-2xl sm:text-3xl">OPEN POSITIONS</h2>
            </div>
            <a href="{{ route('jobs.index') }}" class="c-mono text-xs border border-[#333] px-4 py-2 text-gray-300 hover:text-white hover:border-gray-400 transition-colors">
                VIEW ALL POSITIONS &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-0 border-t border-l border-[#202020]">
            @forelse($latestJobs as $job)
            <div class="border-r border-b border-[#202020] p-8 relative group bg-[#090909] hover:bg-[#121212] transition-colors flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <span class="c-mono text-[10px] text-[#FF4D30] font-bold uppercase tracking-wider">
                        LOC // {{ strtoupper($job->location ?? 'REMOTE') }}
                    </span>
                    <span class="w-1.5 h-1.5 bg-[#FF4D30]"></span>
                </div>

                <h3 class="c-title-card text-xl font-bold mb-3 text-white group-hover:text-[#FF4D30] transition-colors">
                    {{ $job->title }}
                </h3>

                <div class="flex flex-wrap gap-1.5 mb-8">
                    @if(!empty($job->skills_json) && is_array($job->skills_json))
                        @foreach(array_slice($job->skills_json, 0, 3) as $skill)
                        <span class="border border-[#262626] bg-[#000] text-gray-400 px-2 py-0.5 c-mono text-[9px] uppercase tracking-wider">
                            {{ $skill }}
                        </span>
                        @endforeach
                    @else
                        <span class="border border-[#262626] bg-[#000] text-gray-400 px-2 py-0.5 c-mono text-[9px] uppercase tracking-wider">
                            TALENT
                        </span>
                    @endif
                </div>

                <div class="mt-auto pt-4 border-t border-[#181818] flex items-center justify-between">
                    <span class="c-mono text-[10px] text-gray-500">REF: CHL-{{ str_pad($job->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <a href="{{ route('jobs.show', $job->id) }}" class="c-btn-install py-1.5 px-3 text-[10px]">
                        APPLY POSITION &rarr;
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center border-r border-b border-[#202020] bg-[#090909]">
                <p class="c-mono text-gray-400 text-sm mb-4">// NO ACTIVE PUBLIC REQUISITIONS DETECTED</p>
                <a href="{{ route('register') }}" class="c-btn-install text-xs">CREATE FIRST JOB REQUISITION</a>
            </div>
            @endforelse
        </div>
    </section>

</div>
@endsection
