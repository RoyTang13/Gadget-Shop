
<?php
require '../_base.php';

$arr = $_db->query('SELECT * FROM product')->fetchAll();

$_title = 'Product | TechNest';

include '../_head.php';
?>

<div class = "browser">
    <div class = "search">

        <!-- Connection Filter -->
        <div class = "dropdown">
            <button class = "dropbtn">🔌Connection</button>
            <div class = "dropdown-content">
                <label><input type = "checkbox" name = "connectivity[]" value = "wired"> Wired</label>
                <label><input type = "checkbox" name = "connectivity[]" value = "wireless"> Wireless</label>
            </div>
        </div>

        <!-- Fit Type Filter -->
        <div class = "dropdown">         
            <button class = "dropbtn">🎧Fit Type</button>
            <div class = "dropdown-content">
                <label><input type = "checkbox" name = "design[]" value = "in-ear"> In-ear</label>
                <label><input type = "checkbox" name = "design[]" value = "over-ear"> Over-ear</label>
            </div>              
        </div>

        <!-- Acoustic Filter -->
        <div class = "dropdown">            
            <button class = "dropbtn">🎶Acoustic</button>
            <div class = "dropdown-content">
                <label><input type = "checkbox" name = "acoustic[]" value = "noise-canceled"> Noise-canceled</label>
                <label><input type = "checkbox" name = "acoustic[]" value = "balanced"> Balanced</label>
                <label><input type = "checkbox" name = "acoustic[]" value = "clear vocals"> Clear Vocals</label>
            </div>
        </div>

        <!-- Price Range Filter -->
        <div class = "dropdown">
            <button class = "dropbtn">Price Range</button>
            <div class = "dropdown-content">

                <!-- Fixed Price Range -->
                <div class = "dropdown-row">
                    <span>Quick Select</span>
                    <div class = "dropdown-subcontent">
                        <label><input type = "radio" name = "fixed_range" value 0.01-300.00>RM 0.01 - RM 300.00</label>
                        <label><input type = "radio" name = "fixed_range" value 300.01-600.00>RM 300.01 - RM 600.00</label>
                        <label><input type = "radio" name = "fixed_range" value 600.01-900.00>RM 600.01 - RM 900.00</label>
                        <label><input type = "radio" name = "fixed_range" value 900.01-1200.00>RM 900.01 - RM 1200.00</label>
                    </div>
                </div>

                <!-- Custom Price Input -->
                <div class = "dropdown-row"> 
                    <span>Custom Range</span>
                    <div class = "dropdown-subcontent" style = "padding: 10px 15px;">
                        <label>Minimum (RM): <input type = "number" 
                                                    id = "customMin" 
                                                    min = "0.01" 
                                                    step = "0.01" 
                                                    placeholder = "Start 0.01" 
                                                    style = "width: 100px;">
                        </label>
                        <label style = "margin-top: 8px;">Maximum (RM): <input type = "number" 
                                                                               id = "customMax" 
                                                                               min = "0.01" 
                                                                               step = "0.01" 
                                                                               placeholder = "Start 0.02"
                                                                               style = "width: 100px;">
                        </label>
                        <button id = "applyCustomPrice" style = "margin-top: 10px;">Apply</button>
                    </div>
                </div>
            </div>
        </div>

        <div class = "search_bar">
            <form method = "get">
                <input type = "search" 
                    placeholder = "Type the product name..." 
                    value = "<?= ($_GET['search'] ?? '') ?>"
                    class = "search_bar-font"
                    >        
            <button type = "Submit" class = "search_bar-button">Search</button>
            </form>
        </div>
    </div>
</div>

<!-- Script for custom price function -->
<script>
    // When clicking fixed range, clear custom inputs
    document.querySelectorAll("input[name = 'fixedPrice']").forEach(radio => {
        radio.addEventListener("change", () => {
            document.getElementById("customMin").value = "";
            document.getElementById("customMax").value = "";
        });
    });

    // When applying custom range, clear fixed selection
    document.getElementById("applyCustomPrice").addEventListener("click", () => {
        let min = document.getElementById("customMin").value;
        let max = document.getElementById("customMax").value;

        if (min === "" || max === "") {
            // Warning for applied failure: Empty Input
            alert("Please enter both minimum and maximum values.");
        }
        else if (parseFloat(min) >= parseFloat(max)) {
            // Warning for applied failure: Minimum > Maximum
            alert("Minimum price cannot be greater or equal to maximum price.");
        }
        else if (min <= 0.00 ||min >= 1000.00){
            // Warning for applied failure: Minimum input wrongly
            alert("Please input minimum price correctly.");
        }
        else if (max <= 0.01 ||max > 1000.00){
            // Warning for applied failure: Maximum input wrongly
            alert("Please input maximum price correctly.");
        }
        else{
            // Clear fixed radios
            document.querySelectorAll("input[name = 'fixedPrice']").forEach(r => r.checked = false);

            // Notice for applied success
            alert("Custom price applied: RM " + min + " - RM " + max);
            return;
        }
    });
</script>

<!-- Sort Bar + Paging -->
<div class = "sort_bar">
    <div class = "sorting_left">
        <h5>Arranging by: </h5>
        
        <!-- Sort by Name -->
        <div class = "dropdown">
            <button class = "dropbtn">Name</button>
            <div class = "dropdown-content">
                <label><input type = "radio" name = "sort" value = "asc">From A - Z</label>
                <label><input type = "radio" name = "sort" value = "desc">From Z - A</label>
            </div>
        </div>

        <!-- Sort by Price -->
        <div class = "dropdown">
            <button class = "dropbtn">Price</button>
            <div class = "dropdown-content">
                <label><input type = "radio" name = "sort" value = "asc">↑ Ascending</label>
                <label><input type = "radio" name = "sort" value = "desc">↓ Descending</label>
            </div>
        </div>

        <!-- Sort by Latest Release Time -->
        <div class = "direct">
            <button class = "directbtn">Latest Time</button>
        </div>
    </div>

    <div class = "sorting_right">

        <!-- Paging with textable page number -->

    </div>
</div>

<!-- Product Photo with Name, Description and Price-->
<div class="gallery"> 
    <div class="gallery-item"> 
        <img src="images/banner1.jpg"> 
        <div class="desc">Product Name 1<br>RM 199.00</div> 
    </div> 
    
    <div class="gallery-item"> 
        <img src="images/banner1.jpg"> 
        <div class="desc">Product Name 2<br>RM 249.00</div> 
    </div> 
    
    <div class="gallery-item"> 
        <img src="images/banner1.jpg"> 
        <div class="desc">Product Name 3<br>RM 299.00</div> 
    </div> 
    
    <div class="gallery-item"> 
        <img src="images/banner1.jpg"> 
        <div class="desc">Product Name 4<br>RM 349.00</div> 
    </div> 
    
    <div class="gallery-item"> 
        <img src="images/banner1.jpg"> 
        <div class="desc">Product Name 5<br>RM 399.00</div> 
    </div> 
</div>

<style>

</style>


<script>

</script>
