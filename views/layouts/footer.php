<?php
$__ccFooterYear = date('Y');
?>

<style>
    .cc-site-footer {
        --ccf-ink: #111111;
        --ccf-paper: #ffffff;
        --ccf-mist: #f3f3f0;
        --ccf-gray: #aaa9a2;
        --ccf-line: rgba(255, 255, 255, .2);
        position: relative;
        margin-top: 64px;
        padding: 48px 0 20px;
        overflow: hidden;
        background: var(--ccf-ink);
        color: var(--ccf-paper);
        font-family: Manrope, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
    }

    .cc-site-footer *,
    .cc-site-footer *::before,
    .cc-site-footer *::after { box-sizing: border-box; }

    .cc-site-footer a:focus-visible {
        outline: 2px solid #ffffff;
        outline-offset: 4px;
    }

    .cc-site-footer__inner {
        width: min(1180px, 100%);
        margin: 0 auto;
        padding: 0 24px;
    }

    .cc-site-footer__grid {
        display: grid;
        grid-template-columns: minmax(240px, 1.35fr) minmax(150px, .7fr) minmax(260px, 1fr);
        gap: clamp(28px, 6vw, 84px);
        align-items: start;
    }

    .cc-site-footer__brand-mark {
        display: inline-flex;
        width: min(190px, 100%);
        min-height: 78px;
        align-items: center;
        padding: 10px 13px;
        background: #ffffff;
        transition: transform .2s ease;
    }

    .cc-site-footer__brand-mark:hover { transform: translateY(-2px); }

    .cc-site-footer__logo {
        display: block;
        width: 100%;
        height: auto;
    }

    .cc-site-footer__brand-copy {
        max-width: 330px;
        margin: 17px 0 0;
        color: #deded9;
        font-size: 12px;
        line-height: 1.8;
    }

    .cc-site-footer__heading {
        margin: 2px 0 18px;
        color: var(--ccf-paper);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .15em;
        line-height: 1.4;
        text-transform: uppercase;
    }

    .cc-site-footer__links,
    .cc-site-footer__contact-list {
        display: grid;
        gap: 11px;
        margin: 0;
        padding: 0;
        list-style: none;
        font-style: normal;
    }

    .cc-site-footer__link {
        display: inline-flex;
        width: fit-content;
        align-items: baseline;
        color: #deded9;
        font-size: 11px;
        line-height: 1.55;
        text-decoration: none;
        text-underline-offset: 4px;
        transition: color .16s ease, text-decoration-color .16s ease;
    }

    .cc-site-footer__link:hover {
        color: #ffffff;
        text-decoration: underline;
        text-decoration-color: rgba(255,255,255,.7);
    }

    .cc-site-footer__contact-item {
        display: grid;
        grid-template-columns: 17px minmax(0, 1fr);
        gap: 9px;
        align-items: start;
        color: #deded9;
        font-size: 11px;
        line-height: 1.65;
    }

    .cc-site-footer__contact-item i {
        margin-top: 2px;
        color: #ffffff;
        font-size: 13px;
    }

    .cc-site-footer__contact-item a {
        color: inherit;
        text-decoration: none;
        text-underline-offset: 4px;
        transition: color .16s ease;
    }

    .cc-site-footer__contact-item a:hover {
        color: #ffffff;
        text-decoration: underline;
    }

    .cc-site-footer__rule {
        height: 1px;
        margin: 36px 0 17px;
        border: 0;
        background: var(--ccf-line);
    }

    .cc-site-footer__bottom {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        color: #bdbdb7;
        font-size: 10px;
        line-height: 1.6;
        text-align: center;
    }

    .cc-site-footer__copyright { margin: 0; }

    @media (max-width: 760px) {
        .cc-site-footer { margin-top: 44px; padding-top: 38px; }
        .cc-site-footer__grid { grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 30px 28px; }
        .cc-site-footer__brand { grid-column: 1 / -1; }
        .cc-site-footer__brand-copy { max-width: 440px; }
    }

    @media (max-width: 480px) {
        .cc-site-footer { padding: 32px 0 17px; }
        .cc-site-footer__inner { padding: 0 18px; }
        .cc-site-footer__grid { grid-template-columns: 1fr; gap: 27px; }
        .cc-site-footer__brand { grid-column: auto; }
        .cc-site-footer__heading { margin-bottom: 12px; }
        .cc-site-footer__rule { margin: 27px 0 14px; }
        .cc-site-footer__bottom { align-items: center; }
    }

    @media (prefers-reduced-motion: reduce) {
        .cc-site-footer *,
        .cc-site-footer *::before,
        .cc-site-footer *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<footer class="cc-site-footer">
    <div class="cc-site-footer__inner">
        <div class="cc-site-footer__grid">
            <section class="cc-site-footer__brand" aria-label="CafeCat">
                <a class="cc-site-footer__brand-mark" href="/" aria-label="CafeCat home">
                    <img class="cc-site-footer__logo" src="/assets/images/cafe-cat_logo-cropped.jpg" alt="CafeCat — crafted for your cravings" width="282" height="122">
                </a>
                <p class="cc-site-footer__brand-copy">Crafted for your cravings. Fresh coffee, delicious food, delivered with care.</p>
            </section>

            <nav class="cc-site-footer__navigation" aria-labelledby="cc-footer-links-title">
                <h2 class="cc-site-footer__heading" id="cc-footer-links-title">Quick links</h2>
                <ul class="cc-site-footer__links">
                    <li><a class="cc-site-footer__link" href="/">Home</a></li>
                    <li><a class="cc-site-footer__link" href="/menu">Menu</a></li>
                    <li><a class="cc-site-footer__link" href="/about">About Us</a></li>
                    <li><a class="cc-site-footer__link" href="/contact">Contact</a></li>
                </ul>
            </nav>

            <section class="cc-site-footer__contact" aria-labelledby="cc-footer-contact-title">
                <h2 class="cc-site-footer__heading" id="cc-footer-contact-title">Contact info</h2>
                <address class="cc-site-footer__contact-list">
                    <div class="cc-site-footer__contact-item">
                        <i class="bi bi-geo-alt" aria-hidden="true"></i>
                        <span>Feliciano Street, Bulacan, Philippines, 3023</span>
                    </div>
                    <div class="cc-site-footer__contact-item">
                        <i class="bi bi-telephone" aria-hidden="true"></i>
                        <a href="tel:+63212345678">+63 2 1234 5678</a>
                    </div>
                    <div class="cc-site-footer__contact-item">
                        <i class="bi bi-envelope" aria-hidden="true"></i>
                        <a href="mailto:info@cafecat.com">info@cafecat.com</a>
                    </div>
                </address>
            </section>
        </div>

        <hr class="cc-site-footer__rule">

        <div class="cc-site-footer__bottom">
            <p class="cc-site-footer__copyright">&copy; <?= $__ccFooterYear ?> caf&eacute;cat — crafted for your cravings. All rights reserved.</p>
        </div>
    </div>
</footer>
