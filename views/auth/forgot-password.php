<?php
ob_start();
$flashError = Session::getFlash("error");
$flashSuccess = Session::getFlash("success");
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
.cafecat-recovery{font-family:var(--cc-sans);color:var(--cc-ink);padding-inline:20px}.recovery-card{max-width:1180px;margin:0 auto;background:var(--cc-paper);border:1px solid var(--cc-line);box-shadow:0 22px 65px rgba(0,0,0,.08)}.recovery-layout{display:grid;grid-template-columns:310px minmax(0,1fr);min-height:640px}.recovery-rail{background:var(--cc-ink);color:#fff;padding:38px 32px;display:flex;flex-direction:column;position:relative;overflow:hidden}.recovery-rail:after{content:"";position:absolute;width:250px;height:250px;border:1px solid #ffffff1a;border-radius:50%;right:-170px;bottom:-130px;box-shadow:0 0 0 36px #ffffff08,0 0 0 72px #ffffff05;pointer-events:none}.recovery-logo{display:inline-flex;width:178px;height:77px;padding:8px 12px;background:#fff;align-items:center;justify-content:center;position:relative;z-index:1}.recovery-logo img,.recovery-mobile-brand img{width:100%;height:100%;object-fit:contain}.recovery-rail-copy{margin-top:auto;margin-bottom:30px;position:relative;z-index:1}.recovery-rail .eyebrow,.recovery-title .eyebrow{font-size:10px;letter-spacing:.2em;text-transform:uppercase}.recovery-rail .eyebrow{color:#b9b9b5}.recovery-rail h2{font:normal 42px/1.04 var(--cc-serif);letter-spacing:-.025em;margin:15px 0}.recovery-rail p{font-size:13px;line-height:1.75;color:#d0d0cc;margin:0}.recovery-rail-note{border-top:1px solid #ffffff40;padding-top:17px;font-size:11px;line-height:1.7;color:#c4c4c0;position:relative;z-index:1}.recovery-main{padding:38px clamp(28px,5vw,64px) 34px;display:flex;flex-direction:column;min-width:0}.recovery-mobile-brand{display:none}.recovery-topline{display:flex;align-items:center;justify-content:space-between;gap:18px;color:var(--cc-gray);font-size:11px;margin-bottom:37px}.recovery-title{margin-bottom:28px}.recovery-title .eyebrow{color:var(--cc-gray)}.recovery-title h1{font:normal clamp(38px,4.4vw,52px)/1.04 var(--cc-serif);letter-spacing:-.025em;margin:9px 0}.recovery-title p{color:var(--cc-gray);font-size:13px;line-height:1.7;margin:0}.recovery-content{width:100%;max-width:540px}.cafecat-recovery .alert-danger,.cafecat-recovery .alert-success{color:#171717;background:#f3f3f1;border-color:#bbb}.cafecat-recovery .alert .btn-close{opacity:.65}.cafecat-recovery .form-control{min-height:48px;border-radius:0;border-color:#bdbdb9;font-size:14px}.cafecat-recovery .form-control:focus{border-color:#111;box-shadow:0 0 0 3px #11111113}.cafecat-recovery .form-label{font-size:12px;font-weight:700;color:#222}.recovery-submit{width:100%;min-height:50px;border-radius:0;background:#111;border:1px solid #111;color:#fff;font-size:11px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;transition:transform .2s var(--cc-ease),background .2s}.recovery-submit:hover{background:#333;border-color:#333;transform:translateY(-2px)}.recovery-submit:active{transform:scale(.99)}.recovery-back{display:inline-flex;align-items:center;justify-content:center;gap:8px;color:#222!important;font-size:12px;font-weight:700;text-decoration:none!important;border-bottom:1px solid #aaa;padding:5px 0;transition:border-color .2s,color .2s;margin-top:23px}.recovery-back:hover{color:#000!important;border-color:#111}.cafecat-recovery .btn:focus-visible,.cafecat-recovery a:focus-visible,.cafecat-recovery input:focus-visible,.cafecat-recovery button:focus-visible{outline:2px solid #111;outline-offset:3px;z-index:2}.cafecat-recovery h1,.cafecat-recovery h2,.cafecat-recovery p{text-wrap:pretty}
@media(max-width:900px){.recovery-layout{grid-template-columns:260px minmax(0,1fr)}.recovery-rail{padding:30px 24px}.recovery-rail h2{font-size:36px}.recovery-main{padding:30px 28px}.recovery-logo{width:156px;height:68px}}
@media(max-width:700px){.cafecat-recovery{padding:0}.recovery-card{border:0;box-shadow:none}.recovery-layout{display:block;min-height:0}.recovery-rail{display:none}.recovery-main{padding:24px 20px 30px}.recovery-mobile-brand{display:flex;width:138px;height:60px;padding:6px 9px;background:#fff;border:1px solid var(--cc-line);margin-bottom:22px}.recovery-topline{margin-bottom:25px;font-size:10px}.recovery-title{margin-bottom:24px}}
@media(max-width:420px){.recovery-main{padding-inline:16px}.recovery-topline{align-items:flex-start}.recovery-topline>a{max-width:150px;text-align:right}.recovery-title h1{font-size:42px}}
@media(prefers-reduced-motion:reduce){.cafecat-recovery *, .cafecat-recovery *:before,.cafecat-recovery *:after{scroll-behavior:auto!important;animation-duration:.01ms!important;transition-duration:.01ms!important}}
</style>

<div class="container-fluid py-4 cafecat-recovery">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <div class="recovery-card">
                <div class="recovery-layout">
                    <aside class="recovery-rail" aria-label="CafeCat password recovery information">
                        <a class="recovery-logo" href="/" aria-label="CafeCat home">
                            <img src="<?= $escape(
                                $logoUrl,
                            ) ?>" alt="CafeCat — crafted for your cravings">
                        </a>
                        <div class="recovery-rail-copy">
                            <div class="eyebrow">Password recovery</div>
                            <h2>Get back into your account.</h2>
                            <p>Start with the email address linked to your CafeCat account.</p>
                        </div>
                        <div class="recovery-rail-note">You'll need access to your account email to continue.</div>
                    </aside>
                    <main class="recovery-main" aria-label="Reset your password">
                        <div class="recovery-mobile-brand">
                            <img src="<?= $escape(
                                $logoUrl,
                            ) ?>" alt="CafeCat — crafted for your cravings">
                        </div>
                        <div class="recovery-topline">
                            <span>Account recovery</span>
                        </div>
                        <header class="recovery-title">
                            <div class="eyebrow">Account recovery</div>
                            <h1>Forgot password?</h1>
                            <p>Enter your account email and we'll send you a 6-digit reset code.</p>
                        </header>
                        <div class="recovery-content">
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

                            <form method="POST" action="/forgot-password" aria-label="Request a password reset code">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required autocomplete="email">
                                </div>
                                <button type="submit" class="btn recovery-submit">
                                    <i class="bi bi-envelope me-2" aria-hidden="true"></i>Send Reset Code
                                </button>
                            </form>
                            <a href="/login" class="recovery-back"><i class="bi bi-arrow-left" aria-hidden="true"></i><span>Back to Login</span></a>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Forgot Password - CafeCat";
include __DIR__ . "/../layouts/auth-header.php";
echo $content;
include __DIR__ . "/../layouts/auth-footer.php";

?>
