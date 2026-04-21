@extends('layouts.hr')

@push('styles')
<style>
    .brutalist-profile-card {
        background: var(--color-surface);
        border: 2px solid var(--color-border);
        padding: 40px;
        box-shadow: 8px 8px 0 var(--color-border);
        margin-bottom: 40px;
    }
    .profile-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -1.5px;
        margin-bottom: 32px;
        border-left: 8px solid var(--color-accent);
        padding-left: 20px;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 32px;
    }
    .info-group h4 {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--color-text-muted);
        margin-bottom: 8px;
    }
    .info-group p {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text);
    }
    .document-section {
        margin-top: 40px;
        padding-top: 40px;
        border-top: 2px solid var(--color-border);
    }
    .document-card {
        background: var(--color-secondary);
        border: 2px solid var(--color-border);
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .document-card:hover {
        border-color: var(--color-accent);
    }
    .btn-brutalist-sm {
        background: var(--color-accent);
        color: white;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        border: 2px solid black;
        box-shadow: 3px 3px 0 black;
        text-decoration: none;
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
            <p>{{ $user->email }}</p>
            <p>{{ $user->phone ?: 'No phone provided' }}</p>
            <p>{{ ucfirst($user->gender ?: 'Not specified') }}</p>
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
        
        <div class="info-group col-span-full">
            <h4>Professional Summary</h4>
            <p class="font-medium leading-relaxed">{{ $user->user_summary ?: 'No summary provided.' }}</p>
        </div>
    </div>

    @if($user->workExperiences->count() > 0)
        <div class="mt-12">
            <h4 class="font-black text-xs uppercase tracking-widest text-text-muted mb-6">Work History</h4>
            @foreach($user->workExperiences as $exp)
                <div class="mb-6 pb-6 border-b border-border last:border-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-black text-lg">{{ $exp->title }}</p>
                            <p class="font-bold text-accent">{{ $exp->company_name }}</p>
                        </div>
                        <p class="text-sm font-black text-text-muted">{{ $exp->year_start }} — {{ $exp->year_end ?: 'Present' }}</p>
                    </div>
                    <p class="mt-2 text-sm">{{ $exp->description }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <div class="document-section">
        <h3 class="font-black text-xl mb-6">Verified Documentation</h3>
        
        @foreach(['cv' => 'Curriculum Vitae', 'diploma' => 'Educational Diploma', 'photo' => 'Formal Portrait'] as $key => $label)
            @php $path = $key . '_path'; @endphp
            <div class="document-card">
                <div>
                    <p class="font-black">{{ $label }}</p>
                    <p class="text-[10px] font-bold text-text-muted uppercase">
                        {{ $user->$path ? 'Verified & Encrypted' : 'Not Provided' }}
                    </p>
                </div>
                @if($user->$path)
                    <div class="flex gap-4">
                        <a href="{{ route('download.file', ['type' => $key, 'id' => $user->id]) }}" target="_blank" class="btn-brutalist-sm">
                            View Document
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<div class="brutalist-profile-card">
    <h3 class="font-black text-xl mb-6">Decision Center</h3>
    <form action="{{ route('hr.applications.status', $application->id) }}" method="POST" class="flex gap-6">
        @csrf
        <button name="status" value="accepted" class="bg-green-500 text-white px-8 py-4 font-black uppercase border-2 border-black shadow-[6px_6px_0_black] hover:translate-y-[2px] transition-all">Accept Candidate</button>
        <button name="status" value="rejected" class="bg-red-500 text-white px-8 py-4 font-black uppercase border-2 border-black shadow-[6px_6px_0_black] hover:translate-y-[2px] transition-all">Reject Candidate</button>
        <button name="status" value="pending" class="bg-yellow-500 text-black px-8 py-4 font-black uppercase border-2 border-black shadow-[6px_6px_0_black] hover:translate-y-[2px] transition-all">Keep in Review</button>
    </form>
</div>
@endsection
