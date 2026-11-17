@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Box -->
    <div class="border border-[#202020] bg-[#090909] p-8 md:p-12 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#1c1c1c] pb-4 mb-6">
            <span class="c-mono text-xs text-[#FF4D30] font-bold uppercase tracking-widest">// LEGAL &amp; COMPLIANCE PROTOCOL</span>
            <span class="c-mono text-xs text-gray-500">LAST UPDATED: NOVEMBER 2026</span>
        </div>
        <h1 class="c-title-section text-white text-3xl sm:text-5xl mb-4">Privacy, AI &amp; Cookie Policy</h1>
        <p class="c-body-text text-gray-400 text-base">
            This policy explains how Challora HR Platform ("Challora", "we", "our") collects, stores, processes, and protects personal data from Candidates and Recruiters (HR), including our use of artificial intelligence and essential cookies, in accordance with applicable data protection laws (including Indonesia's UU PDP No. 27 Tahun 2022 and GDPR principles).
        </p>
    </div>

    <div class="space-y-6">
        <!-- 01. Data We Collect -->
        <section class="border border-[#202020] bg-[#090909] p-8">
            <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-2">// 01 &middot; DATA COLLECTION</span>
            <h2 class="c-title-card text-white text-xl mb-4">Personal Data We Collect</h2>
            <div class="c-body-text text-sm text-gray-300 space-y-3">
                <p><strong class="text-white">A. Account &amp; Identity Data:</strong> Full name, email address, phone number, account role (Candidate or Recruiter), and bcrypt-hashed password.</p>
                <p><strong class="text-white">B. Professional &amp; Academic Dossier:</strong> Professional summary, skills, educational background (level, major, university, graduation year), work experiences, organizational experiences, and achievements/certifications.</p>
                <p><strong class="text-white">C. Uploaded Documents:</strong> Curriculum Vitae (CV/Resume PDF), educational diplomas (PDF), formal portrait photos, and profile avatars stored in private, access-controlled storage.</p>
                <p><strong class="text-white">D. Optional Demographic &amp; Family Information:</strong> Home address, birth place/date, gender, religion, marital status, parental details (names, occupations, education, phone numbers), and emergency contact information—provided voluntarily by candidates for employer onboarding dossiers.</p>
            </div>
        </section>

        <!-- 02. How Data is Used & Shared -->
        <section class="border border-[#202020] bg-[#090909] p-8">
            <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-2">// 02 &middot; PURPOSE &amp; ACCESS CONTROL</span>
            <h2 class="c-title-card text-white text-xl mb-4">How Your Data Is Used &amp; Who Can Access It</h2>
            <div class="c-body-text text-sm text-gray-300 space-y-3">
                <p><strong class="text-white">Strict Recruiter Scoping:</strong> Your application and uploaded documents (CV, diploma, photo) are only accessible to you and the specific Recruiter (HR) who created the job listing you applied to.</p>
                <p><strong class="text-white">Private Document Storage:</strong> Candidate documents are stored on a non-public disk and streamed exclusively through authenticated, role-verified server routes.</p>
                <p><strong class="text-white">No Sale of Personal Data:</strong> Challora never sells, rents, or trades candidate or recruiter personal information to advertisers or data brokers.</p>
            </div>
        </section>

        <!-- 03. AI Processing Disclosure -->
        <section id="ai-disclosure" class="border border-[#FF4D30]/50 bg-[#090909] p-8">
            <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-2">// 03 &middot; AI &amp; AUTOMATED PROCESSING DISCLOSURE</span>
            <h2 class="c-title-card text-white text-xl mb-4">How Chally AI Processes Candidate Profiles</h2>
            <div class="c-body-text text-sm text-gray-300 space-y-3">
                <p>Challora integrates an internal AI microservice (<strong class="text-white">CHALLY HR AI</strong>) powered by Large Language Models (<strong class="text-white">Groq API</strong>) to provide recruitment intelligence:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong class="text-white">What is sent to the AI service:</strong> Candidate name, professional summary, skills list, education history, work/organizational experiences, achievements, and the target job description.</li>
                    <li><strong class="text-white">What is NOT sent to the AI service:</strong> Passwords, parental/family details, emergency contacts, religion, marital status, phone numbers, or raw PDF binary files.</li>
                    <li><strong class="text-white">Human-in-the-Loop Guarantee:</strong> AI-generated CV Fit Scores, pros/cons summaries, and recommendations are <em class="text-white">assistive decision-support tools only</em>. Final screening, interview, and hiring decisions are always made by human recruiters.</li>
                </ul>
            </div>
        </section>

        <!-- 04. Cookie Policy -->
        <section id="cookies" class="border border-[#202020] bg-[#090909] p-8">
            <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-2">// 04 &middot; COOKIE POLICY</span>
            <h2 class="c-title-card text-white text-xl mb-4">Cookies &amp; Session Storage</h2>
            <div class="c-body-text text-sm text-gray-300 space-y-3">
                <p>Challora uses <strong class="text-white">strictly necessary first-party cookies</strong> required for authentication and security. We do not use third-party advertising, behavioral profiling, or cross-site tracking cookies.</p>
                <div class="overflow-x-auto mt-4">
                    <table class="w-full text-left border border-[#222] c-mono text-xs">
                        <thead class="bg-[#111] text-white border-b border-[#222]">
                            <tr>
                                <th class="p-3">COOKIE NAME</th>
                                <th class="p-3">CATEGORY</th>
                                <th class="p-3">PURPOSE &amp; LIFESPAN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1c1c1c] text-gray-400">
                            <tr>
                                <td class="p-3 text-white">laravel_session</td>
                                <td class="p-3 text-[#4ade80]">Strictly Necessary</td>
                                <td class="p-3">Maintains your authenticated login session across page requests (120 mins).</td>
                            </tr>
                            <tr>
                                <td class="p-3 text-white">XSRF-TOKEN</td>
                                <td class="p-3 text-[#4ade80]">Security</td>
                                <td class="p-3">Protects form submissions against Cross-Site Request Forgery (CSRF) attacks.</td>
                            </tr>
                            <tr>
                                <td class="p-3 text-white">remember_web_*</td>
                                <td class="p-3 text-gray-300">Functional (Opt-in)</td>
                                <td class="p-3">Keeps you signed in when you explicitly check "Remember Me" at login.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- 05. Third-Party Sub-processors -->
        <section class="border border-[#202020] bg-[#090909] p-8">
            <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-2">// 05 &middot; INFRASTRUCTURE &amp; SUB-PROCESSORS</span>
            <h2 class="c-title-card text-white text-xl mb-4">Third-Party Service Providers</h2>
            <div class="c-body-text text-sm text-gray-300 space-y-2">
                <p>To operate the platform, Challora relies on the following sub-processors bound by confidentiality and security standards:</p>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li><strong class="text-white">Groq Cloud API:</strong> Processes structured text prompts for candidate scoring, summaries, and job matching.</li>
                    <li><strong class="text-white">Google / SMTP Mail Relay:</strong> Delivers transactional password-reset links and application status notifications.</li>
                    <li><strong class="text-white">Cloud Hosting &amp; Managed MySQL/TiDB:</strong> Hosts application containers and encrypted database connections.</li>
                </ul>
            </div>
        </section>

        <!-- 06. User Rights & Data Deletion -->
        <section class="border border-[#202020] bg-[#090909] p-8">
            <span class="c-mono text-[11px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-2">// 06 &middot; YOUR RIGHTS &amp; DATA ERASURE</span>
            <h2 class="c-title-card text-white text-xl mb-4">Data Retention, Access &amp; Right to Deletion</h2>
            <div class="c-body-text text-sm text-gray-300 space-y-3">
                <p><strong class="text-white">Self-Service Updates &amp; Deletion:</strong> You may view, edit, or permanently delete your account, applications, and uploaded documents at any time from your <strong class="text-white">Profile Settings</strong> page using the <em>Delete My Account</em> action.</p>
                <p><strong class="text-white">Privacy Inquiries:</strong> For data export requests, privacy questions, or compliance inquiries, contact our Data Protection administrator at <a href="mailto:admin.challora@gmail.com" class="text-[#FF4D30] underline">admin.challora@gmail.com</a>.</p>
            </div>
        </section>
    </div>
</div>
@endsection
