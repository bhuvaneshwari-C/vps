<?php
session_start();
include './database_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
$name = $_POST['enquiry_name'];
$phone = $_POST['number'];
$email = $_POST['email'];
$city = $_POST['city'];
$state = $_POST['state'];
$category = $_POST['enquiry_category'];
$message = $_POST['message'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO enquiries (name, phone, email, city, state, category, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $phone, $email, $city, $state, $category, $message);

if ($stmt->execute()) {
    echo "<script>
    alert('Thank you for your enquiry!');
    window.location.href='agricultural-products.php'; // Redirect to the form page or any other page
    </script>";
} else {
    echo "<script>
    alert('Oops! Something went wrong. Please try again later.');
    window.location.href='agricultural-products.php'; // Redirect to the form page or any other page
    </script>";
}

}

$agriculture_count = 0;
$solar_count = 0;
$domestic_count = 0;

$sql_agriculture = "SELECT COUNT(*) as agriculture_count FROM product_details WHERE product_category = 'Agricultural Product'";
$sql_solar = "SELECT COUNT(*) as solar_count FROM product_details WHERE product_category = 'Solar Product'";
$sql_domestic = "SELECT COUNT(*) as domestic_count FROM product_details WHERE product_category = 'Domestic Product'";

$result_agriculture = $conn->query($sql_agriculture);
$result_solar = $conn->query($sql_solar);
$result_domestic = $conn->query($sql_domestic);

if ($result_agriculture) {
    $row = $result_agriculture->fetch_assoc();
    $agriculture_count = $row['agriculture_count'];
}

if ($result_solar) {
    $row = $result_solar->fetch_assoc();
    $solar_count = $row['solar_count'];
}

