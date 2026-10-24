/**
 * The Cheddarloin — Main Vanilla JavaScript
 * Mobile Navigation, Interactive Customization Modal, and Menu Filtering
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const navToggle = document.getElementById('nav-toggle');
    const navLinks = document.getElementById('nav-links');

    if (navToggle && navLinks) {
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
            navToggle.textContent = navLinks.classList.contains('open') ? '✕' : '☰';
        });

        // Close when clicking a link
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('open');
                navToggle.textContent = '☰';
            });
        });
    }

    // 2. Interactive FAQ Accordion
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const questionBtn = item.querySelector('.faq-question');
        if (questionBtn) {
            questionBtn.addEventListener('click', () => {
                const isOpen = item.classList.contains('open');
                // Close all
                faqItems.forEach(i => i.classList.remove('open'));
                if (!isOpen) {
                    item.classList.add('open');
                }
            });
        }
    });

    // 3. Client-side Menu Category Filter and Live Search
    const searchInput = document.getElementById('menu-search-input');
    const productCards = document.querySelectorAll('.product-card[data-category]');
    const categoryFilterBtns = document.querySelectorAll('.category-filter-btn');

    if (categoryFilterBtns.length > 0 && productCards.length > 0) {
        categoryFilterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                categoryFilterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const selectedCategory = btn.dataset.category || 'all';
                productCards.forEach(card => {
                    const cardCategory = card.dataset.category;
                    if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            productCards.forEach(card => {
                const name = card.querySelector('.product-title')?.textContent.toLowerCase() || '';
                const desc = card.querySelector('.product-desc')?.textContent.toLowerCase() || '';
                const matches = name.includes(query) || desc.includes(query);
                card.style.display = matches ? 'flex' : 'none';
            });
        });
    }

    // 4. Customization Modal Dialog
    const modalBackdrop = document.getElementById('customization-modal');
    const modalCloseBtn = document.getElementById('modal-close-btn');

    if (modalBackdrop && modalCloseBtn) {
        modalCloseBtn.addEventListener('click', () => closeModal());
        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) closeModal();
        });
    }

    function closeModal() {
        if (modalBackdrop) modalBackdrop.classList.remove('open');
    }

    // Attach click handler to "Customize & Add" buttons
    document.querySelectorAll('.open-customize-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const productId = btn.dataset.productId;
            if (!productId) return;
            await openCustomizeModal(productId);
        });
    });

    // Quick Add button (for items without required customizations or direct Add + button)
    document.querySelectorAll('.quick-add-btn, .btn-add').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const product = {
                id: parseInt(btn.dataset.productId),
                name: btn.dataset.productName,
                price: parseFloat(btn.dataset.productPrice),
                image_url: btn.dataset.productImage
            };
            Cart.addItem(product, 1, []);
        });
    });

    async function openCustomizeModal(productId) {
        if (!modalBackdrop) return;

        const modalTitle = document.getElementById('modal-product-title');
        const modalDesc = document.getElementById('modal-product-desc');
        const modalBasePrice = document.getElementById('modal-base-price');
        const modalTotalBtn = document.getElementById('modal-add-to-cart-btn');
        const optionsContainer = document.getElementById('modal-options-container');

        // Loading state
        optionsContainer.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--text-cream-muted);">Loading artisanal options...</div>';
        modalBackdrop.classList.add('open');

        try {
            const res = await fetch(`/api/product-options/${productId}`);
            if (!res.ok) throw new Error('Failed to load product');
            const data = await res.json();

            modalTitle.textContent = data.name;
            modalDesc.textContent = data.description;
            modalBasePrice.textContent = Cart.formatPrice(data.price);

            let html = '';
            let currentOptionsTotal = 0;

            if (data.options && data.options.length > 0) {
                data.options.forEach(opt => {
                    html += `
                        <div class="option-group" data-option-id="${opt.id}" data-option-name="${opt.name}">
                            <div class="option-group-title">
                                <span>${opt.name}</span>
                                ${opt.is_required ? '<span class="badge badge-cheddar" style="font-size: 0.65rem;">Required</span>' : '<span style="font-size: 0.75rem; color: var(--text-dim);">Optional</span>'}
                            </div>
                    `;

                    opt.values.forEach(val => {
                        const isRadio = opt.type === 'radio';
                        const inputType = isRadio ? 'radio' : 'checkbox';
                        const inputName = `opt_${opt.id}`;
                        const isChecked = val.is_default ? 'checked' : '';
                        const priceLabel = parseFloat(val.price) > 0 ? `+${Cart.formatPrice(val.price)}` : 'Free';

                        html += `
                            <label class="option-item-row">
                                <div class="option-label-info">
                                    <input type="${inputType}" name="${inputName}" value="${val.id}" data-price="${val.price}" data-val-name="${val.name}" ${isChecked}>
                                    <span>${val.name}</span>
                                </div>
                                <span class="badge badge-gold" style="font-size: 0.75rem;">${priceLabel}</span>
                            </label>
                        `;
                    });

                    html += `</div>`;
                });
            } else {
                html = '<p style="color: var(--text-cream-muted); margin-bottom: 1.5rem;">No extra customizations needed for this gourmet item.</p>';
            }

            optionsContainer.innerHTML = html;

            // Function to recalculate modal total
            const recalculateModalTotal = () => {
                let optionsAddon = 0;
                optionsContainer.querySelectorAll('input:checked').forEach(input => {
                    optionsAddon += parseFloat(input.dataset.price) || 0;
                });
                const finalTotal = data.price + optionsAddon;
                modalTotalBtn.textContent = `Add to Cart • ${Cart.formatPrice(finalTotal)}`;
            };

            // Listen to option changes
            optionsContainer.querySelectorAll('input').forEach(input => {
                input.addEventListener('change', recalculateModalTotal);
            });

            recalculateModalTotal();

            // Set submit action
            modalTotalBtn.onclick = () => {
                const selectedOptions = [];
                optionsContainer.querySelectorAll('.option-group').forEach(group => {
                    const optName = group.dataset.optionName;
                    group.querySelectorAll('input:checked').forEach(input => {
                        selectedOptions.push({
                            option_name: optName,
                            value_id: parseInt(input.value),
                            choice: input.dataset.valName,
                            price: parseFloat(input.dataset.price) || 0
                        });
                    });
                });

                Cart.addItem({
                    id: data.id,
                    name: data.name,
                    price: data.price,
                    image_url: data.image_url
                }, 1, selectedOptions);

                closeModal();
            };

        } catch (err) {
            optionsContainer.innerHTML = '<div style="color: var(--danger); text-align: center; padding: 1rem;">Failed to load options. Please try again.</div>';
        }
    }
});
