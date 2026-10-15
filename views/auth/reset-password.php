<?php
ob_start();
$errors = Session::getFlash("errors");
$flashError = Session::getFlash("error");
$flashSuccess = Session::getFlash("success");
$old = Session::getFlash("old");
if (!is_array($errors)) {
    $errors = [];
}
if (!is_array($old)) {
    $old = [];
}
$prefillEmail = $email ?? ($old["email"] ?? "");
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
.cafecat-reset{font-family:var(--cc-sans);color:var(--cc-ink);padding-inline:20px}.reset-card{max-width:1180px;margin:0 auto;background:var(--cc-paper);border:1px solid var(--cc-line);box-shadow:0 22px 65px rgba(0,0,0,.08)}.reset-layout{display:grid;grid-template-columns:310px minmax(0,1fr);min-height:700px}.reset-rail{background:var(--cc-ink);color:#fff;padding:38px 32px;display:flex;flex-direction:column;position:relative;overflow:hidden}.reset-rail:after{content:"";position:absolute;width:250px;height:250px;border:1px solid #ffffff1a;border-radius:50%;right:-170px;bottom:-130px;box-shadow:0 0 0 36px #ffffff08,0 0 0 72px #ffffff05;pointer-events:none}.reset-logo{display:inline-flex;width:178px;height:77px;padding:8px 12px;background:#fff;align-items:center;justify-content:center;position:relative;z-index:1}.reset-logo img,.reset-mobile-brand img{width:100%;height:100%;object-fit:contain}.reset-rail-copy{margin-top:auto;margin-bottom:30px;position:relative;z-index:1}.reset-rail .eyebrow,.reset-title .eyebrow{font-size:10px;letter-spacing:.2em;text-transform:uppercase}.reset-rail .eyebrow{color:#b9b9b5}.reset-rail h2{font:normal 42px/1.04 var(--cc-serif);letter-spacing:-.025em;margin:15px 0}.reset-rail p{font-size:13px;line-height:1.75;color:#d0d0cc;margin:0}.reset-rail-note{border-top:1px solid #ffffff40;padding-top:17px;font-size:11px;line-height:1.7;color:#c4c4c0;position:relative;z-index:1}.reset-main{padding:38px clamp(28px,5vw,64px) 34px;display:flex;flex-direction:column;min-width:0}.reset-mobile-brand{display:none}.reset-topline{display:flex;align-items:center;justify-content:space-between;gap:18px;color:var(--cc-gray);font-size:11px;margin-bottom:32px}.reset-title{margin-bottom:24px}.reset-title .eyebrow{color:var(--cc-gray)}.reset-title h1{font:normal clamp(38px,4.4vw,52px)/1.04 var(--cc-serif);letter-spacing:-.025em;margin:9px 0}.reset-title p{color:var(--cc-gray);font-size:13px;line-height:1.7;margin:0}.reset-content{width:100%;max-width:560px}.cafecat-reset .alert-danger,.cafecat-reset .alert-success{color:#171717;background:#f3f3f1;border-color:#bbb}.cafecat-reset .alert .btn-close{opacity:.65}.cafecat-reset .form-control{min-height:48px;border-radius:0;border-color:#bdbdb9;font-size:14px}.cafecat-reset .form-control:focus{border-color:#111;box-shadow:0 0 0 3px #11111113}.cafecat-reset .form-label{font-size:12px;font-weight:700;color:#222}.cafecat-reset .text-muted{color:#73736f!important}.cafecat-reset .input-group .form-control{min-height:48px}.cafecat-reset .password-toggle{min-width:48px;border-radius:0}.reset-code{height:60px!important;text-align:center;font-size:23px!important;font-weight:700;letter-spacing:8px;padding-left:18px!important;font-variant-numeric:tabular-nums}.reset-help{font-size:11px;line-height:1.65}.reset-submit{width:100%;min-height:50px;border-radius:0;background:#111;border:1px solid #111;color:#fff;font-size:11px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;transition:transform .2s var(--cc-ease),background .2s}.reset-submit:hover{background:#333;border-color:#333;transform:translateY(-2px)}.reset-submit:active{transform:scale(.99)}.reset-footer{border-top:1px solid var(--cc-line);margin-top:24px;padding-top:18px;text-align:center;color:#666;font-size:12px;line-height:1.8}.reset-footer a{color:#222;text-decoration:underline;text-underline-offset:3px;font-weight:700}.cafecat-reset .btn:focus-visible,.cafecat-reset a:focus-visible,.cafecat-reset input:focus-visible,.cafecat-reset button:focus-visible{outline:2px solid #111;outline-offset:3px;z-index:2}.cafecat-reset h1,.cafecat-reset h2,.cafecat-reset p{text-wrap:pretty}
@media(max-width:900px){.reset-layout{grid-template-columns:260px minmax(0,1fr)}.reset-rail{padding:30px 24px}.reset-rail h2{font-size:36px}.reset-main{padding:30px 28px}.reset-logo{width:156px;height:68px}}
@media(max-width:700px){.cafecat-reset{padding:0}.reset-card{border:0;box-shadow:none}.reset-layout{display:block;min-height:0}.reset-rail{display:none}.reset-main{padding:24px 20px 30px}.reset-mobile-brand{display:flex;width:138px;height:60px;padding:6px 9px;background:#fff;border:1px solid var(--cc-line);margin-bottom:22px}.reset-topline{margin-bottom:24px;font-size:10px}.reset-title{margin-bottom:22px}.reset-footer{margin-top:21px}}
@media(max-width:420px){.reset-main{padding-inline:16px}.reset-topline{align-items:flex-start}.reset-topline>a{max-width:150px;text-align:right}.reset-title h1{font-size:42px}.reset-code{font-size:21px!important;letter-spacing:7px}}
@media(prefers-reduced-motion:reduce){.cafecat-reset *, .cafecat-reset *:before,.cafecat-reset *:after{scroll-behavior:auto!important;animation-duration:.01ms!important;transition-duration:.01ms!important}}
</style>

<div class="container-fluid py-4 cafecat-reset">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <div class="reset-card">
                <div class="reset-layout">
                    <aside class="reset-rail" aria-label="CafeCat password reset information">
                        <a class="reset-logo" href="/" aria-label="CafeCat home">
                            <img src="<?= $escape(
                                $logoUrl,
                            ) ?>" alt="CafeCat — crafted for your cravings">
                        </a>
                        <div class="reset-rail-copy">
                            <div class="eyebrow">Password recovery</div>
                            <h2>Choose a new password.</h2>
                            <p>Use the code from your email and set a new password for your account.</p>
                        </div>
                        <div class="reset-rail-note">Your reset code expires one hour after it was sent.</div>
                    </aside>
                    <main class="reset-main" aria-label="Reset your CafeCat password">
                        <div class="reset-mobile-brand">
                            <img src="<?= $escape(
                                $logoUrl,
                            ) ?>" alt="CafeCat — crafted for your cravings">
                        </div>
                        <div class="reset-topline">
                            <span>Account recovery</span>
                        </div>
                        <header class="reset-title">
                            <div class="eyebrow">Secure account access</div>
                            <h1>Reset your password</h1>
                            <p>Enter the 6-digit code from your email plus your new password.</p>
                        </header>
                        <div class="reset-content">
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-labelledby="resetFieldErrorsTitle">
                                    <strong id="resetFieldErrorsTitle">Please fix the following errors:</strong>
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

                            <?php if ($flashError): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= $escape($flashError) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if ($flashSuccess): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="status">
                                    <?= $escape($flashSuccess) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="/reset-password" aria-label="Reset password">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="<?= $escape(
                                               $prefillEmail,
                                           ) ?>" required autocomplete="email">
                                </div>

                                <div class="mb-3">
                                    <label for="code" class="form-label">6-Digit Code</label>
                                    <input type="text" class="form-control reset-code" id="code" name="code"
                                           inputmode="numeric" autocomplete="one-time-code" maxlength="6"
                                           pattern="[0-9]{6}" placeholder="••••••" required aria-describedby="resetCodeHelp">
                                    <small class="text-muted reset-help" id="resetCodeHelp">Expires 1 hour after it was sent.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password"
                                               minlength="8" required autocomplete="new-password">
                                        <button type="button" class="btn btn-outline-secondary password-toggle" data-target="password" aria-label="Show new password" aria-controls="password" aria-pressed="false">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted reset-help">At least 8 characters.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                                        <button type="button" class="btn btn-outline-secondary password-toggle" data-target="password_confirmation" aria-label="Show confirmation password" aria-controls="password_confirmation" aria-pressed="false">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="btn reset-submit">
                                    <i class="bi bi-check-circle me-2" aria-hidden="true"></i>Reset Password
                                </button>
                            </form>

                            <div class="reset-footer">
                                <span>Didn't get a code?</span>
                                <a href="/forgot-password">Request a new one</a>
                                <span aria-hidden="true"> · </span>
                                <a href="/login">Back to Login</a>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.password-toggle').forEach(function (button) {
    button.addEventListener('click', function () {
        var input = document.getElementById(button.getAttribute('data-target'));
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        button.setAttribute('aria-pressed', show ? 'true' : 'false');
        button.innerHTML = show ? '<i class="bi bi-eye-slash" aria-hidden="true"></i>' : '<i class="bi bi-eye" aria-hidden="true"></i>';
    });
});
</script>

<?php
$content = ob_get_clean();
$title = "Reset Password - CafeCat";
include __DIR__ . "/../layouts/auth-header.php";
echo $content;
include __DIR__ . "/../layouts/auth-footer.php";

?>
