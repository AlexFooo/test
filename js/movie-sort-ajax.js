jQuery(document).ready(function($) {
    $('.sort-button').on('click', function(e) {
        e.preventDefault(); 

        var sortBy = $(this).data('sort-by'); 
        var sortOrder = $(this).data('sort-order'); 
        var nextSortOrder = sortOrder === 'ASC' ? 'DESC' : 'ASC';
    
        $(this).data('sort-order', nextSortOrder);
        filterMovies(sortBy, nextSortOrder);
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
