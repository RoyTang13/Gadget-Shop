<?php

// ============================================================================
// PHP Setups
// ============================================================================
date_default_timezone_set('Asia/Kuala_Lumpur');


// ============================================================================
// HTML Helpers
// ============================================================================

  // Encode HTML special characters
  function encode($value) {
    return htmlentities($value);
}

// Generate <input type='text'>
function html_text($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='text' id='$key' name='$key' value='$value' $attr>";
}

// Global error array
$_err = [];

// Generate <span class='err'>
function err($key) {
    global $_err; // to check got erro or not     //global is to catch the $_err outside function
    if ($_err[$key] ?? false) {
        echo "<span class='err'>$_err[$key]</span>";
    }
    else {
        echo '<span></span>';
    }
}
