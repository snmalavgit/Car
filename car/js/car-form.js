jQuery(document).ready(function($) {
    $('#car-entry-form').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: carForm.ajax_url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    alert('Car submitted successfully!');
                    $('#car-entry-form')[0].reset();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Error submitting car.');
            }
        });
    });
});
