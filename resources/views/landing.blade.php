@extends('layouts.app')

@push('styles')
<style>
    /* ── RYOKU-INSPIRED REFINED BRUTALIST TOKENS ── */
    :root {
        --c-accent: #FF4D30;
        --c-accent-hover: #e03e00;
        --c-black: #070707;
        --c-dark-surface: #0e0e0e;
        --c-border: #1f1f1f;
        --c-border-light: #2d2d2d;
        --c-border-accent: rgba(255, 77, 48, 0.4);
        --c-white: #FFFFFF;
        --c-cream: #E5E5E0;
        --c-gray-light: #F4F4F4;
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
        color: #8f8f8f;
        font-size: clamp(1.1rem, 2vw, 1.4rem);
        line-height: 1.4;
    }

    .c-body-text {
        font-family: var(--font-body) !important;
        color: #b5b5b5;
        font-size: 1.05rem;
        line-height: 1.65;
    }

    /* ── 3. Mini Text & Decoration: Monospace ── */
    .c-mono {
        font-family: var(--font-mono) !important;
        letter-spacing: 0.06em;
    }

    /* ── Ryoku-Style Zero-Radius Hero Layout ── */
    .c-hero-container {
        min-height: calc(100vh - var(--nav-height));
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        background-color: var(--c-black);
        border-bottom: 1px solid var(--c-border);
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        padding-top: var(--nav-height);
        overflow: hidden;
    }

    .c-hero-content {
        padding: 4rem 4rem 4rem 6rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        z-index: 10;
        position: relative;
    }

    /* Top Platform Pills (Ryoku distro-style) */
    .c-platform-selector {
        display: inline-flex;
        align-items: center;
        gap: 1.5rem;
        padding: 0.35rem 0.85rem;
        background: #0b0b0b;
        border: 1px solid #202020;
        margin-bottom: 2.25rem;
        width: fit-content;
    }

    /* Release Kicker */
    .c-release-tag {
        font-family: var(--font-mono) !important;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #888888;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    /* Ryoku Technical Architecture Specs Table */
    .c-specs-table {
        margin-top: 2rem;
        border-top: 1px solid #1a1a1a;
        width: 100%;
        max-width: 620px;
    }

    .c-spec-row {
        display: grid;
        grid-template-columns: 150px 1fr;
        align-items: baseline;
        padding: 0.85rem 0;
        border-bottom: 1px solid #141414;
        gap: 1.5rem;
    }

    .c-spec-key {
        font-family: var(--font-mono) !important;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #666666;
    }

    .c-spec-val {
        color: #e0e0e0;
        font-size: 0.95rem;
    }

    /* Ryoku 4-Box Telemetry Matrix */
    .c-stat-matrix {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border: 1px solid #222222;
        background: #0a0a0a;
        width: 100%;
        max-width: 580px;
        margin-top: 2.5rem;
    }

    .c-stat-cell {
        padding: 1rem 1.25rem;
        border-right: 1px solid #1c1c1c;
        display: flex;
        flex-direction: column;
    }
    .c-stat-cell:last-child {
        border-right: none;
    }

    .c-stat-number {
        font-family: var(--font-display) !important;
        font-size: 1.75rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.02em;
        line-height: 1;
        margin-bottom: 0.4rem;
    }

    .c-stat-label {
        font-family: var(--font-mono) !important;
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #666666;
    }

    /* Ryoku-Style Zero-Radius Action Buttons */
    .c-btn-install {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.9rem 2.25rem;
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
        padding: 0.9rem 2.25rem;
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

    /* Right Visual & Radial Wireframes */
    .c-hero-visual {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-left: 1px solid var(--c-border);
        background: radial-gradient(circle at 60% 50%, #151515 0%, #070707 70%);
    }

    .c-hero-visual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        filter: grayscale(100%) contrast(1.35) brightness(0.65);
        mix-blend-mode: luminosity;
        position: relative;
        z-index: 2;
    }

    .c-orbital-grid {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
        opacity: 0.28;
    }

    /* Marquee Ribbon */
    .c-marquee {
        background-color: #0c0c0c;
        color: #888888;
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
        animation: c-scroll 35s linear infinite;
    }
    @keyframes c-scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* Sections */
    .c-section {
        padding: 6.5rem 4rem;
        max-width: 1600px;
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
    @media (max-width: 1100px) {
        .c-hero-container {
            grid-template-columns: 1fr;
        }
        .c-hero-content {
            padding: 3rem 2rem;
        }
        .c-hero-visual {
            min-height: 400px;
            border-left: none;
            border-top: 1px solid var(--c-border);
        }
        .c-grid-solutions {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .c-hero-content {
            padding: 2rem 1.25rem;
        }
        .c-stat-matrix {
            grid-template-columns: 1fr 1fr;
        }
        .c-stat-cell:nth-child(2) {
            border-right: none;
        }
        .c-stat-cell:nth-child(1), .c-stat-cell:nth-child(2) {
            border-bottom: 1px solid #1c1c1c;
        }
        .c-spec-row {
            grid-template-columns: 1fr;
            gap: 0.35rem;
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
<div class="flex flex-col w-full bg-[#070707] text-[#E5E5E0] selection:bg-[#FF4D30] selection:text-white">

    <!-- 01 // RYOKU-INSPIRED HERO CONTAINER -->
    <section class="c-hero-container">
        
        <!-- Left Hero Content: Big Title (Sans), Motto/Body (Serif), Meta (Mono) -->
        <div class="c-hero-content">

            <!-- Top Environment Selector (Ryoku distro box style) -->
            <div class="c-platform-selector">
                <div class="flex items-center gap-3">
                    <span class="c-mono text-[10px] text-[#666] font-bold tracking-wider uppercase">DEPLOYMENT</span>
                    <span class="c-mono text-[11px] text-gray-300 flex items-center gap-1.5 font-medium">
                        <span class="w-1.5 h-1.5 bg-[#FF4D30]"></span> Cloud Tier
                    </span>
                    <span class="c-mono text-[11px] text-gray-400">On-Prem VPC</span>
                </div>
                <div class="w-px h-3 bg-[#262626]"></div>
                <div class="flex items-center gap-3">
                    <span class="c-mono text-[10px] text-[#666] font-bold tracking-wider uppercase">INTELLIGENCE</span>
                    <span class="c-mono text-[11px] text-gray-300">Neural ATS v2.4</span>
                    <span class="c-mono text-[11px] text-emerald-400">SOC-2</span>
                </div>
            </div>

            <!-- Version Release Kicker -->
            <div class="c-release-tag">
                <span class="text-[#FF4D30] font-bold">✦</span>
                <span>CHALLORA 5.1 · NEURAL RECRUITMENT · RELEASED</span>
            </div>

            <!-- BIG TITLE: Bold Sans-Serif -->
            <h1 class="c-title-display text-white text-5xl sm:text-6xl md:text-7xl lg:text-8xl mb-3">
                Challora
            </h1>

            <!-- MOTTO / SUBTITLE: Elegant Serif in Italics -->
            <p class="c-motto mb-6">
                人材と可能性のために &nbsp; <span class="text-gray-300">for the sake of talent, precision, and unbiased potential</span>
            </p>

            <!-- Interactive Kinetic Terminal Typewriter Box -->
            <div class="flex items-center gap-2 bg-[#0c0c0c] border border-[#1f1f1f] border-l-2 border-l-[#FF4D30] px-4 py-2.5 max-w-[620px] mb-4">
                <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-wider">// CHALLY &gt;</span>
                <span id="hero-typewriter" class="c-mono text-[12px] text-gray-300 font-medium"></span>
                <span class="c-mono text-[#FF4D30] font-bold animate-pulse">█</span>
            </div>

            <!-- RYOKU ARCHITECTURE SPECS TABLE -->
            <div class="c-specs-table">
                <div class="c-spec-row">
                    <div class="c-spec-key">CV PARSER</div>
                    <div class="c-spec-val">
                        <div class="font-sans font-semibold text-white text-[15px] flex items-center gap-2">
                            Semantic AST <span class="text-[#FF4D30]">⇄</span> Deep Extraction
                        </div>
                        <p class="c-serif italic text-xs text-gray-400 mt-0.5">
                            Multilingual parsing across 40+ resume formats in 0.4s. Resists keyword gaming.
                        </p>
                    </div>
                </div>

                <div class="c-spec-row">
                    <div class="c-spec-key">BIAS CONTROL</div>
                    <div class="c-spec-val">
                        <div class="font-sans font-semibold text-white text-[15px]">
                            Blind Competency Triage
                        </div>
                        <p class="c-serif italic text-xs text-gray-400 mt-0.5">
                            Filters demographic cues, institution pedigree, and unconscious scoring biases.
                        </p>
                    </div>
                </div>

                <div class="c-spec-row">
                    <div class="c-spec-key">MATCH PIPELINE</div>
                    <div class="c-spec-val">
                        <div class="font-sans font-semibold text-white text-[15px]">
                            Neural Trajectory &amp; Culture Velocity
                        </div>
                    </div>
                </div>

                <div class="c-spec-row">
                    <div class="c-spec-key">INTELLIGENCE</div>
                    <div class="c-spec-val">
                        <div class="font-sans font-semibold text-white text-[15px]">
                            Chally Autonomous ATS Agent
                        </div>
                    </div>
                </div>
            </div>

            <!-- RYOKU 4-BOX TELEMETRY MATRIX -->
            <div class="c-stat-matrix">
                <div class="c-stat-cell">
                    <div class="c-stat-number">0.4<span class="text-xs text-[#FF4D30]">s</span></div>
                    <div class="c-stat-label">CV LATENCY</div>
                </div>
                <div class="c-stat-cell">
                    <div class="c-stat-number">99.2<span class="text-xs text-[#FF4D30]">%</span></div>
                    <div class="c-stat-label">MATCH RATE</div>
                </div>
                <div class="c-stat-cell">
                    <div class="c-stat-number">0.0<span class="text-xs text-[#FF4D30]">%</span></div>
                    <div class="c-stat-label">BIAS DRIFT</div>
                </div>
                <div class="c-stat-cell">
                    <div class="c-stat-number">24/7</div>
                    <div class="c-stat-label">AUTONOMOUS</div>
                </div>
            </div>

            <!-- ZERO-RADIUS ACTION BUTTONS -->
            <div class="flex flex-wrap items-center gap-4 mt-8">
                <a href="{{ route('jobs.index') }}" class="c-btn-install">
                    EXPLORE POSITIONS
                </a>
                <a href="{{ route('register') }}" class="c-btn-source">
                    DEPLOY PIPELINE
                </a>
            </div>

            <!-- METADATA FOOTNOTE (Ryoku Star/Fork Style) -->
            <div class="c-mono text-[11px] text-[#666] flex items-center gap-3 mt-6">
                <span class="text-[#FF4D30]">★</span>
                <span>2,480 applicants scored</span>
                <span class="text-[#333]">/</span>
                <span>120 hiring teams</span>
                <span class="text-[#333]">/</span>
                <span>SOC-2 COMPLIANT</span>
            </div>

        </div>

        <!-- Right Hero Visual: Dithered/Monochrome Artwork + Orbital Wireframe Grid -->
        <div class="c-hero-visual">
            <!-- Orbital Geometry Overlay (Concentric circles like Ryoku) -->
            <svg class="c-orbital-grid" viewBox="0 0 800 800" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="400" cy="400" r="380" stroke="#FFFFFF" stroke-width="1" stroke-dasharray="2 6"/>
                <circle cx="400" cy="400" r="280" stroke="#FFFFFF" stroke-width="1"/>
                <circle cx="400" cy="400" r="180" stroke="#FF4D30" stroke-width="1" stroke-dasharray="4 8"/>
                <circle cx="400" cy="400" r="80" stroke="#FFFFFF" stroke-width="1"/>
                <line x1="0" y1="400" x2="800" y2="400" stroke="#FFFFFF" stroke-width="0.5" stroke-dasharray="3 9"/>
                <line x1="400" y1="0" x2="400" y2="800" stroke="#FFFFFF" stroke-width="0.5" stroke-dasharray="3 9"/>
                <!-- Subtle Crosshairs -->
                <path d="M390 400 H410 M400 390 V410" stroke="#FF4D30" stroke-width="1.5"/>
            </svg>

            <!-- Hero Image with High Contrast Editorial Treatment -->
            <img src="{{ asset('hero.jpg') }}" alt="Challora Classical Visual">
            
            <!-- Vignette Gradients -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#070707] via-transparent to-[#070707]/60 z-[3]"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#070707] via-transparent to-transparent z-[3]"></div>
        </div>

    </section>

    <!-- 02 // TICKER MARQUEE -->
    <div class="c-marquee">
        <span>
            ✦ NO KEYWORD BIAS &nbsp;&nbsp;&nbsp;&nbsp; // SMARTER HIRING &nbsp;&nbsp;&nbsp;&nbsp; // CHALLY HR INTELLIGENCE &nbsp;&nbsp;&nbsp;&nbsp; // FIND THE RIGHT TALENT &nbsp;&nbsp;&nbsp;&nbsp; // ZERO RESUME NOISE &nbsp;&nbsp;&nbsp;&nbsp; // ARCHITECTED FOR MODERN TEAMS &nbsp;&nbsp;&nbsp;&nbsp;
            ✦ NO KEYWORD BIAS &nbsp;&nbsp;&nbsp;&nbsp; // SMARTER HIRING &nbsp;&nbsp;&nbsp;&nbsp; // CHALLY HR INTELLIGENCE &nbsp;&nbsp;&nbsp;&nbsp; // FIND THE RIGHT TALENT &nbsp;&nbsp;&nbsp;&nbsp; // ZERO RESUME NOISE &nbsp;&nbsp;&nbsp;&nbsp; // ARCHITECTED FOR MODERN TEAMS &nbsp;&nbsp;&nbsp;&nbsp;
        </span>
    </div>

    <!-- 03 // SOLUTIONS SECTION -->
    <section class="c-section">
        <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-[#202020] pb-6 gap-4">
            <div>
                <span class="c-mono text-xs text-[#FF4D30] font-bold tracking-widest uppercase mb-2 block">// CAPABILITY MATRIX</span>
                <h2 class="c-title-section text-white text-3xl sm:text-4xl md:text-5xl">RECRUITMENT INTELLIGENCE</h2>
            </div>
            <p class="c-body-text max-w-md">
                Engineered to replace brute-force resume scanning with multidimensional contextual intelligence and human-calibrated evaluation.
            </p>
        </div>
        
        <div class="c-grid-solutions">
            <!-- Module 01 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 01</span>
                    <span class="c-mono text-[10px] text-gray-400 border border-[#2a2a2a] px-2 py-0.5 uppercase tracking-wider">SEMANTIC</span>
                </div>
                <h3 class="c-title-card text-white text-xl mb-3 group-hover:text-[#FF4D30] transition-colors">AI CV Screening</h3>
                <p class="c-body-text text-sm mb-8 flex-1">
                    Analyze hundreds of resumes concurrently. Chally maps contextual experience, validates skill authenticity, and ranks applicants with zero pedigree bias.
                </p>
                <div class="mt-auto pt-4 border-t border-[#181818]">
                    <a href="{{ route('register') }}" class="c-mono text-xs text-[#FF4D30] font-bold hover:underline flex items-center justify-between">
                        <span>DEPLOY MODULE</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Module 02 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 02</span>
                    <span class="c-mono text-[10px] text-gray-400 border border-[#2a2a2a] px-2 py-0.5 uppercase tracking-wider">NEURAL</span>
                </div>
                <h3 class="c-title-card text-white text-xl mb-3 group-hover:text-[#FF4D30] transition-colors">Deep Job Matching</h3>
                <p class="c-body-text text-sm mb-8 flex-1">
                    Moves past literal keyword matching. Synthesizes candidate competency trajectories against role complexity, tech stack demands, and organizational pace.
                </p>
                <div class="mt-auto pt-4 border-t border-[#181818]">
                    <a href="{{ route('jobs.index') }}" class="c-mono text-xs text-[#FF4D30] font-bold hover:underline flex items-center justify-between">
                        <span>EXPLORE POSITIONS</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Module 03 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 03</span>
                    <span class="c-mono text-[10px] text-gray-400 border border-[#2a2a2a] px-2 py-0.5 uppercase tracking-wider">FEEDBACK</span>
                </div>
                <h3 class="c-title-card text-white text-xl mb-3 group-hover:text-[#FF4D30] transition-colors">Profile Coach</h3>
                <p class="c-body-text text-sm mb-8 flex-1">
                    Delivers immediate, constructive diagnostic feedback for candidates. Pinpoints skill gaps, structural resume weaknesses, and portfolio recommendations.
                </p>
                <div class="mt-auto pt-4 border-t border-[#181818]">
                    <a href="{{ route('register') }}" class="c-mono text-xs text-[#FF4D30] font-bold hover:underline flex items-center justify-between">
                        <span>GET COACHED</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Module 04 -->
            <div class="c-solution-card group">
                <div class="flex items-center justify-between mb-6">
                    <span class="c-mono text-xs font-bold text-[#FF4D30]">// 04</span>
                    <span class="c-mono text-[10px] text-gray-400 border border-[#2a2a2a] px-2 py-0.5 uppercase tracking-wider">TELEMETRY</span>
                </div>
                <h3 class="c-title-card text-white text-xl mb-3 group-hover:text-[#FF4D30] transition-colors">Velocity Dashboard</h3>
                <p class="c-body-text text-sm mb-8 flex-1">
                    Equip HR leads with real-time pipeline telemetry: candidate score matrices, stage bottlenecks, and hiring velocity analytics in one unified terminal.
                </p>
                <div class="mt-auto pt-4 border-t border-[#181818]">
                    <a href="{{ route('register') }}" class="c-mono text-xs text-[#FF4D30] font-bold hover:underline flex items-center justify-between">
                        <span>VIEW DASHBOARD</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 04 // OPEN POSITIONS (SHARP BRUTALIST GRID) -->
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
                            FULL-STACK
                        </span>
                    @endif
                </div>

                <div class="mt-auto pt-4 border-t border-[#181818] flex items-center justify-between">
                    <span class="c-mono text-[10px] text-gray-500">ID: CHL-{{ str_pad($job->id, 4, '0', STR_PAD_LEFT) }}</span>
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
                typingDelay = 20;
            } else {
                typewriterElement.textContent = currentPhrase.substring(0, charIndex + 1);
                charIndex++;
                typingDelay = 45;
            }

            if (!isDeleting && charIndex === currentPhrase.length) {
                typingDelay = 2200;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                typingDelay = 400;
            }

            setTimeout(type, typingDelay);
        }

        setTimeout(type, 500);
    }
});
</script>
@endpush
