// ====== MOCK DATA ======
const PRODUCTS = [
  { id: 1, name: 'Wireless Headphones', category: 'Electronics', price: 129.99, oldPrice: 179.99, rating: 4.5, reviews: 128, image: '🎧', badge: 'Sale', colors: ['#2D3436', '#fff', '#6C5CE7'] },
  { id: 2, name: 'Smart Watch Pro', category: 'Electronics', price: 249.99, oldPrice: 299.99, rating: 4.7, reviews: 95, image: '⌚', badge: 'New', colors: ['#2D3436', '#E17055'] },
  { id: 3, name: 'Organic Cotton Tee', category: 'Fashion', price: 34.99, oldPrice: null, rating: 4.3, reviews: 210, image: '👕', badge: null, colors: ['#fff', '#6C5CE7', '#E17055'] },
  { id: 4, name: 'Running Sneakers', category: 'Fashion', price: 89.99, oldPrice: 119.99, rating: 4.6, reviews: 156, image: '👟', badge: 'Sale', colors: ['#fff', '#2D3436', '#E17055'] },
  { id: 5, name: 'Leather Backpack', category: 'Fashion', price: 79.99, oldPrice: null, rating: 4.4, reviews: 73, image: '🎒', badge: null, colors: ['#6C5CE7', '#2D3436'] },
  { id: 6, name: 'Bluetooth Speaker', category: 'Electronics', price: 59.99, oldPrice: 79.99, rating: 4.2, reviews: 201, image: '🔊', badge: 'Sale', colors: ['#E17055', '#2D3436', '#6C5CE7'] },
  { id: 7, name: 'Yoga Mat Premium', category: 'Sports', price: 44.99, oldPrice: null, rating: 4.8, reviews: 312, image: '🧘', badge: 'Best Seller', colors: ['#6C5CE7', '#00CEC9'] },
  { id: 8, name: 'Coffee Maker Deluxe', category: 'Home', price: 149.99, oldPrice: 199.99, rating: 4.1, reviews: 88, image: '☕', badge: 'Sale', colors: ['#2D3436', '#fff'] },
  { id: 9, name: 'Denim Jacket', category: 'Fashion', price: 94.99, oldPrice: null, rating: 4.5, reviews: 64, image: '🧥', badge: 'New', colors: ['#2D3436', '#E17055'] },
  { id: 10, name: 'Wireless Mouse', category: 'Electronics', price: 29.99, oldPrice: 49.99, rating: 4.0, reviews: 420, image: '🖱️', badge: 'Sale', colors: ['#2D3436', '#fff'] },
  { id: 11, name: 'Sunglasses Aviator', category: 'Fashion', price: 54.99, oldPrice: null, rating: 4.3, reviews: 47, image: '🕶️', badge: null, colors: ['#2D3436', '#E17055'] },
  { id: 12, name: 'Fitness Tracker', category: 'Sports', price: 39.99, oldPrice: 59.99, rating: 4.4, reviews: 178, image: '🏃', badge: 'Sale', colors: ['#6C5CE7', '#00CEC9', '#E17055'] }
];

const ORDERS = [
  { id: 'ORD-1001', date: '2026-06-15', total: 164.98, status: 'delivered', items: 3, tracking: '1Z999AA10123456784' },
  { id: 'ORD-1002', date: '2026-06-18', total: 249.99, status: 'shipped', items: 1, tracking: '1Z999AA10123456785' },
  { id: 'ORD-1003', date: '2026-06-20', total: 89.99, status: 'processing', items: 2, tracking: '1Z999AA10123456786' }
];

// ====== UTILITY FUNCTIONS ======
function $(sel, ctx = document) { return ctx.querySelector(sel); }
function $$(sel, ctx = document) { return [...ctx.querySelectorAll(sel)]; }

