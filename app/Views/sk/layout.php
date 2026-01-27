<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title><?= $title ?? 'SK Panel' ?></title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3b82f6">
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => console.log('SW registered'))
                    .catch(error => console.log('SW registration failed'));
            });
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #eff6ff;
            overflow-x: hidden;
        }
        @media (min-width: 768px) and (max-width: 1023px) {
            main {
                padding-bottom: env(safe-area-inset-bottom, 7rem);
            }
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">

<?php $page = $page ?? ''; ?>

<?php
function navItem($href, $icon, $label, $active, $page) {
    $cls = ($page === $active)
        ? 'bg-blue-700 font-semibold'
        : 'bg-blue-600 hover:bg-blue-500';

    return "
    <a href='$href' class='flex items-center gap-3 px-3 py-2 rounded-lg transition $cls'>
        <i class='$icon'></i>
        <span>$label</span>
    </a>";
}
?>
<div id="mobileDrawer" class="fixed inset-0 z-40 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" id="drawerBackdrop"></div>
    <div class="absolute left-0 top-0 h-full w-64 bg-blue-600 text-white shadow-xl transform -translate-x-full transition-transform duration-300" id="drawerContent">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-10">E-Learning Resource Reservation</h1>
            <nav class="space-y-4">
                <?php
                echo navItem(base_url('sk/dashboard'),'fa-solid fa-house','Dashboard','dashboard',$page);
                echo navItem(base_url('sk/reservations'),'fa-solid fa-list','Reservations','reservations',$page);
                echo navItem(base_url('sk/new-reservation'),'fa-solid fa-plus','New Reservation','new-reservation',$page);
                echo navItem(base_url('sk/scanner'),'fa-solid fa-qrcode','Scanner','scanner',$page);
                echo navItem(base_url('sk/profile'),'fa-regular fa-user','Profile','profile',$page);
                ?>
            </nav>
        </div>
    </div>
</div>

<div class="flex flex-1">
    <aside class="hidden lg:flex flex-col w-64 bg-blue-600 text-white shadow-xl p-6">
        <h1 class="text-2xl font-bold mb-10">E-Learning Resource Reservation</h1>

        <nav class="space-y-3">
            <?php
            echo navItem(base_url('sk/dashboard'),'fa-solid fa-house','Dashboard','dashboard',$page);
            echo navItem(base_url('sk/reservations'),'fa-solid fa-list','Reservations','reservations',$page);
            echo navItem(base_url('sk/new-reservation'),'fa-solid fa-plus','New Reservation','new-reservation',$page);
            echo navItem(base_url('sk/scanner'),'fa-solid fa-qrcode','Scanner','scanner',$page);
            echo navItem(base_url('sk/profile'),'fa-regular fa-user','Profile','profile',$page);
            ?>
        </nav>
    </aside>
    <main class="flex-1 p-4 lg:p-6 overflow-auto">
        <?= $this->renderSection('content') ?>
    </main>

</div>
<nav class="fixed bottom-0 left-0 right-0 bg-white shadow-xl lg:hidden z-50">
    <div class="flex overflow-x-auto gap-1 p-2">

        <?php
        function mobileNav($href, $icon, $label, $active, $page) {
            $cls = ($page === $active)
                ? 'bg-blue-700 font-semibold text-white'
                : 'hover:bg-blue-500 text-blue-600';

    return "
    <a href='$href' class='flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 flex-1 $cls'>
        <i class='$icon text-lg'></i>
        <span class='text-[11px] mt-1 text-center leading-tight'>$label</span>
    </a>";
        }

        echo mobileNav(base_url('sk/dashboard'),'fa-solid fa-house','Dashboard','dashboard',$page);
        echo mobileNav(base_url('sk/reservations'),'fa-solid fa-list','Reservations','reservations',$page);
        echo mobileNav(base_url('sk/new-reservation'),'fa-solid fa-plus','New Reservation','new-reservation',$page);
        echo mobileNav(base_url('sk/scanner'),'fa-solid fa-qrcode','Scanner','scanner',$page);
        echo mobileNav(base_url('sk/profile'),'fa-regular fa-user','Profile','profile',$page);
        ?>

    </div>
</nav>

</body>
</html>
