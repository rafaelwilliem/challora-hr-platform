@extends('layouts.hr')

@push('styles')
<style>
    .jf-wrap { max-width: 900px; margin: 0 auto; }
    .jf-title { font-size: 32px; font-weight: 800; color: var(--color-text); letter-spacing: -1.5px; margin-bottom: 32px; border-bottom: 4px solid var(--color-accent); padding-bottom: 12px; display: inline-block; }
    .jf-section { background: var(--color-surface); border: 2px solid var(--color-border); padding: 32px; margin-bottom: 24px; box-shadow: 6px 6px 0 var(--color-border); }
    .jf-section-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--color-accent); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; }
    .jf-section-title::after { content: ''; flex: 1; height: 2px; background: var(--color-border); opacity: 0.3; }
    .jf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .jf-field { margin-bottom: 24px; }
    .jf-label { display: block; font-size: 11px; font-weight: 800; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; }
    .jf-label .req { color: var(--color-accent); }
    .jf-input, .jf-textarea, .jf-select {
        width: 100%; background: var(--color-secondary); border: 2px solid var(--color-border);
        color: var(--color-text); padding: 14px 16px; font-size: 14px; font-weight: 600; transition: all 0.2s;
    }
    .jf-input:focus, .jf-textarea:focus, .jf-select:focus { border-color: var(--color-accent); outline: none; box-shadow: 4px 4px 0 var(--color-accent-muted); }
    .jf-tag-wrap { background: var(--color-secondary); border: 2px solid var(--color-border); padding: 12px; display: flex; flex-wrap: wrap; gap: 8px; min-height: 56px; }
    .jf-tag { background: var(--color-accent); color: white; padding: 4px 12px; font-size: 12px; font-weight: 800; border: 2px solid black; display: flex; align-items: center; gap: 6px; }
    .jf-tag-remove { cursor: pointer; opacity: 0.7; }
    .jf-tag-remove:hover { opacity: 1; }
    .jf-btn-submit { background: var(--color-accent); color: white; padding: 16px 32px; font-size: 16px; font-weight: 800; text-transform: uppercase; border: 2px solid black; box-shadow: 6px 6px 0 black; cursor: pointer; transition: all 0.2s; }
    .jf-btn-submit:hover { transform: translate(-2px, -2px); box-shadow: 10px 10px 0 black; }
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
