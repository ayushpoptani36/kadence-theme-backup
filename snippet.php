
// Load FontAwesome for filter icon
function enqueue_fontawesome() {
    wp_enqueue_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_fontawesome' );


// ✅ Add only "Filter" button (no extra sorting)
add_action('woocommerce_before_shop_loop', 'add_mobile_filter_button', 15);
function add_mobile_filter_button() {
    if (is_shop() || is_product_category() || is_product_taxonomy()) {
        echo '<button id="filter-toggle" class="filter-toggle">
                <i class="fas fa-filter"></i> Filter
              </button>';
    }
}


// ✅ Add JS + overlay via footer
add_action('wp_footer', 'mobile_filter_drawer_js');
function mobile_filter_drawer_js() {
    if (is_shop() || is_product_category() || is_product_taxonomy()) : ?>
        <div id="filter-overlay"></div>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
          const sidebar = document.querySelector("#secondary");
          const overlay = document.querySelector("#filter-overlay");

          document.addEventListener("click", function(e) {
            // Toggle filter drawer
            if (e.target.closest("#filter-toggle")) {
              e.preventDefault();
              sidebar.classList.toggle("active");
              overlay.classList.toggle("active");
            }

            // Close when clicking overlay
            if (e.target.closest("#filter-overlay")) {
              sidebar.classList.remove("active");
              overlay.classList.remove("active");
            }

            // Close when applying filters
            if (e.target.closest("#secondary button, #secondary input[type=submit]")) {
              sidebar.classList.remove("active");
              overlay.classList.remove("active");
            }
          });

          // Also close drawer on normal form submit (page reload)
          if (sidebar) {
            sidebar.addEventListener("submit", function() {
              sidebar.classList.remove("active");
              overlay.classList.remove("active");
            });
          }

          // Close drawer after AJAX filter (WBF)
          document.body.addEventListener("wbf_ajax_success", function() {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
          });
        });
        </script>
    <?php endif;
}

