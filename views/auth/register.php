<?php
ob_start();
$errors = Session::getFlash("errors");
if (!is_array($errors)) {
    $errors = [];
}
$old = Session::getFlash("old");
if (!is_array($old)) {
    $old = [];
}
$oldDisc = strtolower($old["discount_type"] ?? "none");
if (!in_array($oldDisc, ["senior", "pwd"], true)) {
    $oldDisc = "none";
}
$err = function ($field) use ($errors) {
    return isset($errors[$field]) ? implode(" ", (array) $errors[$field]) : "";
};
$invalid = function ($field) use ($errors) {
    return isset($errors[$field]) ? " is-invalid" : "";
};
$stepFields = [
    ["first_name", "last_name", "email", "phone", "address"],
    ["password", "password_confirmation"],
    ["discount", "discount_id_no", "discount_id_image", "is_senior", "is_pwd"],
    ["accept_terms", "accept_privacy"],
];
$errorStep = 0;
foreach ($errors as $field => $_messages) {
    foreach ($stepFields as $stepIndex => $fields) {
        if (in_array($field, $fields, true)) {
            $errorStep = $stepIndex;
            break 2;
        }
    }
}
$logoUrl =
    rtrim(defined("APP_URL") ? APP_URL : "", "/") .
    "/assets/images/cafe-cat_logo-cropped.jpg";
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Manrope:wght@400;500;600;700;800&display=swap');
:root{--cc-ink:#111;--cc-paper:#fff;--cc-mist:#f2f2f0;--cc-gray:#777;--cc-line:#dededb;--cc-serif:"DM Serif Display",Georgia,serif;--cc-sans:Manrope,Arial,sans-serif;--cc-ease:cubic-bezier(.22,1,.36,1)}
.register-page{font-family:var(--cc-sans);color:var(--cc-ink);padding-inline:20px}.register-card{max-width:1180px;margin:0 auto;background:var(--cc-paper);border:1px solid var(--cc-line);box-shadow:0 22px 65px rgba(0,0,0,.08)}.register-layout{display:grid;grid-template-columns:310px minmax(0,1fr);min-height:710px}.register-rail{background:var(--cc-ink);color:#fff;padding:38px 32px;display:flex;flex-direction:column;position:relative;overflow:hidden}.register-rail:after{content:"";position:absolute;width:250px;height:250px;border:1px solid #ffffff1a;border-radius:50%;right:-170px;bottom:-130px;box-shadow:0 0 0 36px #ffffff08,0 0 0 72px #ffffff05;pointer-events:none}.register-logo{display:inline-flex;width:178px;height:77px;padding:8px 12px;background:#fff;align-items:center;justify-content:center;position:relative;z-index:1}.register-logo img,.register-mobile-brand img{width:100%;height:100%;object-fit:contain}.register-rail-copy{margin-top:auto;margin-bottom:30px;position:relative;z-index:1}.register-rail .eyebrow,.register-title .eyebrow{font-size:10px;letter-spacing:.2em;text-transform:uppercase}.register-rail .eyebrow{color:#b9b9b5}.register-rail h2{font:normal 42px/1.04 var(--cc-serif);letter-spacing:-.025em;margin:15px 0}.register-rail p{font-size:13px;line-height:1.75;color:#d0d0cc;margin:0}.register-rail-note{border-top:1px solid #ffffff40;padding-top:17px;font-size:11px;line-height:1.7;color:#c4c4c0;position:relative;z-index:1}.register-main{padding:38px clamp(28px,5vw,64px) 34px;display:flex;flex-direction:column;min-width:0}.register-mobile-brand{display:none}.register-topline{display:flex;align-items:center;justify-content:space-between;gap:18px;color:var(--cc-gray);font-size:11px;margin-bottom:28px}.register-topline a{color:var(--cc-ink);font-weight:800;text-decoration:none;border-bottom:1px solid var(--cc-ink);padding-bottom:3px}.register-title{margin-bottom:25px}.register-title .eyebrow{color:var(--cc-gray)}.register-title h1{font:normal clamp(35px,4vw,48px)/1.04 var(--cc-serif);letter-spacing:-.025em;margin:9px 0}.register-title p{color:var(--cc-gray);font-size:13px;line-height:1.7;margin:0}
.reg-steps{list-style:none;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:9px;padding:0;margin:0 0 27px}.reg-step{position:relative;padding-top:13px;color:#888;font-size:9px;letter-spacing:.07em;text-transform:uppercase;line-height:1.35}.reg-step:before{content:"";position:absolute;left:0;right:0;top:0;height:3px;background:#e5e5e2;transition:background .25s}.reg-step.active{color:var(--cc-ink);font-weight:800}.reg-step.active:before,.reg-step.completed:before{background:var(--cc-ink)}.reg-step.completed{color:#555}.reg-step-num{font-size:9px;color:#999;margin-right:5px}.reg-step.active .reg-step-num{color:var(--cc-ink)}.step-announcement{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap}.register-page .alert-danger{color:#171717;background:#f3f3f1;border-color:#bbb}.register-page .form-control{min-height:46px;border-radius:0;border-color:#bdbdb9}.register-page .form-control:focus{border-color:#111;box-shadow:0 0 0 3px #11111113}.register-page .form-control.is-invalid{border-color:#222;background-image:none}.register-page .invalid-feedback{color:#333}.register-page .form-label{font-size:12px;font-weight:700;color:#222}.register-page .text-muted{color:#73736f!important}.register-page .reg-section{border:0;border-radius:0;background:transparent;padding:0;margin:0;animation:regEnter .3s var(--cc-ease)}.register-page .reg-section[hidden]{display:none!important}.reg-section>h5{font:normal 30px/1.1 var(--cc-serif);letter-spacing:-.02em;margin:0 0 8px}.reg-section>h5 .badge{font:700 10px var(--cc-sans);vertical-align:middle;border-radius:0;padding:7px 8px}.reg-section>p.sub{color:#696965;font-size:13px;line-height:1.65;margin:0 0 22px}.register-page .form-control,.register-page .form-select{font-size:14px}.register-page .input-group .form-control{min-height:46px}.register-page .pw-toggle{min-width:48px;border-radius:0}.register-page .pw-toggle:focus-visible,.register-page .btn:focus-visible,.register-page a:focus-visible,.register-page input:focus-visible,.register-page button:focus-visible{outline:2px solid #111;outline-offset:3px;z-index:2}.pw-meter{height:4px;background:#e5e5e2;overflow:hidden}.pw-meter>div{height:100%;width:0;background:#111;transition:width .25s var(--cc-ease),background-color .25s}.register-page #pwHint,.register-page #pwMatch{font-size:11px}.register-page #pwMatch.text-success,.register-page #pwMatch.text-danger{color:#333!important}.disc-pill input{position:absolute;opacity:0;pointer-events:none;width:1px;height:1px}.disc-pill label{height:100%;min-height:94px;border:1px solid var(--cc-line);border-radius:0;padding:13px 8px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s,transform .2s var(--cc-ease);margin:0}.disc-pill label:hover{border-color:#888;transform:translateY(-2px)}.disc-pill label i{font-size:1.25rem;display:block;margin-bottom:.35rem}.disc-pill label small{font-size:10px}.disc-pill input:checked+label{border:2px solid #111;background:#f7f7f5;padding:12px 7px}.disc-pill input:focus-visible+label{outline:2px solid #111;outline-offset:3px}.register-page #discountFields{margin-top:17px;padding-top:17px;border-top:1px solid var(--cc-line)}.register-page #idPreview img{max-height:160px}.register-page .accordion-button{font-weight:700}.register-page .form-check{margin-top:12px}.register-page .form-check-input{width:18px;height:18px;border-radius:0;margin-top:.2rem}.register-page .form-check-input:checked{background-color:#111;border-color:#111}.register-page .form-check-input:focus{border-color:#111;box-shadow:0 0 0 3px #11111118}.register-page .form-check-label{font-size:12px;line-height:1.65}.register-page .form-check-label a{font-weight:700;text-underline-offset:3px}.wizard-controls{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-top:30px;padding-top:20px;border-top:1px solid var(--cc-line)}.wizard-controls-right{display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-left:auto}.wizard-controls .btn{border-radius:0;min-height:46px;padding:11px 20px;font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;transition:transform .2s var(--cc-ease),background .2s,color .2s}.wizard-controls .btn:hover:not(:disabled){transform:translateY(-2px)}.wizard-controls .btn:active:not(:disabled){transform:scale(.98)}.wizard-controls .btn-dark{background:#111;border-color:#111}.wizard-controls .btn-dark:hover:not(:disabled){background:#333;border-color:#333}.wizard-controls .btn-outline-secondary{border-color:#555;color:#222}.wizard-controls .btn:disabled{background:#a5a5a0;border-color:#a5a5a0;opacity:1;cursor:not-allowed}.consent-hint{font-size:10px;color:#666;margin:0;text-align:right;max-width:190px}.register-page [hidden]{display:none!important}.register-page h1,.register-page h2,.register-page h5,.register-page p{text-wrap:pretty}
@keyframes regEnter{from{opacity:0;transform:translateY(7px)}to{opacity:1;transform:none}}@media(max-width:900px){.register-layout{grid-template-columns:260px minmax(0,1fr)}.register-rail{padding:30px 24px}.register-rail h2{font-size:36px}.register-main{padding:30px 28px}.register-logo{width:156px;height:68px}}@media(max-width:700px){.register-page{padding:0}.register-card{border:0;box-shadow:none}.register-layout{display:block;min-height:0}.register-rail{display:none}.register-main{padding:22px 20px 30px}.register-mobile-brand{display:flex;width:138px;height:60px;padding:6px 9px;background:#fff;border:1px solid var(--cc-line);margin-bottom:20px}.register-topline{margin-bottom:21px;font-size:10px}.register-title{margin-bottom:21px}.reg-steps{gap:6px;margin-bottom:23px}.reg-step{font-size:8px;letter-spacing:0}.reg-step-num{display:block;margin:0 0 3px}.reg-section>h5{font-size:28px}.wizard-controls{margin-top:24px}.wizard-controls .btn{padding-inline:14px}.consent-hint{max-width:140px}}@media(max-width:420px){.register-main{padding-inline:16px}.register-topline{align-items:flex-start}.register-topline>a{max-width:150px;text-align:right}.reg-step{font-size:7px}.register-title h1{font-size:37px}.register-page .row.g-2{--bs-gutter-x:.45rem}.disc-pill label{padding-inline:5px}.disc-pill input:checked+label{padding-inline:4px}.disc-pill label strong{font-size:11px}.wizard-controls-right{gap:7px}.wizard-controls .btn{font-size:10px;padding-inline:10px}.consent-hint{font-size:9px;max-width:110px}}
@media(prefers-reduced-motion:reduce){.register-page *, .register-page *:before,.register-page *:after{scroll-behavior:auto!important;animation-duration:.01ms!important;transition-duration:.01ms!important}}
</style>

<div class="container-fluid py-4 register-page">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <div class="register-card">
                <div class="register-layout">
                    <aside class="register-rail" aria-label="CafeCat registration information">
                        <a class="register-logo" href="/" aria-label="CafeCat home"><img src="<?= htmlspecialchars(
                            $logoUrl,
                            ENT_QUOTES,
                            "UTF-8",
                        ) ?>" alt="CafeCat — crafted for your cravings"></a>
                        <div class="register-rail-copy"><div class="eyebrow">Account setup</div><h2>Your CafeCat account, in four steps.</h2><p>Enter your details, set a password, and choose whether to claim an eligible discount.</p></div>
                        <div class="register-rail-note">Senior and PWD discount claims are optional and verified by staff before a discount applies.</div>
                    </aside>
                    <section class="register-main" aria-label="Create your CafeCat account">
                        <div class="register-mobile-brand"><img src="<?= htmlspecialchars(
                            $logoUrl,
                            ENT_QUOTES,
                            "UTF-8",
                        ) ?>" alt="CafeCat — crafted for your cravings"></div>
                        <div class="register-topline"><span>New customer registration</span><a href="/login">Already have an account? Sign in</a></div>
                        <header class="register-title"><div class="eyebrow">CafeCat account</div><h1>Create your account</h1><p>Join caf&eacute;cat — crafted for your cravings</p></header>
                        <ol class="reg-steps" aria-label="Registration progress"><li class="reg-step" data-step-indicator="0"><span class="reg-step-num">01</span>Account</li><li class="reg-step" data-step-indicator="1"><span class="reg-step-num">02</span>Password</li><li class="reg-step" data-step-indicator="2"><span class="reg-step-num">03</span>Discount</li><li class="reg-step" data-step-indicator="3"><span class="reg-step-num">04</span>Consent</li></ol>
                        <p id="stepAnnouncement" class="step-announcement" aria-live="polite" aria-atomic="true"></p>
                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger" id="registerErrorSummary" role="alert" tabindex="-1">
                            <strong>Please fix the following:</strong>
                            <ul class="mb-0 mt-1">
                            <?php foreach (
                                $errors
                                as $field => $fieldErrors
                            ): ?>
                                <?php foreach ((array) $fieldErrors as $e): ?>
                                    <li><small><?= htmlspecialchars(
                                        $e,
                                    ) ?></small></li>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form id="registerForm" method="POST" action="/register" enctype="multipart/form-data" novalidate aria-label="CafeCat registration">
                        <!-- 1 · Account details -->
                        <div class="reg-section wizard-step" data-wizard-step="0" aria-labelledby="wizardTitle0" tabindex="-1" role="group" <?= $errorStep !==
                        0
                            ? "hidden"
                            : "" ?>>
                            <h5 id="wizardTitle0"><span class="badge bg-dark me-2">1</span>Account details</h5>
                            <p class="sub">How we identify you and reach you about orders.</p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control<?= $invalid(
                                        "first_name",
                                    ) ?>" id="first_name" name="first_name"
                                           value="<?= htmlspecialchars(
                                               $old["first_name"] ?? "",
                                           ) ?>" required autocomplete="given-name">
                                    <?php if (
                                        $err("first_name")
                                    ): ?><div class="invalid-feedback"><?= htmlspecialchars(
    $err("first_name"),
) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control<?= $invalid(
                                        "last_name",
                                    ) ?>" id="last_name" name="last_name"
                                           value="<?= htmlspecialchars(
                                               $old["last_name"] ?? "",
                                           ) ?>" required autocomplete="family-name">
                                    <?php if (
                                        $err("last_name")
                                    ): ?><div class="invalid-feedback"><?= htmlspecialchars(
    $err("last_name"),
) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control<?= $invalid(
                                    "email",
                                ) ?>" id="email" name="email"
                                       value="<?= htmlspecialchars(
                                           $old["email"] ?? "",
                                       ) ?>" required autocomplete="email">
                                <?php if (
                                    $err("email")
                                ): ?><div class="invalid-feedback"><?= htmlspecialchars(
    $err("email"),
) ?></div><?php endif; ?>
                                <small class="text-muted">We'll send a 6-digit verification code here.</small>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                           value="<?= htmlspecialchars(
                                               $old["phone"] ?? "",
                                           ) ?>" autocomplete="tel" placeholder="09xxxxxxxxx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Address *</label>
                                    <input type="text" class="form-control<?= $invalid(
                                        "address",
                                    ) ?>" id="address" name="address"
                                           value="<?= htmlspecialchars(
                                               $old["address"] ?? "",
                                           ) ?>" required autocomplete="street-address">
                                    <?php if (
                                        $err("address")
                                    ): ?><div class="invalid-feedback"><?= htmlspecialchars(
    $err("address"),
) ?></div><?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- 2 · Password -->
                        <div class="reg-section wizard-step" data-wizard-step="1" aria-labelledby="wizardTitle1" tabindex="-1" role="group" <?= $errorStep !==
                        1
                            ? "hidden"
                            : "" ?>>
                            <h5 id="wizardTitle1"><span class="badge bg-dark me-2">2</span>Password</h5>
                            <p class="sub">Minimum 8 characters. Use a mix of letters and numbers.</p>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control<?= $invalid(
                                        "password",
                                    ) ?>" id="password" name="password" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary pw-toggle" data-target="password" title="Show password"><i class="bi bi-eye"></i></button>
                                </div>
                                <div class="pw-meter mt-2"><div id="pwBar"></div></div>
                                <small class="text-muted" id="pwHint">Password strength will appear here.</small>
                            </div>
                            <div class="mb-0">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control<?= $invalid(
                                        "password_confirmation",
                                    ) ?>" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary pw-toggle" data-target="password_confirmation" title="Show password"><i class="bi bi-eye"></i></button>
                                </div>
                                <?php if (
                                    $err("password_confirmation")
                                ): ?><div class="invalid-feedback d-block"><?= htmlspecialchars(
    $err("password_confirmation"),
) ?></div><?php endif; ?>
                                <small id="pwMatch" class="d-block mt-1"></small>
                            </div>
                        </div>

                        <!-- 3 · Senior / PWD -->
                        <div class="reg-section wizard-step" data-wizard-step="2" aria-labelledby="wizardTitle2" tabindex="-1" role="group" <?= $errorStep !==
                        2
                            ? "hidden"
                            : "" ?>>
                            <h5 id="wizardTitle2"><span class="badge bg-dark me-2">3</span>Senior Citizen / PWD <small class="text-muted fw-normal">(optional)</small></h5>
                            <p class="sub">Seniors (RA 9994) and PWDs (RA 10754) get <strong>20% off + VAT exemption</strong>. Claims are verified by staff before the discount applies.</p>
                            <div class="row g-2 mb-2" role="radiogroup" aria-label="Discount type">
                                <div class="col-4 disc-pill">
                                    <input type="radio" name="discount_type" id="disc_none" value="none" <?= $oldDisc ===
                                    "none"
                                        ? "checked"
                                        : "" ?>>
                                    <label for="disc_none"><i class="bi bi-person"></i><strong>No discount</strong><br><small class="text-muted">Standard pricing</small></label>
                                </div>
                                <div class="col-4 disc-pill">
                                    <input type="radio" name="discount_type" id="disc_senior" value="senior" <?= $oldDisc ===
                                    "senior"
                                        ? "checked"
                                        : "" ?>>
                                    <label for="disc_senior"><i class="bi bi-award"></i><strong>Senior</strong><br><small class="text-muted">20% off</small></label>
                                </div>
                                <div class="col-4 disc-pill">
                                    <input type="radio" name="discount_type" id="disc_pwd" value="pwd" <?= $oldDisc ===
                                    "pwd"
                                        ? "checked"
                                        : "" ?>>
                                    <label for="disc_pwd"><i class="bi bi-heart"></i><strong>PWD</strong><br><small class="text-muted">20% off</small></label>
                                </div>
                            </div>
                            <!-- legacy checkboxes kept in sync for backward compatibility -->
                            <input type="checkbox" name="is_senior" id="is_senior" value="1" class="d-none" <?= $oldDisc ===
                            "senior"
                                ? "checked"
                                : "" ?> tabindex="-1" aria-hidden="true">
                            <input type="checkbox" name="is_pwd" id="is_pwd" value="1" class="d-none" <?= $oldDisc ===
                            "pwd"
                                ? "checked"
                                : "" ?> tabindex="-1" aria-hidden="true">
                            <?php if (
                                $err("discount")
                            ): ?><div class="alert alert-danger py-2 small"><?= htmlspecialchars(
    $err("discount"),
) ?></div><?php endif; ?>

                            <div id="discountFields" style="<?= $oldDisc !==
                            "none"
                                ? ""
                                : "display:none;" ?>">
                                <div class="mb-3">
                                    <label for="discount_id_no" class="form-label" id="discountIdLabel">ID Number *</label>
                                    <input type="text" class="form-control<?= $invalid(
                                        "discount_id_no",
                                    ) ?>" id="discount_id_no" name="discount_id_no"
                                           value="<?= htmlspecialchars(
                                               $old["discount_id_no"] ?? "",
                                           ) ?>" maxlength="32" autocomplete="off"
                                           placeholder="e.g. OSCA-123456">
                                    <?php if (
                                        $err("discount_id_no")
                                    ): ?><div class="invalid-feedback"><?= htmlspecialchars(
    $err("discount_id_no"),
) ?></div><?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="discount_id_image" class="form-label">ID Photo *</label>
                                    <input type="file" class="form-control<?= $invalid(
                                        "discount_id_image",
                                    ) ?>" id="discount_id_image" name="discount_id_image"
                                           accept="image/jpeg,image/png,image/webp">
                                    <?php if (
                                        $err("discount_id_image")
                                    ): ?><div class="invalid-feedback"><?= htmlspecialchars(
    $err("discount_id_image"),
) ?></div><?php endif; ?>
                                    <small class="text-muted">Clear photo of the front of your ID. JPG, PNG or WebP, max 5MB.</small>
                                    <div id="idPreview" class="mt-2" style="display:none;">
                                        <img id="idPreviewImg" class="img-fluid rounded border" alt="ID preview">
                                        <div><small class="text-muted" id="idMeta"></small></div>
                                    </div>
                                    <div class="invalid-feedback d-none" id="idFileError"></div>
                                </div>
                                <div class="accordion" id="discGuideAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#discGuideBody">
                                                Discount guidelines — read before claiming
                                            </button>
                                        </h2>
                                        <div id="discGuideBody" class="accordion-collapse collapse" data-bs-parent="#discGuideAccordion">
                                            <div class="accordion-body small">
                                                <ul class="mb-0">
                                                    <li><strong>20% off + VAT-exempt</strong> on food and drinks for dine-in, takeaway, and delivery.</li>
                                                    <li><strong>One discount per transaction</strong> — Senior and PWD cannot be combined or shared.</li>
                                                    <li>Present the <strong>physical ID</strong> at pickup/delivery; staff verify every claim before the discount is honored.</li>
                                                    <li>Claims with unreadable, expired, or mismatched IDs are rejected and the order reverts to regular price.</li>
                                                    <li>Fake or borrowed IDs lead to <strong>account suspension</strong> and voided orders.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4 · Consent -->
                        <div class="reg-section wizard-step" data-wizard-step="3" aria-labelledby="wizardTitle3" tabindex="-1" role="group" <?= $errorStep !==
                        3
                            ? "hidden"
                            : "" ?>>
                            <h5 id="wizardTitle3"><span class="badge bg-dark me-2">4</span>Terms &amp; data privacy</h5>
                            <p class="sub">Your ID photo and personal data are protected under RA 10173 (Data Privacy Act of 2012).</p>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="accept_terms" name="accept_terms" value="1">
                                <label class="form-check-label small" for="accept_terms">
                                    I have read and accept the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>. *
                                </label>
                            </div>
                            <?php if (
                                $err("accept_terms")
                            ): ?><div class="alert alert-danger py-1 px-2 small"><?= htmlspecialchars(
    $err("accept_terms"),
) ?></div><?php endif; ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="accept_privacy" name="accept_privacy" value="1">
                                <label class="form-check-label small" for="accept_privacy">
                                    I consent to the collection and verification use of my data, including my ID photo, per the <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Data Privacy Notice</a>. *
                                </label>
                            </div>
                            <?php if (
                                $err("accept_privacy")
                            ): ?><div class="alert alert-danger py-1 px-2 small"><?= htmlspecialchars(
    $err("accept_privacy"),
) ?></div><?php endif; ?>
                        </div>

                        <div class="wizard-controls">
                            <button type="button" class="btn btn-outline-secondary" id="previousStepBtn" hidden>← Back</button>
                            <div class="wizard-controls-right">
                                <p id="consentHint" class="consent-hint">Accept both required checkboxes to create your account.</p>
                                <button type="button" class="btn btn-dark" id="nextStepBtn">Continue&nbsp; →</button>
                                <button type="submit" class="btn btn-dark" id="registerBtn" disabled hidden aria-describedby="consentHint"><i class="bi bi-check-circle me-2"></i><span id="registerBtnText">Create Account</span></button>
                            </div>
                        </div>

                    </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body small">
                <ol>
                    <li><strong>Account accuracy.</strong> You must register with your true name, a working email, and correct contact details. One account per person.</li>
                    <li><strong>Email verification.</strong> You must verify your email with the 6-digit code before ordering.</li>
                    <li><strong>Senior/PWD discounts.</strong> Claims require a valid, unexpired OSCA/PWD ID in your name plus a clear ID photo. Staff verify every claim; unverified claims never discount an order.</li>
                    <li><strong>Misuse.</strong> Fake, borrowed, or tampered IDs lead to account suspension and voided or repriced orders.</li>
                    <li><strong>One discount per transaction.</strong> Senior and PWD discounts cannot be combined, split, or transferred.</li>
                    <li><strong>Orders &amp; payment.</strong> Manual e-payments need proof + reference and are verified within 24 hours; paid e-payment orders cannot be self-cancelled.</li>
                    <li><strong>Fair use.</strong> Abusive behavior toward staff or riders may result in suspension.</li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal" onclick="document.getElementById('accept_terms').checked = true; toggleSubmit();">Accept Terms</button>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacyModalLabel">Data Privacy Notice (RA 10173)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body small">
                <p><strong>What we collect:</strong> your name, email, phone, address, Senior/PWD status, ID number, and ID photo — plus the orders you place.</p>
                <p><strong>Why:</strong> to run your account, fulfill and verify orders, and validate discount eligibility. Your ID photo is used <em>only</em> for discount verification.</p>
                <p><strong>Who sees it:</strong> authorized caf&eacute;cat staff and riders strictly as needed to serve you (e.g. verifying a discount at pickup). We never sell personal data.</p>
                <p><strong>Protection:</strong> uploads use unguessable filenames, access is role-restricted, and ID photos are never shown publicly.</p>
                <p><strong>Retention:</strong> account data is kept while your account is active; ID photos are deleted when you remove your discount claim or close your account.</p>
                <p><strong>Your rights:</strong> access, correct, or delete your data, or withdraw discount consent anytime via your Profile or info@cafecat.com. Withdrawing consent revokes the discount.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal" onclick="document.getElementById('accept_privacy').checked = true; toggleSubmit();">I Consent</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Screen -->