if ($result_domestic) {
    $row = $result_domestic->fetch_assoc();
    $domestic_count = $row['domestic_count'];
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="" />
    <meta name="description" content="Explore the range of water pumps offered by VPS Pumps Corporation, including submersible, borewell, agricultural, and industrial pumps. Find high-quality solutions for all your water management needs in Theni." />
    
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    
    <!-- PAGE TITLE HERE -->
    <title>Our Products | Water Pumps for Every Need in Theni Tamilnadu</title>
    
    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
    <!-- BOOTSTRAP STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <!-- FONTAWESOME STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
    <!-- OWL CAROUSEL STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
  
    <!-- MAGNIFIC POPUP STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/magnific-popup.min.css">
   
    <!-- MAIN STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- FLATICON STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/flaticon.min.css">
    <!-- Lc light box popup -->
    <link rel="stylesheet" href="css/lc_lightbox.css" /> 
    <!-- Price Range Slider -->
    <link rel="stylesheet" href="css/bootstrap-slider.min.css" />


    <!-- THEME COLOR CHANGE STYLE SHEET -->
    <link rel="stylesheet" class="skin" type="text/css" href="css/skin-1.css">
    <!-- SIDE SWITCHER STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/switcher.css">    

        

    <!-- REVOLUTION SLIDER CSS -->
    <link rel="stylesheet" type="text/css" href="plugins/revolution/revolution/css/settings.css">
    <!-- REVOLUTION NAVIGATION STYLE -->
    <link rel="stylesheet" type="text/css" href="plugins/revolution/revolution/css/navigation.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.css">

    <style>
             .search-container {
            width: 100%;
            max-width: 250px;
            margin-bottom: 20px;
            position: relative;
        }

        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: #333;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            outline: none;
        }

        .search-box::placeholder {
            color: #888;
        }

        .search-box:focus {
            border-color: #666;
        }

        .search-container::after {
            content: '\f002';
            font-family: "FontAwesome";
            font-size: 16px;
            color: #333;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }
        /* enquiry form */
        .modal-dialog {
            max-width: 800px; 
        }
       .form-select {
           height: 45px;
           font-size: 14px;
           line-height: 20px;
           border-radius: 5px;
           border: solid 1px #ced4da;
           padding: 10px 12px;
           width: 100%;
           background-color: white;
           background-position: right 10px center;
        }
       .form-control-input {
           height: 45px;
           font-size: 14px;
           line-height: 2px;
           border-radius: 5px;
           border: solid 1px #ced4da;
           padding: 10px 12px;
           display:block;
           width:100%;
        }
        .reset-btn, .btn-primary {
           height: 45px;
           font-size: 14px;
           border-radius: 5px;
           padding: 10px 20px;
           display: inline-block;
           border: none;
        }
        .btn-primary {
           background-color:rgb(40, 69, 51);
           color: white;
        }
       .btn-primary:hover {
          background-color:#d4efdf;
          color: black;;
       }
       .reset-btn {
          background-color:#d4efdf;
          border-color: #228B22;
          color: black;
       }
      .reset-btn:hover {
          background-color:rgb(40, 69, 51);
          color: white;
       }
      .form-group {
          margin-bottom: 15px;
       }
     .modal-header button{
          background-color:white;
          border-radius:5px;
          border:none;
          color:rgb(184,184,184);
          font-size:25px;
      }
     .modal-header button:hover{
         color:black;
     }
    </style>
    
</head>

<body>
<!-- LOADING AREA START ===== -->
<div class="loading-area">
    <div class="loading-box"></div>
    <div class="loading-pic">
        <div class="cssload-spinner">
            <div class="cssload-ball cssload-ball-1"></div>
            <div class="cssload-ball cssload-ball-2"></div>
            <div class="cssload-ball cssload-ball-3"></div>
            <div class="cssload-ball cssload-ball-4"></div>
        </div>
    </div>
</div>
<!-- LOADING AREA  END ====== -->
	<div class="page-wraper">
        <input type="hidden" id="productCategory" value="Agricultural Product">
     
        <!-- HEADER START -->
        <header class="site-header header-style-2 mobile-sider-drawer-menu">
            <div class="header-style-2-content">
                <div class="top-bar site-bg-gray-light">
                    <div class="container">
    
                            <div class="d-flex justify-content-between">
                                <div class="wt-topbar-left d-flex flex-wrap align-content-start">
                                    <ul class="wt-topbar-left-info">
                                        <li>Welcome to VPS Pumps Corporation</li>
                                    </ul>
                                </div>
                                
                                <div class="wt-topbar-right d-flex flex-wrap align-content-center">
                                    <div class="wt-topbar-right-info">
                                        <ul class="social-icons">
                                            <li><a href="javascript:void(0);" class="fa fa-facebook"></a></li>
                                            <li><a href="javascript:void(0);" class="fa fa-instagram"></a></li>
                                            <li><a href="javascript:void(0);" class="fa fa-whatsapp"></a></li>
                                        </ul>
                                    </div> 
                                </div>
                            </div>
                           
    
                    </div>
                </div>
                
                 <div class="header-middle-wraper">     
                    <div class="container">
                        <div class="header-middle d-flex justify-content-between align-items-center">
                            <div class="logo-header">
                                <div class="logo-header-inner logo-header-one">
                                    <a href="index.html">
                                    <img src="images/logo.png" alt="">
                                    </a>
                                </div>
                            </div>
                            
                            <div class="header-info-wraper">
                                
                                <div class="header-info">
                                    <ul>
                                        <li>
                                            <div class="wt-icon-box-wraper  left">
                                                <div class="wt-icon-box-xs site-bg-primary radius">
                                                    <span class="icon-cell site-text-white"><i class="flaticon-location"></i></span>
                                                </div>
                                                <div class="icon-content">
                                                    <p>Ajanta Lodge back side,</p>
                                                    <h5 class="wt-tilte">Periyakulam Road, Theni.</h5>
                                                </div>
                                            </div>
                                        </li> 

                                        <li>
                                            <div class="wt-icon-box-wraper  left">
                                                <div class="wt-icon-box-xs site-bg-primary radius">
                                                    <span class="icon-cell site-text-white"><i class="flaticon-mail"></i></span>
                                                </div>
                                                <div class="icon-content">
                                                    <p>Send Us Email</p>
                                                    <h5 class="wt-tilte">vpspumpscorporation@gmail.com</h5>
                                                </div>
                                            </div>
                                        </li>                                
                                        
                                        <li>
                                            <div class="wt-icon-box-wraper left">
                                                <div class="wt-icon-box-xs site-bg-primary radius">
                                                    <span class="icon-cell site-text-white"><i class="flaticon-phone-call"></i></span>
                                                </div>
                                                <div class="icon-content">
                                                    <p>Get Quick Support</p>
                                                    <h5 class="wt-tilte">+91-9659876688</h5>
                                                </div>
                                            </div>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <div class="header-bottom"> 
                    <div class="sticky-header main-bar-wraper  navbar-expand-lg">
                    
                        <div class="main-bar header-bottom">
                            <div class="container-block clearfix">

                                <div class="navigation-bar">
                                    <!-- NAV Toggle Button -->
                                    <button id="mobile-side-drawer" data-target=".header-nav" data-toggle="collapse" type="button" class="navbar-toggler collapsed">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar icon-bar-first"></span>
                                        <span class="icon-bar icon-bar-two"></span>
                                        <span class="icon-bar icon-bar-three"></span>
                                    </button> 
            
                                    <!-- MAIN Vav -->
                                    <div class="nav-animation header-nav navbar-collapse collapse d-flex justify-content-between">
                                
                                        <ul class=" nav navbar-nav">
                                            <li class="has-child"><a href="javascript:;">Home</a>
                                            </li>
                                            <li class="has-child">
                                                <a href="javascript:;">About us</a>                          
                                            </li>
                                            <li class="has-child"><a href="javascript:;">Products</a>
                                                <ul class="sub-menu">
                                                    <li><a href="solar-products.php">Solar Products</a></li>
                                                    <li><a href="#">Agricultural Products</a></li>
                                                    <li><a href="domestic-products.php">Domestic Products</a></li>
                                                </ul>                                                                 
                                            </li>
                                            <li class="has-child"><a href="javascript:;">Services</a>                       
                                            </li>
                                            <li class="has-child"><a href="javascript:;">Contact us</a>                          
                                            </li>
                                        </ul>
            
                                    </div>
                                    
                                    <!-- Header Right Section-->
                                    <div class="extra-nav header-1-nav">
                                        <div class="extra-cell one">
                                            <div class="header-search">
                                                <a href="#search" class="header-search-icon"><i class="fa fa-search"></i></a>
                                            </div>    
                                        </div>
                                        <div class="extra-cell two">
                                            <a href="../VPS Pumps Corporation/login/login.php" class="wt-cart cart-btn" title="Your Cart">
                                                <span class="link-inner">
                                                    <span class="woo-cart-total"> </span>
                                                    <span class="woo-cart-count">
                                                        <i class="fa fa-sign-in" style="font-size:20px">&nbsp;Login</i>
                                                        
                                                    </span>
                                                </span>
                                            </a>
                                            
                                            <!-- <div class="cart-dropdown-item-wraper clearfix">
                                                <div class="nav-cart-content">
                                                    
                                                    <div class="nav-cart-items p-a15">
                                                        <div class="nav-cart-item clearfix">
                                                            <div class="nav-cart-item-image">
                                                                <a href="shop-detail.html"><img src="images/cart/pic-1.jpg" alt="p-1"></a>
                                                            </div>
                                                            <div class="nav-cart-item-desc">
                                                                <span class="nav-cart-item-price"><strong>2</strong> x $19.99</span>
                                                                <a href="shop-detail.html">Heavy helmet</a>
                                                                <a href="shop-detail.html" class="nav-cart-item-quantity radius-sm">x</a>
                                                            </div>
                                                        </div>
                                                        <div class="nav-cart-item clearfix">
                                                            <div class="nav-cart-item-image">
                                                                <a href="shop-detail.html"><img src="images/cart/pic-2.jpg" alt="p-2"></a>
                                                            </div>
                                                            <div class="nav-cart-item-desc">
                                                                <span class="nav-cart-item-price"><strong>1</strong> x $24.99</span>
                                                                <a href="shop-detail.html">Power drill</a>
                                                                <a href="shop-detail.html" class="nav-cart-item-quantity radius-sm">x</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="nav-cart-title p-tb10 p-lr15 d-flex">
                                                        <h4  class="m-a0">Subtotal:</h4>
                                                        <h5 class="m-a0">$67.97</h5>
                                                    </div>
                                                    <div class="nav-cart-action p-a15 d-flex justify-content-start">
                                                        <a href="shop-detail.html" class="site-button">View Cart</a>
                                                        <a href="shop-detail.html" class="site-button-secondry" >Checkout </a>
                                                    </div>
                                                </div>
                                            </div>                             -->
                                        </div>                                
                                    </div> 

                                </div>
                                
                            </div>   
                        </div>


                        <!-- SITE Search -->
                        <div id="search"> 
                            <span class="close"></span>
                            <form role="search" id="searchform"  method="get" class="radius-xl" onsubmit="return searchRedirect()">
                                <div class="input-group">
                                    <input class="form-control" id="searchQuery" type="search" placeholder="Type to search"/>
                                    <span class="input-group-append"><button type="submit" class="search-btn"><i class="fa fa-paper-plane"></i></button></span>
                                </div> 
                            </form>
                        </div> 
                
                    </div>
                </div>
            </div>                
            
        </header>
        <!-- HEADER END -->

      
        <!-- CONTENT START -->
        <div class="page-content">

            <!-- INNER PAGE BANNER -->
            <div class="wt-bnr-inr overlay-wraper bg-center" style="background-image:url(images/banner/agricultural-product.png);">
            	<div class="overlay-main site-bg-black opacity-07"></div>
                <div class="container">
                    <div class="wt-bnr-inr-entry">
                    	<div class="banner-title-outer">
                        	<div class="banner-title-name">
                        		<h2 class="wt-title">Agricultural Products</h2>
                            </div>
                        </div>
                        <!-- BREADCRUMB ROW -->                            
                        
                            <div>
                                <ul class="wt-breadcrumb breadcrumb-style-2">
                                    <li><a href="index.html">Shop</a></li>
                                    <li>Agricultural Products</li>
                                </ul>
                            </div>
                        
                        <!-- BREADCRUMB ROW END -->                        
                    </div>
                </div>
            </div>
            <!-- INNER PAGE BANNER END -->

            <!-- SHOP SECTION START -->
            <div class="section-full p-t120 p-b90 bg-white">
                <div class="container">
                      
                    <div class="product-filter-wrap d-flex justify-content-between align-items-center m-b30">
                            <div id="result-summary" class="woocommerce-result-count"></div>
                            <div class="search-container woocommerce-ordering select-box-border-style1-wrapper">
                              <input type="text" id="productSearch" class="search-box" placeholder="Search products...">
                            </div>
                    </div>

                    <div class="row d-flex justify-content-center">
                        
                        <!-- SIDE BAR START -->
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 rightSidebar  m-b30">
                        
                            <aside  class="side-bar">
        
                                   <!-- CATEGORY -->   
                                   <div class="widget widget_services p-a20">
                                    <div class="m-b30">
                                        <h4 class="widget-title">Categories</h4>
                                        
                                    </div>
                                    <ul>
                                        <li><a href="solar-products.php">Solar Products</a><span class="badge"><?php echo $solar_count; ?></span></li>
                                        <li><a href="#">Agricultural Products</a><span class="badge"><?php echo $agriculture_count; ?></span></li>
                                        <li><a href="domestic-products.php">Domestic Products</a><span class="badge"><?php echo $domestic_count; ?></span></li>
                                    </ul>
                                  </div> 

                                  <!--PRICE RANGE SLIDER-->                                  
                                    
                                   <!-- ARCHIVES -->   
                                                                            
                                   <!-- TAGS -->
                                   <div class="widget widget_archives p-a20">
                                       <div class="m-b30">
                                           <h4 class="widget-title">Download Archives</h4>
                                       </div>
                                       <ul id="pdfContainer"></ul> <!-- This will be dynamically populated -->
                                   </div>
                                    
                            </aside>
    
                        </div>
                        <!-- SIDE BAR END --> 

                        <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12 m-b30">                       
                        
                                 <div id="productContainer" class="row"></div>
                                 <div id="pagination-container"></div>
                        </div>
                        
                       
                    </div>                                
                </div>
            </div>   
            <!-- SHOP SECTION END -->

             <!-- Bootstrap Modal HTML -->
   <div class="modal custom-modal fade modal-padding" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3">
      <!-- Image at the top of the form -->
      <div class="text-center mb-4">
        <img src="path/to/your/image.jpg" alt="Enquiry Image" class="img-fluid" style="max-width: 100%; height: auto;">
      </div>

      <div class="modal-header header-border justify-content-between">
        <h3 class="modal-title" id="enquiryModalLabel">Enquiry Form</h3>
        <button type="button" class="btn-close position-static" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>

      <div class="modal-body">
        <form id="enquiryForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
          <div class="contact-input-set">
            <div class="row g-3"> <!-- g-3 for better spacing between columns -->
              <div class="col-lg-6 col-md-6 mb-3">
                <label for="enquiry_name" class="col-form-label">Name<span class="text-danger">*</span></label>
                <input type="text" id="enquiry_name" class="form-control-input" name="enquiry_name"  placeholder="Your Name" required>
              </div>
              <div class="col-lg-6 col-md-6 mb-3">
                <label for="number" class="col-form-label">Phone No<span class="text-danger">*</span></label>
                <input type="text" id="number" class="form-control-input" name="number"  placeholder="Number" required>
              </div>
              <div class="col-lg-6 col-md-6 mb-3">
                <label for="email" class="col-form-label">Email<span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control-input">
              </div>
              <div class="col-lg-6 col-md-6 mb-3">
                <label for="city" class="col-form-label">City<span class="text-danger">*</span></label>
                <input type="text" id="city" name="city" class="form-control-input" placeholder="City">
              </div>
              <div class="col-lg-6 col-md-6 mb-3">
                <label for="state" class="col-form-label">State<span class="text-danger">*</span></label>
                <input type="text" id="state" name="state" class="form-control-input" placeholder="State">
              </div>
              <div class="col-lg-6 col-md-6 mb-3">
                <label for="enquiry_category" class="col-form-label">Select Your Application<span class="text-danger">*</span></label>
                <select class="form-select form-control-input select" id="enquiry_category" name="enquiry_category">
                  <option>Select</option>
                  <option>Solar</option>
                  <option>Agricultural</option>
                  <option>Domestic</option>
                  <option>All</option>
                </select>
              </div>
              <div class="col-lg-12 col-md-12 mb-3">
                <label for="message" class="col-form-label">Message</label>
                <textarea id="message" class="form-control-input" name="message" rows="3" style="height:130px;"></textarea>
              </div>
              <div class="col-lg-12 text-end form-wizard-button">
                <button class="button reset-btn" type="reset">Reset</button>
                <button class="btn btn-primary wizard-next-btn" type="submit">Send Enquiry</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
          
        </div>
        <!-- CONTENT END -->




        <!-- FOOTER START -->
        <footer class="site-footer footer-light" style="display: block; height: 651.5px;">

            <!-- FOOTER BLOCKES START -->  
            <div class="footer-top">
                                                                             
                <div class="container">
                    <div class="row">

                        <div class="col-lg-3 col-md-6">
							
                            <div class="widget widget_about">
                                <div class="logo-footer clearfix">
                                    <a href="index.html"><img src="images/logo.png" alt=""></a>
                                </div>
                                <p>If you have any questions or concerns about our Privacy Policy, please contact us at vpspumpscorporation@gmail.com</p>
                                <div class="call-us" >
                                    <i class="flaticon-phone-call"></i><a href="tel:+919659876688" style="font-size: 26px;">+91-9659876688</a>
                                </div>

                            </div>                            
                            
                        </div>

                        <div class="col-lg-3 col-md-6">  
                            <div class="widget n-letter">
                               <h3 class="widget-title">Newsletter</h3>
                               <div class="n-letter-content">
                                    <p>Subscribe our newsletter to get our latest update &amp; news</p>
                                    <div class="nl-type">
                                        <form role="search" method="post">
                                            <div class="nl-form">
                                                <input name="news-letter" class="form-control" placeholder="Enter email address" type="text">
                                                <button type="submit" class="s-btn">Subscribe</button>
                                            </div>
                                        </form>
                                    </div>                                    
                               </div> 
                               <ul class="social-icons">
                                <li><a href="javascript:void(0);" class="fa fa-facebook"></a></li>
                                <li><a href="javascript:void(0);" class="fa fa-instagram"></a></li>
                                <li><a href="javascript:void(0);" class="fa fa-whatsapp"></a></li>
                            </ul>
                            </div>
                        </div>                                                
                    
                        <div class="col-lg-3 col-md-6">
                            <div class="widget">
                                <h3 class="widget-title">Contact Us</h3>
                                <p>Our support available to help you
                                    24 hours a day, seven days a week.</p>                            
                                <ul class="widget_address"> 
                                    <li><i class="flaticon-location"></i>
                                        Ajanta Lodge back side,
                                        Periyakulam Road, Theni.</li>
                                    <li><i class="flaticon-mail"></i>vpspumpscorporation@gmail.com</li>
                                </ul>  
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="widget widget_services">
                                <h3 class="widget-title">Our Links</h3>
                                <ul>
                                    <li><a href="index.html">Home</a></li>
                                    <li><a href="about.html">About</a></li>
                                    <li><a href="products.html">Products</a></li>
                                    <li><a href="services.html">Services</a></li>
                                    <li><a href="contact.html">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>



                    </div>

                </div>
            </div>
            <!-- FOOTER COPYRIGHT -->
                                
            <div class="footer-bottom">
                <div class="container">
                	<div class="footer-bottom-info">

                        <div class="footer-copy-right">
                            <span class="copyrights-text">© 2024 <span class="site-text-primary"><a href="https://www.nearlooks.com/">Nearlook Mart Pvt Ltd</a></span></span>
                        </div>

                        <div class="footer-copy-right">
                            <span class="copyrights-text">Designed & Developed by<span class="site-text-primary"><a href="https://janakrishnamoorthy.in/">Janakrishnamoorthy</a></span></span>
                        </div>
                        
                    </div>
                </div>   
            </div>   
    
        </footer>
        <!-- FOOTER END -->

        <!-- BUTTON TOP START -->
		<button class="scroltop"><span class="fa fa-angle-up  relative" id="btn-vibrate"></span></button>

 	</div>



<!-- JAVASCRIPT  FILES ========================================= --> 
<script  src="js/jquery-3.6.0.min.js"></script><!-- JQUERY.MIN JS -->
<script  src="js/popper.min.js"></script><!-- POPPER.MIN JS -->
<script  src="js/bootstrap.min.js"></script><!-- BOOTSTRAP.MIN JS -->
<script  src="js/magnific-popup.min.js"></script><!-- MAGNIFIC-POPUP JS -->
<script  src="js/waypoints.min.js"></script><!-- WAYPOINTS JS -->
<script  src="js/counterup.min.js"></script><!-- COUNTERUP JS -->
<script  src="js/waypoints-sticky.min.js"></script><!-- STICKY HEADER -->
<script  src="js/isotope.pkgd.min.js"></script><!-- MASONRY  -->
<script  src="js/owl.carousel.min.js"></script><!-- OWL  SLIDER  -->
<script  src="js/theia-sticky-sidebar.js"></script><!-- STICKY SIDEBAR  -->
<script  src="js/jquery.bootstrap-touchspin.js"></script><!-- FORM JS -->
<script  src="js/custom.js"></script><!-- CUSTOM FUCTIONS  -->
<script src="js/lc_lightbox.lite.js" ></script><!-- IMAGE POPUP -->
<script  src="js/bootstrap-slider.min.js"></script><!-- Form js -->
<script  src="js/jquery.bgscroll.js"></script><!-- BACKGROUND SCROLL -->
<script  src="js/switcher.js"></script><!-- SHORTCODE FUCTIONS  -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.min.js"></script>  
<script  src="js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<!-- STYLE SWITCHER  ======= --> 
<div class="styleswitcher">

    <div class="switcher-btn-bx">
        <a class="switch-btn">
            <span class="fa fa-cog fa-spin"></span>
        </a>
    </div>
    
    <div class="styleswitcher-inner">
    
        <h6 class="switcher-title">Color Skin</h6>
        <ul class="color-skins">
            <li><a class="theme-skin skin-1" href="shopa39b.html?theme=css/skin/skin-1"></a></li>
            <li><a class="theme-skin skin-2" href="shop61e7.html?theme=css/skin/skin-2"></a></li>
            <li><a class="theme-skin skin-3" href="shopcce5.html?theme=css/skin/skin-3"></a></li>
            <li><a class="theme-skin skin-4" href="shop13f7.html?theme=css/skin/skin-4"></a></li>
            <li><a class="theme-skin skin-5" href="shop19a6.html?theme=css/skin/skin-5"></a></li>
            <li><a class="theme-skin skin-6" href="shopfe5c.html?theme=css/skin/skin-6"></a></li>
            <li><a class="theme-skin skin-7" href="shopab47.html?theme=css/skin/skin-7"></a></li>
            <li><a class="theme-skin skin-8" href="shop5f8d.html?theme=css/skin/skin-8"></a></li>
            <li><a class="theme-skin skin-9" href="shop5663.html?theme=css/skin/skin-9"></a></li>
            <li><a class="theme-skin skin-10" href="shop28ac.html?theme=css/skin/skin-10"></a></li>
            <li><a class="theme-skin skin-11" href="shop26b8.html?theme=css/skin/skin-11"></a></li>
            <li><a class="theme-skin skin-12" href="shop7f4c.html?theme=css/skin/skin-12"></a></li>
        </ul>           
        
    </div>    
</div>
<!-- STYLE SWITCHER END ==== -->

</body>


<!-- Mirrored from thewebmax.org/consza/shop.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 22 Aug 2024 09:45:19 GMT -->
</html>