function getCart() {
  try { return JSON.parse(localStorage.getItem('cart')) || []; } catch { return []; }
}
function setCart(cart) {
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCartCount();
}
function addToCart(productId, qty = 1) {
  const cart = getCart();
  const idx = cart.findIndex(c => c.id === productId);
  if (idx > -1) cart[idx].qty += qty; else cart.push({ id: productId, qty });
  setCart(cart);
  showToast('Added to cart!', 'success');
}
function removeFromCart(productId) {
  setCart(getCart().filter(c => c.id !== productId));
}
function updateCartQty(productId, qty) {
  if (qty < 1) return removeFromCart(productId);
  const cart = getCart();
  const item = cart.find(c => c.id === productId);
  if (item) item.qty = qty;
  setCart(cart);
}
function getCartTotal() {
  return getCart().reduce((sum, item) => {
    const p = PRODUCTS.find(x => x.id === item.id);
    return sum + (p ? p.price * item.qty : 0);
  }, 0);
}
function getCartCount() {
  return getCart().reduce((sum, item) => sum + item.qty, 0);
}
function updateCartCount() {
  $$('.cart-count').forEach(el => { el.textContent = getCartCount(); el.style.display = getCartCount() > 0 ? 'flex' : 'none'; });
}

function getWishlist() {
  try { return JSON.parse(localStorage.getItem('wishlist')) || []; } catch { return []; }
}
function setWishlist(wl) { localStorage.setItem('wishlist', JSON.stringify(wl)); updateWishlistUI(); }
function toggleWishlist(productId) {
  const wl = getWishlist();
  const idx = wl.indexOf(productId);
  if (idx > -1) wl.splice(idx, 1); else wl.push(productId);
  setWishlist(wl);
  showToast(idx > -1 ? 'Removed from wishlist' : 'Added to wishlist!', 'success');
}
function updateWishlistUI() {
  const wl = getWishlist();
  $$('.wishlist-btn').forEach(btn => {
    const id = parseInt(btn.dataset.id);
    btn.classList.toggle('active', wl.includes(id));
    btn.textContent = wl.includes(id) ? '♥' : '♡';
  });
}

function showToast(msg, type = '') {
  let t = $('#toast');
  if (!t) { t = document.createElement('div'); t.id = 'toast'; t.className = 'toast'; document.body.appendChild(t); }
  t.textContent = msg; t.className = 'toast ' + type;
  requestAnimationFrame(() => t.classList.add('show'));
  clearTimeout(t._hide);
  t._hide = setTimeout(() => t.classList.remove('show'), 3000);
}

function showModal(id) {
  const m = $(`#${id}`); if (!m) return;
  m.classList.add('open');
  m.addEventListener('click', function(e) { if (e.target === this) this.classList.remove('open'); });
}
function closeModal(id) { const m = $(`#${id}`); if (m) m.classList.remove('open'); }

function formatDate(d) { return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }); }
function formatCurrency(n) { return '$' + n.toFixed(2); }

// Stars HTML
function starsHTML(rating) {
  const full = Math.floor(rating); const half = rating % 1 >= 0.5;
  return '★'.repeat(full) + (half ? '½' : '') + '☆'.repeat(5 - full - (half ? 1 : 0));
}

// ====== COUPON DATA ======
const COUPONS = [
  { code: 'WELCOME10', discount: 0.10, type: 'percent', minSpend: 0, desc: '10% off your first order', expiry: '2026-12-31', used: false },
  { code: 'SAVE20', discount: 0.20, type: 'percent', minSpend: 100, desc: '20% off orders over $100', expiry: '2026-08-31', used: false },
  { code: 'FREESHIP', discount: 0, type: 'freeship', minSpend: 50, desc: 'Free shipping on orders $50+', expiry: '2026-12-31', used: false },
  { code: 'FLASH50', discount: 50, type: 'flat', minSpend: 200, desc: '$50 off orders over $200', expiry: '2026-07-15', used: false },
  { code: 'SUMMER25', discount: 0.25, type: 'percent', minSpend: 75, desc: '25% off summer collection', expiry: '2026-09-01', used: false }
];

