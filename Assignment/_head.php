<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $_title ?? 'TechNest' ?></title> 
<link rel="shortcut icon" href="/images/favicon3.png">
<link rel="stylesheet" href="/css/app.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<script src="/js/app.js"></script>
</head>
<body>
<header>

    <h1><a href="/">TechNest</a></h1>
    <div class="Login_Register">
        <?php if (!isset($_SESSION['userID'])): ?>
            <button class="login-btn" data-get="/page/login.php">Login</button>
            <button class="login-btn" data-get="/page/register.php">Register</button>
        <?php else: ?>
            <div class="user-dropdown">
                <button class="user-btn">
                <img class="user-photo" src="/userPhoto/<?= htmlspecialchars($_SESSION['userPhoto']) ?? 'default.jpg' ?>" width="36">
                    <span>Hi, <?= htmlspecialchars($_SESSION['lname'] ?? $_SESSION['fname'] ?? 'User') ?> &#9662; </span>
                </button>
                <div class="dropdown-content">
                    <a href="/page/profile.php">Update Profile</a>
                    <a href="/product/cart.php"><i class="fa fa-shopping-cart" style="font-size:24px"></i> Cart</a>
                    <a href="/product/order_history.php">View History</a>
                    <a href="/page/logout.php">Logout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>

<nav class="navbar">
    <a href="/">Home</a>
    <a href="/product/product.php">Product</a>
    <a href="/product/cart.php">Cart</a>
    <a href="/product/order_history.php">View History</a>
    <a href="/page/contactus.php">Contact Us</a>
</button>
</button>
    </div>
</nav>

<main>
</main>

<script>
function closePopup() {
    const popup = document.getElementById('popupOverlay');
    if (popup) {
        popup.remove();
    }
}
</script>
