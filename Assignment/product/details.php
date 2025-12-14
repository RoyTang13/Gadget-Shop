document.getElementById("qtyMinus").addEventListener("click", () => {
    let qty = document.getElementById("qtyInput");
    if (qty.value > 1) qty.value--;
});

document.getElementById("qtyPlus").addEventListener("click", () => {
    let qty = document.getElementById("qtyInput");
    qty.value++;
});

const qtyInput = document.getElementById("qtyInput");
const addQty   = document.getElementById("addQty");
    
function syncQty() {
    addQty.value = qtyInput.value;
}

document.getElementById("qtyMinus").addEventListener("click", syncQty);
document.getElementById("qtyPlus").addEventListener("click", syncQty);
qtyInput.addEventListener("change", syncQty);
</script>
