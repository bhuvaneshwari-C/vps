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
                        var pdfDataUri = 'data:application/pdf;base64,' + product.pdf_content;
                        $('#pdfLink_' + i).attr('href', pdfDataUri);

                        var pdfDownloadLink = `
                            <li>
                                <a href="${pdfDataUri}" download="${product.pdf_name}">
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
                query = query.toLowerCase();
                filteredItems = items.filter(function(product) {
                    return product.name.toLowerCase().includes(query) || product.description.toLowerCase().includes(query);
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
    var query = document.getElementById("searchQuery").value.toLowerCase();
    console.log("Search Query:", query); // Debugging: Check the input

    // Define your search terms and corresponding URLs
    var pages = {
        'agricultural': 'agricultural-products.php',
        'domestic': 'domestic-products.php',
        'solar': 'solar-products.php'
    };

    // Check if the search term matches any key in the pages object
    if (pages[query]) {
        console.log("Redirecting to:", pages[query]); // Debugging: Check redirection
        // Redirect to the corresponding page
        window.location.href = pages[query];
    } else {
        // If no match is found, show an alert
        alert('No matching category found.');
    }

    // Prevent the default form submission
    return false;
}
