jQuery(document).ready(function($) {
    // Function to fetch products
    function fetchProducts(filters) {
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'filter_products',
                product_name: filters.productName || '',
                brix_level: filters.brixLevel || '',
                country: filters.country || '',
                date: filters.date || '',
                sort_by: filters.sortBy || ''
            },
            success: function(response) {
                $('#price-board-results').html(response);
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error: " + status + " - " + error);
            }
        });
    }

    // Fetch all products when the page loads
    fetchProducts({});

    // Event listener for filters
    $('#product-filter, #brix-filter, #country-filter, #date-filter, #sort-filter').on('change', function() {
        var filters = {
            productName: $('#product-filter').val(),
            brixLevel: $('#brix-filter').val(),
            country: $('#country-filter').val(),
            date: $('#date-filter').val(),
            sortBy: $('#sort-filter').val()
        };

        // Fetch filtered products
        fetchProducts(filters);
    });
});
