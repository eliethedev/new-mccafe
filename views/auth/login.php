<?php
ob_start();

$errors = Session::getFlash("errors");
$loginError = Session::getFlash("error");
$resendVerification = Session::getFlash("resend_verification");
$resendUserId = Session::getFlash("user_id");
$loginSuccess = Session::getFlash("success");
$old = Session::getFlash("old");

if (!is_array($errors)) {
    $errors = [];
}
if (!is_array($old)) {
    $old = [];
}

$escape = static function ($value) {
    return htmlspecialchars(
        is_scalar($value) ? (string) $value : "",
        ENT_QUOTES,
        "UTF-8",
    );
};
$logoUrl =
    rtrim(defined("APP_URL") ? APP_URL : "", "/") .
    "/assets/images/cafe-cat_logo-cropped.jpg";
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Manrope:wght@400;500;600;700;800&display=swap');
:root{--cc-ink:#111;--cc-paper:#fff;--cc-mist:#f2f2f0;--cc-gray:#777;--cc-line:#dededb;--cc-serif:"DM Serif Display",Georgia,serif;--cc-sans:Manrope,Arial,sans-serif;--cc-ease:cubic-bezier(.22,1,.36,1)}
.cafecat-login{font-family:var(--cc-sans);color:var(--cc-ink);padding-inline:20px}.login-card{max-width:1180px;margin:0 auto;background:var(--cc-paper);border:1px solid var(--cc-line);box-shadow:0 22px 65px rgba(0,0,0,.08)}.login-layout{display:grid;grid-template-columns:310px minmax(0,1fr);min-height:640px}.login-rail{background:var(--cc-ink);color:#fff;padding:38px 32px;display:flex;flex-direction:column;position:relative;overflow:hidden}.login-rail:after{content:"";position:absolute;width:250px;height:250px;border:1px solid #ffffff1a;border-radius:50%;right:-170px;bottom:-130px;box-shadow:0 0 0 36px #ffffff08,0 0 0 72px #ffffff05;pointer-events:none}.login-logo{display:inline-flex;width:178px;height:77px;padding:8px 12px;background:#fff;align-items:center;justify-content:center;position:relative;z-index:1}.login-logo img,.login-mobile-brand img{width:100%;height:100%;object-fit:contain}.login-rail-copy{margin-top:auto;margin-bottom:30px;position:relative;z-index:1}.login-rail .eyebrow,.login-title .eyebrow{font-size:10px;letter-spacing:.2em;text-transform:uppercase}.login-rail .eyebrow{color:#b9b9b5}.login-rail h2{font:normal 42px/1.04 var(--cc-serif);letter-spacing:-.025em;margin:15px 0}.login-rail p{font-size:13px;line-height:1.75;color:#d0d0cc;margin:0}.login-rail-note{border-top:1px solid #ffffff40;padding-top:17px;font-size:11px;line-height:1.7;color:#c4c4c0;position:relative;z-index:1}.login-main{padding:38px clamp(28px,5vw,64px) 34px;display:flex;flex-direction:column;min-width:0}.login-mobile-brand{display:none}.login-topline{display:flex;align-items:center;justify-content:space-between;gap:18px;color:var(--cc-gray);font-size:11px;margin-bottom:37px}.login-topline a{color:var(--cc-ink);font-weight:800;text-decoration:none;border-bottom:1px solid var(--cc-ink);padding-bottom:3px}.login-title{margin-bottom:28px}.login-title .eyebrow{color:var(--cc-gray)}.login-title h1{font:normal clamp(38px,4.4vw,52px)/1.04 var(--cc-serif);letter-spacing:-.025em;margin:9px 0}.login-title p{color:var(--cc-gray);font-size:13px;line-height:1.7;margin:0}.login-content{width:100%;max-width:540px}.cafecat-login .alert-danger{color:#171717;background:#f3f3f1;border-color:#bbb}.cafecat-login .alert-success{color:#171717;background:#f3f3f1;border-color:#bbb}.cafecat-login .alert .btn-close{opacity:.65}.cafecat-login .form-control{min-height:48px;border-radius:0;border-color:#bdbdb9;font-size:14px}.cafecat-login .form-control:focus{border-color:#111;box-shadow:0 0 0 3px #11111113}.cafecat-login .form-label{font-size:12px;font-weight:700;color:#222}.cafecat-login .text-muted{color:#73736f!important}.cafecat-login .input-group .form-control{min-height:48px}.cafecat-login .password-toggle{min-width:48px;border-radius:0}.cafecat-login .form-check-input{width:18px;height:18px;border-radius:0;margin-top:.15rem}.cafecat-login .form-check-input:checked{background-color:#111;border-color:#111}.cafecat-login .form-check-input:focus{border-color:#111;box-shadow:0 0 0 3px #11111118}.cafecat-login .form-check-label{font-size:12px;line-height:1.6}.login-options{display:flex;align-items:center;justify-content:space-between;gap:16px;margin:4px 0 25px}.login-options a,.login-footer a{color:#222;text-decoration:underline;text-underline-offset:3px;font-size:12px;font-weight:700}.login-submit{width:100%;min-height:50px;border-radius:0;background:#111;border:1px solid #111;color:#fff;font-size:11px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;transition:transform .2s var(--cc-ease),background .2s}.login-submit:hover{background:#333;border-color:#333;transform:translateY(-2px)}.login-submit:active{transform:scale(.99)}.login-footer{border-top:1px solid var(--cc-line);margin-top:28px;padding-top:20px;text-align:center;color:#666;font-size:12px}.login-footer p{margin:0 0 14px}.login-home{display:inline-flex;align-items:center;gap:8px;color:#222!important;font-size:11px;font-weight:700;text-decoration:none!important;border-bottom:1px solid #aaa;padding-bottom:4px;transition:border-color .2s,color .2s}.login-home:hover{color:#000!important;border-color:#111}.cafecat-login .btn:focus-visible,.cafecat-login a:focus-visible,.cafecat-login input:focus-visible,.cafecat-login button:focus-visible{outline:2px solid #111;outline-offset:3px;z-index:2}.cafecat-login [hidden]{display:none!important}.cafecat-login h1,.cafecat-login h2,.cafecat-login p{text-wrap:pretty}
@media(max-width:900px){.login-layout{grid-template-columns:260px minmax(0,1fr)}.login-rail{padding:30px 24px}.login-rail h2{font-size:36px}.login-main{padding:30px 28px}.login-logo{width:156px;height:68px}}
@media(max-width:700px){.cafecat-login{padding:0}.login-card{border:0;box-shadow:none}.login-layout{display:block;min-height:0}.login-rail{display:none}.login-main{padding:24px 20px 30px}.login-mobile-brand{display:flex;width:138px;height:60px;padding:6px 9px;background:#fff;border:1px solid var(--cc-line);margin-bottom:22px}.login-topline{margin-bottom:25px;font-size:10px}.login-title{margin-bottom:24px}.login-footer{margin-top:24px}}
@media(max-width:420px){.login-main{padding-inline:16px}.login-topline{align-items:flex-start}.login-topline>a{max-width:150px;text-align:right}.login-title h1{font-size:42px}.login-options{gap:10px}.login-options a{font-size:11px}}
@media(prefers-reduced-motion:reduce){.cafecat-login *, .cafecat-login *:before,.cafecat-login *:after{scroll-behavior:auto!important;animation-duration:.01ms!important;transition-duration:.01ms!important}}
</style>

<div class="container-fluid py-4 cafecat-login">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <div class="login-card">
                <div class="login-layout">
                    <aside class="login-rail" aria-label="CafeCat account information">
                        <a class="login-logo" href="/" aria-label="CafeCat home">
                            <img src="<?= $escape(
                                $logoUrl,
                            ) ?>" alt="CafeCat — crafted for your cravings">
                        </a>
                        <div class="login-rail-copy">
                            <div class="eyebrow">CafeCat account</div>
                            <h2>Welcome back.</h2>
                            <p>Crafted for your cravings.</p>
                        </div>
                        <div class="login-rail-note">Sign in to your account to continue.</div>
                    </aside>
                    <main class="login-main" aria-label="Sign in to CafeCat">
                        <div class="login-mobile-brand">
                            <img src="<?= $escape(
                                $logoUrl,
                            ) ?>" alt="CafeCat — crafted for your cravings">
                        </div>
                        <div class="login-topline">
                            <span>CafeCat account</span>
                            <a href="/register">Create an account</a>
                        </div>
                        <header class="login-title">
                            <div class="eyebrow">Member sign in</div>
                            <h1>Welcome back</h1>
                            <p>Sign in to your account</p>
                        </header>
                        <div class="login-content">
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-labelledby="loginFieldErrorsTitle">
                                    <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
                                    <strong id="loginFieldErrorsTitle">Please fix the following errors:</strong>
                                    <div class="mt-2">
                                        <?php foreach (
                                            $errors
                                            as $field => $fieldErrors
                                        ): ?>
                                            <?php foreach (
                                                (array) $fieldErrors
                                                as $error
                                            ): ?>
                                                <div class="small"><?= $escape(
                                                    $error,
                                                ) ?></div>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if ($loginError): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
                                    <?= $escape($loginError) ?>
                                    <?php if ($resendVerification): ?>
                                        <form method="POST" action="/resend-verification" class="mt-3">
                                            <input type="hidden" name="user_id" value="<?= $escape(
                                                $resendUserId ?? "",
                                            ) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-envelope-fill me-1" aria-hidden="true"></i> Resend Verification Email
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if ($loginSuccess): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="status">
                                    <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>
                                    <?= $escape($loginSuccess) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="/login" aria-label="Sign in">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="<?= $escape(
                                               $old["email"] ?? "",
                                           ) ?>" required autocomplete="email">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                                        <button type="button" class="btn btn-outline-secondary password-toggle" id="passwordToggle" aria-label="Show password" aria-controls="password" aria-pressed="false">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="login-options">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a href="/forgot-password">Forgot password?</a>
                                </div>

                                <button type="submit" class="btn login-submit">Sign In</button>
                            </form>

                            <div class="login-footer">
                                <p>Don't have an account? <a href="/register">Sign up</a></p>
                                <a href="/" class="login-home"><i class="bi bi-house-door-fill" aria-hidden="true"></i><span>Back to Home</span></a>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('passwordToggle').addEventListener('click', function () {
    var input = document.getElementById('password');
    var show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    this.setAttribute('aria-pressed', show ? 'true' : 'false');
    this.innerHTML = show ? '<i class="bi bi-eye-slash" aria-hidden="true"></i>' : '<i class="bi bi-eye" aria-hidden="true"></i>';
});
</script>

<?php
$content = ob_get_clean();
$title = "Login - CafeCat";
include __DIR__ . "/../layouts/auth-header.php";
echo $content;
include __DIR__ . "/../layouts/auth-footer.php";

?>
