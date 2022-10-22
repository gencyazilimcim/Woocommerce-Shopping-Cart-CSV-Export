<?php

add_action('woocommerce_after_cart_totals', 'cart2csv');

function cart2csv() 
{
    echo '<button id="cartDownload" class="button alt">Download as CSV</button>';

    $csvList = array("Product Name", "SKU", "Price", "Quantity", "Subtotal"); // CSV header

    foreach (WC()->cart->get_cart() as $cart_item) {
        $product            = $cart_item['data']; // Products Objects

        $regular_price      = $product->get_regular_price(); // Normal Price
        $sale_price         = $product->get_sale_price(); // Sale Price
        $price              = $product->get_price(); // Last Price
        $sku                = $product->get_sku(); // Stock Code
        $name               = $product->get_name(); // Product Name
        $quantity           = $cart_item['quantity']; // Cart Item Product Quantity
        $line_subtotal      = $cart_item['line_subtotal'];  // Cart Item Subtotal
      
        array_push($csvList, $name, $sku, $price, $quantity, $line_subtotal); // Add cart items to $csvList array
    }

    $arr = array_chunk($csvList, 5); // convert one-dimensional array to two-dimensional array php
    $jsArray = json_encode($arr); // convert array to json
  
    ?>
    <script>
      // Example data given in question text
      var data = <?= $jsArray ?>;

      // Building the CSV from the Data two-dimensional array
      // Each column is separated by ";" and new line "\n" for next row
      var csvContent = '';
      data.forEach(function (infoArray, index) {
          dataString = infoArray.join(';');
          csvContent += index < data.length ? dataString + '\n' : dataString;
      });

      // The download function takes a CSV string, the filename and mimeType as parameters
      // Scroll/look down at the bottom of this snippet to see how download is called
      var download = function (content, fileName, mimeType) {
          var a = document.createElement('a');
          mimeType = mimeType || 'application/octet-stream';

          if (navigator.msSaveBlob) {
              // IE10
              navigator.msSaveBlob(
                  new Blob([content], {
                      type: mimeType,
                  }),
                  fileName,
              );
          } else if (URL && 'download' in a) {
              //html5 A[download]
              a.href = URL.createObjectURL(
                  new Blob([content], {
                      type: mimeType,
                  }),
              );
              a.setAttribute('download', fileName);
              document.body.appendChild(a);
              a.click();
              document.body.removeChild(a);
          } else {
              location.href = 'data:application/octet-stream,' + encodeURIComponent(content); // only this mime type is supported
          }
      };

      //download button click
      const downloadClick = document.getElementById('cartDownload');
      downloadClick.addEventListener('click', () => {
          download(csvContent, 'cart-dowload.csv', 'text/csv;encoding:utf-8');
      }, false);
    </script>
<?php
  
}
