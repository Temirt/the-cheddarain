/**
 * The Cheddarain — McDonald's-Inspired Live Order Tracking Engine
 * Auto-polls status every 7s, animates 5-step progress, countdown timers, and audit histories
 */

class OrderTracker {
    constructor(orderReference, initialStatus, initialStep) {
        this.reference = orderReference;
        this.currentStatus = initialStatus;
        this.currentStep = initialStep;
        this.pollInterval = 7000; // 7 seconds
        this.pollTimer = null;
        this.isPolling = true;

        this.init();
    }

    init() {
        this.startPolling();
        this.setupVisibilityHandler();
    }

    startPolling() {
        if (this.pollTimer) clearInterval(this.pollTimer);
        this.pollTimer = setInterval(() => this.fetchUpdate(), this.pollInterval);
    }

    stopPolling() {
        if (this.pollTimer) {
            clearInterval(this.pollTimer);
            this.pollTimer = null;
        }
        this.isPolling = false;
    }

    setupVisibilityHandler() {
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                // Pause aggressive polling when tab is hidden
                if (this.pollTimer) clearInterval(this.pollTimer);
            } else {
                // Resume and immediately check for status change
                this.fetchUpdate();
                this.startPolling();
            }
        });
    }

    async fetchUpdate() {
        if (!this.reference) return;

        try {
            const response = await fetch(`/api/orders/${this.reference}/track`);
            if (!response.ok) return;

            const data = await response.json();
            this.handleDataUpdate(data);
        } catch (error) {
            console.warn('[Tracker] Polling check failed, will retry...', error);
        }
    }

    handleDataUpdate(data) {
        const stepChanged = (data.timeline_step !== this.currentStep);
        const statusChanged = (data.status !== this.currentStatus);

        this.currentStatus = data.status;
        this.currentStep = data.timeline_step;

        // If status advanced, trigger audio/visual feedback
        if (stepChanged || statusChanged) {
            this.playStatusAdvanceChime();
        }

        // 1. Update Timeline Steps & Progress Bar
        this.updateTimelineUI(data);

        // 2. Update Status Banner & Headline
        this.updateStatusBanner(data);

        // 3. Update Audit History Log
        this.updateAuditHistory(data);

        // 4. Update Last Updated Label
        const lastUpdatedEl = document.getElementById('tracker-last-updated');
        if (lastUpdatedEl) {
            lastUpdatedEl.textContent = `Updated ${data.last_updated}`;
        }

        // Stop polling if terminal state reached
        if (data.status === 'delivered' || data.status === 'cancelled') {
            this.stopPolling();
            const liveIndicator = document.getElementById('live-poll-indicator');
            if (liveIndicator) {
                liveIndicator.innerHTML = (data.status === 'delivered') 
                    ? '<span style="color: #10b981;">✓ Order Complete</span>'
                    : '<span style="color: #ef4444;">✕ Order Closed</span>';
            }
        }
    }

    updateTimelineUI(data) {
        const progressBar = document.getElementById('timeline-progress-fill');
        if (progressBar) {
            progressBar.style.width = `${data.progress_percent}%`;
        }

        // Update step nodes (1 to 5)
        for (let i = 1; i <= 5; i++) {
            const stepEl = document.getElementById(`timeline-step-${i}`);
            if (!stepEl) continue;

            stepEl.classList.remove('completed', 'active');

            if (data.status === 'cancelled') {
                // Edge state: cancelled
                continue;
            }

            if (i < data.timeline_step) {
                stepEl.classList.add('completed');
            } else if (i === data.timeline_step) {
                stepEl.classList.add('active');
            }
        }

        // Update step 4 label dynamically based on pickup vs delivery
        const step4Label = document.getElementById('step-4-label');
        if (step4Label) {
            step4Label.textContent = (data.fulfillment_type === 'pickup') ? 'Ready for Pickup' : 'Out for Delivery';
        }
    }

    updateStatusBanner(data) {
        const headlineEl = document.getElementById('tracker-status-headline');
        const descEl = document.getElementById('tracker-status-desc');
        const countdownValEl = document.getElementById('tracker-countdown-value');

        if (headlineEl) headlineEl.textContent = data.status_label;

        if (descEl) {
            let message = '';
            switch (data.status) {
                case 'pending':
                    message = 'We have received your order. The kitchen will review and confirm shortly.';
                    break;
                case 'confirmed':
                    message = 'Your order is confirmed! Chefs are queuing up your artisanal cheddar feast.';
                    break;
                case 'preparing':
                    message = 'Cooking fresh right now! Patties sizzling and cheddar lava dripping.';
                    break;
                case 'ready_for_pickup':
                    message = 'Your order is bagged and waiting hot at the counter. Show order #' + data.reference + ' at pickup!';
                    break;
                case 'out_for_delivery':
                    message = 'Our delivery driver is en route with your piping hot order!';
                    break;
                case 'delivered':
                    message = 'Delivered! We hope you loved every bite of The Cheddarain experience.';
                    break;
                case 'cancelled':
                    message = `Order was cancelled. Reason: ${data.cancellation_reason || 'Restaurant capacity / closed'}. Contact support if you need assistance.`;
                    break;
                default:
                    message = 'Order is in progress.';
            }
            descEl.textContent = message;
        }

        if (countdownValEl) {
            if (data.status === 'preparing' && data.estimated_prep_minutes) {
                countdownValEl.textContent = `~${data.estimated_prep_minutes} mins`;
            } else if (data.status === 'ready_for_pickup') {
                countdownValEl.textContent = 'READY NOW';
                countdownValEl.style.color = 'var(--success)';
            } else if (data.status === 'out_for_delivery') {
                countdownValEl.textContent = 'ON THE WAY';
                countdownValEl.style.color = 'var(--cheddar-bright)';
            } else if (data.status === 'delivered') {
                countdownValEl.textContent = 'COMPLETED';
                countdownValEl.style.color = 'var(--success)';
            } else if (data.status === 'cancelled') {
                countdownValEl.textContent = 'CANCELLED';
                countdownValEl.style.color = 'var(--danger)';
            } else {
                countdownValEl.textContent = '--';
            }
        }
    }

    updateAuditHistory(data) {
        const historyList = document.getElementById('tracker-audit-history');
        if (!historyList || !data.status_histories) return;

        historyList.innerHTML = '';
        data.status_histories.forEach(h => {
            const li = document.createElement('li');
            li.className = 'audit-item';
            li.innerHTML = `
                <div class="audit-dot"></div>
                <div class="audit-time">${h.time}${h.staff_name ? ' • ' + h.staff_name : ''}</div>
                <div class="audit-note">${h.note || 'Status updated.'}</div>
            `;
            historyList.appendChild(li);
        });
    }

    playStatusAdvanceChime() {
        try {
            // Web Audio API chime
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();

            const now = ctx.currentTime;
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, now); // D5
            osc.frequency.exponentialRampToValueAtTime(880.00, now + 0.15); // A5

            gain.gain.setValueAtTime(0.2, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.5);

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.start(now);
            osc.stop(now + 0.5);
        } catch (e) {
            // Audio context might be restricted before user gesture
        }
    }
}

window.OrderTracker = OrderTracker;
