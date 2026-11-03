@extends('layouts.app')

@push('styles')
<style>
    .candidate-show-container {
        padding: 60px 0;
    }
    .hero-glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 4px solid black;
        box-shadow: 12px 12px 0 black;
        padding: 60px;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
    }
    .hero-glass::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 8px;
        background: var(--color-accent);
    }
    .candidate-name-giant {
        font-family: var(--font-display);
        font-size: 80px;
        font-weight: 800;
        letter-spacing: -4px;
        line-height: 0.9;
        margin-bottom: 24px;
        text-transform: uppercase;
    }
    .meta-tags-flex {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 32px;
    }
    .tag-brutalist {
        font-family: var(--font-mono);
        background: black;
        color: white;
        padding: 8px 16px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        border: 2px solid white;
    }
    .profile-grid-premium {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 60px;
    }
    .section-premium {
        background: var(--color-surface);
        border: 4px solid black;
        box-shadow: 8px 8px 0 black;
        padding: 48px;
        margin-bottom: 48px;
    }
    .section-label-giant {
        font-family: var(--font-display);
        font-size: 40px;
        font-weight: 800;
        letter-spacing: -1px;
        margin-bottom: 32px;
        border-bottom: 8px solid var(--color-accent);
        display: inline-block;
        text-transform: uppercase;
    }
    .biodata-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }
    .info-item label {
        font-family: var(--font-mono);
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--color-text-muted);
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }
    .info-item span {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-text);
    }
    .ai-score-card {
        background: black;
        color: white;
        padding: 40px;
        text-align: center;
        border: 4px solid var(--color-accent);
        margin-bottom: 32px;
    }
    .ai-score-number {
        font-family: var(--font-display);
        font-size: 80px;
        font-weight: 900;
        color: var(--color-accent);
        line-height: 1;
    }
    .ai-label {
        font-family: var(--font-mono);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 12px;
        margin-top: 8px;
    }
    .pro-con-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-top: 32px;
    }
    .pro-box { border-left: 8px solid #4ade80; padding-left: 20px; }
    .con-box { border-left: 8px solid #f87171; padding-left: 20px; }
    .box-title { font-family: var(--font-mono); font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; font-size: 12px; margin-bottom: 12px; }
    .box-list { font-family: var(--font-body); font-size: 15px; color: var(--color-text-muted); }
    
    .timeline-item {
        border-left: 4px solid black;
        padding-left: 24px;
        padding-bottom: 32px;
        position: relative;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -12px; top: 0;
        width: 20px; height: 20px;
        background: var(--color-accent);
        border: 4px solid black;
    }
    .timeline-date { font-family: var(--font-mono); font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-accent); margin-bottom: 4px; }
    .timeline-title { font-family: var(--font-display); font-size: 22px; font-weight: 800; margin-bottom: 8px; }
    .timeline-subtitle { font-family: var(--font-body); font-style: italic; font-weight: 600; color: var(--color-text-muted); margin-bottom: 12px; }
</style>
@endpush

@section('content')
<div class="candidate-show-container">
    <a href="{{ route('hr.applications.index') }}" class="inline-flex items-center gap-2 font-black uppercase text-xs tracking-widest mb-8 hover:text-accent transition-colors">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Pipeline
    </a>

    <div class="hero-glass gsap-hero">
        <h1 class="candidate-name-giant">{{ $data['candidate']['name'] }}</h1>
        <p class="text-2xl font-bold text-text-muted">Candidate for: <span class="text-accent">{{ $data['job']['title'] }}</span></p>
        
        <div class="meta-tags-flex">
            <span class="tag-brutalist">{{ $data['candidate']['email'] }}</span>
            <span class="tag-brutalist">{{ $data['candidate']['phone'] ?: 'No Phone' }}</span>
            <span class="tag-brutalist">{{ $data['candidate']['gender'] ?: 'Unspecified' }}</span>
            <span class="tag-brutalist">{{ $data['candidate']['address'] }}</span>
        </div>
    </div>

    <div class="profile-grid-premium">
        <div class="main-details">
            <!-- Biodata Section -->
            <div class="section-premium gsap-section">
                <h2 class="section-label-giant">Full Biodata</h2>
                <div class="biodata-grid">
                    <div class="info-item">
                        <label>Birth Info</label>
                        <span>{{ $data['candidate']['birth_place'] ?: '-' }}, {{ $data['candidate']['birth_date'] ?: '-' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Marital Status</label>
                        <span>{{ $data['candidate']['marital_status'] ?: '-' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Religion</label>
                        <span>{{ $data['candidate']['religion'] ?: '-' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Education</label>
                        <span>{{ $data['candidate']['education_level'] }} - {{ $data['candidate']['education_university'] }} (Graduated: {{ $data['candidate']['graduation_year'] }})</span>
                    </div>
                    <div class="info-item">
                        <label>Major</label>
                        <span>{{ $data['candidate']['education_major'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Experience Section -->
            <div class="section-premium gsap-section">
                <h2 class="section-label-giant">Career History</h2>
                @forelse($data['candidate']['experiences'] ?? [] as $exp)
                    <div class="timeline-item">
                        <div class="timeline-date">{{ $exp->year_start }} — {{ $exp->year_end ?: 'Present' }}</div>
                        <div class="timeline-title">{{ $exp->title }}</div>
                        <div class="timeline-subtitle">{{ $exp->company_name }}</div>
                        <p class="text-text-muted">{{ $exp->description }}</p>
                    </div>
                @empty
                    <p class="font-bold text-text-muted italic">No work experience listed.</p>
                @endforelse
            </div>

            <!-- Skills Section -->
            <div class="section-premium gsap-section">
                <h2 class="section-label-giant">Skills</h2>
                @if(!empty($data['candidate']['skills']))
                    <div class="flex flex-wrap gap-3 mt-6">
                        @foreach(array_filter(array_map('trim', explode(',', $data['candidate']['skills']))) as $skill)
                            <span class="bg-black text-white px-4 py-2 text-xs font-black uppercase tracking-widest border-2 border-black hover:bg-accent hover:border-accent transition-colors cursor-default">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="font-bold text-text-muted italic">No skills listed.</p>
                @endif
            </div>

            <!-- Organization Section -->
            <div class="section-premium gsap-section">
                <h2 class="section-label-giant">Organizational Experience</h2>
                @forelse($data['candidate']['org_experiences'] ?? [] as $org)
                    <div class="timeline-item">
                        <div class="timeline-date">{{ $org->start_year }} — {{ $org->year_end ?: 'Present' }}</div>
                        <div class="timeline-title">{{ $org->position }}</div>
                        <div class="timeline-subtitle">{{ $org->organization_name }}</div>
                        <p class="text-text-muted">{{ $org->description }}</p>
                    </div>
                @empty
                    <p class="font-bold text-text-muted italic">No organizational experience listed.</p>
                @endforelse
            </div>

            <!-- Achievements Section -->
            <div class="section-premium gsap-section">
                <h2 class="section-label-giant">Achievements</h2>
                <div class="grid grid-cols-1 gap-6">
                    @forelse($data['candidate']['achievements'] ?? [] as $ach)
                        <div class="p-6 border-2 border-black bg-secondary">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-black text-xl">{{ $ach->title }}</h4>
                                <div class="flex gap-2">
                                    <span class="bg-gray-800 text-white px-2 py-1 text-[10px] font-black uppercase">{{ $ach->level }}</span>
                                    <span class="bg-accent text-white px-2 py-1 text-[10px] font-black uppercase">{{ $ach->type }}</span>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-text-muted">{{ $ach->organizer }} ({{ $ach->year }})</p>
                            <p class="mt-2">{{ $ach->description }}</p>
                            @if($ach->certificate_link)
                                <a href="{{ $ach->certificate_link }}" target="_blank" class="inline-flex items-center gap-2 mt-4 text-xs font-black uppercase text-accent hover:underline">
                                    <i class="bi bi-patch-check-fill"></i>
                                    View Certificate Proof
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="font-bold text-text-muted italic">No achievements listed.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <aside class="ai-intelligence-sidebar">
            <div class="ai-score-card gsap-sidebar">
                <div class="ai-label">AI Match Score</div>
                <div class="ai-score-number">{{ number_format($data['ai']['score_total'] / 10, 1) }}</div>
                <div class="ai-label text-accent">Confidence: {{ $data['ai']['confidence'] }}%</div>
            </div>

            <div class="section-premium gsap-sidebar">
                <h3 class="box-title">AI Analysis</h3>
                <p class="text-sm italic text-text-muted mb-6">"{{ $data['ai']['summary_text'] ?: 'Analysis pending...' }}"</p>
                
                <div class="pro-con-grid">
                    <div class="pro-box">
                        <div class="box-title text-green-600">Strengths</div>
                        @foreach($data['ai']['pros'] ?? [] as $pro)
                            <div class="text-xs font-bold mb-2">✓ {{ $pro }}</div>
                        @endforeach
                    </div>
                    <div class="con-box">
                        <div class="box-title text-red-600">Risks</div>
                        @foreach($data['ai']['cons'] ?? [] as $con)
                            <div class="text-xs font-bold mb-2">! {{ $con }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="section-premium gsap-sidebar">
                <h3 class="box-title">Supporting Files</h3>
                <div class="grid grid-cols-1 gap-4 mt-6">
                    @if(!empty($data['candidate']['cv_path']))
                        <a href="{{ route('view.document', ['type' => 'cv', 'id' => $data['application_id']]) }}" class="flex items-center gap-3 p-4 border-2 border-black hover:bg-accent hover:text-white transition-all no-underline">
                            <i class="bi bi-file-earmark-person text-2xl"></i>
                            <span class="font-black uppercase text-xs">Curriculum Vitae</span>
                        </a>
                    @endif
                    @if(!empty($data['candidate']['diploma_path']))
                        <a href="{{ route('view.document', ['type' => 'diploma', 'id' => $data['application_id']]) }}" class="flex items-center gap-3 p-4 border-2 border-black hover:bg-accent hover:text-white transition-all no-underline">
                            <i class="bi bi-mortarboard text-2xl"></i>
                            <span class="font-black uppercase text-xs">Academic Diploma</span>
                        </a>
                    @endif
                    @if(!empty($data['candidate']['photo_path']))
                        <a href="{{ route('view.document', ['type' => 'photo', 'id' => $data['application_id']]) }}" class="flex items-center gap-3 p-4 border-2 border-black hover:bg-accent hover:text-white transition-all no-underline">
                            <i class="bi bi-image text-2xl"></i>
                            <span class="font-black uppercase text-xs">Formal Photo</span>
                        </a>
                    @endif
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.from(".gsap-hero", { opacity: 0, y: 40, duration: 1, ease: "power4.out" });
        gsap.from(".gsap-section", { opacity: 0, x: -40, stagger: 0.2, duration: 1, ease: "power4.out", delay: 0.3 });
        gsap.from(".gsap-sidebar", { opacity: 0, x: 40, stagger: 0.2, duration: 1, ease: "power4.out", delay: 0.5 });
    });
</script>
@endpush
