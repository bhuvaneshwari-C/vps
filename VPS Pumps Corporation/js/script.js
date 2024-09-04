
$(document).ready(function() {
    // Get the product category from the hidden input field
    var productCategory = $('#productCategory').val();

    $.ajax({
        url: 'fetch-products.php',
        method: 'GET',
        dataType: 'json',
        data: { category: productCategory }, // Pass the category as a parameter
        success: function(data) {
            var items = data;
            var filteredItems = items;
            var perPage = 10;

            function updateResultSummary(start, end, total) {
                $('#result-summary').text(`Showing ${start + 1}–${end} of ${total} results`);
            }

            function updateProductsAndPDFs(pageNumber) {
                var showFrom = perPage * (pageNumber - 1);
                var showTo = Math.min(showFrom + perPage, filteredItems.length);

                $('#productContainer').empty();
                $('#pdfContainer').empty();

                for (var i = showFrom; i < showTo; i++) {
                    var product = filteredItems[i];
                    var productCard = `
                        <div class="col-lg-6 col-md-4 col-sm-6 m-b30 product-item">
                            <div class="wt-box wt-product-box overflow-hide">
                                <div class="wt-thum-bx wt-img-overlay1">
                                    <img src="data:image/jpeg;base64,${product.image}" alt="Product Image" width="300">
                                </div>
                                <div class="wt-info text-center">
                                    <h4 class="wt-title"><a href="product-detail.html">${product.name}</a></h4>
                                    <span class="price">
                                        <p class="text-justify p-3">${product.description}</p>
                                    </span>
                                    <center>
                                        <div>
                                            <button class="site-button btn-hover-animation m-b30">
                                                <a id="pdfLink_${i}" href="#" target="_blank" style="color:white">
                                                    <i class="fa fa-file-pdf-o"></i> View Pdf
                                                </a>
                                            </button>
                                            <button class="site-button btn-hover-animation m-b30" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                                                <i class="flaticon-right"></i> Enquire Now !
                                            </button>
                                        </div>
                                    </center>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#productContainer').append(productCard);

                    if (product.pdf_name && product.pdf_content) {
                        // Convert Base64 to a Uint8Array
                        var binaryString = window.atob(product.pdf_content);
                        var len = binaryString.length;
                        var bytes = new Uint8Array(len);
                        for (var j = 0; j < len; j++) {
                            bytes[j] = binaryString.charCodeAt(j);
                        }
                    
                        // Create a Blob with the decoded data
                        var pdfBlob = new Blob([bytes], { type: 'application/pdf' });
                        var pdfUrl = URL.createObjectURL(pdfBlob);
                    
                        $('#pdfLink_' + i).attr('href', pdfUrl);
                        
                        var pdfDownloadLink = `
                            <li>
                                <a href="${pdfUrl}" download="${product.pdf_name}">
                                    <i class="fa fa-file-pdf-o"></i> Download ${product.pdf_name}
                                </a>
                            </li>
                        `;
                        $('#pdfContainer').append(pdfDownloadLink);
                    }
                    
                }

                updateResultSummary(showFrom, showTo, filteredItems.length);
            }

            function performSearch(query) {
                // Normalize the search query by trimming spaces, converting to lowercase, and removing extra spaces
                query = query.toLowerCase().trim().replace(/\s+/g, ' ');

                // Handle queries without spaces by checking if they contain parts of product names or descriptions
                filteredItems = items.filter(function(product) {
                    // Combine product name and description, remove spaces, and convert to lowercase
                    var combinedText = (product.name + " " + product.description).toLowerCase().replace(/\s+/g, '');
                    // Also normalize the product name and description by removing extra spaces
                    var normalizedText = (product.name + " " + product.description).toLowerCase().trim().replace(/\s+/g, ' ');

                    // Check if the query matches either the combined text without spaces or the normalized text with spaces
                    return combinedText.includes(query.replace(/\s+/g, '')) || normalizedText.includes(query);
                });

                $('#pagination-container').pagination({
                    dataSource: filteredItems,
                    pageSize: perPage,
                    callback: function(data, pagination) {
                        updateProductsAndPDFs(pagination.pageNumber);
                    }
                });
                updateProductsAndPDFs(1);
            }

            $('#productSearch').on('input', function() {
                var query = $(this).val();
                performSearch(query);
            });

            $('#pagination-container').pagination({
                dataSource: items,
                pageSize: perPage,
                callback: function(data, pagination) {
                    updateProductsAndPDFs(pagination.pageNumber);
                }
            });

            updateProductsAndPDFs(1);
        },
        error: function(err) {
            console.error('Failed to fetch products:', err);
        }
    });
});


// search Redirect

function searchRedirect() {
    // Get the value entered by the user
    var query = document.getElementById("searchQuery").value.toLowerCase().trim();
    console.log("Search Query:", query); // Debugging: Check the input

    // Define your search terms and corresponding URLs
    var pages = {
        'agricultural': 'agricultural-products.php',
        'domestic': 'domestic-products.php',
        'solar': 'solar-products.php'
    };

    // Map of possible search term variations to the correct key
    var searchMap = {
        'agricultural': ['agricultural', 'agri', 'a', 'agr', 'agricultural product'],
        'domestic': ['domestic', 'dom', 'd', 'domestic product'],
        'solar': ['solar', 'sol', 's', 'solar product']
    };

    // Iterate through the searchMap to find a matching term
    for (var key in searchMap) {
        if (searchMap[key].includes(query)) {
            console.log("Redirecting to:", pages[key]); // Debugging: Check redirection
            // Redirect to the corresponding page
            window.location.href = pages[key];
            return false; // Prevent default form submission
        }
    }

    // If no match is found, show an alert
    alert('No matching category found.');
    return false; // Prevent default form submission
}

