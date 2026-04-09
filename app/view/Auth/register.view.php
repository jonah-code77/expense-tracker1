<?php use App\Core\View; ?>

<?php View::section('title'); ?>
Register
<?php View::endSection(); ?>

<?php View::section('left-panel'); ?>
    <svg width="100%" viewBox="0 0 300 200" fill="none" class="mb-4" style="max-width:300px">
        <rect x="10" y="10" width="280" height="165" rx="14" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
        <rect x="26" y="28" width="90" height="9" rx="4" fill="rgba(255,255,255,0.18)"/>
        <rect x="26" y="46" width="60" height="7" rx="3" fill="rgba(255,255,255,0.07)"/>
        <rect x="26" y="66" width="248" height="1" fill="rgba(255,255,255,0.07)"/>
        <rect x="26" y="82" width="248" height="26" rx="6" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.08)" stroke-width="0.5"/>
        <rect x="40" y="91" width="80" height="6" rx="3" fill="rgba(255,255,255,0.1)"/>
        <rect x="26" y="116" width="248" height="26" rx="6" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.08)" stroke-width="0.5"/>
        <rect x="40" y="125" width="60" height="6" rx="3" fill="rgba(255,255,255,0.1)"/>
        <rect x="26" y="150" width="248" height="14" rx="6" fill="rgba(55,138,221,0.25)" stroke="rgba(55,138,221,0.4)" stroke-width="0.5"/>
        <rect x="100" y="155" width="50" height="4" rx="2" fill="rgba(55,138,221,0.7)"/>
    </svg>

    <div class="text-center mb-4">
        <h2 style="font-size:26px;font-weight:600;color:white" class="mb-2">Get started for free</h2>
        <p style="font-size:14px;color:rgba(255,255,255,.5);line-height:1.7;max-width:260px;margin:0 auto">
            Join thousands managing their money smarter every day.
        </p>
    </div>

    <div class="d-flex flex-column gap-3 w-100" style="max-width:280px">
        <div class="d-flex align-items-center gap-3">
            <div class="step-num">1</div>
            <span style="font-size:13px;color:rgba(255,255,255,.55)">Create your free account</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="step-num">2</div>
            <span style="font-size:13px;color:rgba(255,255,255,.55)">Add your income and expenses</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="step-num">3</div>
            <span style="font-size:13px;color:rgba(255,255,255,.55)">Track and analyze your spending</span>
        </div>
    </div>
<?php View::endSection(); ?>

<?php View::section('heading'); ?>
    <h1 class="mb-1" style="font-size:22px;font-weight:600;color:#1a1f2e">Create account</h1>
    <p class="mb-4" style="font-size:14px;color:#6b7280">Fill in your details to get started</p>
<?php View::endSection(); ?>

<?php View::section('form'); ?>
    <form action="" method="POST" id="regForm">

        <div class="mb-3">
            <label class="form-label" for="name">Full name</label>
            <div class="input-icon-wrap">
                <i class="bi bi-person field-icon"></i>
                <input type="text" class="form-control" id="name" name="name"
                       placeholder="John Doe" autocomplete="name">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">Email address</label>
            <div class="input-icon-wrap">
                <i class="bi bi-envelope field-icon"></i>
                <input type="email" class="form-control" id="email" name="email"
                       placeholder="you@example.com" autocomplete="email">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <div class="input-icon-wrap">
                <i class="bi bi-lock field-icon"></i>
                <input type="password" class="form-control pe-5" id="password" name="password"
                       placeholder="Min. 8 characters" autocomplete="new-password"
                       oninput="checkStrength(this.value)">
                <button type="button" class="toggle-pw" onclick="togglePw()">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
            <div class="pw-bar"><div class="pw-fill" id="pwFill"></div></div>
            <span class="pw-label" id="pwLabel"></span>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="terms" id="terms">
            <label class="form-check-label" for="terms">
                I agree to the <a href="#" class="text-link">terms of service</a>
                and <a href="#" class="text-link">privacy policy</a>
            </label>
        </div>

        <button type="submit" name="btn" class="btn btn-main w-100">Create account</button>

    </form>
<?php View::endSection(); ?>

<?php View::section('footer-link'); ?>
    <p class="text-center mt-4 mb-0" style="font-size:13px;color:#6b7280">
        Already have an account?
        <a href="<?= BASE_URL ?>/login" class="text-link">Sign in</a>
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

    function checkStrength(val) {
        const fill  = document.getElementById('pwFill');
        const label = document.getElementById('pwLabel');
        if (!val) { fill.style.width = '0%'; label.textContent = ''; return; }
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        const levels = [
            { w: '25%', bg: '#dc2626', text: 'Weak' },
            { w: '50%', bg: '#f59e0b', text: 'Fair' },
            { w: '75%', bg: '#378add', text: 'Good' },
            { w: '100%', bg: '#16a34a', text: 'Strong' }
        ];
        const l = levels[score - 1] || levels[0];
        fill.style.width = l.w;
        fill.style.background = l.bg;
        label.textContent = l.text;
        label.style.color = l.bg;
    }
</script>
<?php View::endSection(); ?>