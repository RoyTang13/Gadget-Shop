<?php
require '../_base.php';
show_popup();
/* ===================== VARIABLES ===================== */
$allowedBanks = [
    'Maybank','CIMB','Public Bank',
    'RHB','Hong Leong Bank','AmBank','Affin Bank'
];

$bankName   = '';
$cardName   = '';
$cardNumber = '';
$expiry     = '';
$cvv        = '';

$fullName  = '';
$address   = '';
$city      = '';
$postcode  = '';
$state     = '';

/* =====================
   1. AUTH & ORDER CHECK
   ===================== */
if (!isset($_SESSION['userID'])) {
    redirect('/login.php');
    exit;
}

$userID = $_SESSION['userID'];

if (empty($_GET['orderID']) || !ctype_digit($_GET['orderID'])) {
    redirect('/product/cart.php');
    exit;
}

$orderID = (int)$_GET['orderID'];

$stmt = $_db->prepare(
    "SELECT orderID FROM orders WHERE orderID = ? AND userID = ?"
);
$stmt->execute([$orderID, $userID]);

if (!$stmt->fetch()) {
    redirect('/product/cart.php');
    exit;
}

/* =====================
   2. FORM PROCESSING
   ===================== */

    $errors = [];
    $fieldErrors = []; // for per-field errors

    // Fetch last payment info for sticky note
    $stmtInfo = $_db->prepare("
        SELECT billingName, address, city, postcode, state,
            bankName, cardHolder, cardNumber, expiryDate
        FROM payment_info pi
        JOIN orders o ON pi.orderID = o.orderID
        WHERE o.userID = ?
        ORDER BY pi.createdAt DESC
        LIMIT 1
    ");
    $stmtInfo->execute([$userID]);
    $lastPayment = $stmtInfo->fetch(PDO::FETCH_ASSOC);

    if ($lastPayment) {
        $fullName = $lastPayment['billingName'];
        $address  = $lastPayment['address'];
        $city     = $lastPayment['city'];
        $postcode = $lastPayment['postcode'];
        $state    = $lastPayment['state'];
        $bankName = $lastPayment['bankName'];
        $cardName = $lastPayment['cardHolder'];
    } else {
        // Default empty values if no previous payment exists
        $fullName  = '';
        $address   = '';
        $city      = '';
        $postcode  = '';
        $state     = '';
        $bankName  = '';
        $cardName  = '';    
    }

    //sticky note for wrong input
    if ($lastPayment) {
        $fullName = $lastPayment['billingName'];
        $address  = $lastPayment['address'];
        $city     = $lastPayment['city'];
        $postcode = $lastPayment['postcode'];
        $state    = $lastPayment['state'];
        $bankName = $lastPayment['bankName'];
        $cardName = $lastPayment['cardHolder'];

    if (!empty($lastPayment['cardNumber'])) {
        // remove any non-digit characters
        $cardNumber = preg_replace('/\D/', '', $lastPayment['cardNumber']); 
        // format as 1234 5678 9012 3456
        $cardNumberFormatted = chunk_split($cardNumber, 4, ' ');
    } else {
        $cardNumberFormatted = '';
    }

    $expiry = $lastPayment['expiryDate'] ?? '';
    $cvv    = '' ;
}

if (is_post()) {

    // collect inputs
    $bankName   = $_POST['bankName'] ?? '';
    $cardName   = trim($_POST['cardName'] ?? '');
    $cardNumber = preg_replace('/\D/', '', $_POST['cardNumber'] ?? '');
    $cardNumberFormatted = format_card_number($cardNumber);
    $expiry     = $_POST['expiryDate'] ?? '';
    $cvv        = $_POST['cvv'] ?? '';

    $fullName = trim($_POST['fullName'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $city     = trim($_POST['city'] ?? '');
    $postcode = trim($_POST['postcode'] ?? '');
    $state    = trim($_POST['state'] ?? '');

    /* ---- validation ---- */

    if (!in_array($bankName, $allowedBanks)) {
        $fieldErrors['bankName'] = 'Invalid bank selected.';
    }

    if ($cardName === '') {
        $fieldErrors['cardName'] = 'Card holder name is required.';
    }

    if (strlen($cardNumber) < 16 || strlen($cardNumber) > 19) {
        $fieldErrors['cardNumber'] = 'Card number must be 16–19 digits.';
    }

    if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
        $fieldErrors['expiryDate'] = 'Invalid expiry date format.';
    }

    if (!ctype_digit($cvv) || strlen($cvv) !== 3) {
        $fieldErrors['cvv'] = 'Invalid CVV.';
    }

    if ($fullName === '') $fieldErrors['fullName'] = 'Full name is required.';
    if ($address === '')  $fieldErrors['address'] = 'Address is required.';
    if ($city === '')     $fieldErrors['city'] = 'City is required.';
    if ($postcode === '') $fieldErrors['postcode'] = 'Postcode is required.';
    if ($state === '')    $fieldErrors['state'] = 'State is required.';

    /* ---- save ---- */
    if (!$fieldErrors) {
        $maskedCard = '**** **** **** ' . substr($cardNumber, -4);

        $stmt = $_db->prepare("
            INSERT INTO payment_info
            (orderID, bankName, cardHolder, cardNumber, expiryDate,
             billingName, address, city, postcode, state)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $orderID,
            $bankName,
            $cardName,
            $cardNumber,
            $expiry,
            $fullName,
            $address,
            $city,
            $postcode,
            $state
        ]);

        // --- DELETE CART ITEMS AFTER PAYMENT ---
        if (!empty($_SESSION['checkout_items'])) {
            $placeholders = implode(',', array_fill(0, count($_SESSION['checkout_items']), '?'));
            $stmtDelete = $_db->prepare("DELETE FROM cart WHERE userID = ? AND id IN ($placeholders)");
            $stmtDelete->execute(array_merge([$userID], $_SESSION['checkout_items']));
            unset($_SESSION['checkout_items']); // clear session
        }


        set_popup('Payment successful!');
        redirect("/product/order_success.php?orderID=$orderID");
        exit;
    }

        
    }
/* =====================
   3. PAGE OUTPUT
   ===================== */

$_title = 'Payment | TechNest';
include '../_head.php';
?>

<main class="pay-page">
    <form class="pay-wrapper" method="post">

        <!-- LEFT -->
        <div class="pay-left">
            <h2>Billing Details</h2>

            <label>Full Name
                <input type="text" name="fullName" 
                       value="<?= htmlspecialchars($fullName ?? '') ?>">
                <?php if(isset($fieldErrors['fullName'])): ?>
                    <div class="err"><?= htmlspecialchars($fieldErrors['fullName']) ?></div>
                <?php endif; ?>
            </label>

            <label>Address
                <textarea name="address"><?= htmlspecialchars($address ?? '') ?></textarea>
                <?php if(isset($fieldErrors['address'])): ?>
                    <div class="err"><?= htmlspecialchars($fieldErrors['address']) ?></div>
                <?php endif; ?>
            </label>

            <label>City
                <input type="text" name="city" value="<?= htmlspecialchars($city ?? '') ?>">
                <?php if(isset($fieldErrors['city'])): ?>
                    <div class="err"><?= htmlspecialchars($fieldErrors['city']) ?></div>
                <?php endif; ?>
            </label>

            <label>Postcode
                <input type="text" name="postcode" value="<?= htmlspecialchars($postcode ?? '') ?>">
                <?php if(isset($fieldErrors['postcode'])): ?>
                    <div class="err"><?= htmlspecialchars($fieldErrors['postcode']) ?></div>
                <?php endif; ?>
            </label>

            <label>State
                <input type="text" name="state" value="<?= htmlspecialchars($state ?? '') ?>">
                <?php if(isset($fieldErrors['state'])): ?>
                    <div class="err"><?= htmlspecialchars($fieldErrors['state']) ?></div>
                <?php endif; ?>
            </label>
        </div>

        <!-- RIGHT -->
        <div class="pay-right">
            <h2>Payment</h2>

            <div class="pay-card">
                <label>Bank Name
                    <select name="bankName" class="pay-select">
                        <option value="">-- Select Bank --</option>
                        <?php foreach ($allowedBanks as $b): ?>
                            <option value="<?= $b ?>" <?= ($bankName==$b?'selected':'') ?>><?= $b ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if(isset($fieldErrors['bankName'])): ?>
                        <div class="err"><?= htmlspecialchars($fieldErrors['bankName']) ?></div>
                    <?php endif; ?>
                </label>

                <label>Card Holder Name
                    <input type="text" name="cardName" value="<?= htmlspecialchars($cardName ?? '') ?>">
                    <?php if(isset($fieldErrors['cardName'])): ?>
                        <div class="err"><?= htmlspecialchars($fieldErrors['cardName']) ?></div>
                    <?php endif; ?>
                </label>

                <label>Card Number
                    <input type="text" name="cardNumber" value="<?= htmlspecialchars($cardNumberFormatted ?? '') ?>"
                           placeholder="1234 5678 9012 3456">
                    <?php if(isset($fieldErrors['cardNumber'])): ?>
                        <div class="err"><?= htmlspecialchars($fieldErrors['cardNumber']) ?></div>
                    <?php endif; ?>
                </label>

                <div class="pay-row">
                    <label>Expiry
                        <input type="text" name="expiryDate" value="<?= htmlspecialchars($expiry ?? '') ?>" placeholder="MM/YY">
                        <?php if(isset($fieldErrors['expiryDate'])): ?>
                            <div class="err"><?= htmlspecialchars($fieldErrors['expiryDate']) ?></div>
                        <?php endif; ?>
                    </label>

                    <label>CVV
                        <input type="password" name="cvv" value="<?= htmlspecialchars($cvv ?? '') ?>">
                        <?php if(isset($fieldErrors['cvv'])): ?>
                            <div class="err"><?= htmlspecialchars($fieldErrors['cvv']) ?></div>
                        <?php endif; ?>
                    </label>
                </div>
            </div>

            <button class="pay-btn">Pay Now</button>
        </div>

    </form>
</main>

<?php include '../_foot.php'; ?>
<style>
/* ================= PAYMENT PAGE ONLY ================= */

.pay-page {
    padding: 140px 20px 80px; /* avoids header + nav */
    display: flex;
    justify-content: center;
}

.pay-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    max-width: 900px;
    width: 100%;
}

