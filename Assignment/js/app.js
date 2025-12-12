// ============================================================================
// General Functions
// ============================================================================



// ============================================================================
// Page Load (jQuery)
// ============================================================================

$(() => {

    // Autofocus
    $('form :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();

    // Initiate GET request
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        location = url || location;
    });

    // Initiate POST request
    $('[data-post]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    // Reset form
    $('[type=reset]').on('click', e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

    $(document).ready(function () {
        // Toggle dropdown on button click
        $('.user-btn').click(function (e) {
            e.stopPropagation(); // Prevent closing immediately
            $(this).siblings('.dropdown-content').toggleClass('show');
        });
        
        // Close dropdown if clicked outside
        $(document).click(function () {
            $('.dropdown-content').removeClass('show');
        });
    });

    // Attach a click event to the "Previous" button
    document.getElementById('prevBtn').addEventListener('click', function() {
        const input = document.getElementById('pageInput');
        const current = parseInt(input.value) || 1;
        if (current > 1) 
            input.value = current - 1;
    });

    // Attach a click event to the "Next" button
    document.getElementById('nextBtn').addEventListener('click', function() {
        const input = document.getElementById('pageInput');
        input.value = parseInt(input.value) + 1;
        document.querySelector('.pagination').closest('form').submit();
    });

    // Attach a keypress event to the page input
    document.getElementById('pageInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            // Trigger page load with the entered page number
        console.log('Navigate to page:', this.value);
        }
    });

    window.closePopup = function () {
        const overlay = document.getElementById('popupOverlay');
        if (overlay) overlay.style.display = 'none';
    }

    // Photo preview
    $('label.upload input[type=file]').on('change', e => {
        const f = e.target.files[0];
        const img = $(e.target).siblings('img')[0];

        if (!img) return;

        img.dataset.src ??= img.src;

        if (f?.type.startsWith('image/')) {
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
            e.target.value = '';
        }
    });
});

// Show the modal
$('#myModal').modal('show');
// Hide the modal
$('#myModal').modal('hide');
