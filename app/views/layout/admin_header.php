<!DOCTYPE html>
<html lang="vi">
<head>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.add('light');
        }
    </script>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $data['title']; ?></title>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-container": "#1c1b19",
                        "on-surface-variant": "#46474a",
                        "on-secondary-fixed-variant": "#0040a2",
                        "secondary-fixed-dim": "#b2c5ff",
                        "outline": "#76777b",
                        "on-surface": "#191c1e",
                        "on-secondary": "#ffffff",
                        "on-background": "#191c1e",
                        "surface-container": "#edeef0",
                        "primary-container": "#1b1b1c",
                        "error-container": "#ffdad6",
                        "surface-container-lowest": "#ffffff",
                        "error": "#ba1a1a",
                        "surface-container-low": "#f3f4f6",
                        "secondary-container": "#356ee7",
                        "on-primary-container": "#858384",
                        "tertiary-fixed": "#e6e2de",
                        "primary-fixed-dim": "#c8c6c7",
                        "outline-variant": "#c7c6ca",
                        "background": "#f8f9fb",
                        "on-error-container": "#93000a",
                        "on-primary-fixed": "#1b1b1c",
                        "on-primary": "#ffffff",
                        "inverse-on-surface": "#f0f1f3",
                        "on-error": "#ffffff",
                        "surface-bright": "#f8f9fb",
                        "surface-variant": "#e1e2e4",
                        "secondary": "#0453cd",
                        "primary": "#000000",
                        "surface": "#f8f9fb",
                        "inverse-primary": "#c8c6c7",
                        "surface-container-high": "#e7e8ea",
                        "on-tertiary": "#ffffff",
                        "on-primary-fixed-variant": "#474647",
                        "surface-container-highest": "#e1e2e4",
                        "secondary-fixed": "#dae2ff",
                        "on-tertiary-fixed-variant": "#484644",
                        "on-tertiary-fixed": "#1c1b19",
                        "inverse-surface": "#2e3132",
                        "surface-tint": "#5f5e5f",
                        "primary-fixed": "#e5e2e3",
                        "on-tertiary-container": "#868380",
                        "tertiary-fixed-dim": "#cac6c2",
                        "tertiary": "#000000",
                        "on-secondary-container": "#fefcff",
                        "surface-dim": "#d9dadc",
                        "on-secondary-fixed": "#001848"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "3xl": "1.5rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "container-max": "1280px",
                        "card-gap": "32px",
                        "gutter": "24px",
                        "section-padding": "80px",
                        "unit": "8px"
                    },
                    "fontFamily": {
                        "price-display": ["Outfit", "sans-serif"],
                        "h2": ["Outfit", "sans-serif"],
                        "body-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "label-bold": ["Plus Jakarta Sans", "sans-serif"],
                        "h3": ["Outfit", "sans-serif"],
                        "h1": ["Outfit", "sans-serif"],
                        "body-md": ["Plus Jakarta Sans", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <script src="<?php echo URLROOT; ?>/js/media-selector.js?v=1.1.0"></script>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css">
</head>
<body class="bg-background text-on-background dark:bg-neutral-950 dark:text-neutral-200 min-h-screen w-full flex overflow-hidden">
