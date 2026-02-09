// DOM Elements
const productsGrid = document.querySelector('.products-grid');
const searchInput = document.querySelector('#search-input');
const cartCountElement = document.querySelector('.cart-count');

/**
 * Fetch and display products with animations
 */
async function loadProducts(filter = '') {
    if (!productsGrid) return;

    // Show loading state
    productsGrid.style.opacity = '0.5';

    try {
        const url = filter ? `includes/get_products.php?${filter}` : 'includes/get_products.php';
        const response = await fetch(url);
        const products = await response.json();

        productsGrid.innerHTML = '';
        productsGrid.style.opacity = '1';

        if (products.length === 0) {
            productsGrid.innerHTML = '<p class="animate-fade" style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted);">No products found matching your search.</p>';
            return;
        }

        products.forEach((product, index) => {
            const productCard = createProductCard(product);
            productCard.style.animationDelay = `${index * 0.05}s`;
            productCard.classList.add('animate-fade');
            productsGrid.appendChild(productCard);
        });
    } catch (error) {
        console.error('Error loading products:', error);
        showNotification('Failed to load products', 'error');
    }
}

/**
 * Create a premium glassmorphic product card
 */
function createProductCard(product) {
    const card = document.createElement('article');
    card.className = 'product-card glass-card';

    // Image source logic
    let src = product.image || 'assets/images/default-product.jpg';

    card.innerHTML = `
        <div class="product-image-wrapper" style="position: relative; overflow: hidden; border-radius: var(--radius-md);">
            <img src="${src}" alt="${product.name}" style="width: 100%; height: 220px; object-fit: cover; transition: var(--transition);">
            ${product.is_organic ? '<span class="organic-badge" style="position: absolute; top: 12px; right: 12px; background: var(--primary); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">Organic</span>' : ''}
        </div>
        <div class="product-info" style="margin-top: 15px;">
            <h3 style="font-size: 1.2rem; margin-bottom: 5px;">${product.name}</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-bottom: 15px;">${product.description}</p>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <span style="font-size: 1.4rem; font-weight: 700; color: var(--primary-light);">₹${product.price}</span>
                <span style="font-size: 0.8rem; color: var(--text-muted);">Stock: ${product.stock}</span>
            </div>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 15px;">Seller: <span style="color: var(--primary);">${product.farmer_name || 'Farmer'}</span></p>
            <button onclick="addToCart(${product.id})" class="add-to-cart-btn" style="width: 100%;">Add to Cart</button>
        </div>
    `;

    return card;
}

/**
 * Cart Functionality
 */
async function addToCart(productId) {
    try {
        const response = await fetch('includes/add_to_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Added to cart!', 'success');
            updateCartCount();
        } else {
            showNotification(result.message || 'Failed to add', 'error');
        }
    } catch (error) {
        showNotification('Connection error', 'error');
    }
}

async function updateCartCount() {
    try {
        const response = await fetch('includes/get_cart_count.php');
        const data = await response.json();
        if (cartCountElement) {
            cartCountElement.textContent = data.count;
            cartCountElement.classList.add('pulse');
            setTimeout(() => cartCountElement.classList.remove('pulse'), 1000);
        }
    } catch (error) { }
}

/**
 * Notification System
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `glass-panel notification-v2 ${type}`;
    notification.innerHTML = `
        <span class="notif-icon">${type === 'success' ? '✅' : '❌'}</span>
        <span class="notif-text">${message}</span>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// Add notification styles
const notifStyles = document.createElement('style');
notifStyles.textContent = `
    .notification-v2 {
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 15px 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 9999;
        animation: slideInUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(20, 20, 20, 0.9);
        border: 1px solid var(--glass-border);
        box-shadow: var(--shadow-premium);
    }
    .notification-v2.success { border-left: 4px solid var(--primary); }
    .notification-v2.error { border-left: 4px solid #f44336; }
    .notif-icon { font-size: 1.2rem; }
    .notif-text { font-weight: 500; font-size: 0.9rem; }
    
    @keyframes slideInUp {
        from { transform: translateY(100px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .fade-out { opacity: 0; transform: translateY(20px); transition: var(--transition); }
    
    .pulse { animation: pulseAnim 0.5s ease-in-out; }
    @keyframes pulseAnim {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(notifStyles);

/**
 * Debounce utility
 */
function debounce(func, wait) {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    updateCartCount();

    if (searchInput) {
        searchInput.addEventListener('input', debounce((e) => {
            loadProducts(`term=${e.target.value}`);
        }, 300));
    }
});