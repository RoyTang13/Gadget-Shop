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

    // Generate <input type='checkbox'> list
    function html_checkboxes($key, $items, $br = false) {
        $value = is_array($GLOBALS[$key]) ? $GLOBALS[$key] : [];
        echo '<div>';
        foreach ($items as $id => $text) {
            $state = in_array($id, $value) ? 'checked' : '';
            echo "<label><input type='checkbox' id='{$key}_$id' name='$key' value='$id' $state>$text</label>";
            if ($br) {
                echo '<br>';
            }
        }
        echo '</div>';
    }

    // Generate <input type='search'> list
    function html_search($key, $attr = '') {
        $value = encode($GLOBALS[$key] ?? '');
        echo "<input type='search' id='$key' name='$key' value='$value' $attr>";
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

    // Generate <input type='file'>
    function html_file($key, $accept = '', $attr = '') {
        echo "<input type='file' id='$key' name='$key' accept='$accept' $attr>";
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

    // Obtain uploaded file --> cast to object
    function get_file($key) {
        $f = $_FILES[$key] ?? null;
    
        if ($f && $f['error'] == 0) {
            return (object)$f;
        }

        return null;
    }

    // Crop, resize and save photo
    function save_photo($f, $folder, $width = 300, $height = 300) {
        $productImg = uniqid() . '.jpg';
    
        require_once 'lib/SimpleImage.php';
        $productImg = new SimpleImage();
        $productImg->fromFile($f->tmp_name)
                   ->thumbnail($width, $height)
                   ->toFile("$folder/$productImg", 'image/jpeg');

        return $productImg;
    }

    // ============================================================================
    // Admin-Specific Functions
    // ============================================================================

    // Connect to admin database
    try {
        $_admin_db = new PDO('mysql:host=localhost;dbname=technest;charset=utf8mb4', 'root', '', [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $_admin_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Admin database connection failed: " . $e->getMessage());
    }

    // Check if admin is logged in
    function check_admin_login() {
        if (!isset($_SESSION['adminID'])) {
            redirect('/admin/index.php');
            exit;
        }
    }

    // Admin logout
    function admin_logout() {
        session_unset();
        session_destroy();
        redirect('/admin/login.php');
    }

    // Hash password
    function hash_password($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Verify password
    function verify_password($password, $hash) {
        return password_verify($password, $hash);
    }

    // Fetch admin by email
    function get_admin_by_email($email) {
        global $_admin_db;
        $stm = $_admin_db->prepare("SELECT * FROM admin WHERE email = ? LIMIT 1");
        $stm->execute([$email]);
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    // Optional: Add a new admin
    function add_admin($fname, $lname, $email, $phoneNo, $password) {
        global $_admin_db;
        $hash = hash_password($password);
        $stm = $_admin_db->prepare("INSERT INTO admin (fname, lname, email, phoneNo, password) VALUES (?, ?, ?, ?, ?)");
        return $stm->execute([$fname, $lname, $email, $phoneNo, $hash]);
}



    // ------------------ Popup Function ------------------

    // Set a temporary message (stored in session)
    function set_popup($msg) {
        $_SESSION['popup'] = $msg;
    }
    
    function show_popup() {
        if (!empty($_SESSION['popup'])) {
            $msg = $_SESSION['popup'];
            if (is_array($msg)) {
                $msg = implode('<br>', $msg);  // optional: join array to string
            }
            echo '<div class="popup-overlay" id="popupOverlay">
                    <div class="popup-box">
                        <p>'.htmlspecialchars($msg).'</p>
                        <button onclick="closePopup()">OK</button>
                    </div>
                  </div>';
            unset($_SESSION['popup']);
        }
    }
    


        
