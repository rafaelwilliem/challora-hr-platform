@extends('layouts.app')

@push('styles')
    <style>
        /* Premium Brutalist - Z-Axis Depth */
        .brutalist-card {
            border: 4px solid black;
            box-shadow: 8px 8px 0px 0px black;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: var(--color-surface);
        }

        .brutalist-card:hover {
            transform: translate(-6px, -6px);
            box-shadow: 16px 16px 0px 0px var(--color-accent);
        }

        .brutalist-btn {
            border: 4px solid black;
            box-shadow: 6px 6px 0px 0px black;
            transition: all 0.1s ease;
        }

        .brutalist-btn:active {
            transform: translate(4px, 4px);
            box-shadow: 0px 0px 0px 0px black;
        }

        /* Terminal Styles */
        .terminal-header {
            background: #333;
            padding: 8px 16px;
            display: flex;
            gap: 8px;
            border-bottom: 2px solid black;
        }

        .terminal-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 1px solid black;
        }

        .scanline {
            width: 100%;
            height: 100px;
            z-index: 10;
            background: linear-gradient(0deg, rgba(0, 255, 65, 0) 0%, rgba(0, 255, 65, 0.1) 50%, rgba(0, 255, 65, 0) 100%);
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        /* Marquee Edge-to-Edge */
        .marquee-container {
            border-top: 4px solid black;
            border-bottom: 4px solid black;
            overflow: hidden;
            white-space: nowrap;
            background: var(--color-accent);
            color: var(--color-surface);
            padding: 1.5rem 0;
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
        }

        .marquee-content {
            display: inline-block;
            animation: marquee 30s linear infinite;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 2rem;
            letter-spacing: 0.1em;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .hero-title {
            font-size: clamp(3.5rem, 10vw, 8.5rem);
            line-height: 0.85;
            letter-spacing: -0.06em;
        }

        /* Radar Chart Fake Stylings */
        .radar-grid {
            stroke: #333;
            stroke-width: 1;
            fill: none;
        }

        .radar-axis {
            stroke: #444;
            stroke-width: 1;
        }

        .radar-area {
            fill: rgba(255, 69, 0, 0.4);
            stroke: var(--color-accent);
            stroke-width: 3;
        }

        .radar-point {
            fill: var(--color-accent);
            stroke: black;
            stroke-width: 2;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col gap-32 pb-32">

        <!-- HERO SECTION -->
        <section class="flex flex-col items-center text-center gap-10 mt-20" id="hero-section">
            <div class="space-y-4">
                <h1 class="font-black uppercase hero-title text-text tracking-tighter">
                    Recruiting <br>
                    <span
                        class="text-accent underline decoration-8 decoration-black hover:bg-black hover:text-white transition-colors px-4">Without</span>
                    <br>
                    The Guesswork.
                </h1>
            </div>
            <p class="text-xl md:text-2xl font-bold text-text-muted max-w-3xl leading-relaxed uppercase tracking-tight">
                Stop relying on gut feelings. Chally AI analyzes competence, reduces bias, and delivers the best talent
                directly to your desk.
            </p>

            <div class="flex flex-wrap justify-center gap-8 mt-10">
                <a href="{{ route('jobs.index') }}"
                    class="brutalist-btn bg-accent text-surface font-black uppercase tracking-widest px-10 py-5 text-xl hover:translate-x-[-4px] hover:translate-y-[-4px] hover:shadow-[10px_10px_0_0_black]">
                    Find Jobs
                </a>
                <a href="{{ route('register') }}"
                    class="brutalist-btn bg-surface text-text font-black uppercase tracking-widest px-10 py-5 text-xl hover:bg-secondary">
                    Post A Job
                </a>
            </div>
        </section>

        <!-- MARQUEE -->
        <div class="marquee-container" id="marquee-section">
            <div class="marquee-content">
                // NO BIAS // HIGH PRECISION // CHALLY AI // 90% FASTER SCREENING // POWERED BY DATA // // NO BIAS // HIGH
                PRECISION // CHALLY AI // 90% FASTER SCREENING // POWERED BY DATA //
            </div>
        </div>

        <!-- AI SCAN SIMULATOR (The Terminal) -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center" id="ai-section">
            <div class="space-y-8">
                <div
                    class="inline-block bg-accent px-4 py-1 font-black uppercase text-surface border-4 border-black transform -rotate-1">
                    AI Powered Core
                </div>
                <h2 class="text-6xl font-black uppercase leading-none text-text">Instant <br>Resume Intelligence</h2>
                <p class="text-xl text-text-muted font-bold max-w-lg">
                    Chally AI dissects thousands of CVs in seconds. We extract skill-sets semantically, detect relevant
                    experience, and eliminate data noise.
                </p>
                <ul class="space-y-4 font-mono text-accent">
                    <li class="flex items-center gap-3">
                        <span class="w-2 h-2 bg-accent"></span> AI Semantic Extraction (1.2s)
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-2 h-2 bg-accent"></span> Experience Validation Logic
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-2 h-2 bg-accent"></span> Diversity-First Ranking
                    </li>
                </ul>
            </div>

            <div class="terminal-window rounded-none relative overflow-hidden brutalist-shadow-deep" id="terminal-emulator">
                <div class="terminal-header">
                    <div class="terminal-dot bg-red-500"></div>
                    <div class="terminal-dot bg-yellow-500"></div>
                    <div class="terminal-dot bg-green-500"></div>
                    <span class="ml-4 font-mono text-xs opacity-50 uppercase">chally-scanner-v3.1.0</span>
                </div>
                <div class="p-8 font-mono text-sm space-y-2 relative h-[400px] overflow-hidden">
                    <div class="scanline" id="terminal-scanline"></div>
                    <div id="terminal-output" class="text-terminal-text">
                        <p class="terminal-accent text-lg mb-4">Awaiting signal...</p>
                        <!-- Teks akan diisi oleh JS -->
                    </div>
                </div>
            </div>
        </section>

        <!-- INTELLIGENCE RADAR (Matching) -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center" id="radar-section">
            <div class="order-2 lg:order-1 flex justify-center">
                <div
                    class="brutalist-card p-10 bg-secondary relative max-w-md w-full aspect-square flex items-center justify-center overflow-hidden">
                    <svg viewBox="0 0 200 200" class="w-full h-full transform -rotate-90">
                        <!-- Grid -->
                        <polygon points="100,20 180,60 180,140 100,180 20,140 20,60" class="radar-grid" />
                        <polygon points="100,40 160,70 160,130 100,160 40,130 40,70" class="radar-grid" opacity="0.5" />
                        <!-- Axis -->
                        <line x1="100" y1="100" x2="100" y2="20" class="radar-axis" />
                        <line x1="100" y1="100" x2="180" y2="60" class="radar-axis" />
                        <line x1="100" y1="100" x2="180" y2="140" class="radar-axis" />
                        <line x1="100" y1="100" x2="100" y2="180" class="radar-axis" />
                        <line x1="100" y1="100" x2="20" y2="140" class="radar-axis" />
                        <line x1="100" y1="100" x2="20" y2="60" class="radar-axis" />
                        <!-- Area -->
                        <polygon id="radar-shape" points="100,50 160,80 140,120 100,150 40,110 50,80" class="radar-area" />
                        <!-- Points -->
                        <circle cx="100" cy="50" r="4" class="radar-point" />
                        <circle cx="160" cy="80" r="4" class="radar-point" />
                        <circle cx="140" cy="120" r="4" class="radar-point" />
                        <circle cx="100" cy="150" r="4" class="radar-point" />
                        <circle cx="40" cy="110" r="4" class="radar-point" />
                        <circle cx="50" cy="80" r="4" class="radar-point" />
                    </svg>
                    <div
                        class="absolute inset-0 flex flex-col justify-between p-4 pointer-events-none font-black text-[10px] uppercase text-accent">
                        <div class="text-center">Technical</div>
                        <div class="flex justify-between w-full">
                            <div class="transform -rotate-45">Cultural</div>
                            <div class="transform rotate-45">Stability</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-1 lg:order-2 space-y-8">
                <h2 class="text-6xl font-black uppercase leading-none text-text">Intelligent <br>Matching Score</h2>
                <p class="text-xl text-text-muted font-bold">
                    More than just keyword matching. Chally AI maps candidate competencies against your specific team needs:
                    technical skills, cultural fit, and career stability.
                </p>
                <div class="brutalist-card bg-surface p-6 border-l-[12px] border-l-accent">
                    <div class="text-4xl font-black text-accent mb-2">92.4%</div>
                    <div class="font-bold text-sm uppercase tracking-widest text-text">Overall Match Confidence</div>
                </div>
            </div>
        </section>

        <!-- RECRUITMENT JOURNEY (Section 3) -->
        <section class="flex flex-col gap-16" id="journey-section">
            <div class="text-center space-y-4">
                <h2 class="text-5xl font-black uppercase tracking-tighter">Your Path to <span
                        class="text-accent underline">Hired.</span></h2>
                <p class="text-text-muted font-bold text-lg">Transparent process, no drama, fully powered by AI.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 relative">
                <!-- Journey Step 1 -->
                <div class="brutalist-card p-8 flex flex-col gap-4 relative z-10 journey-step">
                    <div class="text-6xl font-black text-accent opacity-20 absolute top-4 right-4">01</div>
                    <h3 class="text-2xl font-black uppercase text-text">Upload CV</h3>
                    <p class="text-text-muted font-medium">Upload your resume in PDF format. Let Chally do the heavy
                        lifting.</p>
                </div>
                <!-- Journey Step 2 -->
                <div class="brutalist-card p-8 flex flex-col gap-4 relative z-10 mt-8 md:mt-16 journey-step">
                    <div class="text-6xl font-black text-accent opacity-20 absolute top-4 right-4">02</div>
                    <h3 class="text-2xl font-black uppercase text-text">AI Analysis</h3>
                    <p class="text-text-muted font-medium">The system objectively dissects your competencies within seconds.
                    </p>
                </div>
                <!-- Journey Step 3 -->
                <div class="brutalist-card p-8 flex flex-col gap-4 relative z-10 journey-step">
                    <div class="text-6xl font-black text-accent opacity-20 absolute top-4 right-4">03</div>
                    <h3 class="text-2xl font-black uppercase text-text">Get Matched</h3>
                    <p class="text-text-muted font-medium">Find jobs that truly fit your profile through intelligent
                        scoring.</p>
                </div>
                <!-- Journey Step 4 -->
                <div
                    class="brutalist-card p-8 bg-accent text-surface flex flex-col gap-4 relative z-10 mt-8 md:mt-16 journey-step">
                    <div class="text-6xl font-black text-surface opacity-30 absolute top-4 right-4">04</div>
                    <h3 class="text-2xl font-black uppercase">Direct Hire</h3>
                    <p class="text-surface font-bold opacity-80">Bypass bureaucracy and connect directly with the hiring
                        team.</p>
                </div>
            </div>
        </section>

        <!-- AI BRANDING COACH (Section 4) -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center" id="coach-section">
            <div class="brutalist-card bg-surface p-10 relative overflow-hidden group">
                <div
                    class="absolute -top-10 -right-10 w-40 h-40 bg-accent opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="font-mono text-sm text-accent mb-6 uppercase tracking-widest">// CHALLY COACH ADVICE //</div>

                <div class="space-y-6">
                    <div class="bg-secondary p-4 border-2 border-black shadow-[4px_4px_0_0_black]">
                        <p class="font-bold text-text mb-2">"My advice for your Resume:"</p>
                        <p class="text-sm text-text-muted">"Add specific projects using <strong>Laravel 10</strong> to boost
                            your compatibility score in the Fintech industry by up to <strong>+18%</strong>."</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1 bg-black p-4 text-xs font-mono text-white">
                            <span class="text-green-400">Current Score:</span> 68/100
                        </div>
                        <div class="flex-1 bg-accent p-4 text-xs font-mono text-surface font-bold">
                            <span>Potential Score:</span> 86/100
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <h2 class="text-6xl font-black uppercase leading-none text-text">Your Personal <br><span
                        class="text-accent">Branding Coach</span></h2>
                <p class="text-xl text-text-muted font-bold">
                    Chally doesn't just evaluate, it guides. Get instant advice to enhance your profile and get noticed by
                    top recruiters.
                </p>
                <a href="{{ route('register') }}"
                    class="brutalist-btn bg-surface text-text font-black uppercase tracking-widest px-8 py-4 inline-block hover:bg-black hover:text-white transition-colors">
                    Try Coach Mode
                </a>
            </div>
        </section>

        <!-- FEATURED JOBS (Section 5) -->
        <section class="flex flex-col gap-16" id="jobs-preview-section">
            <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                <div class="space-y-3">
                    <h2 class="text-5xl font-black uppercase tracking-tighter">New <span
                            class="text-accent">Opportunities</span></h2>
                    <p class="text-text-muted font-bold">Don't miss the golden opportunities that hit the radar today.</p>
                </div>
                <a href="{{ route('jobs.index') }}"
                    class="font-black uppercase text-accent hover:underline decoration-4 flex items-center gap-2">
                    Explore All Jobs
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3">
                        </path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($latestJobs as $job)
                    <div class="brutalist-card p-6 flex flex-col gap-6 group">
                        <div class="flex justify-between items-start">
                            <div
                                class="w-12 h-12 bg-secondary border-2 border-black flex items-center justify-center font-black uppercase">
                                {{ strtoupper(substr($job->creator->name ?? 'TC', 0, 2)) }}
                            </div>
                            @php
                                $badgeColors = [
                                    'full_time' => 'bg-success-bg text-success-text',
                                    'remote' => 'bg-sky-bg text-sky-text',
                                    'contract' => 'bg-warning-soft text-warning-text',
                                    'freelance' => 'bg-accent text-surface',
                                    'internship' => 'bg-info-soft text-info-text',
                                ];
                                $type = $job->job_type->value ?? 'full_time';
                                $color = $badgeColors[$type] ?? 'bg-secondary text-text';
                            @endphp
                            <span class="{{ $color }} px-3 py-1 font-black text-[10px] uppercase border-2 border-black">
                                {{ str_replace('_', '-', $type) }}
                            </span>
                        </div>
                        <div>
                            <h3
                                class="text-xl font-black uppercase text-text group-hover:text-accent transition-colors leading-tight">
                                {{ $job->title }}
                            </h3>
                            <p class="text-text-muted font-bold text-sm">{{ $job->creator->name ?? 'Anonymous' }} ·
                                {{ $job->location }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-auto">
                            @if($job->skills_json)
                                @foreach(array_slice($job->skills_json, 0, 3) as $skill)
                                    <span class="text-[10px] font-mono border border-black px-2 py-0.5">{{ strtoupper($skill) }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-2xl font-black uppercase text-text-muted opacity-50">No opportunities available right
                            now.</p>
                    </div>
                @endforelse
            </div>
        </section>


        <!-- FINAL CTA -->
        <section
            class="brutalist-card bg-primary p-16 flex flex-col md:flex-row items-center justify-between gap-8 border-[6px]">
            <div class="max-w-xl">
                <h2 class="text-6xl font-black uppercase text-white mb-4 leading-none">Ready to <span
                        class="text-accent">win</span> <br>the talent war?</h2>
                <p class="text-gray-400 font-bold text-lg">Join the recruitment ecosystem of the future, powered by
                    artificial intelligence.</p>
            </div>
            <div class="flex flex-col gap-4 w-full md:w-auto">
                <a href="{{ route('register') }}"
                    class="brutalist-btn bg-accent text-surface text-center font-black uppercase tracking-widest px-12 py-5 text-xl hover:translate-x-[-4px] hover:translate-y-[-4px] hover:shadow-[10px_10px_0_0_white]">
                    Join Challora
                </a>
                <a href="{{ route('login') }}"
                    class="text-center font-black text-white hover:text-accent transition-colors">Aready have an account?
                    Sign In</a>
            </div>
        </section>


    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            // Hero Entrance
            const heroTl = gsap.timeline();
            heroTl.from(".hero-title", { y: 100, opacity: 0, duration: 1.2, ease: "power4.out" })
                .from(".hero-title span", { x: -50, opacity: 0, duration: 0.8 }, "-=0.8")
                .from("#hero-section p, #hero-section .brutalist-btn", { y: 30, opacity: 0, stagger: 0.2 }, "-=0.5");

            // Mouse Parallax for Hero
            document.addEventListener('mousemove', (e) => {
                const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
                const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
                gsap.to(".hero-title", { x: moveX, y: moveY, duration: 0.5 });
            });

            // AI Terminal Typing Effect
            const terminalOutput = document.getElementById('terminal-output');
            const lines = [
                "> Initializing CV Parser v3.1...",
                "> Loading PDF: Resume_Candidate_77.pdf",
                "> Extracting semantic markers...",
                "> Found: [Laravel, Docker, Redis, Vue.js]",
                "> Analyzing work history: 4.5 years",
                "> Sentiment Analysis: Positive",
                "> Match Confidence: 92.4%",
                "> [COMPLETED] Profile added to Shortlist."
            ];

            let lineIdx = 0;
            function typeLine() {
                if (lineIdx < lines.length) {
                    const p = document.createElement('p');
                    p.className = 'mb-1 ' + (lineIdx === lines.length - 1 ? 'terminal-accent font-bold' : '');
                    terminalOutput.appendChild(p);

                    let charIdx = 0;
                    const line = lines[lineIdx];
                    const typeInterval = setInterval(() => {
                        p.textContent += line[charIdx];
                        charIdx++;
                        if (charIdx === line.length) {
                            clearInterval(typeInterval);
                            lineIdx++;
                            setTimeout(typeLine, 400);
                        }
                    }, 30);
                }
            }

            ScrollTrigger.create({
                trigger: "#ai-section",
                start: "top 60%",
                onEnter: () => {
                    typeLine();
                    gsap.to("#terminal-scanline", { y: 400, repeat: -1, duration: 3, ease: "none" });
                }
            });

            // Radar Chart Animation
            gsap.from("#radar-shape", {
                scale: 0,
                transformOrigin: "center center",
                duration: 1.5,
                ease: "elastic.out(1, 0.5)",
                scrollTrigger: {
                    trigger: "#radar-section",
                    start: "top 60%"
                }
            });

            // Journey Steps Entrance
            gsap.from(".journey-step", {
                y: 50,
                opacity: 0,
                stagger: 0.2,
                duration: 1,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#journey-section",
                    start: "top 70%"
                }
            });

            // Coach Section Entrance
            gsap.from("#coach-section > div", {
                x: (i) => i === 0 ? -100 : 100,
                opacity: 0,
                duration: 1.2,
                ease: "power4.out",
                scrollTrigger: {
                    trigger: "#coach-section",
                    start: "top 70%"
                }
            });

            // Jobs Preview Cards Entrance
            gsap.from("#jobs-preview-section .brutalist-card", {
                scale: 0.8,
                opacity: 0,
                stagger: 0.1,
                duration: 0.8,
                ease: "back.out(1.7)",
                scrollTrigger: {
                    trigger: "#jobs-preview-section",
                    start: "top 75%"
                }
            });
        });
    </script>
@endpush