function getAppliedCoupon() {
  try { return JSON.parse(localStorage.getItem('appliedCoupon')); } catch { return null; }
}
function setAppliedCoupon(c) {
  if (c) localStorage.setItem('appliedCoupon', JSON.stringify(c));
  else localStorage.removeItem('appliedCoupon');
}
function applyCoupon(code) {
  const c = COUPONS.find(x => x.code.toUpperCase() === code.toUpperCase());
  if (!c) return { ok: false, msg: 'Invalid coupon code' };
  const cartTotal = getCartTotal();
  if (cartTotal < c.minSpend) return { ok: false, msg: `Minimum spend of $${c.minSpend.toFixed(2)} required` };
  if (new Date(c.expiry) < new Date()) return { ok: false, msg: 'This coupon has expired' };
  setAppliedCoupon(c);
  return { ok: true, msg: `Coupon "${c.code}" applied! ${c.desc}` };
}
function removeCoupon() { setAppliedCoupon(null); }
function getDiscountAmount(subtotal) {
  const c = getAppliedCoupon();
  if (!c) return 0;
  if (c.type === 'percent') return subtotal * c.discount;
  if (c.type === 'flat') return c.discount;
  return 0;
}

// ====== LOYALTY DATA ======
const LOYALTY_TIERS = [
  { name: 'Bronze', minSpend: 0, color: '#CD7F32', benefits: ['Standard shipping', 'Birthday reward'] },
  { name: 'Silver', minSpend: 500, color: '#C0C0C0', benefits: ['Free standard shipping', 'Birthday reward', 'Early access to sales'] },
  { name: 'Gold', minSpend: 2000, color: '#FFD700', benefits: ['Free express shipping', 'Birthday reward', 'Early access', 'Exclusive Gold products', 'Priority support'] },
  { name: 'Platinum', minSpend: 5000, color: '#E5E4E2', benefits: ['Free next-day shipping', 'Birthday reward', 'Early access', 'Exclusive products', 'Priority support', 'Personal shopper', 'Double points'] }
];

function getLoyaltyData() {
  try { return JSON.parse(localStorage.getItem('loyalty')); } catch { return null; }
}
function setLoyaltyData(d) { localStorage.setItem('loyalty', JSON.stringify(d)); }
function initLoyalty() {
  let d = getLoyaltyData();
  if (!d) {
    d = { points: 2850, totalSpent: 3450, tier: 'Gold', joinDate: '2026-01-15' };
    setLoyaltyData(d);
  }
  return d;
}
function getLoyaltyTier(spent) {
  let tier = LOYALTY_TIERS[0];
  for (const t of LOYALTY_TIERS) { if (spent >= t.minSpend) tier = t; }
  return tier;
}
function pointsToCurrency(points) { return (points / 100).toFixed(2); }

// ====== SELLER DATA ======
const SELLER_STORE = {
  name: 'TechGear Store',
  logo: '🛍️',
  banner: 'linear-gradient(135deg, #6C5CE7, #FD79A8)',
  description: 'Premium electronics and accessories at unbeatable prices.',
  joined: '2026-03-01',
  rating: 4.6,
  totalProducts: 24,
  totalSales: 156,
  totalRevenue: 12450.00,
  commission: 0.08,
  balance: 3420.50,
  nextPayout: '2026-07-01'
};

const SELLER_PRODUCTS = [
  { id: 101, name: 'Bluetooth Earbuds Pro', category: 'Electronics', price: 79.99, oldPrice: 99.99, stock: 45, sold: 89, image: '🎧', status: 'active', rating: 4.3 },
  { id: 102, name: 'USB-C Hub 7-in-1', category: 'Electronics', price: 34.99, oldPrice: null, stock: 120, sold: 203, image: '🔌', status: 'active', rating: 4.1 },
  { id: 103, name: 'Phone Stand Adjustable', category: 'Electronics', price: 19.99, oldPrice: 24.99, stock: 200, sold: 312, image: '📱', status: 'active', rating: 4.5 },
  { id: 104, name: 'Wireless Charger Pad', category: 'Electronics', price: 29.99, oldPrice: 39.99, stock: 78, sold: 145, image: '⚡', status: 'active', rating: 4.2 },
  { id: 105, name: 'Laptop Sleeve 13"', category: 'Electronics', price: 24.99, oldPrice: null, stock: 0, sold: 67, image: '💼', status: 'outofstock', rating: 4.0 },
  { id: 106, name: 'Cable Organizer Kit', category: 'Electronics', price: 14.99, oldPrice: 19.99, stock: 56, sold: 178, image: '📦', status: 'active', rating: 4.4 },
  { id: 107, name: 'Desk Lamp LED', category: 'Home', price: 44.99, oldPrice: 59.99, stock: 34, sold: 92, image: '💡', status: 'draft', rating: 0 },
  { id: 108, name: 'Webcam 4K Ultra', category: 'Electronics', price: 89.99, oldPrice: null, stock: 22, sold: 45, image: '📹', status: 'active', rating: 4.6 }
];

