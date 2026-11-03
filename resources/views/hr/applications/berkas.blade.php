@extends('layouts.app')

@push('styles')
<style>
    .brutalist-profile-card {
        background: #090909;
        border: 1px solid #1d1d1d;
        padding: 40px;
        margin-bottom: 32px;
    }
    .profile-title {
        font-family: var(--font-display);
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.03em;
        margin-bottom: 32px;
        border-left: 3px solid #FF4D30;
        padding-left: 20px;
        text-transform: uppercase;
        color: #ffffff;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 32px;
    }
    .info-group h4 {
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #FF4D30;
        margin-bottom: 8px;
    }
    .info-group p {
        font-size: 15px;
        font-weight: 500;
        color: #E5E5DF;
    }
    .document-section {
        margin-top: 40px;
        padding-top: 32px;
        border-top: 1px solid #1d1d1d;
    }
    .document-card {
        background: #070707;
        border: 1px solid #1d1d1d;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        transition: border-color 0.2s;
    }
    .document-card:hover {
        border-color: #FF4D30;
    }
    .btn-brutalist-sm {
        font-family: var(--font-mono);
        background: #ECECE7;
        color: #000000;
        padding: 8px 16px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        border: 1px solid #ECECE7;
        text-decoration: none;
        transition: all 0.18s ease;
    }
    .btn-brutalist-sm:hover {
        background: #FF4D30;
        border-color: #FF4D30;
        color: #ffffff;
    }
    .action-select-premium {
        font-family: var(--font-mono);
        background: #070707;
        border: 1px solid #262626;
        color: #E5E5DF;
        padding: 10px 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 10px;
        cursor: pointer;
        border-radius: 0;
    }
    .action-select-premium:hover {
        border-color: var(--color-accent);
    }
</style>
@endpush

@section('content')
<div class="lowercase mb-8">
    <a href="{{ route('hr.applications.index') }}" class="font-black text-accent uppercase tracking-widest text-sm flex items-center gap-2">
        <i class="bi bi-arrow-left"></i> back to pipeline
    </a>
</div>

