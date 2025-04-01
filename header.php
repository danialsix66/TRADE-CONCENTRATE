<?php get_header(); ?>

<main>
    <section class="hero-banner">
        <div class="banner-content">
            <h1>Welcome to Our Marketplace</h1>
            <p>Buy and sell high-quality fruit concentrates, purees, and tomato paste.</p>
            <a href="/register" class="cta-btn">Become a Buyer or Seller</a>
        </div>
    </section>
    <section class="price-board">
    <h2>Global Pricing Board</h2>
    
    <!-- Filters Section -->
    <div class="filters">
        <label for="product-name">Product Name:</label>
        <input type="text" id="product-name" placeholder="Search by product name">

        <label for="brix-filter">Brix Level:</label>
        <select id="brix-filter">
            <option value="all">All</option>
            <option value="20">20 Brix</option>
            <option value="30">30 Brix</option>
            <option value="36">36 Brix</option>
        </select>

        <label for="country-filter">Country of Origin:</label>
        <select id="country-filter">
            <option value="all">All</option>
            <option value="usa">USA</option>
            <option value="italy">Italy</option>
            <option value="turkey">Turkey</option>
        </select>

        <label for="sort-by">Sort By:</label>
        <select id="sort-by">
            <option value="price-asc">Price (Low to High)</option>
            <option value="price-desc">Price (High to Low)</option>
            <option value="brix-asc">Brix (Low to High)</option>
            <option value="brix-desc">Brix (High to Low)</option>
        </select>
    </div>

    <!-- Product Table -->
    <table class="pricing-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Brix Level</th>
                <th>Country of Origin</th>
                <th>Price (USD)</th>
            </tr>
        </thead>
        <tbody id="price-board-results">
            <!-- Products will be dynamically inserted here by AJAX -->
        </tbody>
    </table>
</section>

    <section class="product-categories">
        <h2>Explore Our Products</h2>
        <div class="categories-container">
            <div class="category-card">
                <img src="<?php echo get_template_directory_uri(); ?>/images/concentrate.jpg" alt="Fruit Concentrates">
                <h3>Fruit Concentrates</h3>
                <a href="/products/concentrates" class="btn">View Products</a>
            </div>
            <div class="category-card">
                <img src="<?php echo get_template_directory_uri(); ?>/images/puree.jpg" alt="Fruit Purees">
                <h3>Fruit Purees</h3>
                <a href="/products/purees" class="btn">View Products</a>
            </div>
            <div class="category-card">
                <img src="<?php echo get_template_directory_uri(); ?>/images/tomato-paste.jpg" alt="Tomato Paste">
                <h3>Tomato Paste</h3>
                <a href="/products/tomato-paste" class="btn">View Products</a>
            </div>
        </div>
    </section>
    <section class="featured-products">
    <h2>Featured Products</h2>
    <div class="products-container">
        <div class="product-card">
            <img src="<?php echo get_template_directory_uri(); ?>/images/product1.jpg" alt="Tomato Paste">
            <h3>Tomato Paste</h3>
            <p>Country: Italy</p>
            <a href="/product/tomato-paste" class="btn">View Product</a>
        </div>
        <div class="product-card">
            <img src="<?php echo get_template_directory_uri(); ?>/images/product2.jpg" alt="Pomegranate Concentrate">
            <h3>Pomegranate Concentrate</h3>
            <p>Country: Turkey</p>
            <a href="/product/pomegranate-concentrate" class="btn">View Product</a>
        </div>
        <div class="product-card">
            <img src="<?php echo get_template_directory_uri(); ?>/images/product3.jpg" alt="Apple Puree">
            <h3>Apple Puree</h3>
            <p>Country: USA</p>
            <a href="/product/apple-puree" class="btn">View Product</a>
        </div>
    </div>
</section>
    <section class="site-stats">
    <h2>Platform Statistics</h2>
    <div class="stats-container">
        <div class="stat-item">
            <h3>Registered Users</h3>
            <p>5,200</p>
        </div>
        <div class="stat-item">
            <h3>Total Products</h3>
            <p>320</p>
        </div>
        <div class="stat-item">
            <h3>Sellers</h3>
            <p>150</p>
        </div>
        <div class="stat-item">
            <h3>Transactions</h3>
            <p>$2,000,000</p>
        </div>
    </div>
</section>

<section class="site-features">
    <h2>Our Platform Features</h2>
    <div class="features-container">
        <div class="feature-item">
            <img src="<?php echo get_template_directory_uri(); ?>/images/secure.png" alt="Secure Transactions">
            <h3>Secure Transactions</h3>
            <p>We ensure every transaction is secure using an escrow system to protect both buyers and sellers.</p>
        </div>
        <div class="feature-item">
            <img src="<?php echo get_template_directory_uri(); ?>/images/verified.png" alt="Verified Products">
            <h3>Verified Products</h3>
            <p>All products undergo rigorous verification to ensure authenticity and quality.</p>
        </div>
        <div class="feature-item">
            <img src="<?php echo get_template_directory_uri(); ?>/images/support.png" alt="Customer Support">
            <h3>24/7 Customer Support</h3>
            <p>Our team is available 24/7 to assist with any inquiries or issues.</p>
        </div>
    </div>
</section>

<section class="testimonials">
    <h2>What Our Customers Say</h2>
    <div class="testimonials-container">
        <div class="testimonial-item">
            <p>"This platform has been a game-changer for my business. The quality of the products is top-notch!"</p>
            <h4>- John Doe, Buyer</h4>
        </div>
        <div class="testimonial-item">
            <p>"I love the transparency and ease of transactions. Highly recommended for sellers!"</p>
            <h4>- Jane Smith, Seller</h4>
        </div>
    </div>
</section>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Load all products initially
        loadProducts();

        // Event listener for live filtering
        $('#product-name, #brix-filter, #country-filter, #sort-by').on('change keyup', function() {
            loadProducts();
        });

        function loadProducts() {
            var productName = $('#product-name').val();
            var brixLevel = $('#brix-filter').val();
            var country = $('#country-filter').val();
            var sortBy = $('#sort-by').val();

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'filter_products',
                    product_name: productName,
                    brix_level: brixLevel,
                    country: country,
                    sort_by: sortBy
                },
                success: function(response) {
                    $('#price-board-results').html(response);
                }
            });
        }
    });
</script>
<label for="date-filter">Date:</label>
<input type="date" id="date-filter">
<label for="sort-filter">Sort by:</label>
<select id="sort-filter">
    <option value="price-asc">Price (Low to High)</option>
    <option value="price-desc">Price (High to Low)</option>
    <option value="country-asc">Country (A-Z)</option>
    <option value="country-desc">Country (Z-A)</option>
</select>

</main>
</body>
<?php get_footer(); ?>
