<?php

add_action('woocommerce_after_cart_totals', 'cart2csv');

function cart2csv() 
{
    $csvList = array("Product Name", "SKU", "Price", "Quantity", "Subtotal"); // Excel header

    foreach (WC()->cart->get_cart() as $cart_item) {
        $product         = $cart_item['data']; // Products Objects

        $regular_price   = $product->get_regular_price(); // Normal Price
        $sale_price      = $product->get_sale_price(); // Sale Price
        $price           = $product->get_price(); // Last Price
        $sku             = $product->get_sku(); // Stock Code
        $name            = $product->get_name(); // Product Name
        $quantity        = $cart_item['quantity']; // Cart Item Product Quantity
        $line_subtotal   = $cart_item['line_subtotal'];  // Cart Item Subtotal
      
        array_push($csvList, $name, $sku, $price, $quantity, $line_subtotal); // Add cart items to $csvList array
    }

    $arr = array_chunk($csvList, 5); // convert one-dimensional array to two-dimensional array php
    $jsArray = json_encode($arr); // convert array to json
    
    ?>
    <label class="button alt">
		Download: 
		<select id="cartDownload">
			<option value="csv">CSV</option>
			<option value="xml">XML</option>
			<option value="xls">Excel</option>
			<option value="txt">Text</option>
			<option value="json">JSON</option>
			<option value="html">HTML</option>
			<option value="css">CSS</option>
		</select>
	</label>
    <script src="https://unpkg.com/export-from-json@1.7.0/dist/umd/index.min.js"></script>
    <script>
    	var data = <?= $jsArray ?>;

        //Format: txt , json , csv , xls ,xml , html ,css
        function download(exportType, fileName) {
        	(exportType == "txt") ? data = JSON.stringify(data) : "";
            window.exportFromJSON({ data, fileName, exportType });
        }

        const downloadClick = document.getElementById("cartDownload");
        downloadClick.addEventListener("change", (event) => {
			download(event.target.value, `${event.target.value}-cart-download`);
		}, false);
    </script>
    <?php 
}
