jQuery(document).ready(function($) {
  
    $('#movie-filter').submit(function(e) {
        e.preventDefault(); 

        var formData = $(this).serialize(); 
        var selectedParams = $(this).find('input:checked').serialize(); 

        var urlParams = new URLSearchParams(window.location.search);
        var sortBy = urlParams.get('sort_by');
        var sortOrder = urlParams.get('sort_order');

        window.history.pushState(null, null, window.location.pathname + '?' + selectedParams);

        
        filterMovies(sortBy, sortOrder);
    });

    $('#reset-filter').click(function() {
        $('#movie-filter input[type="checkbox"]').prop('checked', false);

        filterMovies('', '');
    });

    function filterMovies(sortBy, sortOrder) {
        var formData = $('#movie-filter').serialize(); 

        formData += '&sort_by=' + sortBy + '&sort_order=' + sortOrder;

        $.ajax({
            type: 'POST',
            url: movie_filter_ajax_object.ajax_url,
            data: {
                action: 'filter_movies',
                data: formData
            },
            success: function(response) {
                $('#movie-results').html(response); 
            }
        });
    }
});