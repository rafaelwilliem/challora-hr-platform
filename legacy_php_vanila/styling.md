# Panduan styling — Challora

Dokumen ringkas untuk menjaga konsistensi warna, token, dan CSS lokal di view PHP.

---

## 1) Struktur styling project (layer)

Alur dari global ke spesifik halaman:

| Layer | Lokasi / bentuk | Peran |
|--------|------------------|--------|
| **Token** | `public/assets/css/design-tokens.css` | Variabel `:root` (`--color-*`, `--gray-*`, `--radius-*`, `--shadow-*`, alias `--primary`, dll.) + base `html, body`. Satu sumber kebenaran untuk tema. |
| **Utility & map** | File yang sama (bagian bawah) | Kelas siap pakai: `bg-primary`, `text-muted`, `sem-*`, serta **compatibility map** Tailwind (`bg-gray-100` → token). |
| **Komponen / lokal view** | `<style>` inline di view (mis. `app/views/user/jobs/index.php`) atau CSS terpisah jika nanti dipisah | Layout grid, komponen hanya dipakai halaman itu. **Wajib** memakai `var(--...)` dari token, bukan hex baru. |

Urutan muat: token harus dimuat **sebelum** Tailwind / stylesheet lain (sesuai komentar di `design-tokens.css`).

---

## 2) Aturan penggunaan color token

- **Jangan** menambah warna hardcoded (`#rrggbb`, `rgb()`, nama warna) di view/CSS baru kecuali benar-benar sekali pakai dan sudah direncanakan jadi token.
- **Pakai** `var(--color-…)` atau `var(--gray-…)` di CSS custom; di markup, utamakan utility yang sudah ada (`bg-surface`, `text-secondary`, dll.) bila cocok.
- **Kontras & “on color”**: teks di atas primary/secondary/accent pakai `--color-on-primary`, `--color-on-secondary`, `--color-on-accent` agar tema bisa diganti aman.
- **Semantic UI** (error, sukses, info): pakai pasangan `--color-*-bg` / `--color-*-text` atau kelas `*-soft` / `text-danger`, dll. dari file token.
- **Bayangan & radius**: `var(--shadow-sm|md|lg)`, `var(--radius-…)` — hindari nilai piksel acak yang menduplikasi arti yang sama.
- Jika butuh **opacity / campuran**, prefer `color-mix(in srgb, var(--color-surface) 20%, transparent)` (sudah dipakai di beberapa view) daripada hex semi-transparan baru.

---

## 3) Mapping token penting

### Brand

| Token | Kegunaan singkat |
|--------|-------------------|
| `--color-primary`, `--color-primary-hover`, `--color-primary-muted` | Latar/aksen brand terang, hover, area lembut |
| `--color-secondary`, `--color-secondary-hover`, `--color-secondary-muted` | Brand gelap, navigasi kuat, teks kontras terang |
| `--color-accent`, `--color-accent-hover`, `--color-accent-muted` | Aksen kuning/emphasis, CTA sekunder |
| `--color-on-primary`, `--color-on-secondary`, `--color-on-accent` | Teks/ikon di atas masing-masing warna dasar |

### Teks & permukaan

| Token | Kegunaan |
|--------|-----------|
| `--color-text`, `--color-text-muted` | Teks utama & sekunder |
| `--color-surface` | Latar kartu/halaman putih |
| `--color-border` | Border netral default |

### Netral (gray scale)

| Token | Kegunaan |
|--------|-----------|
| `--gray-50` … `--gray-900` | Hierarki netral (placeholder kompatibilitas dengan kelas Tailwind yang sudah di-map) |

### Semantic

| Token | Kegunaan |
|--------|-----------|
| `--color-danger-bg` / `--color-danger-text` | Error |
| `--color-success-bg` / `--color-success-text` | Sukses |
| `--color-warning-bg` / `--color-warning-text` | Peringatan |
| `--color-info-bg` / `--color-info-text` | Info |
| `--color-sky-bg` / `--color-sky-text` | Variasi info/highlight lembut |

### Alias cepat (stylesheet lama / auth)

`--primary`, `--secondary`, `--accent`, `--text`, `--muted`, `--danger-bg`, `--success-bg`, dll. mengacu ke token di atas.

---

## 4) Konvensi naming class lokal per halaman

- **Prefix halaman** + **bagian**: contoh nyata `jobs-search-sticky`, `jobs-filter-opt`, `job-card`, `job-title` — konsisten dengan route/feature (`jobs`, `job`).
- Gunakan **kebab-case**; hindari class generik satu kata (`title`, `card`) tanpa prefix agar tidak bentrok global.
- Satu blok `<style>` di atas konten view itu boleh; kelompokkan selector terkait berurutan (layout → komponen → state → media query).
- **Variabel layout** yang sudah dipakai project (mis. `--user-content-pad-x`, `--user-bar-pad-x`) tetap dipakai untuk alignment dengan layout user; jangan mengganti nama sembarangan tanpa update layout induk.

---

## 5) Workflow implementasi UI (tamvan-dan-berani)

1. Baca requirement layout/state (desktop/tablet/mobile).
2. Cek `design-tokens.css`: apakah sudah ada utility/kelas yang cukup? Jika ya, pakai di HTML; jika tidak, tulis CSS lokal dengan **`var(--token)`**.
3. Susun struktur HTML dulu, lalu tambah class prefix halaman.
4. Di `<style>` lokal: grid/flex, spacing, ukuran khusus — **warna hanya dari token**.
5. Tambah `@media` breakpoints mengikuti pola halaman lain (contoh: 1300 / 1024 / 640 seperti jobs).
6. Uji hover/focus (`:focus-visible` untuk aksesibilitas checkbox/link).
7. Jika warna baru benar-benar dibutuhkan: **tambahkan dulu ke `design-tokens.css`**, baru dipakai di view (hindari sekali pakai tersebar).

---

## 6) Checklist QA visual sebelum merge

- [ ] Tidak ada hex/rgb warna baru di view kecuali sudah ada di token.
- [ ] Teks di atas latar gelap/terang memakai pasangan `on-*` atau kontras yang jelas.
- [ ] Hover/active state tidak “hilang” atau kontras turun drastis.
- [ ] Breakpoint utama: layout tidak pecah (overflow horizontal, sticky yang menutupi konten).
- [ ] Focus keyboard terlihat untuk input/tombol custom.
- [ ] Typography dan spacing selaras dengan halaman user lain (padding konten vs. `--user-*`).
- [ ] Cepat cek satu level zoom browser (110%) — tidak tabrakan parah.

---

## 7) Contoh: hardcoded → token

**Sebelum (hindari):**

```css
.job-banner {
  background: #011627;
  color: #ffffff;
  border: 1px solid #dbe2ea;
}
.cta-pill {
  background: #ffd166;
  color: #011627;
}
```

**Sesudah (disarankan):**

```css
.job-banner {
  background: var(--color-secondary);
  color: var(--color-surface);
  border: 1px solid var(--color-border);
}
.cta-pill {
  background: var(--color-accent);
  color: var(--color-on-accent);
}
```

**Di HTML**, jika tidak perlu selector kustom, boleh langsung utility dari token file, mis.: `bg-secondary text-on-secondary`, `bg-accent text-on-accent`, `border-default`.

---

*Referensi utama: `public/assets/css/design-tokens.css`. Contoh pola view + token: `app/views/user/jobs/index.php`.*
