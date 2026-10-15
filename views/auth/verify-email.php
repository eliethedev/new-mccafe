<?php
ob_start();
$flashError = Session::getFlash("error");
$flashSuccess = Session::getFlash("success");
$prefillEmail = $email ?? "";
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
.cafecat-verify{font-family:var(--cc-sans);color:var(--cc-ink);padding-inline:20px}.verify-card{max-width:1180px;margin:0 auto;background:var(--cc-paper);border:1px solid var(--cc-line);box-shadow:0 22px 65px rgba(0,0,0,.08)}.verify-layout{display:grid;grid-template-columns:310px minmax(0,1fr);min-height:680px}.verify-rail{background:var(--cc-ink);color:#fff;padding:38px 32px;display:flex;flex-direction:column;position:relative;overflow:hidden}.verify-rail:after{content:"";position:absolute;width:250px;height:250px;border:1px solid #ffffff1a;border-radius:50%;right:-170px;bottom:-130px;box-shadow:0 0 0 36px #ffffff08,0 0 0 72px #ffffff05;pointer-events:none}.verify-logo{display:inline-flex;width:178px;height:77px;padding:8px 12px;background:#fff;align-items:center;justify-content:center;position:relative;z-index:1}.verify-logo img,.verify-mobile-brand img{width:100%;height:100%;object-fit:contain}.verify-rail-copy{margin-top:auto;margin-bottom:30px;position:relative;z-index:1}.verify-rail .eyebrow,.verify-title .eyebrow{font-size:10px;letter-spacing:.2em;text-transform:uppercase}.verify-rail .eyebrow{color:#b9b9b5}.verify-rail h2{font:normal 42px/1.04 var(--cc-serif);letter-spacing:-.025em;margin:15px 0}.verify-rail p{font-size:13px;line-height:1.75;color:#d0d0cc;margin:0}.verify-rail-note{border-top:1px solid #ffffff40;padding-top:17px;font-size:11px;line-height:1.7;color:#c4c4c0;position:relative;z-index:1}.verify-main{padding:38px clamp(28px,5vw,64px) 34px;display:flex;flex-direction:column;min-width:0}.verify-mobile-brand{display:none}.verify-topline{display:flex;align-items:center;justify-content:space-between;gap:18px;color:var(--cc-gray);font-size:11px;margin-bottom:37px}.verify-topline a{color:var(--cc-ink);font-weight:800;text-decoration:none;border-bottom:1px solid var(--cc-ink);padding-bottom:3px}.verify-title{margin-bottom:26px}.verify-title .eyebrow{color:var(--cc-gray)}.verify-title h1{font:normal clamp(38px,4.4vw,52px)/1.04 var(--cc-serif);letter-spacing:-.025em;margin:9px 0}.verify-title p{color:var(--cc-gray);font-size:13px;line-height:1.7;margin:0}.verify-content{width:100%;max-width:540px}.cafecat-verify .alert-danger,.cafecat-verify .alert-success{color:#171717;background:#f3f3f1;border-color:#bbb}.cafecat-verify .alert .btn-close{opacity:.65}.cafecat-verify .form-control{min-height:48px;border-radius:0;border-color:#bdbdb9;font-size:14px}.cafecat-verify .form-control:focus{border-color:#111;box-shadow:0 0 0 3px #11111113}.cafecat-verify .form-label{font-size:12px;font-weight:700;color:#222}.cafecat-verify .text-muted{color:#73736f!important}.verification-code{height:62px!important;text-align:center;font-size:24px!important;font-weight:700;letter-spacing:10px;padding-left:20px!important;font-variant-numeric:tabular-nums}.verify-code-help{font-size:11px;line-height:1.65}.verify-actions{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:22px}.verify-actions .btn{min-height:48px;border-radius:0;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;letter-spacing:.07em;text-transform:uppercase}.verify-actions .btn-primary{background:#111;border-color:#111;color:#fff}.verify-actions .btn-primary:hover{background:#333;border-color:#333}.verify-actions .btn-outline-secondary{border-color:#555;color:#222}.verify-resend{border-top:1px solid var(--cc-line);margin-top:28px;padding-top:20px}.verify-resend p{font-size:12px;line-height:1.6;color:#666;margin:0 0 14px}.verify-resend .btn{min-height:43px;border-radius:0;border-color:#555;color:#222;font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.verify-resend .btn:hover{background:#f2f2f0;color:#111}.verify-sent{font-size:12px;line-height:1.6;color:#222;background:#f3f3f1;border:1px solid #bbb;padding:12px 14px}.verify-sent i{margin-right:7px}.verify-home{display:inline-flex;align-items:center;gap:8px;color:#222!important;font-size:11px;font-weight:700;text-decoration:none!important;border-bottom:1px solid #aaa;padding-bottom:4px;transition:border-color .2s,color .2s;margin-top:auto;align-self:flex-start}.verify-home:hover{color:#000!important;border-color:#111}.cafecat-verify .btn:focus-visible,.cafecat-verify a:focus-visible,.cafecat-verify input:focus-visible,.cafecat-verify button:focus-visible{outline:2px solid #111;outline-offset:3px;z-index:2}.cafecat-verify [hidden]{display:none!important}.cafecat-verify h1,.cafecat-verify h2,.cafecat-verify h5,.cafecat-verify p{text-wrap:pretty}
@media(max-width:900px){.verify-layout{grid-template-columns:260px minmax(0,1fr)}.verify-rail{padding:30px 24px}.verify-rail h2{font-size:36px}.verify-main{padding:30px 28px}.verify-logo{width:156px;height:68px}}
@media(max-width:700px){.cafecat-verify{padding:0}.verify-card{border:0;box-shadow:none}.verify-layout{display:block;min-height:0}.verify-rail{display:none}.verify-main{padding:24px 20px 30px}.verify-mobile-brand{display:flex;width:138px;height:60px;padding:6px 9px;background:#fff;border:1px solid var(--cc-line);margin-bottom:22px}.verify-topline{margin-bottom:25px;font-size:10px}.verify-title{margin-bottom:23px}.verify-home{margin-top:24px}}
@media(max-width:420px){.verify-main{padding-inline:16px}.verify-topline{align-items:flex-start}.verify-topline>a{max-width:150px;text-align:right}.verify-title h1{font-size:42px}.verification-code{font-size:22px!important;letter-spacing:8px}.verify-actions{gap:8px}.verify-actions .btn{font-size:10px;padding-inline:8px}}
@media(prefers-reduced-motion:reduce){.cafecat-verify *, .cafecat-verify *:before,.cafecat-verify *:after{scroll-behavior:auto!important;animation-duration:.01ms!important;transition-duration:.01ms!important}}
</style>

<div class="container-fluid py-4 cafecat-verify">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <div class="verify-card">
                <div class="verify-layout">
                    <aside class="verify-rail" aria-label="CafeCat verification information">
                        <a class="verify-logo" href="/" aria-label="CafeCat home">
                            <img src="<?= $escape(
                                $logoUrl,
                            ) ?>" alt="CafeCat — crafted for your cravings">
                        </a>
                        <div class="verify-rail-copy">
                            <div class="eyebrow">Account verification</div>
                            <h2>One quick step to activate your account.</h2>
                            <p>Enter the code sent to your email address.</p>
                        </div>
                        <div class="verify-rail-note">Keep this page open while you check your inbox.</div>
                    </aside>
                    <main class="verify-main" aria-label="Verify your email address">
                        <div class="verify-mobile-brand">
                            <img src="<?= $escape(
                                $logoUrl,
                            ) ?>" alt="CafeCat — crafted for your cravings">
                        </div>
                        <div class="verify-topline">
                            <span>CafeCat account</span>
                            <a href="/login">Go to Login</a>
                        </div>
                        <header class="verify-title">
                            <div class="eyebrow">Email verification</div>
                            <h1>Enter your 6-digit code</h1>
                            <p>We sent a 6-digit verification code to your email. Enter it below to activate your account.</p>
                        </header>
                        <div class="verify-content">
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

                            <form method="POST" action="/verify-email" aria-label="Verify email">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="<?= $escape(
                                               $prefillEmail,
                                           ) ?>" required autocomplete="email">
                                </div>

                                <div class="mb-3">
                                    <label for="code" class="form-label">6-Digit Code</label>
                                    <input type="text" class="form-control verification-code" id="code" name="code"
                                           inputmode="numeric" autocomplete="one-time-code" maxlength="6"
                                           pattern="[0-9]{6}" placeholder="••••••" required aria-describedby="verificationCodeHelp">
                                    <small class="text-muted verify-code-help" id="verificationCodeHelp">The code expires in 24 hours. After 5 wrong attempts you'll need a new one.</small>
                                </div>

                                <div class="verify-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-patch-check me-2" aria-hidden="true"></i>Verify Email
                                    </button>
                                    <a href="/login" class="btn btn-outline-secondary">
                                        <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Go to Login
                                    </a>
                                </div>
                            </form>

                            <div class="verify-resend">
                                <p>Didn't receive the code? Check spam, then resend:</p>
                                <form action="/resend-verification" method="POST" id="resendForm" class="d-grid gap-2">
                                    <input type="hidden" name="email" id="resendEmail" value="<?= $escape(
                                        $prefillEmail,
                                    ) ?>">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="bi bi-envelope me-2" aria-hidden="true"></i>Resend Code
                                    </button>
                                </form>
                                <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var emailInput = document.getElementById('email');
                                    var resendEmail = document.getElementById('resendEmail');
                                    if (emailInput && resendEmail) {
                                        emailInput.addEventListener('input', function () { resendEmail.value = emailInput.value; });
                                    }
                                });
                                </script>
                                <?php
                                $newUserEmail = Session::getFlash(
                                    "new_user_email",
                                );
                                Session::getFlash("new_user_id"); // consume legacy flash
                                if ($newUserEmail): ?>
                                    <div class="verify-sent mt-3" role="status">
                                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                                        Code sent to: <strong><?= $escape(
                                            $newUserEmail,
                                        ) ?></strong>
                                    </div>
                                    <?php if (empty($prefillEmail)): ?>
                                        <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            var sentEmail = <?= json_encode(
                                                $newUserEmail,
                                                JSON_HEX_TAG |
                                                    JSON_HEX_APOS |
                                                    JSON_HEX_QUOT |
                                                    JSON_HEX_AMP,
                                            ) ?>;
                                            var emailInput = document.getElementById('email');
                                            var resendEmail = document.getElementById('resendEmail');
                                            if (emailInput) emailInput.value = sentEmail;
                                            if (resendEmail) resendEmail.value = sentEmail;
                                        });
                                        </script>
                                    <?php endif; ?>
                                <?php endif;
                                ?>
                            </div>
                        </div>
                        <a href="/" class="verify-home"><i class="bi bi-house-door-fill" aria-hidden="true"></i><span>Back to Home</span></a>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Verify Email - CafeCat";
include __DIR__ . "/../layouts/auth-header.php";
echo $content;
include __DIR__ . "/../layouts/auth-footer.php";

?>
