const results = document.getElementById('results');
$('form').submit(function(event) {
    // Prevent default form submission
    event.preventDefault();

    // Send AJAX request to server
    $.ajax({
        url: 'submit-update.php',
        type: 'POST',
        success: function(response){
            results.innerHTML = response;
        }
    });
});
