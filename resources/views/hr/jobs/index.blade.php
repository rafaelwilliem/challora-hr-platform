@extends('layouts.hr')

@push('styles')
<style>
    .panel-card {
        background-color: #1a1a1a;
        border: 4px solid black;
        box-shadow: 8px 8px 0 rgba(0,0,0,1);
        padding: 24px;
        margin-bottom: 24px;
        border-radius: 0;
    }
    .panel-header {
        font-size: 14px;
        font-weight: 700;
        color: #9ca3af;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    .btn-orange {
        background-color: var(--color-accent);
        color: #000;
        padding: 12px 24px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        border: 4px solid black;
        box-shadow: 4px 4px 0 black;
        transition: all 0.1s;
    }
    .btn-orange:hover {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0 black;
    }
    .table-container {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .table-container th {
        text-align: left;
        color: #9ca3af;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #374151;
    }
    .table-container td {
        padding: 16px;
        border-bottom: 1px solid #2d2d2d;
        color: #e5e7eb;
        vertical-align: middle;
    }
    .table-container tr:last-child td {
        border-bottom: none;
    }
    .tag-orange {
        background-color: rgba(249, 115, 22, 0.1);
        color: #f97316;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    .action-btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        background-color: transparent;
        color: #d1d5db;
        border: 1px solid #4b5563;
        transition: all 0.2s;
    }
    .action-btn:hover {
        background-color: #374151;
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
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-black text-white mb-2 uppercase tracking-tight">Manage Listings</h1>
        <p class="text-gray-400 text-sm font-bold uppercase tracking-widest opacity-60">Overview of your published positions</p>
    </div>
    <a href="{{ route('hr.jobs.create') }}" class="btn-orange">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
        Create New Listing
    </a>
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
                                <span class="bg-red-600 text-black px-2 py-1 text-[10px] font-black uppercase border-2 border-black">Closed</span>
                            @else
                                <span class="bg-green-500 text-black px-2 py-1 text-[10px] font-black uppercase border-2 border-black">Active</span>
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
        {{ $jobs->links() }}
    </div>
</div>
@endsection
