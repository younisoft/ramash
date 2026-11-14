@php($freeShipping = number_format(config('store.free_shipping') / 100))
@php($shippingFee = number_format(config('store.shipping') / 100))
<p class="muted">Last updated: 14 November 2026</p>
@if(config('store.demo'))<div class="notice">The store is in its pre-launch stage. No payments are collected on the site and demo orders are not shipped. This policy will be updated when payment and shipping go live.</div>@endif

<p>Welcome to RAMASH. This page sets out the terms for using ramash.sa and explains how we handle your personal data. By using the site, creating an account or placing an order, you agree to it.</p>

<nav aria-label="On this page" class="policy-toc"><ol>
<li><a href="#use">Using the site</a></li>
<li><a href="#account">Your account</a></li>
<li><a href="#products">Products and prices</a></li>
<li><a href="#orders">Orders and tailoring</a></li>
<li><a href="#delivery">Shipping and delivery</a></li>
<li><a href="#returns">Exchanges and returns</a></li>
<li><a href="#privacy">Privacy and data protection</a></li>
<li><a href="#cookies">Cookies</a></li>
<li><a href="#rights">Your data rights</a></li>
<li><a href="#law">Governing law</a></li>
</ol></nav>

<h2 id="use">1. Using the site</h2>
<ul>
<li>All site content, including text, images, logos and designs, is owned by or licensed to RAMASH and may not be copied or republished for commercial use without written permission.</li>
<li>Do not use the site in ways that harm it or its users, such as unauthorised access attempts, heavy automated requests or misleading information.</li>
<li>We may update these terms when our services or the regulations change. The last-updated date appears at the top, and changes apply from the date they are published.</li>
</ul>

<h2 id="account">2. Your account</h2>
<ul>
<li>Provide accurate, current information when you create an account.</li>
<li>You are responsible for keeping your password confidential and for activity on your account. Tell us straight away if you suspect unauthorised use.</li>
<li>We may suspend accounts that breach these terms or misuse the site.</li>
</ul>

<h2 id="products">3. Products and prices</h2>
<ul>
<li>We aim for accurate descriptions and photos. Colours may vary slightly between screens, and some images are concept renders, which the product description states.</li>
<li>Prices are in Saudi riyals. Products marked “price coming soon” are not yet available to buy.</li>
<li>If a clearly wrong price appears because of a technical error, we may cancel the order, let you know and refund any amount paid in full.</li>
<li>The price stored on our side when you confirm the order applies, subject to stock availability.</li>
</ul>

<h2 id="orders">4. Orders and tailoring</h2>
<ul>
<li>You receive a reference number when your order is confirmed and can follow its status from your account.</li>
<li>Tailored items are made to the measurements you approve: either measurements we already hold, or new ones arranged with you. Preparation starts once measurements are approved.</li>
<li>You can cancel an order by contacting us before it is prepared or shipped. Once a made-to-order item is in production, cancellation may not be possible.</li>
</ul>

<h2 id="delivery">5. Shipping and delivery</h2>
<ul>
<li>Shipping costs SAR {{ $shippingFee }}, and is free when the products in your order total SAR {{ $freeShipping }} or more.</li>
<li>Expected preparation times are shown on the <a href="/pages/shipping">shipping page</a>; the carrier's delivery time is added to them.</li>
<li>If an order is delayed by circumstances beyond our control, we will tell you. If delivery fails and we cannot reach you, the order may be cancelled and any payment refunded.</li>
</ul>

<h2 id="returns">6. Exchanges and returns</h2>
<ul>
<li>Ready-made items can be exchanged or returned within 7 days of delivery if they are unused, in their original condition, and with all accessories and packaging.</li>
<li>Made-to-order items, items made to measurements you approved, and items damaged through misuse cannot be returned. Your rights for manufacturing defects or items that do not match their description are unaffected.</li>
<li>We cover return shipping for defective or wrongly sent items; otherwise the customer pays shipping.</li>
<li>Refunds go back to the original payment method after we receive and inspect the item. Full details are on the <a href="/pages/returns">returns page</a>.</li>
</ul>

<h2 id="privacy">7. Privacy and data protection</h2>
<p>We handle your data under the Personal Data Protection Law of the Kingdom of Saudi Arabia and collect only what we need to provide the service.</p>
<h3>Data we collect</h3>
<ul>
<li><strong>Account data:</strong> name, email and password. Passwords are stored as a one-way hash that no one can read.</li>
<li><strong>Order data:</strong> name, mobile number, city, address, notes and the products ordered.</li>
<li><strong>What you send us:</strong> contact-form messages and the product reviews you write.</li>
<li><strong>Payment data:</strong> we do not store card details on our servers. When online payment goes live, it is processed by a licensed payment provider.</li>
</ul>
<h3>How we use it</h3>
<ul>
<li>To process, deliver and contact you about your order.</li>
<li>To run your account and keep your favourites and bag.</li>
<li>To answer your enquiries and review product reviews before publishing them.</li>
<li>To protect the site against fraud and misuse, and to meet legal requirements.</li>
</ul>
<p>We do not sell or rent your data, and we do not send marketing messages without your consent.</p>
<h3>Who we share it with</h3>
<ul>
<li>The hosting provider that runs the site's servers.</li>
<li>Shipping and payment providers once they are live, only as far as needed to fulfil your order.</li>
<li>Government authorities when there is a lawful request.</li>
</ul>
<h3>Security and retention</h3>
<ul>
<li>The site runs over an encrypted connection (HTTPS), and access to the admin panel is limited to authorised staff.</li>
<li>We keep order records for as long as accounting and commercial regulations require, and account data while the account exists. When you ask for deletion, we delete what we are not legally required to keep.</li>
</ul>

<h2 id="cookies">8. Cookies</h2>
<ul>
<li>We only use cookies the site needs to work: your browsing session, your bag, favourites and language, signing in, and protection against forged requests.</li>
<li>We do not currently use advertising trackers or third-party analytics.</li>
<li>The site's fonts load from Google Fonts, so your device's IP address reaches Google when a page loads.</li>
</ul>

<h2 id="rights">9. Your data rights</h2>
<p>You have the right to:</p>
<ul>
<li>Know what data we hold about you and receive a copy of it.</li>
<li>Correct or complete inaccurate data.</li>
<li>Ask us to delete your data when it is no longer needed.</li>
<li>Withdraw consent for any processing based on consent.</li>
</ul>
<p>Send your request through the <a href="/pages/contact">contact page</a> from the email registered on your account, and we will reply within 30 days. You can also complain to the Saudi Data and AI Authority (SDAIA).</p>

<h2 id="law">10. Governing law</h2>
<p>These terms are governed by the laws of the Kingdom of Saudi Arabia, including the E-Commerce Law and the Personal Data Protection Law, and Saudi courts have jurisdiction over any dispute. Nothing here limits your statutory rights as a consumer.</p>

<h2>Contact us</h2>
<p>For any question about this policy or your data, contact us and we will get back to you as soon as we can.</p>
<a class="button" href="/pages/contact">Contact us</a>