const SELLER_ORDERS = [
  { id: 'SORD-001', date: '2026-06-20', customer: 'Alice M.', items: [{ name: 'Bluetooth Earbuds Pro', qty: 1, price: 79.99 }], total: 79.99, status: 'pending', shipping: '123 Oak St, NY', payment: 'Paid' },
  { id: 'SORD-002', date: '2026-06-20', customer: 'Bob K.', items: [{ name: 'USB-C Hub 7-in-1', qty: 2, price: 34.99 }, { name: 'Cable Organizer Kit', qty: 1, price: 14.99 }], total: 84.97, status: 'processing', shipping: '456 Pine Rd, LA', payment: 'Paid' },
  { id: 'SORD-003', date: '2026-06-19', customer: 'Carol S.', items: [{ name: 'Wireless Charger Pad', qty: 1, price: 29.99 }], total: 29.99, status: 'shipped', shipping: '789 Elm Ave, SF', payment: 'Paid' },
  { id: 'SORD-004', date: '2026-06-18', customer: 'David L.', items: [{ name: 'Phone Stand Adjustable', qty: 3, price: 19.99 }], total: 59.97, status: 'delivered', shipping: '321 Maple Dr, CHI', payment: 'Paid' },
  { id: 'SORD-005', date: '2026-06-17', customer: 'Eve R.', items: [{ name: 'Webcam 4K Ultra', qty: 1, price: 89.99 }], total: 89.99, status: 'cancelled', shipping: '654 Birch Ln, MIA', payment: 'Refunded' }
];

const SELLER_COMMISSION_RATES = [
  { category: 'Electronics', rate: 0.08 },
  { category: 'Fashion', rate: 0.10 },
  { category: 'Home', rate: 0.07 },
  { category: 'Sports', rate: 0.06 }
];

function getSellerData() {
  try { return JSON.parse(localStorage.getItem('sellerStore')) || SELLER_STORE; } catch { return SELLER_STORE; }
}
function setSellerData(d) { localStorage.setItem('sellerStore', JSON.stringify(d)); }
function getSellerProducts() {
  try { return JSON.parse(localStorage.getItem('sellerProducts')) || SELLER_PRODUCTS; } catch { return SELLER_PRODUCTS; }
}
function setSellerProducts(p) { localStorage.setItem('sellerProducts', JSON.stringify(p)); }
function getSellerOrders() {
  try { return JSON.parse(localStorage.getItem('sellerOrders')) || SELLER_ORDERS; } catch { return SELLER_ORDERS; }
}
function setSellerOrders(o) { localStorage.setItem('sellerOrders', JSON.stringify(o)); }

function sellerAddProduct(product) {
  const products = getSellerProducts();
  product.id = Date.now();
  products.unshift(product);
  setSellerProducts(products);
  return product;
}
function sellerUpdateProduct(id, data) {
  const products = getSellerProducts();
  const idx = products.findIndex(p => p.id === id);
  if (idx > -1) { Object.assign(products[idx], data); setSellerProducts(products); }
}
function sellerDeleteProduct(id) {
  setSellerProducts(getSellerProducts().filter(p => p.id !== id));
}
function sellerUpdateOrderStatus(orderId, status) {
  const orders = getSellerOrders();
  const o = orders.find(o => o.id === orderId);
  if (o) { o.status = status; setSellerOrders(orders); }
}

// ====== RECENTLY VIEWED ======
function getRecentlyViewed() {
  try { return JSON.parse(localStorage.getItem('recentlyViewed')) || []; } catch { return []; }
}
function addRecentlyViewed(productId) {
  let rv = getRecentlyViewed();
  rv = rv.filter(id => id !== productId);
  rv.unshift(productId);
  if (rv.length > 12) rv = rv.slice(0, 12);
  localStorage.setItem('recentlyViewed', JSON.stringify(rv));
}

