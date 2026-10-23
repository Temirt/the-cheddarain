/**
 * The Cheddarloin — Artisan Burger Restaurant
 * Product Catalog & Pricing (in Ethiopian Birr - ETB)
 */

const PRODUCTS = [
    // -------------------------------------------------------------------------
    // BURGERS
    // -------------------------------------------------------------------------
    {
        id: "burger-cheddar-royal",
        name: "The Cheddarloin Royal",
        category: "burgers",
        price: 650,
        badge: "Chef's Signature",
        prepTime: "12-15 min",
        description: "Double smashed prime Angus beef, blanketed in thick Wisconsin aged cheddar lava, charred shallot aioli, and micro-greens inside toasted golden brioche.",
        image: "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "burger-smoked-bacon-melt",
        name: "Smoked Bacon & Melted Cheddar",
        category: "burgers",
        price: 620,
        badge: "Best Seller",
        prepTime: "12-15 min",
        description: "Crispy cured beef bacon ribbons, melted sharp cheddar slab, caramelized brown onion jam, and house smoked glaze on toasted artisan brioche.",
        image: "https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "burger-truffle-gold",
        name: "Truffle Gold Reserve",
        category: "burgers",
        price: 780,
        badge: "Luxury Cut",
        prepTime: "15-18 min",
        description: "Prime coarse-grind sirloin patty infused with black truffle butter, dripping white cheddar cream, sautéed king trumpet mushrooms, and garlic aioli.",
        image: "https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "burger-fiery-jalapeno",
        name: "Fiery Cheddar Jalapeño",
        category: "burgers",
        price: 580,
        badge: "Spicy",
        prepTime: "10-12 min",
        description: "Double seared beef patties, roasted fire-charred jalapeños, molten pepper jack cheddar cascade, chipotle emulsion, and crisp butterhead lettuce.",
        image: "https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "burger-classic-single",
        name: "Classic Artisan Cheddar",
        category: "burgers",
        price: 450,
        badge: "Classic",
        prepTime: "10-12 min",
        description: "Single smashed Angus patty, classic melted cheddar, house-brined kosher pickles, sliced heirloom tomato, and secret Cheddarloin relish.",
        image: "https://images.unsplash.com/photo-1572802419224-296b0aeee0d9?auto=format&fit=crop&w=800&q=80"
    },

    // -------------------------------------------------------------------------
    // SIDES
    // -------------------------------------------------------------------------
    {
        id: "side-loaded-fries",
        name: "Loaded Cheddar & Bacon Fries",
        category: "sides",
        price: 240,
        badge: "Crowd Favorite",
        prepTime: "8-10 min",
        description: "Skin-on double-fried russet potatoes drenched in warm liquid cheddar cheese, topped with crispy beef bacon crumbles and chopped fresh chives.",
        image: "https://images.unsplash.com/photo-1585109649139-366815a0d713?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "side-rustic-wedges",
        name: "Rustic Golden Wedges",
        category: "sides",
        price: 180,
        badge: "Crispy",
        prepTime: "8-10 min",
        description: "Thick-cut potato wedges tossed in smoked sea salt, cracked black pepper, and garlic rosemary, served with smoked paprika dip.",
        image: "https://images.unsplash.com/photo-1630384060421-cb20d0e0649d?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "side-cheddar-bites",
        name: "Crispy Molten Cheddar Bites",
        category: "sides",
        price: 280,
        badge: "House Made",
        prepTime: "8-10 min",
        description: "Panko-crusted molten aged cheddar balls fried to golden crunch, served with our signature honey-mustard garlic dipping pot.",
        image: "https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "side-parmesan-truffle-fries",
        name: "Truffle & Herb Shoestring Fries",
        category: "sides",
        price: 260,
        badge: "Gourmet",
        prepTime: "8-10 min",
        description: "Shoestring crispy potatoes scented with aromatic white truffle oil, dusted with freshly grated aged parmesan and garden parsley.",
        image: "https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=800&q=80"
    },

    // -------------------------------------------------------------------------
    // DRINKS
    // -------------------------------------------------------------------------
    {
        id: "drink-salted-caramel-shake",
        name: "Salted Caramel Velvet Milkshake",
        category: "drinks",
        price: 220,
        badge: "Hand-Spun",
        prepTime: "5 min",
        description: "Rich whole milk cream spun with French vanilla bean gelato, fleur de sel sea salt, and thick ribbons of handmade dark caramel.",
        image: "https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "drink-sparkling-hibiscus",
        name: "Sparkling Hibiscus Lemon Cooler",
        category: "drinks",
        price: 150,
        badge: "Refreshing",
        prepTime: "3 min",
        description: "Organic dried hibiscus steeped with fresh Meyer lemon juice, cane sugar, crushed ice, and effervescent sparkling water.",
        image: "https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "drink-mint-lime-sparkler",
        name: "Fresh Mint & Lime Sparkler",
        category: "drinks",
        price: 140,
        badge: "Zero Alcohol",
        prepTime: "3 min",
        description: "Freshly muddled garden spearmint, pressed lime wedges, and crisp sparkling tonic poured over hand-cut ice.",
        image: "https://images.unsplash.com/photo-1556881286-fc6915169721?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "drink-craft-botanical-cola",
        name: "Artisanal Botanical Cola",
        category: "drinks",
        price: 120,
        badge: "Craft Soda",
        prepTime: "2 min",
        description: "Small-batch craft soda infused with kola nut, vanilla pod, cinnamon bark, and citrus oils served ice-cold.",
        image: "https://images.unsplash.com/photo-1622483767028-3f66f32aef97?auto=format&fit=crop&w=800&q=80"
    },

    // -------------------------------------------------------------------------
    // MEALS & COMBOS
    // -------------------------------------------------------------------------
    {
        id: "meal-royal-combo",
        name: "The Royal Cheddarloin Feast",
        category: "meals",
        price: 980,
        badge: "Best Value",
        prepTime: "15 min",
        description: "Includes The Cheddarloin Royal burger, a full portion of Loaded Cheddar Bacon Fries, and your choice of cold beverage.",
        image: "https://images.unsplash.com/photo-1551782450-a2132b4ba21d?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "meal-bacon-smokehouse",
        name: "Smokehouse Bacon Combo",
        category: "meals",
        price: 860,
        badge: "Popular Combo",
        prepTime: "15 min",
        description: "Includes Smoked Bacon & Melted Cheddar burger, hand-cut Rustic Golden Wedges, and a chilled Artisanal Botanical Cola.",
        image: "https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&w=800&q=80"
    },
    {
        id: "meal-duo-platter",
        name: "Cheddar Lovers Duo (For 2)",
        category: "meals",
        price: 1650,
        badge: "Feast For Two",
        prepTime: "18 min",
        description: "2 Signature Burgers of your choice, 1 Large Loaded Cheddar Bacon Fries, 1 Crispy Molten Cheddar Bites, and 2 Artisanal Drinks.",
        image: "https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?auto=format&fit=crop&w=800&q=80"
    }
];

// Helper to find a product by ID
function getProductById(id) {
    return PRODUCTS.find(p => p.id === id) || null;
}

// Format price in Ethiopian Birr
function formatETB(amount) {
    const num = Number(amount) || 0;
    return `ETB ${num.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}`;
}

// Export for Node.js environments (Netlify serverless function) if applicable
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        PRODUCTS,
        getProductById,
        formatETB
    };
}
