<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'HR Recruitment') ?></title>
  <!-- Bootstrap (masih digunakan di beberapa bagian, terutama HR) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Token tema (warna, font, radius) — edit di public/assets/css/design-tokens.css -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/design-tokens.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['var(--font-sans)'],
          },
          borderRadius: {
            brand: 'var(--radius-xl)',
            'brand-lg': 'var(--radius-2xl)',
          },
          boxShadow: {
            brand: 'var(--shadow-md)',
            'brand-lg': 'var(--shadow-lg)',
          },
          colors: {
            primary: {
              DEFAULT: 'var(--color-primary)',
              hover: 'var(--color-primary-hover)',
              muted: 'var(--color-primary-muted)',
            },
            secondary: {
              DEFAULT: 'var(--color-secondary)',
              hover: 'var(--color-secondary-hover)',
            },
            accent: {
              DEFAULT: 'var(--color-accent)',
              hover: 'var(--color-accent-hover)',
            },
          },
        },
      },
    };
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="antialiased bg-primary text-default" style="min-height: 100vh; font-family: var(--font-sans);">