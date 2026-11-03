@extends('layouts.app')

@section('content')
<div class="lowercase mb-8 flex justify-between items-center" style="font-family: var(--font-mono);">
    <a href="javascript:history.back()" class="font-bold text-accent uppercase tracking-widest text-xs flex items-center gap-2">
        <i class="bi bi-arrow-left"></i> back to previous page
    </a>
</div>

<div class="border-2 border-border shadow-[8px_8px_0_var(--color-border)] p-16 flex flex-col items-center justify-center text-center" style="min-height: 400px;">
    <div class="text-6xl mb-6">📄</div>
    <h2 class="font-extrabold text-3xl uppercase tracking-tight mb-3" style="font-family: var(--font-display);">File Not Available</h2>
    <p class="text-text-muted font-bold text-xs uppercase tracking-widest mb-2" style="font-family: var(--font-mono);">
        {{ strtoupper($type) }} &nbsp;·&nbsp; {{ $message ?? 'This file could not be found on the server.' }}
    </p>
    <p class="text-text-muted text-base italic mt-4 max-w-md leading-relaxed" style="font-family: var(--font-body);">
        This usually happens because the file was uploaded before the latest deployment.
        The candidate may need to re-upload their document.
    </p>
</div>
@endsection