.pay-wrapper h2 {
    margin-bottom: 15px;
    color: #5b21b6;
}

.err { color: #991b1b; margin-top: 5px; font-size: 14px; }

/* LEFT */
.pay-left label {
    display: block;
    margin-bottom: 14px;
    font-weight: 500;
}

.pay-left input,
.pay-left textarea {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.pay-left textarea {
    resize: none;
    height: 80px;
}

/* RIGHT */
.pay-card {
    background: linear-gradient(135deg, #693b9f, #4c1d95);
    padding: 25px;
    border-radius: 18px;
    color: #fff;
    box-shadow: 0 12px 30px rgba(0,0,0,.35);
}

.pay-card label {
    display: block;
    margin-bottom: 14px;
    font-weight: 500;
}

.pay-card input {
    width: 100%;
    padding: 5px;
    margin-top: 6px;
    border-radius: 8px;
    border: none;
    background: rgba(255,255,255,.18);
    color: #fff;
}

.pay-card input::placeholder {
    color: rgba(255,255,255,.75);
}

.pay-row {
    display: flex;
    gap: 15px;
}

.pay-select {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 8px;
    border: none;
    background: rgba(255,255,255,.18);
    color: #fff;
    font-size: 15px;
    cursor: pointer;
}

.pay-select option {
    color: #000;
}


/* BUTTON */
.pay-btn {
    width: 100%;
    margin-top: 25px;
    padding: 14px;
    border-radius: 12px;
    border: none;
    background: #693b9f;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}

.pay-btn:hover {
    background: #de87ee;
}

/* MOBILE */
@media (max-width: 768px) {
    .pay-wrapper {
        grid-template-columns: 1fr;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cardInput = document.querySelector('input[name="cardNumber"]');
    const expiryInput = document.querySelector('input[name="expiryDate"]');

    if (!cardInput) return;

    cardInput.addEventListener('input', e => {
        let value = e.target.value.replace(/\D/g, ''); // remove non-digits
        value = value.match(/.{1,4}/g)?.join(' ') || ''; // add space every 4 digits
        e.target.value = value;
    });

     // Format expiry date: MM/YY
     if (expiryInput) {
        expiryInput.addEventListener('input', e => {
            let value = e.target.value.replace(/\D/g, ''); // remove non-digits
            if (value.length > 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
    }
});
</script>
