# Flow Logic Regression Checklist (`app/views`)

## User Flow
- Open `jobs` list, combine search + sidebar filters, then move to page 2 and back.
- Save and unsave job from `jobs/index`, `jobs/show`, and `jobs/saved`; verify redirect target remains correct.
- Apply to job from `jobs/show`; verify modal flow still works and missing-document guard still blocks apply.
- Open `applications` list and verify status badge labels map correctly for known and unknown statuses.
- Edit `user/settings` profile and verify one education entry is persisted as expected.

## HR Flow
- Open `hr/jobs` and change `per_page`; verify listing and pagination reflect selected page size.
- Verify HR dashboard no longer shows pseudo-random stage distribution.
- Open `hr/jobs/applicants?id=...` and update status to `reviewed/accepted/rejected`.
- When automatic email is unavailable, verify `Kirim Email Manual` button appears and mailto link is valid.
- Open `download/berkas` page with and without applicant documents; verify empty-state behavior.

## Auth Flow
- Trigger register success then login page; verify flash type and message render correctly.
- Submit forgot-password with valid/invalid email; verify message priority and fallback link behavior.
- Open reset page with invalid token and valid token; verify form only appears for valid token.
- Submit reset with validation errors (short password/mismatch) and ensure form remains usable.
