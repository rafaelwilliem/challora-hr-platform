@extends('layouts.app')

@push('styles')
<style>
    /* ── CHALLORA BRUTALIST DESIGN TOKENS ── */
    :root {
        --c-accent: #FF4D30;
        --c-accent-hover: #e03e00;
        --c-black: #000000;
        --c-dark: #0a0a0a;
        --c-dark-surface: #111111;
        --c-border: #222222;
        --c-border-accent: rgba(255, 77, 48, 0.4);
        --c-white: #FFFFFF;
        --c-gray-light: #F4F4F4;
        --c-gray-mid: #A0A0A0;
        --c-gray-dark: #666666;
        --nav-height: 80px;
    }

    body, html {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        background-color: var(--c-black);
        color: var(--c-white);
    }

    /* ── Typography Scale & Rhythm ── */
    .c-mono {
        font-family: "JetBrains Mono", monospace;
        letter-spacing: 0.06em;
    }

    .c-title-mega {
        font-size: clamp(3.75rem, 9.5vw, 8.5rem);
        font-weight: 900;
        line-height: 0.88;
        letter-spacing: -0.045em;
        text-transform: uppercase;
    }

    .c-title-section {
        font-size: clamp(2rem, 3.8vw, 3.25rem);
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: -0.03em;
        text-transform: uppercase;
    }

    .c-title-card {
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    /* ── Zero-Radius Brutalist Buttons ── */
    .c-btn {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.95rem 1.85rem;
        border-radius: 0px !important;
        font-family: "JetBrains Mono", monospace;
        font-size: 0.825rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.18s ease-in-out;
        cursor: pointer;
        position: relative;
    }

    .c-btn-accent {
        background-color: var(--c-accent);
        color: var(--c-white);
        border: 2px solid var(--c-accent);
    }
    .c-btn-accent:hover {
        background-color: var(--c-black);
        color: var(--c-accent);
        border-color: var(--c-accent);
        box-shadow: 4px 4px 0px var(--c-accent);
        transform: translate(-2px, -2px);
    }

    .c-btn-outline {
        background-color: transparent;
        color: var(--c-white);
        border: 2px solid #444444;
    }
    .c-btn-outline:hover {
        background-color: var(--c-white);
        color: var(--c-black);
        border-color: var(--c-white);
        box-shadow: 4px 4px 0px rgba(255, 255, 255, 0.4);
        transform: translate(-2px, -2px);
    }

    .c-btn-ghost-accent {
        background-color: transparent;
        color: var(--c-accent);
        border: 1px solid var(--c-accent);
        padding: 0.55rem 1.2rem;
        font-size: 0.725rem;
    }
    .c-btn-ghost-accent:hover {
        background-color: var(--c-accent);
        color: var(--c-black);
        box-shadow: 3px 3px 0px #ffffff;
        transform: translate(-1px, -1px);
    }

    /* ── Split Hero Layout ── */
    .c-hero {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: calc(100vh - var(--nav-height));
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        border-bottom: 2px solid var(--c-border);
        background-color: var(--c-black);
    }

    .c-hero-left {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: calc(var(--nav-height) + 2.5rem) 4rem 4rem;
        border-right: 2px solid var(--c-border);
        background-color: var(--c-dark);
        overflow: hidden;
    }

    .c-hero-left-bg {
        position: absolute;
        inset: 0;
        z-index: 1;
    }
    .c-hero-left-bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        filter: grayscale(100%) contrast(1.25) brightness(0.45);
    }

    .c-hero-grid-overlay {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(to right, rgba(255, 255, 255, 0.04) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        background-size: 40px 40px;
        z-index: 2;
        pointer-events: none;
    }

    .c-hero-left-content {
        position: relative;
        z-index: 3;
    }

    .c-hero-right {
        background-color: var(--c-black);
        color: var(--c-white);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: calc(var(--nav-height) + 2.5rem) 4rem 4rem;
        position: relative;
    }

    /* ── Interactive Terminal Typewriter ── */
    .c-typewriter-box {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        background: #0d0d0d;
        border: 1px solid #262626;
        border-left: 3px solid var(--c-accent);
        padding: 0.75rem 1.25rem;
        font-family: "JetBrains Mono", monospace;
        font-size: 0.85rem;
        color: #d1d5db;
        margin-bottom: 2rem;
        min-height: 48px;
    }

    .c-cursor {
        display: inline-block;
        color: var(--c-accent);
        font-weight: 900;
        animation: c-blink 0.9s infinite step-start;
    }

    @keyframes c-blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }

    /* ── Sharp Technical System Status ── */
    .c-status-panel {
        margin-top: 3.5rem;
        border-top: 1px solid #222222;
        padding-top: 1.75rem;
    }

    .c-status-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.6rem 2rem;
    }

    .c-status-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.8rem;
        color: var(--c-gray-mid);
        border-bottom: 1px solid #1a1a1a;
        padding-bottom: 0.4rem;
        transition: color 0.15s ease;
    }
    .c-status-item:hover {
        color: var(--c-white);
    }

    .c-status-led {
        width: 7px;
        height: 7px;
        border-radius: 0px !important;
        background-color: var(--c-accent);
        box-shadow: 0 0 6px var(--c-accent);
    }

    /* ── High-Impact Brutalist Metrics Bar ── */
    .c-metrics-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-bottom: 2px solid var(--c-border);
        background: #090909;
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
    }

    .c-metric-cell {
        padding: 2.25rem 2rem;
        border-right: 1px solid var(--c-border);
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: background-color 0.2s ease;
    }
    .c-metric-cell:last-child {
        border-right: none;
    }
    .c-metric-cell:hover {
        background-color: #121212;
    }

    /* ── Marquee Ribbon ── */
    .c-marquee {
        background-color: var(--c-accent);
        color: var(--c-black);
        padding: 0.85rem 0;
        overflow: hidden;
        white-space: nowrap;
        font-family: "JetBrains Mono", monospace;
        font-weight: 900;
        font-size: 0.95rem;
        text-transform: uppercase;
        border-bottom: 2px solid var(--c-black);
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        letter-spacing: 0.08em;
    }
    .c-marquee span {
        display: inline-block;
        animation: c-scroll 32s linear infinite;
    }
    @keyframes c-scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* ── Sections & Grids (Sharp Corners Only) ── */
    .c-section {
        padding: 6.5rem 4rem;
        max-width: 1600px;
        margin: 0 auto;
        width: 100%;
    }

    .c-grid-solutions {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid #2a2a2a;
        border-left: 1px solid #2a2a2a;
        margin-top: 3.5rem;
    }

    .c-solution-card {
        border-right: 1px solid #2a2a2a;
        border-bottom: 1px solid #2a2a2a;
        padding: 2.5rem 2rem;
        display: flex;
        flex-direction: column;
        background: #070707;
        position: relative;
        border-radius: 0px !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .c-solution-card:hover {
        background: #111111;
        border-color: var(--c-accent);
        z-index: 10;
        box-shadow: inset 0 0 0 1px var(--c-accent);
    }

    .c-tag-sharp {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.65rem;
        border-radius: 0px !important;
        border: 1px solid rgba(255, 77, 48, 0.4);
        background-color: rgba(255, 77, 48, 0.06);
        color: var(--c-accent);
        font-family: "JetBrains Mono", monospace;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    /* ── Responsive Adaptations ── */
    @media (max-width: 1200px) {
        .c-metrics-strip { grid-template-columns: repeat(2, 1fr); }
        .c-metric-cell:nth-child(2) { border-right: none; }
        .c-metric-cell:nth-child(1), .c-metric-cell:nth-child(2) { border-bottom: 1px solid var(--c-border); }
        .c-grid-solutions { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 900px) {
        .c-hero { grid-template-columns: 1fr; }
        .c-hero-left { min-height: 45vh; padding: calc(var(--nav-height) + 1.5rem) 1.5rem 2.5rem; border-right: none; border-bottom: 2px solid var(--c-border); }
        .c-hero-right { padding: 2.5rem 1.5rem; }
        .c-section { padding: 4rem 1.5rem; }
    }

    @media (max-width: 640px) {
        .c-metrics-strip { grid-template-columns: 1fr; }
        .c-metric-cell { border-right: none; border-bottom: 1px solid var(--c-border); padding: 1.5rem; }
        .c-grid-solutions { grid-template-columns: 1fr; }
        .c-status-grid { grid-template-columns: 1fr; }
        .c-title-mega { font-size: clamp(3rem, 14vw, 4.5rem); }
        .c-title-section { font-size: clamp(1.75rem, 8vw, 2.5rem); }
    }
</style>
@endpush

@section('content')
<div class="flex flex-col w-full bg-black text-white selection:bg-[#FF4D30] selection:text-black">

    <!-- 01 // SPLIT HERO SECTION -->
    <section class="c-hero">
        <!-- Left Column: Visual & Mission Architecture -->
        <div class="c-hero-left">
            <div class="c-hero-left-bg">
                <img src="{{ asset('hero.jpg') }}" alt="Challora Autonomous HR Platform">
            </div>
            <div class="c-hero-grid-overlay"></div>
            
            <!-- Top Metadata -->
            <div class="c-hero-left-content flex items-center justify-between border-b border-white/20 pb-4">
                <span class="c-mono text-[11px] font-bold text-white tracking-widest uppercase">// CHL-KERNEL-CORE</span>
                <span class="c-mono text-[11px] text-white/70">ARCH: NEURAL.ATS</span>
            </div>

            <!-- Lower Quote / Statement -->
            <div class="c-hero-left-content mt-auto pt-16">
                <div class="c-mono text-xs text-[#FF4D30] font-bold tracking-widest mb-3 uppercase">
                    [ OBJECTIVE : UNBIASED TALENT MATCHING ]
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold leading-tight tracking-tight text-white max-w-xl">
                    High-precision recruitment calibrated for skill depth, cultural velocity, and zero human bias.
                </h2>
                <div class="mt-6 flex items-center gap-4 text-xs c-mono text-gray-400">
                    <span class="inline-block w-2 h-2 bg-[#FF4D30]"></span>
                    <span>STANDARDIZED ATS PROTOCOL 2026</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Mega Title, Typewriter & Status Board -->
        <div class="c-hero-right">
            <div>
                <!-- Top Status Badge -->
                <div class="flex items-center justify-between mb-8 pb-3 border-b border-[#222]">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-[#FF4D30] animate-pulse"></span>
                        <span class="c-mono text-[11px] font-bold text-gray-300 uppercase tracking-widest">CHALLY SYSTEM : OPERATIONAL</span>
                    </div>
                    <span class="c-mono text-[11px] text-gray-500">PING: 14MS</span>
                </div>

                <!-- Interactive Typewriter Microcopy -->
                <div class="c-typewriter-box">
                    <span class="text-[#FF4D30] font-bold tracking-wider">// RUN &gt;</span>
                    <span id="hero-typewriter" class="text-white font-medium"></span>
                    <span class="c-cursor">█</span>
                </div>

                <!-- Mega Headline -->
                <h1 class="c-title-mega mb-8 text-white">
                    HR /<br>
                    <span class="text-[#FF4D30]">INTELLIGENCE</span>
                </h1>

                <p class="text-gray-400 text-base md:text-lg leading-relaxed max-w-lg mb-10 font-normal">
                    Transforming messy applicant noise into ranked, verified talent. Chally semantically evaluates candidate competencies in seconds without keyword games.
                </p>

                <!-- Sharp CTA Buttons (Zero Rounding) -->
                <div class="flex flex-wrap gap-4 items-center">
                    <a href="{{ route('jobs.index') }}" class="c-btn c-btn-accent">
                        <span>EXPLORE POSITIONS</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('register') }}" class="c-btn c-btn-outline">
                        <span>DEPLOY HIRING PIPELINE</span>
                    </a>
                </div>
            </div>

            <!-- System Status Matrix -->
            <div class="c-status-panel">
                <div class="flex items-center justify-between mb-3">
                    <span class="c-mono text-[11px] font-bold text-gray-400 uppercase tracking-widest">// SYSTEM MATRIX HEALTH</span>
                    <span class="c-mono text-[10px] text-emerald-400 font-bold bg-emerald-950/60 border border-emerald-800/80 px-2 py-0.5">ALL NODES NOMINAL</span>
                </div>
                <div class="c-mono c-status-grid">
                    @php $statuses = [
                        ['01', 'CV Parser', 'ONLINE'],       ['02', 'Match Engine', 'ACTIVE'],
                        ['03', 'Bias Filter', 'ENGAGED'],    ['04', 'Skill Extractor', 'ONLINE'],
                        ['05', 'Score Ranking', 'ONLINE'],   ['06', 'Profile Coach', 'STANDBY'],
                        ['07', 'Job Indexer', 'ONLINE'],     ['08', 'Candidate DB', 'SECURE'],
                    ]; @endphp
                    @foreach($statuses as $s)
                    <div class="c-status-item">
                        <span class="tracking-wider">{{ $s[0] }}. {{ $s[1] }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-500">{{ $s[2] }}</span>
                            <div class="c-status-led"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- 02 // REAL-TIME METRICS BAR -->
    <div class="c-metrics-strip">
        <div class="c-metric-cell">
            <span class="c-mono text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">// SCREENING SPEED</span>
            <div class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight">0.4 SEC</div>
            <p class="text-xs text-gray-400 mt-1">Average semantic CV evaluation latency</p>
        </div>
        <div class="c-metric-cell">
            <span class="c-mono text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">// MATCH ACCURACY</span>
            <div class="text-3xl lg:text-4xl font-extrabold text-[#FF4D30] tracking-tight">99.2%</div>
            <p class="text-xs text-gray-400 mt-1">Competency-to-role semantic alignment</p>
        </div>
        <div class="c-metric-cell">
            <span class="c-mono text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">// COGNITIVE BIAS</span>
            <div class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight">0.00%</div>
            <p class="text-xs text-gray-400 mt-1">Blind candidate evaluation algorithm</p>
        </div>
        <div class="c-metric-cell">
            <span class="c-mono text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">// PIPELINE AVAILABILITY</span>
            <div class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight">24/7</div>
            <p class="text-xs text-gray-400 mt-1">Autonomous candidate scoring &amp; triage</p>
        </div>
    </div>

    <!-- 03 // TICKER MARQUEE -->
    <div class="c-marquee">
        <span>
            // NO KEYWORD BIAS &nbsp;&nbsp;&nbsp;&nbsp; // SMARTER HIRING &nbsp;&nbsp;&nbsp;&nbsp; // CHALLY HR INTELLIGENCE &nbsp;&nbsp;&nbsp;&nbsp; // FIND THE RIGHT TALENT &nbsp;&nbsp;&nbsp;&nbsp; // NOT JUST RESUME BUZZWORDS &nbsp;&nbsp;&nbsp;&nbsp; // ZERO GUESSWORK &nbsp;&nbsp;&nbsp;&nbsp; // ARCHITECTED FOR MODERN TEAMS &nbsp;&nbsp;&nbsp;&nbsp;
            // NO KEYWORD BIAS &nbsp;&nbsp;&nbsp;&nbsp; // SMARTER HIRING &nbsp;&nbsp;&nbsp;&nbsp; // CHALLY HR INTELLIGENCE &nbsp;&nbsp;&nbsp;&nbsp; // FIND THE RIGHT TALENT &nbsp;&nbsp;&nbsp;&nbsp; // NOT JUST RESUME BUZZWORDS &nbsp;&nbsp;&nbsp;&nbsp; // ZERO GUESSWORK &nbsp;&nbsp;&nbsp;&nbsp; // ARCHITECTED FOR MODERN TEAMS &nbsp;&nbsp;&nbsp;&nbsp;
        </span>
    </div>

    <!-- 04 // SOLUTIONS GRID -->
    <section class="c-section">
        <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-[#262626] pb-6 gap-4">
            <div>
                <span class="c-mono text-xs text-[#FF4D30] font-bold tracking-widest uppercase mb-2 block">// CORE CAPABILITIES</span>
                <h2 class="c-title-section text-white">RECRUITMENT INTELLIGENCE</h2>
            </div>
            <p class="text-gray-400 max-w-md text-sm leading-relaxed">
                Engineered to replace brute-force resume scanning with multidimensional contextual intelligence.
            </p>
        </div>
        
        <div class="c-grid-solutions">
            <!-- Module 01 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 01</span>
                    <span class="c-tag-sharp">SEMANTIC</span>
                </div>
                <h3 class="c-title-card text-white mb-3 group-hover:text-[#FF4D30] transition-colors">AI CV Screening</h3>
                <p class="text-sm text-gray-400 leading-relaxed mb-8 flex-1">
                    Analyze hundreds of resumes concurrently. Chally maps contextual experience, validates skill authenticity, and ranks applicants with zero pedigree bias.
                </p>
                <div class="mt-auto pt-4 border-t border-[#1c1c1c]">
                    <a href="{{ route('register') }}" class="c-btn c-btn-ghost-accent w-full text-center">
                        DEPLOY MODULE &rarr;
                    </a>
                </div>
            </div>

            <!-- Module 02 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 02</span>
                    <span class="c-tag-sharp">NEURAL</span>
                </div>
                <h3 class="c-title-card text-white mb-3 group-hover:text-[#FF4D30] transition-colors">Deep Job Matching</h3>
                <p class="text-sm text-gray-400 leading-relaxed mb-8 flex-1">
                    Moves past literal keyword matching. Synthesizes candidate competency trajectories against role complexity, tech stack demands, and organizational pace.
                </p>
                <div class="mt-auto pt-4 border-t border-[#1c1c1c]">
                    <a href="{{ route('jobs.index') }}" class="c-btn c-btn-ghost-accent w-full text-center">
                        EXPLORE POSITIONS &rarr;
                    </a>
                </div>
            </div>

            <!-- Module 03 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 03</span>
                    <span class="c-tag-sharp">FEEDBACK</span>
                </div>
                <h3 class="c-title-card text-white mb-3 group-hover:text-[#FF4D30] transition-colors">Profile Coach</h3>
                <p class="text-sm text-gray-400 leading-relaxed mb-8 flex-1">
                    Delivers immediate, constructive diagnostic feedback for candidates. Pinpoints skill gaps, structural resume weaknesses, and portfolio recommendations.
                </p>
                <div class="mt-auto pt-4 border-t border-[#1c1c1c]">
                    <a href="{{ route('register') }}" class="c-btn c-btn-ghost-accent w-full text-center">
                        GET COACHED &rarr;
                    </a>
                </div>
            </div>

            <!-- Module 04 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 04</span>
                    <span class="c-tag-sharp">ANALYTICS</span>
                </div>
                <h3 class="c-title-card text-white mb-3 group-hover:text-[#FF4D30] transition-colors">Velocity Dashboard</h3>
                <p class="text-sm text-gray-400 leading-relaxed mb-8 flex-1">
                    Equip HR leads with real-time pipeline telemetry: candidate score matrices, stage bottlenecks, and hiring velocity analytics in one unified terminal.
                </p>
                <div class="mt-auto pt-4 border-t border-[#1c1c1c]">
                    <a href="{{ route('register') }}" class="c-btn c-btn-ghost-accent w-full text-center">
                        VIEW DASHBOARD &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 05 // OPEN POSITIONS (SHARP BRUTALIST GRID) -->
    <section class="c-section pt-0">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b-2 border-white/20 pb-4 mb-8 gap-4">
            <div>
                <span class="c-mono text-xs text-[#FF4D30] font-bold tracking-widest uppercase mb-1 block">// ACTIVE PIPELINE</span>
                <h2 class="c-title-section text-white">OPEN ROLES</h2>
            </div>
            <a href="{{ route('jobs.index') }}" class="c-btn c-btn-outline text-xs py-2 px-5">
                VIEW ALL POSITIONS &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-0 border-t border-l border-[#262626]">
            @forelse($latestJobs as $job)
            <div class="border-r border-b border-[#262626] p-8 relative group bg-[#080808] hover:bg-[#121212] transition-colors flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <span class="c-mono text-[10px] text-[#FF4D30] font-bold uppercase tracking-wider">
                        LOC // {{ strtoupper($job->location ?? 'REMOTE') }}
                    </span>
                    <span class="w-1.5 h-1.5 bg-[#FF4D30]"></span>
                </div>

                <h3 class="text-xl sm:text-2xl font-bold mb-4 leading-snug text-white group-hover:text-[#FF4D30] transition-colors">
                    {{ $job->title }}
                </h3>

                <div class="flex flex-wrap gap-1.5 mb-8">
                    @if(!empty($job->skills_json) && is_array($job->skills_json))
                        @foreach(array_slice($job->skills_json, 0, 3) as $skill)
                        <span class="border border-[#333] bg-[#000] text-gray-300 px-2.5 py-1 c-mono text-[9px] uppercase tracking-wider">
                            {{ $skill }}
                        </span>
                        @endforeach
                    @else
                        <span class="border border-[#333] bg-[#000] text-gray-400 px-2.5 py-1 c-mono text-[9px] uppercase tracking-wider">
                            FULL-STACK
                        </span>
                    @endif
                </div>

                <div class="mt-auto pt-4 border-t border-[#1a1a1a] flex items-center justify-between">
                    <span class="c-mono text-[10px] text-gray-500">ID: CHL-{{ str_pad($job->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <a href="{{ route('jobs.show', $job->id) }}" class="c-btn c-btn-ghost-accent py-2 px-4 text-[10px]">
                        APPLY POSITION &rarr;
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center border-r border-b border-[#262626] bg-[#080808]">
                <p class="c-mono text-gray-400 text-sm mb-4">// NO ACTIVE PUBLIC REQUISITIONS DETECTED</p>
                <a href="{{ route('register') }}" class="c-btn c-btn-accent text-xs">CREATE FIRST JOB REQUISITION</a>
            </div>
            @endforelse
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ── KINETIC TYPEWRITER ENGINE ──
    const typewriterElement = document.getElementById('hero-typewriter');
    if (typewriterElement) {
        const phrases = [
            "Parsing 1,200+ resumes concurrently with zero semantic hallucination...",
            "Filtering keyword stuffing, prompt injection, and pedigree bias...",
            "Ranking candidates by verified competencies and technical velocity...",
            "Autonomous applicant triage calibrated to your engineering stack..."
        ];

        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typingDelay = 42;

        function type() {
            const currentPhrase = phrases[phraseIndex];
            
            if (isDeleting) {
                typewriterElement.textContent = currentPhrase.substring(0, charIndex - 1);
                charIndex--;
                typingDelay = 22;
            } else {
                typewriterElement.textContent = currentPhrase.substring(0, charIndex + 1);
                charIndex++;
                typingDelay = 45;
            }

            if (!isDeleting && charIndex === currentPhrase.length) {
                typingDelay = 2400; // Pause at end of sentence
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                typingDelay = 450; // Pause before new phrase
            }

            setTimeout(type, typingDelay);
        }

        // Initialize typewriter after brief delay
        setTimeout(type, 600);
    }

    // ── GSAP SCROLL ANIMATIONS ──
    if (window.gsap && window.ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);

        gsap.from(".c-solution-card", {
            y: 30,
            opacity: 0,
            duration: 0.65,
            stagger: 0.08,
            ease: "power2.out",
            scrollTrigger: {
                trigger: ".c-grid-solutions",
                start: "top 82%"
            }
        });

        gsap.from(".c-metric-cell", {
            opacity: 0,
            y: 20,
            duration: 0.5,
            stagger: 0.08,
            ease: "power2.out",
            scrollTrigger: {
                trigger: ".c-metrics-strip",
                start: "top 88%"
            }
        });
    }
});
</script>
@endpush
