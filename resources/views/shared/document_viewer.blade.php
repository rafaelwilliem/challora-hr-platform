@extends('layouts.app')

@section('content')
<div class="lowercase mb-8 flex justify-between items-center" style="font-family: var(--font-mono);">
    <a href="javascript:history.back()" class="font-bold text-accent uppercase tracking-widest text-xs flex items-center gap-2">
        <i class="bi bi-arrow-left"></i> back to previous page
    </a>
    <span class="text-[10px] font-bold text-text-muted uppercase tracking-widest">
        Security Protocol: Read-Only View
    </span>
</div>

<div class="bg-black border-2 border-border shadow-[8px_8px_0_var(--color-border)] overflow-hidden relative" 
     style="height: calc(100vh - 250px);"
     oncontextmenu="return false;">
    
    <iframe src="{{ $url }}" class="w-full h-full border-0" title="Document Viewer"></iframe>
    
    <!-- Shielding Overlay (Prevents most clicks/selections inside the iframe) -->
    <div class="absolute inset-0 bg-transparent z-10 pointer-events-none"></div>

    <!-- Read Only Label -->
    <div class="absolute top-0 right-0 p-4 pointer-events-none">
        <div class="bg-accent text-white px-3 py-1 text-[9px] font-bold uppercase tracking-widest border border-black" style="font-family: var(--font-mono);">
            Locked Document
        </div>
    </div>
</div>

<div class="mt-8 text-center">
    <p class="text-[10px] font-bold text-text-muted uppercase tracking-widest" style="font-family: var(--font-mono);">
        If you cannot see the document, <a href="{{ $url }}" target="_blank" class="text-accent underline">click here to open in new tab</a>.
    </p>
</div>
@endsection
