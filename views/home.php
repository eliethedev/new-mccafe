<?php ob_start();
$baseUrl = rtrim(APP_URL, "/") . "/assets/images/";
$categories = (isset($categories) && is_array($categories) && !empty($categories))
    ? $categories
    : [
        ["name" => "Coffee", "product_count" => 0, "image" => "cafecat/spanish-latte.jpg"],
    ];
$categoryImages = [
    "Coffee" => "cafecat/spanish-latte.jpg",
    "Matcha Series" => "cafecat/menu-matcha-noncoffee.jpg",
    "Non Coffee" => "cafecat/vanilla-creme.jpg",
    "Fruit Teas" => "cafecat/menu-matcha-noncoffee.jpg",
    "Boneless Chicken" => "cafecat/chicken-platter.jpg",
    "Rice Bowls" => "cafecat/rice-bowls.jpg",
    "Chicken Tenders" => "cafecat/menu-tenders.jpg",
    "Sandwiches" => "cafecat/menu-sandwiches-snacks.jpg",
    "Snacks" => "cafecat/menu-sandwiches-snacks.jpg",
];
$subcategories = [
    "coffee-frappe" => "coffee/frappe",
    "non-coffee-frappe" => "non-coffee/frappe",
    "milk-tea" => "beverage/milk-tea",
    "fruit-tea" => "beverage/fruit-tea",
];
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Manrope:wght@400;500;600;700;800&display=swap');
:root{--ink:#111;--paper:#fff;--mist:#f2f2f0;--gray:#777;--line:#dededb;--serif:"DM Serif Display",Georgia,serif;--sans:Manrope,Arial,sans-serif;--ease:cubic-bezier(.22,1,.36,1)}
.cafecat-page{margin:0;background:var(--paper);color:var(--ink);font-family:var(--sans);-webkit-font-smoothing:antialiased;overflow-x:hidden}.cafecat-page *{box-sizing:border-box}.cafecat-page a{color:inherit;text-decoration:none}.cafecat-page button,.cafecat-page input,.cafecat-page textarea{font:inherit}.cafecat-page img{max-width:100%;display:block}.cafecat-page :is(h1,h2,h3,p){text-wrap:pretty}.cafecat-page :is(a,button,input,textarea):focus-visible{outline:2px solid #111;outline-offset:3px}.cafecat-page :is(.cc-button,.cc-fab):active{transform:scale(.98)}.cc-submit:disabled{opacity:.6;cursor:wait}.cc-wrap{width:min(1180px,calc(100% - 64px));margin-inline:auto}.cc-topline{height:35px;background:var(--ink);color:#fff;display:flex;align-items:center;justify-content:center;font-size:9px;letter-spacing:.19em;text-transform:uppercase}.cc-nav{height:86px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--line);background:#fff}.cc-brand{display:flex;align-items:center;width:165px;height:60px;overflow:hidden}.cc-brand img{width:100%;height:100%;object-fit:contain}.cc-navlinks{display:flex;align-items:center;gap:34px;font-size:11px;letter-spacing:.09em;text-transform:uppercase}.cc-navlinks a{position:relative;padding:8px 0}.cc-navlinks a:after{content:"";position:absolute;left:0;right:100%;bottom:2px;height:1px;background:var(--ink);transition:right .35s var(--ease)}.cc-navlinks a:hover:after{right:0}.cc-nav-cta,.cc-button{min-height:46px;display:inline-flex;align-items:center;justify-content:center;padding:0 23px;border:1px solid var(--ink);font-size:10px;font-weight:700;letter-spacing:.13em;text-transform:uppercase;transition:background .3s,color .3s,transform .3s var(--ease)}.cc-nav-cta:hover,.cc-button-dark:hover{background:#333;color:#fff;transform:translateY(-2px)}.cc-button-dark{background:var(--ink);color:#fff}.cc-button-light{color:#fff;border-color:rgba(255,255,255,.55)}.cc-button-light:hover{background:#fff;color:var(--ink);transform:translateY(-2px)}
.cc-hero{background:var(--ink);color:#fff;min-height:610px;display:grid;grid-template-columns:1fr 1fr;overflow:hidden}.cc-hero-logo{width:148px;height:64px;padding:7px 12px;background:#fff;margin-bottom:27px}.cc-hero-logo img{width:100%;height:100%;object-fit:contain}.cc-hero-copy{padding:94px max(48px,calc((100vw - 1180px)/2)) 80px max(32px,calc((100vw - 1180px)/2));display:flex;flex-direction:column;align-items:flex-start;justify-content:center}.cc-eyebrow{font-size:10px;letter-spacing:.22em;text-transform:uppercase;opacity:.72;display:flex;align-items:center;gap:12px}.cc-eyebrow:before{content:"";width:25px;height:1px;background:currentColor}.cc-hero h1{font:normal clamp(56px,7vw,94px)/.98 var(--serif);letter-spacing:-.035em;margin:27px 0 22px;max-width:560px}.cc-hero h1 em{font-weight:400;color:#a9a9a6}.cc-hero p{font-size:16px;line-height:1.8;color:#c9c9c5;max-width:435px;margin:0 0 32px}.cc-actions{display:flex;gap:12px;flex-wrap:wrap}.cc-hero-art{position:relative;min-height:500px;background:#242424;overflow:hidden}.cc-hero-art img{width:100%;height:100%;object-fit:cover;filter:grayscale(100%);opacity:.72;transform:scale(1.04);transition:transform 1.2s var(--ease),opacity .8s ease}.cc-hero:hover .cc-hero-art img{transform:scale(1.09);opacity:.9}.cc-hero-art:after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(17,17,17,.5),transparent 55%,rgba(17,17,17,.14));pointer-events:none}.cc-ticker{border-bottom:1px solid var(--line);border-top:1px solid var(--line);padding:17px 0;overflow:hidden}.cc-ticker-in{display:flex;justify-content:space-between;gap:30px;white-space:nowrap;font-size:9px;letter-spacing:.17em;text-transform:uppercase;color:#5d5d5d}.cc-ticker-in span+span:before{content:"?";margin-right:30px;color:var(--ink)}
.cc-section{padding:100px 0}.cc-section-head{display:flex;align-items:end;justify-content:space-between;gap:28px;margin-bottom:38px}.cc-kicker{font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:#666}.cc-section-head h2,.cc-about h2{font:normal clamp(42px,5vw,64px)/1.03 var(--serif);letter-spacing:-.025em;margin:12px 0 0}.cc-section-head p{color:var(--gray);font-size:15px;line-height:1.7;max-width:310px;margin:0}.cc-text-link{font-size:10px;letter-spacing:.13em;text-transform:uppercase;border-bottom:1px solid;padding:0 0 8px;white-space:nowrap}.cc-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}.cc-card{border:1px solid var(--line);background:#fff;transition:transform .45s var(--ease),border-color .3s;overflow:hidden}.cc-card:hover{transform:translateY(-6px);border-color:#888}.cc-card-photo{height:235px;background:#ddd;position:relative;overflow:hidden}.cc-card-photo img{width:100%;height:100%;object-fit:cover;filter:grayscale(100%);transition:transform .8s var(--ease),filter .5s ease}.cc-card:hover .cc-card-photo img{transform:scale(1.07);filter:grayscale(35%)}.cc-card-body{padding:19px 18px 20px}.cc-card-top{display:flex;justify-content:space-between;align-items:start;gap:10px}.cc-card h3{font:normal 22px/1.12 var(--serif);margin:0}.cc-price{font-size:11px;font-weight:700;white-space:nowrap}.cc-card-desc{font-size:12px;color:#777;margin:10px 0 15px}.cc-card-foot{border-top:1px solid var(--line);padding-top:13px;font-size:10px;letter-spacing:.13em;text-transform:uppercase;display:flex;justify-content:space-between}.cc-add-cart{border:1px solid var(--ink);background:#fff;min-height:44px;padding:0 10px;text-transform:uppercase;letter-spacing:.1em;font-size:10px;cursor:pointer}.cc-add-cart:hover{text-decoration:underline}.cc-menu-band{background:var(--mist)}.cc-category-list{display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid #c9c9c6}.cc-category{display:flex;align-items:center;justify-content:space-between;padding:22px 12px;border-bottom:1px solid #c9c9c6;gap:12px;transition:padding .35s var(--ease)}.cc-category:hover{padding-left:22px}.cc-category strong{font:normal 23px var(--serif)}.cc-category small{font-size:10px;letter-spacing:.1em;color:#777;text-transform:uppercase}.cc-category i{font-style:normal;font-size:18px;transition:transform .3s var(--ease)}.cc-category:hover i{transform:translateX(5px)}
.cc-service-grid{display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid var(--line)}.cc-service{padding:24px 22px 18px;border-bottom:1px solid var(--line);transition:transform .4s var(--ease)}.cc-service:hover{transform:translateY(-4px)}.cc-service h3{font:normal 24px var(--serif);margin:18px 0 6px}.cc-service p{margin:0;color:#777;font-size:14px;line-height:1.7}.cc-badge{position:absolute;top:12px;right:12px;padding:7px 9px;background:#111;color:#fff;font-size:8px;letter-spacing:.12em;text-transform:uppercase}.cc-stars{font-size:12px;letter-spacing:.13em;margin:12px 0;color:#222}.cc-category{display:grid;grid-template-columns:76px 1fr auto;align-items:center;gap:14px}.cc-category-thumb{width:76px;height:60px;object-fit:cover;filter:grayscale(100%);transition:filter .4s,transform .4s var(--ease)}.cc-category:hover .cc-category-thumb{filter:grayscale(35%);transform:scale(1.03)}.cc-category-explore{font-size:10px;letter-spacing:.1em;text-transform:uppercase;white-space:nowrap}.cc-fab{position:fixed;right:24px;bottom:24px;z-index:15;width:48px;height:48px;border:1px solid #fff;background:#111;color:#fff;font-size:20px;cursor:pointer;opacity:0;transform:translateY(12px);pointer-events:none;transition:opacity .3s,transform .35s var(--ease),background .25s}.cc-fab:hover{background:#333;transform:translateY(-3px)}.cc-fab.cc-visible{opacity:1;transform:none;pointer-events:auto}.cc-about{background:var(--ink);color:#fff;padding:100px 0;display:grid;grid-template-columns:.8fr 1.2fr;gap:8vw;align-items:center}.cc-about h2{max-width:440px}.cc-about p{font-size:15px;line-height:1.9;color:#c7c7c3;max-width:580px;padding:12px 0}.cc-about-note{border-top:1px solid #ffffff45;margin-top:28px;padding-top:17px;color:#ddd;font-size:9px;letter-spacing:.15em;text-transform:uppercase}.cc-about .cc-kicker{color:#a7a7a2}.cc-about-image{min-height:290px;border:1px solid #ffffff30;overflow:hidden;position:relative;background:#272727}.cc-about-image img{width:100%;height:100%;min-height:290px;object-fit:cover;filter:grayscale(100%);opacity:.8;transition:transform .9s var(--ease)}.cc-about-image:hover img{transform:scale(1.06)}.cc-about-image span{position:absolute;bottom:16px;left:17px;z-index:1;font-size:9px;letter-spacing:.16em;text-transform:uppercase;color:#fff}.cc-location{display:grid;grid-template-columns:.75fr 1.25fr;gap:50px;align-items:center}.cc-location h2{font:normal 54px/1 var(--serif);margin:12px 0 15px}.cc-location p{font-size:15px;color:#777;line-height:1.8}.cc-map{height:300px;border:1px solid var(--line);overflow:hidden}.cc-map iframe{width:100%;height:100%;border:0;filter:grayscale(100%)}.cc-contact{background:var(--mist)}.cc-contact-form{max-width:680px;margin:0 auto;background:#fff;border:1px solid var(--line);padding:30px}.cc-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}.cc-field{display:flex;flex-direction:column;gap:8px}.cc-field-full{grid-column:1/-1}.cc-field label{font-size:10px;letter-spacing:.14em;text-transform:uppercase}.cc-field input,.cc-field textarea{border:1px solid #c9c9c6;background:#fff;padding:13px 14px;outline:none;transition:border-color .25s,box-shadow .25s}.cc-field input:focus,.cc-field textarea:focus{border-color:#111;box-shadow:0 0 0 3px #1111}.cc-field input.is-invalid,.cc-field textarea.is-invalid{border-color:#111}.cc-field input.is-valid,.cc-field textarea.is-valid{border-color:#777}.cc-invalid{display:none;color:#222;font-size:11px}.cc-field .is-invalid~.cc-invalid{display:block}.cc-submit{margin-top:4px;cursor:pointer}.cc-reveal{opacity:0;transform:translateY(18px);transition:opacity .7s var(--ease),transform .7s var(--ease)}.cc-reveal.cc-in{opacity:1;transform:none}.cc-delay-1{transition-delay:.1s}.cc-delay-2{transition-delay:.2s}.cc-delay-3{transition-delay:.3s}.cc-toast{position:fixed;right:22px;top:22px;z-index:20;background:#111;color:#fff;padding:15px 19px;border:1px solid #555;font-size:11px;box-shadow:0 15px 40px #0003;animation:ccToastIn .4s var(--ease)}@keyframes ccToastIn{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:none}}@media(max-width:850px){.cc-wrap{width:min(100% - 40px,680px)}.cc-nav{height:72px}.cc-brand{width:142px}.cc-navlinks{gap:18px}.cc-navlinks a:nth-child(2){display:none}.cc-hero{grid-template-columns:1fr}.cc-hero-copy{padding:70px 32px 56px;min-height:490px}.cc-hero-art{min-height:350px}.cc-cards{grid-template-columns:repeat(2,1fr)}.cc-category-list{grid-template-columns:repeat(2,1fr)}.cc-about{grid-template-columns:1fr;gap:26px}.cc-service-grid{grid-template-columns:1fr}.cc-location{grid-template-columns:1fr;gap:20px}.cc-section-head{align-items:start;flex-direction:column}.cc-section-head p{max-width:460px}.cc-ticker-in{justify-content:flex-start;width:max-content;animation:ccMarquee 32s linear infinite}.cc-form-grid{grid-template-columns:1fr}.cc-field-full{grid-column:auto}}@media(max-width:520px){.cc-wrap{width:calc(100% - 36px)}.cc-topline{font-size:8px}.cc-navlinks{gap:14px;font-size:9px}.cc-nav-cta{display:none}.cc-hero-copy{padding:62px 22px 46px;min-height:440px}.cc-hero h1{font-size:60px}.cc-hero p{font-size:14px}.cc-hero-art{min-height:280px}.cc-actions{width:100%}.cc-actions .cc-button{flex:1;padding-inline:12px}.cc-ticker-in{font-size:8px}.cc-cards{gap:10px}.cc-card-photo{height:165px}.cc-card-body{padding:14px 12px}.cc-card h3{font-size:19px}.cc-price{font-size:9px}.cc-card-desc{font-size:11px}.cc-category-list{grid-template-columns:1fr}.cc-category{padding:18px 8px;grid-template-columns:62px 1fr auto}.cc-category-thumb{width:62px;height:52px}.cc-category-explore{display:none}.cc-about h2,.cc-location h2{font-size:45px}.cc-contact-form{padding:20px}}@keyframes ccMarquee{to{transform:translateX(-30%)}}@media(prefers-reduced-motion:reduce){.cafecat-page,.cafecat-page *{animation-duration:.01ms!important;transition-duration:.01ms!important}.cc-reveal{opacity:1;transform:none}}
</style>
<div class="cafecat-page" id="top">
<main>
<section class="cc-hero"><div class="cc-hero-copy"><div class="cc-hero-logo"><img src="<?= $baseUrl ?>cafe-cat_logo-cropped.jpg" alt="CaféCat — crafted for your cravings"></div><div class="cc-eyebrow">Coffee · matcha · comfort food</div><h1>Cravings?<br>Come get it at<br><em>caf&eacute;cat</em></h1><p>Savor the Moment: Premium Coffee, Ceremonial Matcha, Crispy Chicken &amp; Rice Bowls</p><div class="cc-actions"><a class="cc-button cc-button-dark" href="/menu">Explore food&nbsp; ?</a><a class="cc-button cc-button-light" href="/menu">Order now&nbsp; ?</a></div></div><div class="cc-hero-art"><img src="<?= $baseUrl ?>BG.jpg" alt=""></div></section>
<div class="cc-ticker"><div class="cc-wrap cc-ticker-in"><span>Good coffee, no rush</span><span>Maliit 12oz · Malaki 16oz</span><span>Made for your cravings</span><span>Good coffee, no rush</span></div></div>
<section class="cc-wrap cc-section" id="favorites"><div class="cc-section-head cc-reveal"><div><div class="cc-kicker">Customer Favorites</div><h2>Popular Orders</h2><p>Handpicked by our customers, loved by everyone.</p></div><a class="cc-text-link" href="/menu">View the full menu&nbsp; ?</a></div><div class="cc-cards">
<?php
$popular = [
    [
        "Spanish Latte",
        "cafecat/spanish-latte.jpg",
        "?80 12oz ? ?90 16oz",
        "Bestseller",
        "????½",
    ],
    [
        "CafeCat Signature Blend",
        "cafecat/signature-blend.jpg",
        "?120 16oz",
        "Must Try",
        "?????",
    ],
    ["Cacao Latte", "cafecat/cacao-latte.jpg", "?120 16oz", "Popular", "?????"],
    ["Chicken Pepper Steak", "cafecat/rice-bowls.jpg", "?155", "Hot", "????½"],
];
foreach (
    $popular
    as $i => $item
): ?><article class="cc-card popular-item cc-reveal cc-delay-<?= $i ?>"><div class="cc-card-photo"><img src="<?= $baseUrl .
    $item[1] ?>" alt="<?= htmlspecialchars(
    $item[0],
) ?>"><span class="cc-badge"><?= htmlspecialchars(
    $item[3],
) ?></span></div><div class="cc-card-body"><div class="cc-card-top"><h3 class="card-title"><?= htmlspecialchars(
    $item[0],
) ?></h3><span class="cc-price card-text"><?= htmlspecialchars(
    $item[2],
) ?></span></div><div class="cc-stars" aria-label="Customer rating"><?= $item[4] ?></div><div class="cc-card-foot"><span><?= htmlspecialchars(
    $item[3],
) ?></span><button class="cc-add-cart btn-add-cart" type="button" data-product="<?= htmlspecialchars(
    $item[0],
) ?>" data-price="<?= htmlspecialchars(
    $item[2],
) ?>" aria-label="Add <?= htmlspecialchars(
    $item[0],
) ?> to cart">Add&nbsp; +</button></div></div></article><?php endforeach;
?></div></section>
<section class="cc-wrap cc-section cc-services" id="services" aria-labelledby="cc-services-title"><div class="cc-section-head cc-reveal"><div><div class="cc-kicker">Why Choose Us</div><h2 id="cc-services-title">Our Services</h2></div><p>We provide quality service with love and care.</p></div><div class="cc-service-grid"><article class="cc-service cc-reveal"><h3>Fast Delivery</h3><p>Quick and reliable delivery service</p></article><article class="cc-service cc-reveal cc-delay-1"><h3>Quality Food</h3><p>Fresh ingredients and great taste</p></article><article class="cc-service cc-reveal cc-delay-2"><h3>24/7 Support</h3><p>Always here to help</p></article></div></section>
<section class="cc-menu-band cc-section" id="menu"><div class="cc-wrap"><div class="cc-section-head cc-reveal"><div><div class="cc-kicker">Explore Our Menu</div><h2>Browse Our Hottest<br>Menu</h2></div><p>From premium coffee to delicious meals, we have it all.</p></div><div class="cc-category-list"><?php foreach (
    $categories
    as $category
):
    $routePath = isset($subcategories[$category["name"]])
        ? "/menu/" . $subcategories[$category["name"]]
        : "/menu/" .
            $category[
                "name"
            ]; ?><a class="cc-category menu-category-card" href="<?= htmlspecialchars(
    $routePath,
) ?>"><img class="cc-category-thumb" src="<?= $baseUrl .
    ($category["image"] ??
        ($categoryImages[$category["name"]] ??
            "default.jpg")) ?>" alt=""><div><strong><?= htmlspecialchars(
    $category["name"],
) ?></strong><br><small><?= (int) $category[
    "product_count"
] ?> items available</small></div><span class="cc-category-explore btn-explore">Explore&nbsp; ?</span></a><?php
endforeach; ?></div></div></section>
<section class="cc-about" id="about-us"><div class="cc-wrap" style="display:contents"><div><div class="cc-kicker">About us</div><h2>Learn more about our story.</h2><p>Crafted for your cravings — from our best-loved Spanish Latte and must-try CafeCat Signature Blend to ceremonial-grade matcha, crispy boneless chicken, rice bowls, chick tenders and sandwiches.</p><p>Choose your size — Maliit 12oz or Malaki 16oz — pick your brew style, flavor and dip, and make every order your own. Come visit us and experience the warmth that makes caf&eacute;cat special.</p><div class="cc-about-note">Crafted for your cravings</div></div><div class="cc-about-image"><img src="<?= $baseUrl ?>cafecat/storefront-night.jpg" alt="CaféCat storefront at night"><span>See you at CaféCat</span></div></div></section>
</main>
<button class="cc-fab" id="backToTop" type="button" aria-label="Back to top" title="Back to top">?</button>
</div>
<script>
(function(){const page=document.querySelector('.cafecat-page');const backToTop=page.querySelector('#backToTop');const updateBackToTop=()=>backToTop.classList.toggle('cc-visible',window.scrollY>400);window.addEventListener('scroll',updateBackToTop,{passive:true});updateBackToTop();backToTop.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('cc-in');observer.unobserve(entry.target)}}),{threshold:.12,rootMargin:'0px 0px -30px 0px'});page.querySelectorAll('.cc-reveal').forEach(el=>observer.observe(el));
function validateField(field){const value=field.value.trim();let valid=value.length>0;if(field.id==='name')valid=value.length>=2;if(field.id==='email')valid=/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);if(field.id==='message')valid=value.length>=10;field.classList.toggle('is-valid',valid&&value.length>0);field.classList.toggle('is-invalid',!valid&&value.length>0);return valid}
page.querySelectorAll('#name,#email,#message').forEach(field=>{field.addEventListener('input',()=>validateField(field));field.addEventListener('blur',()=>validateField(field));field.addEventListener('focus',()=>field.classList.remove('is-invalid'))});
window.handleContactSubmit=function(event){event.preventDefault();const form=page.querySelector('#contactForm'),name=page.querySelector('#name'),email=page.querySelector('#email'),message=page.querySelector('#message'),button=page.querySelector('#contactSubmitBtn');if(![name,email,message].every(validateField)){const invalid=[name,email,message].find(field=>field.classList.contains('is-invalid')||!field.value.trim());if(invalid)invalid.focus();return false}const original=button.innerHTML;button.disabled=true;button.innerHTML='Sending...';setTimeout(()=>{button.disabled=false;button.innerHTML=original;form.reset();[name,email,message].forEach(field=>field.classList.remove('is-valid','is-invalid'));showToast('Message sent — thank you for reaching out!')},900);return false};
function showToast(message){const toast=document.createElement('div');toast.className='cc-toast';toast.textContent=message;page.appendChild(toast);setTimeout(()=>toast.remove(),4200)}
page.querySelectorAll('.cc-add-cart').forEach(button=>button.addEventListener('click',function(){const product=this.dataset.product;let count=parseInt(localStorage.getItem('cartCount')||'0',10)+1;localStorage.setItem('cartCount',String(count));const cart=document.getElementById('cartCount');if(cart){cart.textContent=String(count);cart.style.display='flex'}this.textContent='Added ?';showToast(product+' added to cart');setTimeout(()=>{this.textContent='Add  +'},1800)}));if(typeof window.updateCartCount==='function')window.updateCartCount();else{const cart=document.getElementById('cartCount');if(cart){const count=localStorage.getItem('cartCount')||'0';cart.textContent=count;cart.style.display=count==='0'?'none':'flex'}}})();
</script>
<?php
$content = ob_get_clean();
include __DIR__ . "/layouts/main.php";
 ?>


