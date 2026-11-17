@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Box -->
    <div class="border border-[#202020] bg-[#090909] p-8 md:p-12 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#1c1c1c] pb-4 mb-6">
            <span class="c-mono text-xs text-[#FF4D30] font-bold uppercase tracking-widest">// TERMS OF SERVICE</span>
            <span class="c-mono text-xs text-gray-500">EFFECTIVE: NOVEMBER 2026</span>
        </div>
        <h1 class="c-title-section text-white text-3xl sm:text-5xl mb-4">Terms of Service</h1>
        <p class="c-body-text text-gray-400 text-base">
            By registering an account or using the Challora HR Platform, you agree to abide by the following terms and responsibilities governing Candidates and Recruiters (HR).
        </p>
    </div>

    <div class="space-y-6">
        <!-- 01. Candidate Obligations -->
        <section class="border border-[#202020] bg-[#090909] p-8">
            <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-2">// 01 &middot; CANDIDATE RESPONSIBILITIES</span>
            <h2 class="c-title-card text-white text-xl mb-4">Accuracy of Credentials &amp; Submissions</h2>
            <div class="c-body-text text-sm text-gray-300 space-y-3">
                <p>As a Candidate on Challora, you represent and warrant that:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>All profile information, work histories, academic degrees, and uploaded documents (CVs, diplomas, photos) are truthful, accurate, and belong to you.</li>
                    <li>You will not upload malicious files, falsified academic credentials, or another individual's personal data without authorization.</li>
                    <li>You consent to having your professional profile evaluated by Chally AI and reviewed by the Recruiter responsible for the positions you apply to.</li>
                </ul>
            </div>
        </section>

        <!-- 02. Recruiter Obligations -->
        <section class="border border-[#202020] bg-[#090909] p-8">
            <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-2">// 02 &middot; RECRUITER (HR) RESPONSIBILITIES</span>
            <h2 class="c-title-card text-white text-xl mb-4">Authorized Use of Candidate Data</h2>
            <div class="c-body-text text-sm text-gray-300 space-y-3">
                <p>As an authorized Recruiter (HR) on Challora, you agree that:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Candidate profiles, CVs, diplomas, and contact details accessed through Challora may be used <strong class="text-white">strictly for legitimate recruitment evaluation</strong> within your organization.</li>
                    <li>You will not redistribute, publicly publish, scrape, sell, or use candidate personal or family data for unsolicited marketing, harassment, or unlawful discrimination.</li>
                    <li>You will keep your organization's <strong class="text-white">HR Invite Code</strong> confidential and rotate it immediately via the HR Dashboard if compromised.</li>
                </ul>
            </div>
        </section>

        <!-- 03. AI & Platform Disclaimer -->
        <section class="border border-[#202020] bg-[#090909] p-8">
            <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-2">// 03 &middot; AI SCORING &amp; LIABILITY DISCLAIMER</span>
            <h2 class="c-title-card text-white text-xl mb-4">No Guarantee of Employment or Candidate Fit</h2>
            <div class="c-body-text text-sm text-gray-300 space-y-3">
                <p><strong class="text-white">Advisory AI Outputs:</strong> AI-generated CV Fit Scores, candidate summaries, and job recommendations are automated estimates provided "as-is" to assist human review. Recruiters are solely responsible for verifying candidate qualifications and making final hiring decisions.</p>
                <p><strong class="text-white">Platform Availability:</strong> Challora does not guarantee uninterrupted availability or specific hiring outcomes for either Candidates or Recruiters.</p>
            </div>
        </section>

        <!-- 04. Account Termination -->
        <section class="border border-[#202020] bg-[#090909] p-8">
            <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-2">// 04 &middot; TERMINATION &amp; CONTACT</span>
            <h2 class="c-title-card text-white text-xl mb-4">Account Suspension &amp; Contact</h2>
            <div class="c-body-text text-sm text-gray-300 space-y-3">
                <p>Challora reserves the right to suspend or terminate accounts that submit fraudulent job postings, falsified documents, or abuse platform APIs.</p>
                <p>Questions regarding these Terms may be directed to <a href="mailto:admin.challora@gmail.com" class="text-[#FF4D30] underline">admin.challora@gmail.com</a>.</p>
            </div>
        </section>
    </div>
</div>
@endsection
