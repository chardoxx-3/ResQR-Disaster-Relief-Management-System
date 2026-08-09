<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResQR | Command Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 240px;
            --brand-green: #77BC3F;
            --brand-green-deep: #4a7a26;
            --brand-orange: #F58220;
            --active-bg: #f0fdf4;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --bg: #f8fafc;
            --mobile-nav-height: 60px;
            --radius: 12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text-main);
            font-size: 0.875rem;
        }

        /* ══════════════════════════════
           SIDEBAR — Desktop
        ══════════════════════════════ */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--surface);
            position: fixed;
            left: 0; top: 0;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: transform 0.28s cubic-bezier(.4,0,.2,1);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: 100vh;
            transition: margin-left 0.28s cubic-bezier(.4,0,.2,1);
        }

        /* ── Sidebar Header ── */
        .sidebar-header {
            padding: 1rem 1.1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .sidebar-logo { height: 36px; width: auto; }
        .brand-name { font-size: 1rem; font-weight: 800; color: var(--brand-green-deep); line-height: 1; }
        .brand-tag  { font-size: 0.58rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }

        /* ── Nav Wrapper ── */
        .nav-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem 0 0.5rem;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }
        .nav-wrapper::-webkit-scrollbar { width: 4px; }
        .nav-wrapper::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .nav-section-label {
            font-size: 0.58rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #94a3b8;
            font-weight: 700;
            padding: 0.9rem 1.1rem 0.3rem;
        }

        .nav-link {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.78rem;
            padding: 0.48rem 1rem;
            border-radius: 8px;
            margin: 0.1rem 0.6rem;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.18s ease;
        }
        .nav-link i { width: 20px; font-size: 0.88rem; flex-shrink: 0; }
        .nav-link:hover { background: #f8fafc; color: var(--brand-orange); }
        .nav-link.active {
            background: var(--active-bg);
            color: var(--brand-green-deep) !important;
            font-weight: 700;
            box-shadow: inset 3px 0 0 var(--brand-orange);
        }

        /* ── Sidebar Footer ── */
        .sidebar-footer {
            padding: 0.75rem 1.1rem;
            border-top: 1px solid var(--border);
            background: #fafafa;
            flex-shrink: 0;
        }
        .footer-tagline { font-size: 0.52rem; color: #94a3b8; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 6px; text-transform: uppercase; }
        .logout-btn {
            color: var(--text-muted);
            padding: 0.45rem 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 0.78rem;
            text-decoration: none;
            transition: color 0.15s;
        }
        .logout-btn:hover { color: #dc2626; }

        /* ── Overlay ── */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.35);
            z-index: 1045;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active { display: block; }

        /* ══════════════════════════════
           MOBILE TOP BAR
        ══════════════════════════════ */
        .mobile-header {
            display: none;
            background: var(--surface);
            padding: 0 1rem;
            height: var(--mobile-nav-height);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 1040;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 6px rgba(0,0,0,.06);
        }

        .mobile-header .mob-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .hamburger-btn {
            width: 36px; height: 36px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-main);
            font-size: 0.9rem;
            transition: all 0.15s;
        }
        .hamburger-btn:hover { background: var(--brand-green); color: #fff; border-color: var(--brand-green); }

        .mob-brand { font-size: 0.9rem; font-weight: 800; color: var(--brand-green-deep); }
        .mob-logo  { height: 28px; width: auto; }

        /* ══════════════════════════════
           MOBILE BOTTOM NAV
        ══════════════════════════════ */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: var(--mobile-nav-height);
            background: var(--surface);
            border-top: 1px solid var(--border);
            z-index: 1040;
            padding: 0 0.25rem;
            box-shadow: 0 -2px 12px rgba(0,0,0,.07);
        }
        .bottom-nav-inner {
            display: flex;
            align-items: stretch;
            height: 100%;
        }
        .bnav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.58rem;
            font-weight: 600;
            padding: 0 4px;
            border-radius: 10px;
            margin: 4px 2px;
            transition: all 0.15s;
            position: relative;
        }
        .bnav-item i { font-size: 1.1rem; transition: transform 0.2s; }
        .bnav-item:hover { color: var(--brand-green); }
        .bnav-item.active {
            color: var(--brand-green-deep);
        }
        .bnav-item.active i { transform: translateY(-1px); }
        .bnav-item.active::before {
            content: '';
            position: absolute;
            top: 0; left: 20%; right: 20%;
            height: 2.5px;
            background: var(--brand-green);
            border-radius: 0 0 3px 3px;
        }
        .bnav-item.bnav-scan {
            background: linear-gradient(135deg, var(--brand-green-deep), var(--brand-green));
            color: #fff !important;
            flex: 0 0 52px;
            border-radius: 14px;
            margin: 6px 4px;
            box-shadow: 0 4px 14px rgba(74,122,38,.35);
        }
        .bnav-item.bnav-scan i { font-size: 1.2rem; }
        .bnav-item.bnav-scan::before { display: none; }

        /* ══════════════════════════════
           PAGE TITLE (desktop)
        ══════════════════════════════ */
        .page-title-bar {
            margin-bottom: 1rem;
        }
        .page-title-bar h5 {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 0;
            letter-spacing: -0.3px;
        }
        .page-title-bar small {
            font-size: 0.68rem;
            color: var(--text-muted);
        }

        /* ══════════════════════════════
           RESPONSIVE BREAKPOINTS
        ══════════════════════════════ */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 270px;
                box-shadow: none;
            }
            .sidebar.show {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,.12);
            }
            .main-content {
                margin-left: 0;
                padding: 1rem 0.875rem;
                padding-bottom: calc(var(--mobile-nav-height) + 0.5rem);
            }
            .mobile-header { display: flex; }
            .bottom-nav { display: block; }
            .page-title-bar { display: none; }
        }

        @media (max-width: 480px) {
            .main-content { padding: 0.75rem 0.75rem; }
        }

        /* ══════════════════════════════
   DESKTOP SIDEBAR TOGGLE
══════════════════════════════ */
.desktop-toggle-btn {
    position: fixed;
    left: var(--sidebar-width);
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 48px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-left: none;
    border-radius: 0 8px 8px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1060;
    transition: all 0.28s cubic-bezier(.4,0,.2,1);
    color: var(--text-muted);
    font-size: 0.75rem;
}