// ====== NOTIFICATIONS ======
const NOTIFICATIONS = [
  { id: 1, type: 'order', title: 'Order Shipped!', message: 'Your order ORD-1002 has been shipped.', time: '2 hours ago', read: false, icon: '📦' },
  { id: 2, type: 'promo', title: 'Flash Sale - 25% Off', message: 'Summer sale ends soon! Use code SUMMER25.', time: '5 hours ago', read: false, icon: '🏷️' },
  { id: 3, type: 'review', title: 'Review Request', message: 'How was your Wireless Headphones? Leave a review.', time: '1 day ago', read: false, icon: '⭐' },
  { id: 4, type: 'payment', title: 'Payment Received', message: 'Your payment of $249.99 was successful.', time: '2 days ago', read: true, icon: '✅' },
  { id: 5, type: 'wishlist', title: 'Price Drop Alert', message: 'Running Sneakers is now $79.99 (was $89.99).', time: '3 days ago', read: true, icon: '💸' },
  { id: 6, type: 'loyalty', title: 'Loyalty Points Earned', message: 'You earned 250 points from your last purchase.', time: '1 week ago', read: true, icon: '🎯' },
  { id: 7, type: 'seller', title: 'New Order Received', message: 'You have a new order #SORD-006 for $45.99.', time: '30 min ago', read: false, icon: '🛍️' }
];

function getNotifications() {
  try { return JSON.parse(localStorage.getItem('notifications')) || NOTIFICATIONS; } catch { return NOTIFICATIONS; }
}
function setNotifications(n) { localStorage.setItem('notifications', JSON.stringify(n)); }
function markNotifRead(id) {
  const n = getNotifications(); const i = n.findIndex(x => x.id === id);
  if (i > -1) { n[i].read = true; setNotifications(n); }
}
function markAllRead() { getNotifications().forEach(n => n.read = true); setNotifications(getNotifications()); }
function getUnreadCount() { return getNotifications().filter(n => !n.read).length; }

// ====== REFERRAL ======
const REFERRAL_CODE = 'SHOPWAVE-JOHN';
const REFERRALS = [
  { name: 'Alice M.', date: '2026-06-15', reward: 10, status: 'completed' },
  { name: 'Bob K.', date: '2026-06-10', reward: 10, status: 'completed' },
  { name: 'Carol S.', date: '2026-06-05', reward: 5, status: 'pending' }
];

// ====== GIFT CARDS ======
const GIFT_CARD_DESIGNS = [
  { id: 1, name: 'Classic', preview: '🎁', colors: ['#6C5CE7', '#A29BFE'] },
  { id: 2, name: 'Celebration', preview: '🎉', colors: ['#FD79A8', '#FDCB6E'] },
  { id: 3, name: 'Minimal', preview: '✨', colors: ['#2D3436', '#636E72'] },
  { id: 4, name: 'Nature', preview: '🌿', colors: ['#00B894', '#00CEC9'] }
];

// ====== PRODUCT Q&A ======
const PRODUCT_QA = {
  1: [
    { id: 1, user: 'Mike T.', question: 'Does this support noise cancellation?', answer: 'Yes, it has active noise cancellation up to 35dB.', date: '2026-06-10', votes: 5 },
    { id: 2, user: 'Sarah K.', question: 'What is the battery life?', answer: 'Up to 30 hours with ANC on, 40 hours with ANC off.', date: '2026-06-08', votes: 8 },
    { id: 3, user: 'James W.', question: 'Are the ear cushions replaceable?', answer: null, date: '2026-06-12', votes: 2 }
  ]
};

