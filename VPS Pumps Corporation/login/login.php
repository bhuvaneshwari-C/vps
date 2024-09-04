<?php
session_start();
include '../database_conn.php';

//Handle form submission
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

//prepare the sql statement
$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $stored_password);
    $stmt->fetch();

    // Compare plain text passwords
    if ($password === $stored_password) {
        $_SESSION['username'] = $username;
        echo "<script>
        alert('Login Successful! Welcome, " . htmlspecialchars($username) . "');
        window.location.href='../dashboard/dashboard.php'; 
    </script>";
    exit();
    } else {
        echo "<script>
        alert('Invalid password.');
        window.location.href='" . $_SERVER['PHP_SELF'] . "';
    </script>";
    exit();
    }
} else {
    echo "<script>
    alert('No user found with that username.');
    window.location.href='" . $_SERVER['PHP_SELF'] . "';
</script>";
exit();
}
$stmt->close();
$conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="login.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
        <div class="card card0 border-0">
            <div class="row d-flex">
                <div class="col-lg-6">
                    <div class="card1 pb-5">
                        <div class="row mr-4">
                            <p class="welcome-text">Welcome to VPS Pumps Corporation, your trusted partner in pumping solutions.</p>
                        </div>
                        <div class="row px-3 justify-content-center mb-5 border-line">
                            <img src="../images/background-1.png" class="image" width=500>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card2 card border-0 px-3 py-5">
                        <div class="row px-3 justify-content-center">
                            <h3 class="mr-4 mt-2 welcome-back">Hi Welcome Back!</h3>
                        </div>
                        <div class="text-align:center mb-5"> 
                            <img src="../images/favicon.png" class="logo">
                        </div>
                       
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="row px-3 justify-content-center">
                            <h4 class="mb-5 mr-4 login">Admin Login</h4>
                        </div>
                        <div class="row px-3">
                            <!-- <label class="mb-1"><h6 class="mb-0 text-sm">Email Address</h6></label> -->
                            <i class="fa-solid fa-user"></i>
                            <input class="mb-4 p-3" type="text" id="inputEmail" name="username" placeholder="Email address">
                        </div>
                        <div class="row px-3">
                            <!-- <label class="mb-1"><h6 class="mb-0 text-sm">Password</h6></label> -->
                            <i class="fa fa-eye-slash" id="togglePassword"></i>
                            <input type="password" class="p-3" id="inputPassword" name="password" placeholder="Password">
                        </div>
                        <div class="row mt-4 px-3">
                        <span class="follow-us-text">follow us</span>
               
                <a href="#" class="social-icon">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-icon">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="#" class="social-icon">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </div>
                        <div class="row mt-5 px-3">
                            <button type="submit" class="btn btn-blue text-center">Login Now</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#inputPassword');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>



