<!DOCTYPE html>
<html lang="en" class="bg-slate-50 dark:bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'DuckyCMS' ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4.0.0-alpha.12/tailwindcss.js" defer></script>
    <script src="https://unpkg.com/htmx.org@1.9.10" defer></script>
    <script type="text/javascript" src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-primary: #4f46e5;
            --color-secondary: #10b981;
        }
        .btn { @apply font-bold py-2 px-4 rounded transition-colors; }
        .btn-primary { @apply bg-primary text-white hover:bg-indigo-700; }
    </style>
</head>
<body class="font-sans text-slate-800 dark:text-slate-200">
    <div class="flex min-h-screen">