<div class="brutalist-profile-card">
    <h1 class="profile-title">Applicant Dossier: {{ $user->name }}</h1>
    
    <div class="info-grid">
        <div class="info-group">
            <h4>Identity</h4>
            <p>{{ $user->name }}</p>
            <p>{{ $user->email }}</p>
            <p>{{ $user->phone ?: 'No phone provided' }}</p>
            <p>{{ ucfirst($user->gender ?: 'Not specified') }} &bull; {{ $user->religion ?: '-' }}</p>
            <p>{{ $user->marital_status ? ucfirst($user->marital_status) : 'Marital status not specified' }}</p>
        </div>

        <div class="info-group">
            <h4>Birth</h4>
            <p>{{ $user->birth_place ?: '-' }}</p>
            <p>{{ $user->birth_date ? $user->birth_date->format('d F Y') : '-' }}</p>
        </div>

        <div class="info-group">
            <h4>Address</h4>
            <p>{{ $user->address ?: 'No address provided' }}</p>
            @if($user->address_type)
                <p class="text-sm text-text-muted">{{ ucfirst($user->address_type) }}</p>
            @endif
            @if($user->address_family)
                <p class="text-sm text-text-muted">Family: {{ $user->address_family }}</p>
            @endif
        </div>

        <div class="info-group">
            <h4>Education</h4>
            <p>{{ $user->education_level ?: '-' }} &bull; {{ $user->education_university ?: '-' }}</p>
            <p>{{ $user->education_major ?: '-' }} ({{ $user->graduation_year ?: '-' }})</p>
        </div>

        <div class="info-group">
            <h4>Applied For</h4>
            <p class="text-accent">{{ $job->title }}</p>
            <p>Status: {{ strtoupper($application->status->value) }}</p>
        </div>

        <div class="info-group">
            <h4>Emergency Contact</h4>
            <p>{{ $user->emergency_name ?: '-' }}</p>
            <p>{{ $user->emergency_phone ?: '-' }}</p>
        </div>

        <div class="info-group">
            <h4>Father</h4>
            <p>{{ $user->father_name ?: '-' }}</p>
            <p>{{ $user->father_job ?: '-' }} &bull; {{ $user->father_education ?: '-' }}</p>
            <p>{{ $user->father_phone ?: '-' }}</p>
        </div>

        <div class="info-group">
            <h4>Mother</h4>
            <p>{{ $user->mother_name ?: '-' }}</p>
            <p>{{ $user->mother_job ?: '-' }} &bull; {{ $user->mother_education ?: '-' }}</p>
            <p>{{ $user->mother_phone ?: '-' }}</p>
        </div>

        @if($user->social_media)
        <div class="info-group">
            <h4>Social Media</h4>
            <p>{{ $user->social_media }}</p>
        </div>
        @endif

        <div class="info-group col-span-full">
            <h4>Professional Summary</h4>
            <p class="font-normal leading-relaxed" style="font-family: var(--font-body); font-size: 18px;">{{ $user->user_summary ?: 'No summary provided.' }}</p>
        </div>
    </div>

    @if($user->workExperiences->count() > 0)
        <div class="mt-12">
            <h4 class="font-bold text-xs uppercase tracking-widest text-text-muted mb-6" style="font-family: var(--font-mono);">Work History</h4>
            @foreach($user->workExperiences as $exp)
                <div class="mb-6 pb-6 border-b border-border last:border-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-extrabold text-lg uppercase" style="font-family: var(--font-display);">{{ $exp->title }}</p>
                            <p class="font-bold text-accent">{{ $exp->company_name }}</p>
                        </div>
                        <p class="text-xs font-bold text-text-muted uppercase" style="font-family: var(--font-mono);">{{ $exp->year_start }} — {{ $exp->year_end ?: 'Present' }}</p>
                    </div>
                    <p class="mt-2 text-base" style="font-family: var(--font-body);">{{ $exp->description }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <div class="document-section">
        <h3 class="font-black text-xl mb-6">Verified Documentation</h3>
        
        @foreach(['cv' => 'Curriculum Vitae', 'diploma' => 'Educational Diploma', 'photo' => 'Formal Portrait'] as $key => $label)
            @php
                $path = $key . '_path';
                $hasDoc = !empty($application->$path) || !empty($user->$path);
            @endphp
            <div class="document-card">
                <div>
                    <p class="font-black">{{ $label }}</p>
                    <p class="text-[10px] font-bold text-text-muted uppercase">
                        {{ $hasDoc ? 'Verified & Encrypted' : 'Not Provided' }}
                    </p>
                </div>
                @if($hasDoc)
                    <div class="flex gap-4">
                        <a href="{{ route('view.document', ['type' => $key, 'id' => $application->id]) }}" target="_blank" class="btn-brutalist-sm">
                            View Document
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<div class="brutalist-profile-card" id="ai-assessment-card" data-application-id="{{ $application->id }}">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-black text-xl">AI Talent Assessment</h3>
        <form method="post" action="{{ route('hr.applications.ai_refresh', $application->id) }}" id="refresh-ai-form">
            @csrf
            <button type="submit" class="action-select-premium text-xs">Refresh AI</button>
        </form>
    </div>

    <div id="ai-score-section">
    @if($application->aiScore && $application->aiScore->status === 'completed')
        <div class="mb-6">
            <p class="text-sm uppercase font-black text-text-muted">CV Fit Score</p>
            <p class="text-5xl font-black text-accent">{{ number_format($application->aiScore->score_total / 10, 1) }}/10</p>
            <p class="text-sm font-bold mt-2">Core Strength: {{ $application->aiScore->core_strength ?: '-' }}</p>
        </div>
    @elseif($application->aiScore && $application->aiScore->status === 'failed')
        <p class="text-sm font-bold text-accent uppercase mb-6">AI score failed. <button form="refresh-ai-form" type="submit" class="underline">Try refreshing.</button></p>
    @else
        <div class="flex items-center gap-3 mb-6" id="score-spinner">
            <div class="h-6 w-6 border-4 border-accent border-t-transparent rounded-full animate-spin"></div>
            <span class="text-yellow-500 font-bold text-xs uppercase animate-pulse">Analyzing score...</span>
        </div>
    @endif
    </div>

    <div id="ai-summary-section">
    @if($application->aiSummary && $application->aiSummary->status === 'completed')
        <div class="grid grid-cols-2 gap-8">
            <div>
                <p class="font-black uppercase text-xs mb-3 text-green-600">Plus</p>
                <ul class="list-disc pl-5 text-sm font-medium">
                    @foreach($application->aiSummary->pros_json ?? [] as $pro)
                        <li>{{ $pro }}</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="font-black uppercase text-xs mb-3 text-red-600">Minus</p>
                <ul class="list-disc pl-5 text-sm font-medium">
                    @foreach($application->aiSummary->cons_json ?? [] as $con)
                        <li>{{ $con }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="mt-6">
            <p class="font-black uppercase text-xs text-text-muted">Summary</p>
            <p class="mt-2">{{ $application->aiSummary->summary_text }}</p>
            <p class="mt-3 text-xs uppercase font-black text-accent">Recommendation: {{ $application->aiSummary->recommendation }}</p>
        </div>
    @elseif($application->aiSummary && $application->aiSummary->status === 'failed')
        <p class="text-sm font-bold text-accent uppercase">AI summary failed. <button form="refresh-ai-form" type="submit" class="underline">Try refreshing.</button></p>
    @else
        <div class="flex items-center gap-3" id="summary-spinner">
            <div class="h-6 w-6 border-4 border-accent border-t-transparent rounded-full animate-spin"></div>
            <span class="text-yellow-500 font-bold text-xs uppercase animate-pulse">Analyzing summary...</span>
        </div>
    @endif
    </div>
</div>

@push('scripts')
<script>
(function() {
    const card = document.getElementById('ai-assessment-card');
    if (!card) return;

    const appId       = card.dataset.applicationId;
    const scoreEl     = document.getElementById('ai-score-section');
    const summaryEl   = document.getElementById('ai-summary-section');

    // Only poll if there's a spinner visible
    const hasSpinner = card.querySelector('.animate-spin');
    if (!hasSpinner) return;

    let interval = setInterval(async () => {
        try {
            const res  = await fetch(`/hr/applications/${appId}/ai-status`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();

            const scoreDone   = data.score.status   === 'completed' || data.score.status   === 'failed';
            const summaryDone = data.summary.status === 'completed' || data.summary.status === 'failed';

            // Update score section
            if (scoreDone && scoreEl) {
                if (data.score.status === 'completed') {
                    scoreEl.innerHTML = `
                        <div class="mb-6">
                            <p class="text-sm uppercase font-black text-text-muted">CV Fit Score</p>
                            <p class="text-5xl font-black text-accent">${(data.score.score_total / 10).toFixed(1)}/10</p>
                            <p class="text-sm font-bold mt-2">Core Strength: ${data.score.core_strength || '-'}</p>
                        </div>`;
                } else {
                    scoreEl.innerHTML = `<p class="text-sm font-bold text-red-500 uppercase mb-6">AI score failed. Try refreshing.</p>`;
                }
            }

            // Update summary section
            if (summaryDone && summaryEl) {
                if (data.summary.status === 'completed') {
                    const pros = (data.summary.pros || []).map(p => `<li>${p}</li>`).join('');
                    const cons = (data.summary.cons || []).map(c => `<li>${c}</li>`).join('');
                    summaryEl.innerHTML = `
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <p class="font-black uppercase text-xs mb-3 text-green-600">Plus</p>
                                <ul class="list-disc pl-5 text-sm font-medium">${pros}</ul>
                            </div>
                            <div>
                                <p class="font-black uppercase text-xs mb-3 text-red-600">Minus</p>
                                <ul class="list-disc pl-5 text-sm font-medium">${cons}</ul>
                            </div>
                        </div>
                        <div class="mt-6">
                            <p class="font-black uppercase text-xs text-text-muted">Summary</p>
                            <p class="mt-2">${data.summary.summary_text || ''}</p>
                            <p class="mt-3 text-xs uppercase font-black text-accent">Recommendation: ${data.summary.recommendation || ''}</p>
                        </div>`;
                } else {
                    summaryEl.innerHTML = `<p class="text-sm font-bold text-red-500 uppercase">AI summary failed. Try refreshing.</p>`;
                }
            }

            if (scoreDone && summaryDone) {
                clearInterval(interval);
            }
        } catch (e) {
            // network error — keep trying
        }
    }, 3000);
})();
</script>
@endpush

<div class="brutalist-profile-card">
    <h3 class="c-title-card text-xl text-white uppercase mb-6">// Decision Center</h3>
    <form action="{{ route('hr.applications.status', $application->id) }}" method="POST" class="flex flex-wrap gap-4">
        @csrf
        <button name="status" value="accepted" class="c-mono bg-green-500/10 hover:bg-green-500 text-green-400 hover:text-black px-6 py-3.5 text-xs font-bold uppercase tracking-widest border border-green-500/40 transition-colors cursor-pointer">Accept Candidate</button>
        <button name="status" value="rejected" class="c-mono bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white px-6 py-3.5 text-xs font-bold uppercase tracking-widest border border-red-500/40 transition-colors cursor-pointer">Reject Candidate</button>
        <button name="status" value="pending" class="c-mono bg-yellow-500/10 hover:bg-yellow-500 text-yellow-400 hover:text-black px-6 py-3.5 text-xs font-bold uppercase tracking-widest border border-yellow-500/40 transition-colors cursor-pointer">Keep in Review</button>
    </form>
</div>
@endsection
