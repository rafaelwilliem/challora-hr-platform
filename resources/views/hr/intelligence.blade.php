@extends('layouts.app')

@section('content')
<div class="border-b border-[#1d1d1d] pb-8 mb-8">
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#171717] pb-3 mb-6">
        <div class="c-release-tag mb-0">
            <span class="w-2 h-2 bg-[#FF4D30]"></span>
            <span>CHALLORA // AI TALENT INTELLIGENCE</span>
        </div>
        <div class="c-mono text-[11px] text-[#777]">COMPATIBILITY &amp; DOSSIER ENGINE</div>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="c-title-section text-4xl text-white uppercase">HR Intelligence</h1>
            <p class="c-motto text-lg mt-2">Top candidates, compatibility list, and AI insights per job.</p>
        </div>
        <a href="{{ route('hr.applications.index') }}" class="c-btn-install">Manage Pipeline &rarr;</a>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div class="bg-[#090909] border border-[#1d1d1d] p-7">
            <h2 class="text-xs uppercase tracking-widest font-bold text-[#FF4D30] mb-5 border-b border-[#181818] pb-3" style="font-family: var(--font-mono);">// 01 &middot; Top Candidates Per Job</h2>
            @forelse($topCandidatesByJob as $item)
                <div class="mb-5 last:mb-0">
                    <div class="text-white font-bold uppercase tracking-tight mb-2.5 text-sm" style="font-family: var(--font-display);">{{ $item['job']['title'] }}</div>
                    @foreach($item['candidates'] as $candidate)
                        <button class="candidate-trigger w-full text-left border border-[#262626] bg-[#070707] hover:border-[#FF4D30] p-3.5 mb-2 transition-colors {{ ($defaultSelectedApplicationId ?? null) === $candidate['application_id'] ? 'ring-1 ring-[#FF4D30] border-[#FF4D30]' : '' }}" data-application-id="{{ $candidate['application_id'] }}">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-white" style="font-family: var(--font-display);">{{ $candidate['candidate_name'] }}</span>
                                <span class="text-xs text-[#FF4D30] font-bold uppercase tracking-wider" style="font-family: var(--font-mono);">Score {{ number_format($candidate['score_total'] / 10, 1) }}/10</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @empty
                <p class="text-base text-gray-400 italic" style="font-family: var(--font-body);">No candidates with AI score yet.</p>
            @endforelse
        </div>

        <div class="bg-[#090909] border border-[#1d1d1d] p-7">
            <h2 class="text-xs uppercase tracking-widest font-bold text-[#FF4D30] mb-5 border-b border-[#181818] pb-3" style="font-family: var(--font-mono);">// 02 &middot; Available Compatible Candidate</h2>
            @forelse($availableCompatibleCandidates as $candidate)
                <button class="candidate-trigger w-full text-left border-b border-[#181818] py-3.5 hover:bg-[#111] px-2 transition-colors {{ ($defaultSelectedApplicationId ?? null) === $candidate['application_id'] ? 'text-[#FF4D30]' : 'text-white' }}" data-application-id="{{ $candidate['application_id'] }}">
                    <div class="flex justify-between items-center">
                        <span class="font-bold" style="font-family: var(--font-display);">{{ $candidate['candidate_name'] }}</span>
                        <span class="text-xs font-bold uppercase tracking-wider" style="font-family: var(--font-mono);">Score {{ number_format($candidate['score_total'] / 10, 1) }}/10</span>
                    </div>
                    <div class="text-xs text-gray-400 italic mt-0.5" style="font-family: var(--font-body);">{{ $candidate['job_title'] }}</div>
                </button>
            @empty
                <p class="text-base text-gray-400 italic" style="font-family: var(--font-body);">No compatible candidates yet.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-[#090909] border border-[#1d1d1d] p-7">
        <h2 class="text-xs uppercase tracking-widest font-bold text-[#FF4D30] mb-5 border-b border-[#181818] pb-3" style="font-family: var(--font-mono);">// 03 &middot; Candidate Insight</h2>
        <div id="insight-empty" class="{{ $selectedCandidateDetail ? 'hidden' : '' }}">
            <p class="text-base text-gray-400 italic" style="font-family: var(--font-body);">Select a candidate to view insights.</p>
        </div>
        <div id="insight-body" class="{{ $selectedCandidateDetail ? '' : 'hidden' }}">
            <div class="mb-5 pb-4 border-b border-[#181818]">
                <div id="insight-name" class="text-2xl font-extrabold text-white uppercase tracking-tight" style="font-family: var(--font-display);"></div>
                <div id="insight-job" class="text-base text-gray-400 italic mt-1" style="font-family: var(--font-body);"></div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="border border-[#262626] bg-[#070707] p-4 text-center">
                    <div class="text-[10px] uppercase tracking-widest text-gray-500 mb-1" style="font-family: var(--font-mono);">AI Score</div>
                    <div id="insight-score" class="text-2xl text-white font-extrabold" style="font-family: var(--font-display);">0</div>
                </div>
                <div class="border border-[#262626] bg-[#070707] p-4 text-center">
                    <div class="text-[10px] uppercase tracking-widest text-gray-500 mb-1" style="font-family: var(--font-mono);">Confidence</div>
                    <div id="insight-confidence" class="text-2xl text-white font-extrabold" style="font-family: var(--font-display);">0%</div>
                </div>
            </div>
            <div class="mb-5">
                <h3 class="text-xs uppercase tracking-widest font-bold text-green-400 mb-2" style="font-family: var(--font-mono);">Plus</h3>
                <ul id="insight-pros" class="list-disc list-inside text-base text-gray-300 space-y-1.5" style="font-family: var(--font-body);"></ul>
            </div>
            <div class="mb-5">
                <h3 class="text-xs uppercase tracking-widest font-bold text-red-400 mb-2" style="font-family: var(--font-mono);">Minus</h3>
                <ul id="insight-cons" class="list-disc list-inside text-base text-gray-300 space-y-1.5" style="font-family: var(--font-body);"></ul>
            </div>
            <div class="pt-4 border-t border-[#181818]">
                <h3 class="text-xs uppercase tracking-widest font-bold text-[#FF4D30] mb-2" style="font-family: var(--font-mono);">Summary</h3>
                <p id="insight-summary" class="text-base text-gray-300 leading-relaxed" style="font-family: var(--font-body);"></p>
            </div>
        </div>
    </div>
</div>

<script id="intel-bootstrap" type="application/json">{!! json_encode([
    'detailUrlTemplate' => route('hr.intelligence.candidates.show', ['application' => '__APP__']),
    'initialDetail' => $selectedCandidateDetail,
]) !!}</script>
@endsection

@push('scripts')
<script>
    const initHrIntelligence = () => {
        const bootstrapNode = document.getElementById('intel-bootstrap');
        if (!bootstrapNode || bootstrapNode.dataset.initialized === '1') return;
        bootstrapNode.dataset.initialized = '1';

        const emptyState = document.getElementById('insight-empty');
        const bodyState = document.getElementById('insight-body');
        if (!emptyState || !bodyState) return;

        const payload = JSON.parse(bootstrapNode.textContent || '{}');
        const detailUrlTemplate = payload.detailUrlTemplate || '';
        const triggers = document.querySelectorAll('.candidate-trigger');

        const renderList = (targetId, values, fallback) => {
            const list = document.getElementById(targetId);
            if (!list) return;
            list.innerHTML = '';
            (Array.isArray(values) && values.length ? values : [fallback]).forEach((item) => {
                const li = document.createElement('li');
                li.textContent = item;
                list.appendChild(li);
            });
        };

        const renderDetail = (detail) => {
            if (!detail) {
                bodyState.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            bodyState.classList.remove('hidden');
            emptyState.classList.add('hidden');
            document.getElementById('insight-name').textContent = detail.candidate?.name ?? '-';
            document.getElementById('insight-job').textContent = detail.job?.title ?? '-';
            document.getElementById('insight-score').textContent = ((detail.ai?.score_total ?? 0)).toFixed(1) + '/10';
            document.getElementById('insight-confidence').textContent = `${Math.round((detail.ai?.confidence ?? 0) * 100)}%`;
            document.getElementById('insight-summary').textContent = detail.ai?.summary_text ?? 'No AI summary available.';
            renderList('insight-pros', detail.ai?.pros, 'No strengths listed.');
            renderList('insight-cons', detail.ai?.cons, 'No weaknesses listed.');
        };

        let loadingAppId = null;

        const loadDetail = async (applicationId) => {
            if (loadingAppId === applicationId) return;
            loadingAppId = applicationId;
            if (!detailUrlTemplate) { loadingAppId = null; return; }
            triggers.forEach(t => t.classList.remove('ring-2', 'ring-accent'));
            const activeTrigger = document.querySelector(`[data-application-id="${applicationId}"]`);
            if (activeTrigger) activeTrigger.classList.add('ring-2', 'ring-accent');
            try {
                const response = await fetch(detailUrlTemplate.replace('__APP__', applicationId), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                loadingAppId = null;
                if (!response.ok) return;
                const result = await response.json();
                if (result?.ok) renderDetail(result.data);
            } catch (_) { loadingAppId = null; }
        };

        triggers.forEach((trigger) => {
            trigger.addEventListener('click', async () => {
                await loadDetail(trigger.dataset.applicationId);
            });
        });

        renderDetail(payload.initialDetail ?? null);
    };

    document.addEventListener('DOMContentLoaded', initHrIntelligence);
</script>
@endpush


