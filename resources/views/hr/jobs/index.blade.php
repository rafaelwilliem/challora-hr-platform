@extends('layouts.app')

@push('styles')
<style>
    .panel-card {
        background-color: #090909;
        border: 1px solid #1d1d1d;
        padding: 28px;
        margin-bottom: 24px;
        border-radius: 0;
    }
    .panel-header {
        font-family: var(--font-mono);
        font-size: 13px;
        font-weight: 700;
        color: #9ca3af;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    .btn-orange {
        background-color: #ECECE7;
        color: #000;
        padding: 12px 24px;
        font-family: var(--font-mono);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        border: 1px solid #ECECE7;
        transition: all 0.18s ease;
    }
    .btn-orange:hover {
        background-color: #FF4D30;
        border-color: #FF4D30;
        color: #fff;
    }
    .table-container {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .table-container th {
        text-align: left;
        color: #888888;
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        padding: 14px 16px;
        border-bottom: 1px solid #1d1d1d;
    }
    .table-container td {
        padding: 18px 16px;
        border-bottom: 1px solid #181818;
        color: #e5e7eb;
        vertical-align: middle;
    }
    .table-container tr:last-child td {
        border-bottom: none;
    }
    .tag-orange {
        background-color: rgba(255, 77, 48, 0.08);
        border: 1px solid rgba(255, 77, 48, 0.35);
        color: #FF4D30;
        padding: 4px 10px;
        border-radius: 0;
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .action-btn {
        padding: 7px 12px;
        border-radius: 0;
        font-family: var(--font-mono);
        font-size: 12px;
        font-weight: 600;
        background-color: #070707;
        color: #d1d5db;
        border: 1px solid #262626;
        transition: all 0.2s;
    }
    .action-btn:hover {
        background-color: #141414;
        border-color: #777777;
        color: #fff;
    }
    .action-btn.delete:hover {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-color: #ef4444;
    }
</style>
@endpush

@section('content')
<div class="border-b border-[#1d1d1d] pb-8 mb-8">
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#171717] pb-3 mb-6">
        <div class="c-release-tag mb-0">
            <span class="w-2 h-2 bg-[#FF4D30]"></span>
            <span>CHALLORA // REQUISITION MANAGEMENT</span>
        </div>
        <div class="c-mono text-[11px] text-[#777]">RECRUITER WORKSPACE</div>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="c-title-section text-4xl text-white uppercase">Manage Listings</h1>
            <p class="c-motto text-lg mt-2">Overview of your published positions</p>
        </div>
        <a href="{{ route('hr.jobs.create') }}" class="btn-orange">
            + Create New Listing
        </a>
    </div>
</div>

<div class="panel-card mt-6">
    <div class="overflow-x-auto">
        <table class="table-container">
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Type / Location</th>
                    <th>Applicants</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobs as $job)
                    <tr>
                        <td>
                            <div class="font-black text-white text-base uppercase tracking-tight">{{ $job->title }}</div>
                            <div class="text-gray-500 text-[10px] font-black uppercase mt-1">Created: {{ $job->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            <span class="tag-orange">{{ str_replace('_', '-', ucfirst($job->job_type->value)) }}</span>
                            <div class="text-gray-400 text-xs mt-2"><i class="bi bi-geo-alt-fill"></i> {{ $job->location ?: 'Remote' }}</div>
                        </td>
                        <td>
                            <a href="{{ route('hr.applications.index', ['job_id' => $job->id]) }}" class="hover:text-accent font-black text-white transition-colors text-lg">
                                {{ $job->applications_count }} <span class="text-xs uppercase opacity-60">Applicants</span>
                            </a>
                        </td>
                        <td>
                            @if($job->deadline && $job->deadline->isPast())
                                <span class="c-mono bg-red-500/10 text-red-400 px-2.5 py-1 text-[10px] font-bold uppercase border border-red-500/40">Closed</span>
                            @else
                                <span class="c-mono bg-green-500/10 text-green-400 px-2.5 py-1 text-[10px] font-bold uppercase border border-green-500/40">Active</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('jobs.show', $job->id) }}" target="_blank" class="action-btn">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('hr.jobs.edit', $job->id) }}" class="action-btn border-2 border-black">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('hr.jobs.destroy', $job->id) }}" onsubmit="return confirm('Are you sure you want to delete this listing? All related application data will be lost!');" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-20">
                            <div class="text-gray-700 mb-4 opacity-20"><i class="bi bi-inbox text-6xl"></i></div>
                            <h3 class="text-white font-black text-2xl uppercase tracking-tighter mb-1">No Listings Found</h3>
                            <p class="text-gray-500 text-sm font-bold">You haven't published any positions yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $jobs->appends(request()->query())->links('components.premium-pagination') }}
    </div>
</div>
@endsection
