<?php

// ============================================================================
// PHP Setups
// ============================================================================

date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

// ============================================================================
// General Page Functions
// ============================================================================

    // Is GET request?
    function is_get() {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    // Is POST request?
    function is_post() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    // Obtain GET parameter
    function get($key, $value = null) {
        $value = $_GET[$key] ?? $value;
        return is_array($value) ? array_map('trim', $value) : trim($value);
    }

    // Obtain POST parameter
    function post($key, $value = null) {
        $value = $_POST[$key] ?? $value;
        return is_array($value) ? array_map('trim', $value) : trim($value);
    }

    // Obtain REQUEST (GET and POST) parameter
    function req($key, $value = null) {
        $value = $_REQUEST[$key] ?? $value;
        return is_array($value) ? array_map('trim', $value) : trim($value);
    }

    // Redirect to URL
    function redirect($url = null) {
        $url ??= $_SERVER['REQUEST_URI'];
        header("Location: $url");
        exit();
    }

    // Set or get temporary session variable
    function temp($key, $value = null) {
        if ($value !== null) {
            $_SESSION["temp_$key"] = $value;
        }
        else {
            $value = $_SESSION["temp_$key"] ?? null;
            unset($_SESSION["temp_$key"]);
            return $value;
        }
    }

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
    function html_password($name, $attr = '') {
        // Keep value if form was submitted (optional)
        $value = $_POST[$name] ?? '';
    
        // Return input with type password
        return "<input type='password' name='$name' value='$value' $attr>";
    }

    // Generate <input type='radio'> list
    function html_radios($key, $items, $br = false) {
        $value = encode($GLOBALS[$key] ?? '');
        echo '<div>';
        foreach ($items as $id => $text) {
            $state = $id == $value ? 'checked' : '';
            echo "<label><input type='radio' id='{$key}_$id' name='$key' value='$id' $state>$text</label>";
            if ($br) {
                echo '<br>';
            }
        }
        echo '</div>';
    }

    // Generate <select>
    function html_select($key, $items, $default = '- Select One -', $attr = '') {
        $value = encode($GLOBALS[$key] ?? '');
        echo "<select id='$key' name='$key' $attr>";
        if ($default !== null) {
            echo "<option value=''>$default</option>";
        }
        foreach ($items as $id => $text) {
            $state = $id == $value ? 'selected' : '';
            echo "<option value='$id' $state>$text</option>";
        }
        echo '</select>';
    }

    // ============================================================================
    // Error Handlings
    // ============================================================================

    // Global error array
    $_err = [];

    // Generate <span class='err'>
    function err($key) {
        global $_err;
        if (!is_post()) return '';  
        if ($_err[$key] ?? false) {
            echo "<span class='err'>$_err[$key]</span>";
        }
        else {
            echo '<span></span>';
        }
    }

    // ============================================================================
    // Database Setups and Functions
    // ============================================================================

    // Global PDO object
    //TODO
    $_db = new PDO('mysql:dbname=technest', 'root', '', [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ]);


    // Is unique?
    function is_unique($value, $table, $field) {
        global $_db;
        $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
        $stm->execute([$value]);
        return $stm->fetchColumn() == 0;
    }

    // Is exists?
    function is_exists($value, $table, $field) {
        global $_db;
        $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
        $stm->execute([$value]);
        return $stm->fetchColumn() > 0;
    }