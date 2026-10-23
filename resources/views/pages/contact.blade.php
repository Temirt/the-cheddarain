@extends('layouts.app')

@section('title', 'Contact Us & Hours — The Cheddarloin')

@section('content')
<div class="container" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="section-title">
        <span class="badge badge-cheddar">Get In Touch</span>
        <h2>Visit Us or Send a Message</h2>
        <p>Questions, catering inquiries, or feedback? We’d love to hear from you.</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 3.5rem; align-items: start;">
        <!-- Contact Info & Opening Hours -->
        <div>
            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.3rem; margin-bottom: 1.5rem; color: var(--gold-bright);">Restaurant Information</h3>

                <div style="display: flex; flex-direction: column; gap: 1.4rem;">
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <span style="font-size: 1.5rem;">📍</span>
                        <div>
                            <strong style="color: var(--text-cream); display: block;">Address</strong>
                            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">
                                {{ \App\Models\RestaurantSetting::get('restaurant_address', '108 Artisan Burger Way, Melt District') }}
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <span style="font-size: 1.5rem;">📞</span>
                        <div>
                            <strong style="color: var(--text-cream); display: block;">Phone Number</strong>
                            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">
                                {{ \App\Models\RestaurantSetting::get('restaurant_phone', '+1 (555) 243-3327') }}
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <span style="font-size: 1.5rem;">✉️</span>
                        <div>
                            <strong style="color: var(--text-cream); display: block;">Email</strong>
                            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">
                                {{ \App\Models\RestaurantSetting::get('restaurant_email', 'orders@thecheddarloin.com') }}
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <span style="font-size: 1.5rem;">💬</span>
                        <div>
                            <strong style="color: var(--text-cream); display: block;">WhatsApp Direct Line</strong>
                            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">
                                {{ \App\Models\RestaurantSetting::get('whatsapp_number', '+15552433327') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle);">
                    @php
                        $rawWa = \App\Models\RestaurantSetting::get('whatsapp_number', '15552433327');
                        $cleanWa = preg_replace('/[^0-9]/', '', $rawWa);
                    @endphp
                    <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode('Hello The Cheddarloin, I have a quick question!') }}" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-block">
                        <span>💬 Chat on WhatsApp</span>
                    </a>
                </div>
            </div>

            <!-- Operating Hours -->
            <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2rem;">
                <h4 style="font-size: 1.15rem; margin-bottom: 1rem; color: var(--gold-bright);">🕒 Operating Hours</h4>
                <div style="display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.92rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Monday – Thursday</span>
                        <span style="color: var(--text-cream-soft); font-weight: 600;">11:00 AM – 11:00 PM</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Friday – Saturday</span>
                        <span style="color: var(--gold-bright); font-weight: 600;">11:00 AM – Midnight</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Sunday</span>
                        <span style="color: var(--text-cream-soft); font-weight: 600;">11:00 AM – 10:30 PM</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2.5rem; box-shadow: var(--shadow-lg);">
            <h3 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Send Us a Message</h3>

            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;">
                    <div class="form-group">
                        <label class="form-label" for="contact_name">Your Name *</label>
                        <input type="text" name="name" id="contact_name" class="form-input" required placeholder="Michael">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contact_email">Email Address *</label>
                        <input type="email" name="email" id="contact_email" class="form-input" required placeholder="michael@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="contact_phone">Phone Number (Optional)</label>
                    <input type="tel" name="phone" id="contact_phone" class="form-input" placeholder="e.g. 555-123-4567">
                </div>

                <div class="form-group">
                    <label class="form-label" for="contact_subject">Subject *</label>
                    <input type="text" name="subject" id="contact_subject" class="form-input" required placeholder="e.g. Event Catering or General Question">
                </div>

                <div class="form-group">
                    <label class="form-label" for="contact_message">Your Message *</label>
                    <textarea name="message" id="contact_message" class="form-textarea" required placeholder="How can we assist you?"></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 1rem;">
                    <span>Send Message</span>
                    <span>&rarr;</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
