<?php use App\Core\View; ?>
<?php View::section('title'); ?>
Login
<?php View::endSection(); ?>

<?php View::section('left-panel'); ?>
    <svg width="100%" viewBox="0 0 300 200" fill="none" class="mb-4" style="max-width:300px">
        <rect x="10" y="10" width="280" height="165" rx="14" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
        <rect x="26" y="28" width="110" height="10" rx="4" fill="rgba(255,255,255,0.18)"/>
        <rect x="26" y="46" width="70" height="7" rx="3" fill="rgba(255,255,255,0.08)"/>
        <rect x="26" y="66" width="248" height="1" fill="rgba(255,255,255,0.08)"/>
        <rect x="26" y="82" width="55" height="7" rx="3" fill="rgba(55,138,221,0.5)"/>
        <rect x="26" y="98" width="248" height="28" rx="7" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.09)" stroke-width="0.5"/>
        <rect x="40" y="107" width="90" height="6" rx="3" fill="rgba(255,255,255,0.12)"/>
        <rect x="26" y="136" width="248" height="28" rx="7" fill="rgba(55,138,221,0.2)" stroke="rgba(55,138,221,0.35)" stroke-width="0.5"/>
        <rect x="100" y="145" width="52" height="6" rx="3" fill="rgba(55,138,221,0.7)"/>
    </svg>

    <div class="text-center mb-4">
        <h2 style="font-size:26px;font-weight:600;color:white" class="mb-2">Track every expense</h2>
        <p style="font-size:14px;color:rgba(255,255,255,.5);line-height:1.7;max-width:260px;margin:0 auto">
            Stay on top of your finances with smart categorization and real-time insights.
        </p>
    </div>

    <div class="row text-center w-100" style="max-width:280px">
        <div class="col-4"><div class="pstat-num">2k+</div><div class="pstat-label">Users</div></div>
        <div class="col-4"><div class="pstat-num">98%</div><div class="pstat-label">Uptime</div></div>
        <div class="col-4"><div class="pstat-num">Free</div><div class="pstat-label">Always</div></div>
    </div>
<?php View::endSection(); ?>

<?php View::section('heading'); ?>
    <h1 class="mb-1" style="font-size:22px;font-weight:600;color:#1a1f2e">Sign in</h1>
    <p class="mb-4" style="font-size:14px;color:#6b7280">Enter your details to access your account</p>
<?php View::endSection(); ?>

<?php View::section('form'); ?>
    <form action="" method="POST" id="logIn">
        <div class="mb-3">
            <label class="form-label" for="nameorEmail">Email or username</label>
            <div class="input-icon-wrap">
                <i class="bi bi-envelope field-icon"></i>
                <input type="text" class="form-control" id="nameorEmail" name="nameOrEmail"
                       placeholder="you@example.com" autocomplete="email">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <div class="input-icon-wrap">
                <i class="bi bi-lock field-icon"></i>
                <input type="password" class="form-control pe-5" id="password" name="password"
                       placeholder="Enter your password" autocomplete="current-password">
                <button type="button" class="toggle-pw" onclick="togglePw()">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a href="#" class="text-link">Forgot password?</a>
        </div>

        <button type="submit" name="btn" class="btn btn-main w-100">Sign in</button>

    </form>
<?php View::endSection(); ?>

<?php View::section('footer-link'); ?>
    <p class="text-center mt-4 mb-0" style="font-size:13px;color:#6b7280">
        Don't have an account?
        <a href="<?= BASE_URL ?>/register" class="text-link">Create one</a>
    </p>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>

<script>
    function togglePw() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        input.type  = input.type === 'password' ? 'text' : 'password';
        icon.className = input.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
    }
</script>
<?php View::endSection(); ?>