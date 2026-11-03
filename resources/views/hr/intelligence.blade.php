@extends('layouts.app')

@section('content')
<div class="bg-[#1a1a1a] border-4 border-black shadow-[8px_8px_0_#000] p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-white uppercase tracking-tight" style="font-family: var(--font-display);">HR Intelligence</h1>
            <p class="text-base text-gray-300 italic mt-1" style="font-family: var(--font-body);">Top candidates, compatibility list, and AI insights per job.</p>
        </div>
        <a href="{{ route('hr.applications.index') }}" class="px-4 py-2.5 text-xs bg-accent text-black font-bold uppercase tracking-widest border-2 border-black shadow-[4px_4px_0_#000]" style="font-family: var(--font-mono);">Manage Pipeline</a>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div class="bg-[#1a1a1a] border-4 border-black shadow-[6px_6px_0_#000] p-6">
            <h2 class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-4 border-b border-gray-800 pb-2" style="font-family: var(--font-mono);">Top Candidates Per Job</h2>
            @forelse($topCandidatesByJob as $item)
                <div class="mb-5 last:mb-0">
                    <div class="text-white font-extrabold uppercase tracking-tight mb-2" style="font-family: var(--font-display);">{{ $item['job']['title'] }}</div>
                    @foreach($item['candidates'] as $candidate)
                        <button class="candidate-trigger w-full text-left border border-gray-700 bg-[#111] hover:border-accent p-3 mb-2 transition-colors {{ ($defaultSelectedApplicationId ?? null) === $candidate['application_id'] ? 'ring-2 ring-accent' : '' }}" data-application-id="{{ $candidate['application_id'] }}">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-white" style="font-family: var(--font-display);">{{ $candidate['candidate_name'] }}</span>
                                <span class="text-xs text-accent font-bold uppercase tracking-wider" style="font-family: var(--font-mono);">Score {{ number_format($candidate['score_total'] / 10, 1) }}/10</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @empty
                <p class="text-base text-gray-400 italic" style="font-family: var(--font-body);">No candidates with AI score yet.</p>
            @endforelse
        </div>

        <div class="bg-[#1a1a1a] border-4 border-black shadow-[6px_6px_0_#000] p-6">
            <h2 class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-4 border-b border-gray-800 pb-2" style="font-family: var(--font-mono);">Available Compatible Candidate</h2>
            @forelse($availableCompatibleCandidates as $candidate)
                <button class="candidate-trigger w-full text-left border-b border-gray-800 py-3 hover:bg-white/5 px-2 transition-colors {{ ($defaultSelectedApplicationId ?? null) === $candidate['application_id'] ? 'text-accent' : 'text-white' }}" data-application-id="{{ $candidate['application_id'] }}">
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

    <div class="bg-[#1a1a1a] border-4 border-black shadow-[6px_6px_0_#000] p-6">
        <h2 class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-4 border-b border-gray-800 pb-2" style="font-family: var(--font-mono);">Candidate Insight</h2>
        <div id="insight-empty" class="{{ $selectedCandidateDetail ? 'hidden' : '' }}">
            <p class="text-base text-gray-400 italic" style="font-family: var(--font-body);">Select a candidate to view insights.</p>
        </div>
        <div id="insight-body" class="{{ $selectedCandidateDetail ? '' : 'hidden' }}">
            <div class="mb-4 pb-3 border-b border-gray-800">
                <div id="insight-name" class="text-2xl font-extrabold text-white uppercase tracking-tight" style="font-family: var(--font-display);"></div>
                <div id="insight-job" class="text-base text-gray-300 italic mt-0.5" style="font-family: var(--font-body);"></div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div class="border-2 border-gray-700 bg-[#111] p-3 text-center">
                    <div class="text-[11px] uppercase tracking-widest text-gray-400 mb-1" style="font-family: var(--font-mono);">AI Score</div>
                    <div id="insight-score" class="text-xl text-white font-extrabold" style="font-family: var(--font-display);">0</div>
                </div>
                <div class="border-2 border-gray-700 bg-[#111] p-3 text-center">
                    <div class="text-[11px] uppercase tracking-widest text-gray-400 mb-1" style="font-family: var(--font-mono);">Confidence</div>
                    <div id="insight-confidence" class="text-xl text-white font-extrabold" style="font-family: var(--font-display);">0%</div>
                </div>
            </div>
            <div class="mb-4">
                <h3 class="text-xs uppercase tracking-widest font-bold text-green-400 mb-1.5" style="font-family: var(--font-mono);">Plus</h3>
                <ul id="insight-pros" class="list-disc list-inside text-base text-gray-300 space-y-1" style="font-family: var(--font-body);"></ul>
            </div>
            <div class="mb-4">
                <h3 class="text-xs uppercase tracking-widest font-bold text-red-400 mb-1.5" style="font-family: var(--font-mono);">Minus</h3>
                <ul id="insight-cons" class="list-disc list-inside text-base text-gray-300 space-y-1" style="font-family: var(--font-body);"></ul>
            </div>
            <div class="pt-3 border-t border-gray-800">
                <h3 class="text-xs uppercase tracking-widest font-bold text-accent mb-1.5" style="font-family: var(--font-mono);">Summary</h3>
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