// ====== ADMIN DATA ======
const ADMIN_STATS = {
  totalUsers: 28450, totalSellers: 1240, totalOrders: 18760,
  totalRevenue: 892450, pendingDisputes: 12, newRegistrations: 342,
  platformFee: 0.08, monthlyGrowth: 12.5
};
const ADMIN_USERS = [
  { id: 1, name: 'John Doe', email: 'john@example.com', type: 'buyer', orders: 8, joined: '2026-01-15', status: 'active', spent: 1245.50 },
  { id: 2, name: 'TechGear Store', email: 'seller@techgear.com', type: 'seller', products: 24, joined: '2026-03-01', status: 'active', revenue: 12450 },
  { id: 3, name: 'Alice M.', email: 'alice@example.com', type: 'buyer', orders: 3, joined: '2026-04-20', status: 'active', spent: 345.99 },
  { id: 4, name: 'FashionHub', email: 'info@fashionhub.com', type: 'seller', products: 56, joined: '2026-02-10', status: 'suspended', revenue: 28900 },
  { id: 5, name: 'Bob K.', email: 'bob@test.com', type: 'buyer', orders: 1, joined: '2026-06-01', status: 'inactive', spent: 84.97 }
];
const ADMIN_DISPUTES = [
  { id: 'DSP-001', order: 'ORD-1001', buyer: 'John D.', seller: 'TechGear Store', reason: 'Item not as described', status: 'open', date: '2026-06-18', amount: 129.99 },
  { id: 'DSP-002', order: 'ORD-1005', buyer: 'Alice M.', seller: 'FashionHub', reason: 'Wrong size delivered', status: 'investigating', date: '2026-06-19', amount: 54.99 },
  { id: 'DSP-003', order: 'ORD-1007', buyer: 'Carol S.', seller: 'TechGear Store', reason: 'Defective product', status: 'resolved', date: '2026-06-14', amount: 34.99 }
];
const ADMIN_CATEGORIES = [
  { id: 1, name: 'Electronics', products: 156, sales: 8450, commission: 0.08, active: true },
  { id: 2, name: 'Fashion', products: 342, sales: 12300, commission: 0.10, active: true },
  { id: 3, name: 'Home', products: 89, sales: 3450, commission: 0.07, active: true },
  { id: 4, name: 'Sports', products: 67, sales: 2100, commission: 0.06, active: true }
];
const ADMIN_BANNERS = [
  { id: 1, title: 'Summer Sale - Up to 50% Off', active: true, image: '🏖️', link: '/promos.html' },
  { id: 2, title: 'New Electronics Arrived', active: true, image: '💻', link: '/index.html?cat=Electronics' },
  { id: 3, title: 'Free Shipping Over $50', active: false, image: '🚚', link: '/' }
];

// ====== SELLER MESSAGES ======
const SELLER_MESSAGES = [
  { id: 1, from: 'Alice M.', subject: 'Order #SORD-001 - Shipping Question', preview: 'Hi, when will my order be shipped? I need it by Friday.', date: '2026-06-20', read: false, messages: [
    { from: 'Alice M.', text: 'Hi, when will my order be shipped? I need it by Friday.', time: '10:30 AM' }
  ]},
  { id: 2, from: 'David L.', subject: 'Product Inquiry - Phone Stand', preview: 'Does this come with a warranty? I want to buy 10 units.', date: '2026-06-19', read: true, messages: [
    { from: 'David L.', text: 'Does this come with a warranty? I want to buy 10 units.', time: '2:15 PM' },
    { from: 'You', text: 'Yes, 1 year warranty included. Bulk pricing available for 10+ units.', time: '3:00 PM' }
  ]},
  { id: 3, from: 'Carol S.', subject: 'Return Request - Wireless Charger', preview: 'The charger stopped working after 2 days. Can I get a replacement?', date: '2026-06-18', read: false, messages: [
    { from: 'Carol S.', text: 'The charger stopped working after 2 days. Can I get a replacement?', time: '9:00 AM' }
  ]}
];

// ====== SELLER PAYOUTS ======
const SELLER_PAYOUTS = [
  { id: 'PAY-001', amount: 1240.50, date: '2026-06-15', status: 'completed', method: 'Bank Transfer - Chase ****6789', period: 'Jun 1-14' },
  { id: 'PAY-002', amount: 980.00, date: '2026-06-08', status: 'completed', method: 'Bank Transfer - Chase ****6789', period: 'May 25-31' },
  { id: 'PAY-003', amount: 1450.75, date: '2026-06-01', status: 'completed', method: 'Bank Transfer - Chase ****6789', period: 'May 18-24' },
  { id: 'PAY-004', amount: 3400.00, date: '2026-05-25', status: 'completed', method: 'Bank Transfer - Chase ****6789', period: 'May 11-17' }
];