.desktop-toggle-btn:hover {
    background: var(--brand-green);
    color: #fff;
    border-color: var(--brand-green);
}

/* Sidebar collapsed state (desktop) */
body.sidebar-collapsed .sidebar {
    transform: translateX(calc(-1 * var(--sidebar-width)));
}

body.sidebar-collapsed .main-content {
    margin-left: 0;
}

body.sidebar-collapsed .desktop-toggle-btn {
    left: 0;
}

body.sidebar-collapsed .desktop-toggle-btn i {
    transform: rotate(180deg);
}

/* Ensure desktop toggle doesn't show on mobile */
@media (max-width: 992px) {
    .desktop-toggle-btn {
        display: none;
    }
}
    </style>
</head>
<body>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="overlay"></div>

    <!-- ══ Mobile Top Header ══ -->
    <header class="mobile-header">
        <div class="mob-left">
            <button class="hamburger-btn" id="toggleSidebar" aria-label="Toggle menu">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="mob-brand">ResQR</span>
        </div>
        <img src="/uploads/logo.png" alt="ResQR Logo" class="mob-logo">
    </header>

    <!-- ══ Sidebar ══ -->
    <div class="sidebar" id="sidebar">

        <!-- Header -->
        <div class="sidebar-header">
            <img src="/uploads/logo.png" alt="ResQR Logo" class="sidebar-logo">
            <div>
                <div class="brand-name">ResQR</div>
                <div class="brand-tag">Aksyon at Kalinga</div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="nav-wrapper">
            <nav>
                <?php
                    $userRole = session()->get('role');
                    $currentUri = uri_string();
                ?>

                <div class="nav-section-label">General</div>
                <a href="/dashboard" class="nav-link <?= ($currentUri == 'dashboard') ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>

                <?php if($userRole != 'distributor'): ?>
                    <div class="nav-section-label">Data</div>
                    <a href="/beneficiaries/resident-list" class="nav-link <?= ($currentUri == 'beneficiaries/resident-list') ? 'active' : '' ?>">
                        <i class="fa-solid fa-address-book"></i> Residents
                    </a>
                    <a href="/beneficiaries" class="nav-link <?= ($currentUri == 'beneficiaries') ? 'active' : '' ?>">
                        <i class="fa-solid fa-users"></i> Beneficiaries
                    </a>
                    <a href="/attendance" class="nav-link <?= ($currentUri == 'attendance') ? 'active' : '' ?>">
                        <i class="fa-solid fa-calendar-check"></i> Attendance
                    </a>
                        <a href="/smart-kitchen/monitor" class="nav-link <?= (strpos($currentUri, 'smart-kitchen/monitor') === 0) ? 'active' : '' ?>">
        <i class="fa-solid fa-kitchen-set"></i> Kitchen Monitor
    </a>

                    <div class="nav-section-label">Admin</div>
                    <a href="/events" class="nav-link <?= ($currentUri == 'events' || strpos($currentUri, 'events/') === 0) ? 'active' : '' ?>">
                        <i class="fa-solid fa-calendar-days"></i> Events
                    </a>
                    <a href="/users" class="nav-link <?= ($currentUri == 'users') ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-gear"></i> Accounts
                    </a>
                <?php endif; ?>

                <div class="nav-section-label">Logistics</div>
                <a href="/inventory" class="nav-link <?= ($currentUri == 'inventory' || strpos($currentUri, 'inventory/') === 0) ? 'active' : '' ?>">
                    <i class="fa-solid fa-boxes-stacked"></i> Inventory
                </a>
                <a href="/reports" class="nav-link <?= ($currentUri == 'reports') ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-contract"></i> Reports
                </a>

                <div class="nav-section-label">Ops</div>
                <?php if($userRole == 'distributor'): ?>
                    <a href="/distribution/scanner" class="nav-link <?= ($currentUri == 'distribution/scanner') ? 'active' : '' ?>">
                        <i class="fa-solid fa-qrcode"></i> Quick Scan
                    </a>
                <?php endif; ?>
                <a href="/scanner/settings" class="nav-link <?= ($currentUri == 'scanner/settings') ? 'active' : '' ?>">
                    <i class="fa-solid fa-sliders"></i> Settings
                </a>
            </nav>
        </div>

        <!-- Footer -->
        <div class="sidebar-footer">
            <div class="footer-tagline">Ligtas sa Serbisyong Digital</div>
            <a href="/auth/logout" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>

    <!-- ══ Main Content ══ -->
    <main class="main-content">
        <!-- Desktop Sidebar Toggle Button -->
