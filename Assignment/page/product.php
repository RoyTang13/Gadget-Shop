
<?php
require '../_base.php';

include '../_head.php';
?>

<div class = "browser">
    <div class = "search">
        <div class = "dropdown">
            <button class = "dropbtn">Filter</button>
            <div class = "dropdown-content">
                <div class = "dropdown-row">
                    <span>🔌 Connectivity</span>
                    <div class = "dropdown-subcontent">
                        <label><input type = "checkbox" name = "connectivity[]" value = "wired"> Wired</label>
                        <label><input type = "checkbox" name = "connectivity[]" value = "wireless"> Wireless</label>
                    </div>
                </div>

                <div class = "dropdown-row">
                    <span>🎧 Fit Type</span>
                    <div class = "dropdown-subcontent">
                        <label><input type = "checkbox" name = "design[]" value = "in-ear"> In-ear</label>
                        <label><input type = "checkbox" name = "design[]" value = "over-ear"> Over-ear</label>
                    </div>
                </div>

                <div class = "dropdown-row">
                    <span>🎶 Acoustic</span>
                    <div class = "dropdown-subcontent">
                        <label><input type = "checkbox" name = "acoustic[]" value = "noise-canceled"> Noise-canceled</label>
                        <label><input type = "checkbox" name = "acoustic[]" value = "balanced"> Balanced</label>
                        <label><input type = "checkbox" name = "acoustic[]" value = "clear vocals"> Clear Vocals</label>
                    </div>
                </div>
            </div>
        </div>

        <div class = "dropdown">
            <button class = "dropbtn">Price Range</button>
            <div class = "dropdown-content">

                <!-- Fixed Price Range -->
                <div class = "dropdown-row">
                    <span>Quick Select</span>
                    <div class = "dropdown-subcontent">
                        <label><input type = "radio" name = "fixed_range" value 0.01-250.00>RM 0.01 - RM 250.00</label>
                        <label><input type = "radio" name = "fixed_range" value 250.01-500.00>RM 250.01 - RM 500.00</label>
                        <label><input type = "radio" name = "fixed_range" value 500.01-750.00>RM 500.01 - RM 750.00</label>
                        <label><input type = "radio" name = "fixed_range" value 750.01-1000.00>RM 750.01 - RM 1000.00</label>
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