// ====== SELLER PROMOTIONS ======
const SELLER_PROMOTIONS = [
  { id: 1, name: 'Summer Clearance', type: 'percent', value: 15, products: ['Bluetooth Earbuds Pro', 'USB-C Hub'], status: 'active', usage: 23, start: '2026-06-01', end: '2026-07-15' },
  { id: 2, name: 'Free Shipping Weekend', type: 'freeship', value: 0, products: ['All Store Products'], status: 'active', usage: 45, start: '2026-06-20', end: '2026-06-22' },
  { id: 3, name: 'Bundle Deal - 20% Off', type: 'percent', value: 20, products: ['Phone Stand', 'Cable Organizer'], status: 'scheduled', usage: 0, start: '2026-07-01', end: '2026-07-31' }
];

// ====== SELLER REVIEWS ======
const SELLER_REVIEWS_RECEIVED = [
  { id: 1, product: 'Bluetooth Earbuds Pro', user: 'Mike T.', rating: 5, text: 'Amazing sound quality and comfortable fit!', date: '2026-06-15', responded: true, response: 'Thank you Mike! Glad you love them.' },
  { id: 2, product: 'USB-C Hub 7-in-1', user: 'Sarah K.', rating: 4, text: 'Works well for my laptop. Could be more compact.', date: '2026-06-12', responded: false, response: null },
  { id: 3, product: 'Phone Stand Adjustable', user: 'James W.', rating: 5, text: 'Perfect for my desk setup. Very sturdy!', date: '2026-06-10', responded: false, response: null },
  { id: 4, product: 'Bluetooth Earbuds Pro', user: 'Emily R.', rating: 2, text: 'Battery life is not as advertised. Disappointed.', date: '2026-06-08', responded: true, response: 'Hi Emily, please contact our support for a replacement.' }
];

// ====== UTILITY: RECENTLY VIEWED ON PRODUCT PAGE ======
function trackProductView(productId) {
  addRecentlyViewed(productId);
}

// ====== DARK MODE ======
function toggleDarkMode() {
  document.body.classList.toggle('dark-mode');
  localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
  const btn = $('#darkModeToggle');
  if (btn) btn.textContent = document.body.classList.contains('dark-mode') ? '☀️' : '🌙';
}
function initDarkMode() {
  if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark-mode');
    const btn = $('#darkModeToggle');
    if (btn) btn.textContent = '☀️';
  }
}

// ====== COOKIE CONSENT ======
function initCookieConsent() {
  if (localStorage.getItem('cookieConsent')) return;
  const bar = $('#cookieBar');
  if (bar) bar.classList.add('show');
}
function acceptCookies() {
  localStorage.setItem('cookieConsent', 'true');
  const bar = $('#cookieBar');
  if (bar) bar.classList.remove('show');
}

// ====== PWA ======
function registerSW() {
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js').catch(() => {});
  }
}

