document.addEventListener('DOMContentLoaded', function() {
    // Buy a cow button (Page A)
    var buyCowButton = document.getElementById('buyCowButton');
    if (buyCowButton) {
        buyCowButton.addEventListener('click', function() {
            // Hide the button section
            var buyCowSection = document.getElementById('buyCowSection');
            var thankYouSection = document.getElementById('thankYouSection');
            if (buyCowSection) buyCowSection.style.display = 'none';
            if (thankYouSection) thankYouSection.style.display = 'block';

            // Track the button click
            fetch('/api/activity/track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    type: 'button_click',
                    button: 'buy_cow',
                    page: 'page_a'
                })
            });
        });
    }

    // Download button (Page B)
    var downloadButton = document.getElementById('downloadButton');
    if (downloadButton) {
        downloadButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent immediate navigation

            fetch('/api/activity/track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    type: 'button_click',
                    button: 'download',
                    page: 'page_b'
                })
            }).then(function() {
                // After tracking, proceed to download
                window.location.href = '/downloads/sample.exe';
            });
        });
    }
});
