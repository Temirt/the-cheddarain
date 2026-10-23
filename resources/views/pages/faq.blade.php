@extends('layouts.app')

@section('title', 'Frequently Asked Questions — The Cheddarloin')

@push('styles')
<style>
.faq-item {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg);
    margin-bottom: 1rem;
    overflow: hidden;
    transition: all 0.2s ease;
}
.faq-question {
    width: 100%;
    background: transparent;
    border: none;
    padding: 1.4rem 1.8rem;
    text-align: left;
    font-family: var(--font-heading);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-cream);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.faq-icon {
    font-size: 1.2rem;
    transition: transform 0.25s ease;
    color: var(--cheddar-bright);
}
.faq-item.open .faq-icon {
    transform: rotate(45deg);
}
.faq-answer {
    display: none;
    padding: 0 1.8rem 1.6rem 1.8rem;
    color: var(--text-cream-muted);
    font-size: 0.98rem;
    line-height: 1.65;
}
.faq-item.open .faq-answer {
    display: block;
}
</style>
@endpush

@section('content')
<div class="container container-narrow" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="section-title">
        <span class="badge badge-cheddar">Help Center</span>
        <h2>Frequently Asked Questions</h2>
        <p>Everything you need to know about our ingredients, ordering, delivery, and real-time tracking.</p>
    </div>

    <div class="faq-list">
        <div class="faq-item open">
            <button type="button" class="faq-question">
                <span>How does the McDonald's-style live order tracking work?</span>
                <span class="faq-icon">+</span>
            </button>
            <div class="faq-answer">
                When you place an order, our system generates a unique reference number (e.g., #CHED-782901). You can view the live progress feed on our <strong>Track My Order</strong> page. As kitchen staff confirm your ticket, start grilling, and bag your food for pickup or delivery, the 5-stage timeline updates automatically in real-time without needing to refresh your browser.
            </div>
        </div>

        <div class="faq-item">
            <button type="button" class="faq-question">
                <span>Why do I need to verify my phone number to track an order?</span>
                <span class="faq-icon">+</span>
            </button>
            <div class="faq-answer">
                To protect your privacy and ensure strangers cannot view your delivery address or contact details, our system verifies access using the phone number registered during checkout.
            </div>
        </div>

        <div class="faq-item">
            <button type="button" class="faq-question">
                <span>What kind of beef is used in The Cheddarloin burgers?</span>
                <span class="faq-icon">+</span>
            </button>
            <div class="faq-answer">
                We use exclusively 100% prime Angus beef—a custom coarse grind of chuck, brisket, and short rib. Our beef is freshly ground daily and never frozen, with zero artificial fillers or preservatives.
            </div>
        </div>

        <div class="faq-item">
            <button type="button" class="faq-question">
                <span>Do you offer gluten-free or allergen-friendly options?</span>
                <span class="faq-icon">+</span>
            </button>
            <div class="faq-answer">
                Yes! Every burger can be customized with our artisanal Gluten-Free Bun or a crisp Iceberg Lettuce Wrap (Low Carb). If you have severe allergies to dairy, gluten, or sesame, please specify in the special instructions at checkout or contact our kitchen dispatch via WhatsApp.
            </div>
        </div>

        <div class="faq-item">
            <button type="button" class="faq-question">
                <span>How do payments work? Do I need to pay online?</span>
                <span class="faq-icon">+</span>
            </button>
            <div class="faq-answer">
                No online payment is required! We use an in-person payment model. When you place an order on our website, you instantly receive a unique <strong>Order Reference ID</strong> (e.g. #CHED-782901). For pickup orders, simply bring this Order ID to our store counter and pay with Cash, Debit/Credit Card, or Contactless (Apple/Google Pay). For home delivery orders, show your Order ID to the courier upon arrival and pay in cash.
            </div>
        </div>

        <div class="faq-item">
            <button type="button" class="faq-question">
                <span>What is your typical preparation and delivery timeframe?</span>
                <span class="faq-icon">+</span>
            </button>
            <div class="faq-answer">
                Kitchen preparation typically takes 12 to 15 minutes because every smash patty is seared fresh to order. For delivery orders, typical transit time is 15 to 25 minutes depending on traffic and your distance from our store.
            </div>
        </div>
    </div>

    <div style="margin-top: 3.5rem; text-align: center; background: var(--bg-surface); padding: 2.5rem; border-radius: var(--radius-xl); border: 1px solid var(--border-subtle);">
        <h3 style="margin-bottom: 0.6rem;">Still Have Questions?</h3>
        <p style="color: var(--text-cream-muted); margin-bottom: 1.5rem;">Our customer care team is available on WhatsApp daily from 11:00 AM to 11:00 PM.</p>
        <a href="{{ route('contact') }}" class="btn btn-secondary">Contact Us Directly &rarr;</a>
    </div>
</div>
@endsection