<div id="loadingScreen" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; backdrop-filter: blur(5px);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white;">
        <div class="spinner-border text-light mb-3" style="width: 3rem; height: 3rem; border-width: 0.3em;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5 class="mb-2">Creating Your Account</h5>
        <p class="mb-0 text-muted">Please wait while we process your registration...</p>
    </div>
</div>

<script>
function toggleSubmit(){var ok=document.getElementById('accept_terms').checked&&document.getElementById('accept_privacy').checked;var btn=document.getElementById('registerBtn');var hint=document.getElementById('consentHint');btn.disabled=!ok;if(hint)hint.hidden=ok||currentStep!==3;}
var currentStep=<?= (int) $errorStep ?>;
(function(){
 var submitted=false,form=document.getElementById('registerForm'),sections=Array.prototype.slice.call(form.querySelectorAll('.wizard-step')),markers=Array.prototype.slice.call(document.querySelectorAll('[data-step-indicator]')),prevBtn=document.getElementById('previousStepBtn'),nextBtn=document.getElementById('nextStepBtn'),submitBtn=document.getElementById('registerBtn'),hint=document.getElementById('consentHint'),announcement=document.getElementById('stepAnnouncement'),stepNames=['Account details','Password','Senior Citizen / PWD discount','Terms and data privacy'];
 function showStep(index,focusInvalid){currentStep=Math.max(0,Math.min(index,sections.length-1));sections.forEach(function(section,i){section.hidden=i!==currentStep;section.setAttribute('aria-hidden',i!==currentStep?'true':'false')});markers.forEach(function(marker,i){marker.classList.toggle('active',i===currentStep);marker.classList.toggle('completed',i<currentStep);if(i===currentStep)marker.setAttribute('aria-current','step');else marker.removeAttribute('aria-current')});prevBtn.hidden=currentStep===0;nextBtn.hidden=currentStep===sections.length-1;submitBtn.hidden=currentStep!==sections.length-1;hint.hidden=currentStep!==sections.length-1||!submitBtn.disabled;announcement.textContent='Step '+(currentStep+1)+' of '+sections.length+': '+stepNames[currentStep];if(focusInvalid){var invalid=sections[currentStep].querySelector('.is-invalid, :invalid');if(invalid)invalid.focus({preventScroll:true});else sections[currentStep].focus({preventScroll:true})}window.scrollTo({top:0,behavior:'smooth'})}
 function validateCurrentStep(){var fields=sections[currentStep].querySelectorAll('input,select,textarea');for(var i=0;i<fields.length;i++){var field=fields[i];if(field.disabled||!field.willValidate)continue;if(!field.checkValidity()){field.classList.add('is-invalid');field.reportValidity();field.focus();return false}field.classList.remove('is-invalid')}return true}
 nextBtn.addEventListener('click',function(){if(validateCurrentStep())showStep(currentStep+1,true)});prevBtn.addEventListener('click',function(){showStep(currentStep-1,true)});
 var errorSummary=document.getElementById('registerErrorSummary');if(errorSummary){var invalid=sections[currentStep].querySelector('.is-invalid');if(invalid)invalid.focus({preventScroll:true});else errorSummary.focus({preventScroll:true})}
 document.getElementById('accept_terms').addEventListener('change',toggleSubmit);document.getElementById('accept_privacy').addEventListener('change',toggleSubmit);toggleSubmit();
 var pills=document.getElementsByName('discount_type'),isSenior=document.getElementById('is_senior'),isPwd=document.getElementById('is_pwd'),fields=document.getElementById('discountFields'),idInput=document.getElementById('discount_id_no'),idFile=document.getElementById('discount_id_image'),idLabel=document.getElementById('discountIdLabel');
 function selectedDisc(){for(var i=0;i<pills.length;i++)if(pills[i].checked)return pills[i].value;return 'none'}
 function syncDisc(){var sel=selectedDisc();isSenior.checked=sel==='senior';isPwd.checked=sel==='pwd';fields.style.display=sel==='none'?'none':'';idInput.required=sel!=='none';idFile.required=sel!=='none';if(idLabel)idLabel.textContent=sel==='senior'?'Senior Citizen ID Number *':(sel==='pwd'?'PWD ID Number *':'ID Number *');if(sel==='none'){idInput.classList.remove('is-invalid');idFile.classList.remove('is-invalid')}}
 for(var i=0;i<pills.length;i++)pills[i].addEventListener('change',syncDisc);syncDisc();
 document.querySelectorAll('.pw-toggle').forEach(function(btn){btn.setAttribute('aria-pressed','false');btn.setAttribute('aria-label','Show password');btn.addEventListener('click',function(){var input=document.getElementById(btn.getAttribute('data-target')),show=input.type==='password';input.type=show?'text':'password';btn.innerHTML=show?'<i class="bi bi-eye-slash" aria-hidden="true"></i>':'<i class="bi bi-eye" aria-hidden="true"></i>';btn.setAttribute('aria-label',show?'Hide password':'Show password');btn.setAttribute('aria-pressed',show?'true':'false')})});
 var pw=document.getElementById('password'),pw2=document.getElementById('password_confirmation'),bar=document.getElementById('pwBar'),pwHint=document.getElementById('pwHint'),match=document.getElementById('pwMatch');
 function score(value){var n=0;if(value.length>=8)n+=25;if(value.length>=12)n+=15;if(/[a-z]/.test(value)&&/[A-Z]/.test(value))n+=20;if(/\d/.test(value))n+=20;if(/[^A-Za-z0-9]/.test(value))n+=20;return Math.min(n,100)}
 function refreshPw(){var n=score(pw.value);bar.style.width=n+'%';bar.style.backgroundColor=n<40?'#bdbdb9':(n<70?'#777':'#111');pwHint.textContent=pw.value===''?'Password strength will appear here.':(n<40?'Weak — add length, mixed case, numbers or symbols.':(n<70?'Okay — a longer passphrase is stronger.':'Strong password.'));var mismatch=pw2.value!==''&&pw.value!==pw2.value;pw2.setCustomValidity(mismatch?'Passwords must match.':'');if(pw2.value!==''){var ok=pw.value===pw2.value&&pw.value!=='';match.textContent=ok?'Passwords match.':'Passwords do not match.';match.className='d-block mt-1 small '+(ok?'text-success':'text-danger')}else{match.textContent='';match.className='d-block mt-1 small'}}
 pw.addEventListener('input',refreshPw);pw2.addEventListener('input',refreshPw);
 var preview=document.getElementById('idPreview'),previewImg=document.getElementById('idPreviewImg'),meta=document.getElementById('idMeta'),fileErr=document.getElementById('idFileError');
 idFile.addEventListener('change',function(){var f=idFile.files[0];fileErr.classList.add('d-none');idFile.classList.remove('is-invalid');if(!f){preview.style.display='none';return}var allowed=['image/jpeg','image/png','image/webp'];if(allowed.indexOf(f.type)===-1){fileErr.textContent='Invalid type. Use JPG, PNG or WebP.';fileErr.classList.remove('d-none');idFile.classList.add('is-invalid');idFile.value='';preview.style.display='none';return}if(f.size>5*1024*1024){fileErr.textContent='File too large. Max 5MB.';fileErr.classList.remove('d-none');idFile.classList.add('is-invalid');idFile.value='';preview.style.display='none';return}meta.textContent=f.name+' ('+Math.round(f.size/1024)+' KB)';var reader=new FileReader();reader.onload=function(e){previewImg.src=e.target.result;preview.style.display='block'};reader.readAsDataURL(f)});
 form.addEventListener('submit',function(e){if(submitted){e.preventDefault();return}if(!form.checkValidity()){e.preventDefault();var invalid=form.querySelector(':invalid');if(invalid){var panel=invalid.closest('.wizard-step');if(panel)showStep(parseInt(panel.getAttribute('data-wizard-step'),10),false);invalid.classList.add('is-invalid');invalid.focus();invalid.reportValidity()}return}var sel=selectedDisc();if(sel!=='none'&&(!idInput.value.trim()||!idFile.files.length)){e.preventDefault();showStep(2,false);if(!idInput.value.trim())idInput.focus();else idFile.focus();return}submitted=true;submitBtn.disabled=true;document.getElementById('registerBtnText').textContent='Creating…';document.getElementById('loadingScreen').style.display='block'});
 if(window.history.replaceState)window.history.replaceState(null,null,window.location.href);showStep(currentStep,!!errorSummary);
})();
</script>

<?php
$content = ob_get_clean();
$title = "Register - CafeCat";
include __DIR__ . "/../layouts/auth-header.php";
echo $content;
include __DIR__ . "/../layouts/auth-footer.php";


?>
