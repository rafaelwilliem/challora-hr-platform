@extends('layouts.app')

@push('styles')
<style>
    .jf-wrap { max-width: 900px; margin: 0 auto; }
    .jf-title { font-family: var(--font-display); font-size: 36px; font-weight: 800; color: #ffffff; letter-spacing: -0.03em; margin-bottom: 32px; border-bottom: 1px solid #1d1d1d; padding-bottom: 16px; display: block; text-transform: uppercase; }
    .jf-section { background: #090909; border: 1px solid #1d1d1d; padding: 36px; margin-bottom: 24px; }
    .jf-section-title { font-family: var(--font-mono); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #FF4D30; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; }
    .jf-section-title::after { content: ''; flex: 1; height: 1px; background: #1d1d1d; }
    .jf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .jf-field { margin-bottom: 24px; }
    .jf-label { font-family: var(--font-mono); display: block; font-size: 10px; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 8px; }
    .jf-label .req { color: #FF4D30; }
    .jf-input, .jf-textarea, .jf-select {
        width: 100%; background: #070707; border: 1px solid #262626; border-radius: 0;
        color: #E5E5DF; padding: 14px 16px; font-size: 14px; font-weight: 500; transition: border-color 0.2s;
    }
    .jf-textarea { font-family: var(--font-body); font-size: 16px; font-weight: 400; line-height: 1.65; }
    .jf-input:focus, .jf-textarea:focus, .jf-select:focus { border-color: #FF4D30; outline: none; }
    .jf-tag-wrap { background: #070707; border: 1px solid #262626; padding: 12px; display: flex; flex-wrap: wrap; gap: 8px; min-height: 56px; }
    .jf-tag { font-family: var(--font-mono); background: #090909; color: #FF4D30; padding: 4px 12px; font-size: 11px; font-weight: 700; border: 1px solid #FF4D30; display: flex; align-items: center; gap: 6px; }
    .jf-tag-remove { cursor: pointer; opacity: 0.7; }
    .jf-tag-remove:hover { opacity: 1; }
    .jf-btn-submit { font-family: var(--font-mono); background: #ECECE7; color: #000000; padding: 16px 32px; font-size: 12px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; border: 1px solid #ECECE7; cursor: pointer; transition: all 0.18s ease; }
    .jf-btn-submit:hover { background: #FF4D30; border-color: #FF4D30; color: #ffffff; }
</style>
@endpush

@section('content')
<div class="jf-wrap">
    <h1 class="jf-title">Post New Opportunity</h1>

    <form method="post" action="{{ route('hr.jobs.store') }}">
        @csrf

        <div class="jf-section">
            <div class="jf-section-title">Identity & Narrative</div>
            <div class="jf-field">
                <label class="jf-label">Role Title <span class="req">*</span></label>
                <input type="text" name="title" class="jf-input" required value="{{ old('title') }}" placeholder="e.g. Lead System Architect">
            </div>
            <div class="jf-field">
                <label class="jf-label">Short Summary</label>
                <textarea name="short_description" class="jf-textarea" rows="2" maxlength="255" placeholder="Brief elevator pitch for the role...">{{ old('short_description') }}</textarea>
            </div>
            <div class="jf-field">
                <label class="jf-label">Detailed Description <span class="req">*</span></label>
                <textarea name="description" class="jf-textarea" rows="10" required placeholder="Full responsibilities and impact expectation...">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="jf-section">
            <div class="jf-section-title">Technical DNA</div>
            <div class="jf-row">
                <div class="jf-field">
                    <label class="jf-label">Job Type</label>
                    <select name="job_type" class="jf-select">
                        @foreach(\App\Enums\JobType::cases() as $type)
                            <option value="{{ $type->value }}" {{ old('job_type') === $type->value ? 'selected' : '' }}>{{ ucfirst($type->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="jf-field">
                    <label class="jf-label">Experience Tier</label>
                    <select name="experience_level" class="jf-select">
                        @foreach(\App\Enums\ExperienceLevel::cases() as $level)
                            <option value="{{ $level->value }}" {{ old('experience_level') === $level->value ? 'selected' : '' }}>{{ $level->value }} Years</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="jf-field">
                <label class="jf-label">Skills (Comma Separated)</label>
                <input type="text" name="skills" class="jf-input" value="{{ old('skills') }}" placeholder="React, Laravel, AWS, Docker...">
            </div>
            <div class="jf-field">
                <label class="jf-label">Benefits (Comma Separated)</label>
                <input type="text" name="benefits" class="jf-input" value="{{ old('benefits') }}" placeholder="Remote Work, Health Insurance, Stock Options...">
            </div>
        </div>

        <div class="jf-section">
            <div class="jf-section-title">Logistics & Economics</div>
            <div class="jf-row">
                <div class="jf-field">
                    <label class="jf-label">Location</label>
                    <input type="text" name="location" class="jf-input" value="{{ old('location') }}" placeholder="e.g. Jakarta / Remote">
                </div>
                <div class="jf-field">
                    <label class="jf-label">Deadline</label>
                    <input type="date" name="deadline" class="jf-input" value="{{ old('deadline') }}">
                </div>
            </div>
            <div class="jf-row">
                <div class="jf-field">
                    <label class="jf-label">Min Salary (IDR)</label>
                    <input type="number" name="min_salary" class="jf-input" value="{{ old('min_salary') }}" placeholder="e.g. 15000000">
                </div>
                <div class="jf-field">
                    <label class="jf-label">Max Salary (IDR)</label>
                    <input type="number" name="max_salary" class="jf-input" value="{{ old('max_salary') }}" placeholder="e.g. 25000000">
                </div>
            </div>
        </div>

        <div class="flex gap-6 mt-12 pb-12">
            <button type="submit" class="jf-btn-submit">Publish Opening</button>
            <a href="{{ route('hr.dashboard') }}" class="inline-block bg-secondary border-2 border-black px-8 py-4 font-black uppercase text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        gsap.from(".jf-title", { opacity: 0, x: -30, duration: 0.8, ease: "power4.out" });
        gsap.from(".jf-section", { opacity: 0, y: 30, stagger: 0.1, duration: 1, ease: "power4.out", delay: 0.2 });
    });
</script>
@endpush