// ====== LIVE CHAT ======
function createChatWidget() {
  if ($('#liveChatWidget')) return;
  const btn = document.createElement('button');
  btn.id = 'liveChatWidget';
  btn.innerHTML = '💬';
  btn.title = 'Chat with us';
  btn.onclick = toggleChat;
  document.body.appendChild(btn);

  const modal = document.createElement('div');
  modal.id = 'chatModal';
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal" style="max-width:380px;padding:0;overflow:hidden">
      <div style="background:var(--primary);color:#fff;padding:16px 20px;display:flex;justify-content:space-between;align-items:center">
        <strong>💬 Live Chat</strong>
        <button onclick="toggleChat()" style="color:#fff;font-size:1.25rem">✕</button>
      </div>
      <div id="chatMessages" style="padding:16px;height:280px;overflow-y:auto;font-size:.875rem">
        <div style="background:var(--gray-200);padding:10px 14px;border-radius:12px 12px 12px 4px;margin-bottom:8px;display:inline-block;max-width:80%">👋 Hi! How can we help you today?</div>
      </div>
      <div style="display:flex;border-top:1px solid var(--gray-400);padding:8px">
        <input id="chatInput" placeholder="Type a message..." style="flex:1;border:none;padding:10px 12px" onkeydown="if(event.key==='Enter')sendChat()">
        <button onclick="sendChat()" style="background:var(--primary);color:#fff;border-radius:8px;padding:8px 16px;font-weight:600">Send</button>
      </div>
    </div>`;
  document.body.appendChild(modal);
}
function toggleChat() {
  const m = $('#chatModal');
  if (m) m.classList.toggle('open');
}
function sendChat() {
  const input = $('#chatInput');
  const msg = input?.value.trim();
  if (!msg) return;
  const container = $('#chatMessages');
  if (container) {
    container.innerHTML += '<div style="text-align:right;margin-bottom:8px"><span style="background:var(--primary);color:#fff;padding:10px 14px;border-radius:12px 12px 4px 12px;display:inline-block;max-width:80%">' + msg + '</span></div>';
    container.scrollTop = container.scrollHeight;
  }
  input.value = '';
  setTimeout(() => {
    if (container) {
      container.innerHTML += '<div style="background:var(--gray-200);padding:10px 14px;border-radius:12px 12px 12px 4px;margin-bottom:8px;display:inline-block;max-width:80%">Thanks for your message! A team member will respond shortly. ⏳</div>';
      container.scrollTop = container.scrollHeight;
    }
  }, 1000);
}

// ====== MOBILE MENU ======
function createMobileMenu() {
  if ($('#mobileMenuToggle')) return;
  const btn = document.createElement('button');
  btn.id = 'mobileMenuToggle';
  btn.innerHTML = '☰';
  btn.setAttribute('aria-label', 'Menu');
  document.body.appendChild(btn);

  const overlay = document.createElement('div');
  overlay.id = 'mobileMenuOverlay';
  overlay.className = 'mobile-menu-overlay';
  overlay.innerHTML = `
    <div class="mobile-menu-panel">
      <button class="mobile-menu-close" onclick="toggleMobileMenu()">✕</button>
      <div class="mobile-menu-links">
        <a href="index.html">🏠 Home</a>
        <a href="category.html">📁 Categories</a>
        <a href="cart.html">🛒 Cart</a>
        <a href="orders.html">📋 Orders</a>
        <a href="wishlist.html">♡ Wishlist</a>
        <a href="notifications.html">🔔 Notifications</a>
        <a href="recently-viewed.html">👁️ Recently Viewed</a>
        <a href="compare.html">⚖️ Compare</a>
        <a href="become-seller.html">🏪 Sell</a>
        <a href="support.html">💬 Support</a>
        <a href="signin.html">👤 Sign In</a>
      </div>
    </div>`;
  document.body.appendChild(overlay);

  btn.onclick = toggleMobileMenu;
  overlay.addEventListener('click', function(e) { if (e.target === this) toggleMobileMenu(); });
}
function toggleMobileMenu() {
  const o = $('#mobileMenuOverlay');
  if (o) o.classList.toggle('open');
}

// ====== BACK TO TOP ======
function createBackToTop() {
  if ($('#backToTop')) return;
  const btn = document.createElement('button');
  btn.id = 'backToTop';
  btn.innerHTML = '↑';
  btn.title = 'Back to top';
  document.body.appendChild(btn);

  window.addEventListener('scroll', () => {
    btn.style.display = window.scrollY > 300 ? 'flex' : 'none';
  });
  btn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ====== NEWSLETTER ======
function subscribeNewsletter() {
  const input = $('#newsletterEmail');
  const email = input?.value.trim();
  if (!email) { showToast('Please enter your email', 'error'); return; }
  showToast('🎉 Subscribed! Check your inbox for a welcome offer.', 'success');
  if (input) input.value = '';
}

// ====== INIT ======
document.addEventListener('DOMContentLoaded', () => {
  updateCartCount();
  updateWishlistUI();
  initDarkMode();
  initCookieConsent();
  registerSW();
  createChatWidget();
  createMobileMenu();
  createBackToTop();

  // Mobile search toggle
  const searchToggle = $('#search-toggle');
  const searchBar = $('.search-bar');
  if (searchToggle && searchBar) {
    searchToggle.addEventListener('click', () => {
      searchBar.style.display = searchBar.style.display === 'block' ? 'none' : 'block';
    });
  }

  // Dark mode toggle button listener
  const dmBtn = $('#darkModeToggle');
  if (dmBtn) dmBtn.addEventListener('click', toggleDarkMode);
});