<button class="desktop-toggle-btn" id="desktopToggleSidebar" aria-label="Toggle sidebar">
    <i class="fa-solid fa-chevron-left"></i>
</button>
        <div class="container-fluid px-0">
            <!-- Page Title (desktop only) -->
<div class="page-title-bar d-none d-lg-block">
    <h5><?= ucfirst(str_replace(['/', '-'], [' › ', ' '], $currentUri)) ?></h5>
</div>
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar   = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const overlay   = document.getElementById('overlay');

        function openSidebar() {
            sidebar.classList.add('show');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
        });

        overlay.addEventListener('click', closeSidebar);

        // Close sidebar on nav link click (mobile)
        sidebar.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 992) closeSidebar();
            });
        });

        // "More" button opens sidebar as a full menu on mobile
        const moreBtn = document.getElementById('moreMenuBtn');
        if (moreBtn) {
            moreBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openSidebar();
            });
        }

        // Close sidebar on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) closeSidebar();
        });

        // Desktop sidebar toggle
const desktopToggleBtn = document.getElementById('desktopToggleSidebar');
const body = document.body;

// Check localStorage for saved sidebar state
const sidebarState = localStorage.getItem('sidebarCollapsed');
if (sidebarState === 'true') {
    body.classList.add('sidebar-collapsed');
    const toggleIcon = desktopToggleBtn?.querySelector('i');
    if (toggleIcon) toggleIcon.classList.replace('fa-chevron-left', 'fa-chevron-right');
}

// Desktop toggle functionality
if (desktopToggleBtn) {
    desktopToggleBtn.addEventListener('click', function() {
        body.classList.toggle('sidebar-collapsed');
        
        // Change icon direction
        const icon = this.querySelector('i');
        if (body.classList.contains('sidebar-collapsed')) {
            icon.classList.remove('fa-chevron-left');
            icon.classList.add('fa-chevron-right');
            localStorage.setItem('sidebarCollapsed', 'true');
        } else {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-left');
            localStorage.setItem('sidebarCollapsed', 'false');
        }
    });
}

// Ensure mobile sidebar behavior still works correctly
// Override the existing resize handler to respect desktop toggle
const originalResizeHandler = window.resize;
window.addEventListener('resize', function() {
    if (window.innerWidth > 992) {
        // On desktop, restore saved sidebar state
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            body.classList.add('sidebar-collapsed');
        } else {
            body.classList.remove('sidebar-collapsed');
        }
        // Ensure mobile sidebar is closed
        closeSidebar();
    } else {
        // On mobile, remove collapsed class to show full width
        body.classList.remove('sidebar-collapsed');
    }
});
    </script>
</body>
</html>