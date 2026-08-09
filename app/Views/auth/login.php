<?= $this->extend('layout/auth_layout') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --brand-green: #77BC3F;
        --brand-orange: #F58220;
        --brand-red: #E31E24;
        --soft-bg: #F8FAFC;
        --text-dark: #1e293b;
    }

    /* Force full-screen edge-to-edge */
    html, body {
        height: 100vh;
        width: 100vw;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .auth-wrapper {
        display: flex;
        height: 100vh;
        width: 100%;
    }

    /* Left Side: Login Form */
    .auth-form-section {
        flex: 1;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        z-index: 10;
    }

    .form-content {
        width: 100%;
        max-width: 420px;
    }

    .brand-container {
        margin-bottom: 30px;
    }

    .brand-logo {
        width: 100px;
        margin-bottom: 15px;
    }

    /* System Name Styling */
    .system-name-main {
        font-size: 1.8rem;
        font-weight: 900;
        color: var(--brand-green);
        letter-spacing: -1px;
        margin-bottom: 0;
    }

    .system-tagline {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .auth-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-top: 30px;
        margin-bottom: 8px;
    }

    .auth-subtitle {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 30px;
    }

    .form-control {
        padding: 14px 18px;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        background: #fdfdfd;
        font-size: 0.95rem;
    }

    .form-control:focus {
        border-color: var(--brand-green);
        box-shadow: 0 0 0 4px rgba(119, 188, 63, 0.1);
    }

    .btn-signin {
        background: var(--brand-orange);
        border: none;
        padding: 16px;
        border-radius: 12px;
        font-weight: 700;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-signin:hover {
        background: #e0751b;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(245, 130, 32, 0.2);
    }

    /* Right Side: Green Hero */
    .auth-hero-section {
        flex: 1.3;
        background: linear-gradient(135deg, var(--brand-green) 0%, #5a912f 100%);
        position: relative;
        display: flex;
        align-items: flex-end;
        padding: 80px;
        color: white;
        overflow: hidden;
    }

    /* Decorative circles from reference */
    .auth-hero-section::before {
        content: '';
        position: absolute;
        top: -10%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .hero-text-content {
        position: relative;
        z-index: 5;
    }

    .hero-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 25px;
        display: inline-block;
        backdrop-filter: blur(5px);
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 20px;
    }

    .hero-desc {
        font-size: 1.15rem;
        opacity: 0.9;
        max-width: 500px;
        line-height: 1.6;
    }

    .avatar-stack div {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 2px solid white;
        margin-left: -10px;
        background-color: rgba(255,255,255,0.3);
    }

    @media (max-width: 992px) {
        .auth-hero-section { display: none; }
    }
</style>

<div class="auth-wrapper">
    <!-- Left Section -->
    <div class="auth-form-section">
        <div class="form-content">
            <div class="brand-container text-center text-lg-start">
                <img src="/uploads/logo.png" alt="ResQR Logo" class="brand-logo">
                <h1 class="system-name-main">ResQR</h1>
                <p class="system-tagline">Aksyon at Kalingang Lokal, Ligtas sa Serbisyong Digital</p>
            </div>
            
            <h2 class="auth-title">Welcome Back</h2>
            <p class="auth-subtitle">Log in to the Command Center portal.</p>

            <?php if(session()->getFlashdata('msg')): ?>
                <div class="alert alert-danger d-flex align-items-center py-2" style="border-radius: 10px; border: none; background: #fff1f2; color: var(--brand-red);">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <span class="small fw-bold"><?= session()->getFlashdata('msg') ?></span>
                </div>
            <?php endif; ?>

            <form action="/auth/authenticate" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter your username" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Remember me</label>
                    </div>
                    <a href="#" class="small fw-bold text-decoration-none" style="color: var(--brand-green);">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-signin w-100 shadow-sm">
                    Sign In to Portal
                </button>
            </form>

<div class="d-flex align-items-center justify-content-center mt-3">
    <p class="small text-muted mb-0 me-2" style="font-size: 0.72rem; letter-spacing: 0.5px; text-transform: uppercase; color: #b0bec5;">Powered by</p>
    <img src="/uploads/teamlogo.png" alt="Internix" style="height: 28px; opacity: 0.7; transition: opacity 0.3s ease;" 
         onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
</div>
        </div>
    </div>

    <!-- Right Section (Hero) -->
    <div class="auth-hero-section">
        <div class="hero-text-content">
            <h2 class="hero-title">Empowering Rapid <br><span style="color: var(--brand-orange);">Response.</span></h2>
            <p class="hero-desc">
                Log in to coordinate relief efforts and manage resources. 
                <strong>ResQR</strong> provides local action and care through 
                secure digital services for the community.
            </p>
            
            <div class="mt-5 d-flex align-items-center">
                <div class="d-flex me-3">
                    <div style="margin-left:0;"></div>
                    <div></div>
                    <div></div>
                </div>
                <span class="small opacity-75">500+ active responders online.</span>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>