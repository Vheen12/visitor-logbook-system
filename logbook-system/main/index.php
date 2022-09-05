<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: auth/login.php");
  exit;
}

require_once "auth/config.php";

// Get number of Registered Visitor
$sql = "SELECT * FROM visitor_information";
if ($result = mysqli_query($con, $sql)) {
  if (mysqli_num_rows($result) > 0) {
    $visitor = mysqli_num_rows($result);
  }
}

// Get number of Visitor Log
$sql2 = "SELECT * FROM visiting_details";
if ($result = mysqli_query($con, $sql2)) {
  if (mysqli_num_rows($result) > 0) {
    $transactions = mysqli_num_rows($result);
  }
  else {
    $transactions = 0;
  }
}
else {
  header("location: error.php");
}

// Get number of Visitor Log Today
$sql_sortDate = "SELECT * FROM visiting_details WHERE dateIn = CURRENT_DATE()";
if($result = mysqli_query($con, $sql_sortDate)) {
  if(mysqli_num_rows($result) > 0) {
    $logToday = mysqli_num_rows($result);
  }
  else {
    $logToday = 0;
  }
}

// Get number of Guest Visitor
$sql_guest = "SELECT * FROM visiting_details WHERE visitor_type = 0";
if($result = mysqli_query($con, $sql_guest)) {
  if(mysqli_num_rows($result) > 0) {
    $guest = mysqli_num_rows($result);
  }else{
    $guest = 0;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../css/flag-icon.min.css">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="../css/style.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="../assets/images/lgu-quezon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- Navigations -->
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="index.php"><img src="https://upload.wikimedia.org/wikipedia/commons/d/db/Quezon_Isabela.svg" alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../assets/images/lgu-quezon.svg" alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>

    <!-- SideBar Code -->
    <div class="container-fluid page-body-wrapper">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-category">Main</li>
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/profiles.php">
              <span class="icon-bg"><i class="mdi mdi-account-multiple menu-icon"></i></span>
              <span class="menu-title">Profiles</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/admins.php">
              <span class="icon-bg"><i class="mdi mdi-account-circle menu-icon"></i></span>
              <span class="menu-title">User Accounts</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/transaction.php">
              <span class="icon-bg"><i class="mdi mdi-file-document-box menu-icon"></i></span>
              <span class="menu-title">Visitor Transactions</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/department.php">
              <span class="icon-bg"><i class="mdi mdi-home-modern menu-icon"></i></span>
              <span class="menu-title">Departments</span>
            </a>
          </li>
          <li class="nav-item sidebar-user-actions">
            <div class="user-details">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <div class="d-flex align-items-center">
                    <div class="sidebar-profile-text">
                      <p class="mb-1"><b class=" font-weight-bold text-success">Current User</b><b class="ms-3"><?php echo htmlspecialchars($_SESSION["adminUsername"]); ?></b></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li class="nav-item sidebar-user-actions">
            <div class="sidebar-user-menu">
              <a href="auth/reset-password.php" class="nav-link"><i class="mdi mdi-key-change menu-icon"></i>
                <span class="menu-title">Change Password</span></a>
            </div>
          </li>
          <li class="nav-item sidebar-user-actions">
            <div class="sidebar-user-menu">
              <a href="auth/logout.php" class="nav-link"><i class="mdi mdi-logout menu-icon"></i>
                <span class="menu-title">Log Out</span></a>
            </div>
          </li>
        </ul>
      </nav>

      <!-- Main Panel -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="d-xl-flex justify-content-between align-items-start">
            <h2 class="text-dark font-weight-bold mb-2"> Overview dashboard </h2>
          </div>
          <div class="row">
            <div class="col-md-12">
              <!-- div for Tab lists -->
              <div class="d-sm-flex justify-content-between align-items-center transaparent-tab-border">
                <ul class="nav nav-tabs tab-transparent" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-selected="false">Users</a>
                  </li>
                </ul>
                <div class="d-md-block d-none">
                  <a href="#" class="text-light p-1"><i class="mdi mdi-view-dashboard"></i></a>
                  <a href="#" class="text-light p-1"><i class="mdi mdi-dots-vertical"></i></a>
                </div>
              </div>
              <!-- div for Cards -->
              <div class="tab-content tab-transparent-content">
                <!-- Users Tab -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                  <!-- Row 1 -->
                  <div class="row">
                  <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body text-center">
                          <h6 class="mb-4 text-success font-weight-normal">Registered Visitor</h6>
                          <h1 class="text-dark font-weight-bold"> <?php echo $visitor; ?> </h1>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body text-center">
                          <h5 class="mb-4 text-secondary font-weight-normal">Guest Visitor</h5>
                          <h1 class="text-dark font-weight-bold"> <?php echo $guest; ?> </h1>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                      <div class="card" >
                        <div class="card-body text-center">
                          <h5 class="mb-4 text-primary font-weight-normal">Total Visitors</h5>
                          <h1 class="text-dark font-weight-bold"> <?php echo $transactions; ?> </h1>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-3  col-lg-6 col-sm-6 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body text-center">
                          <h5 class="mb-4 text-info font-weight-normal">Total Visit Today</h5>
                          <h1 class="text-dark font-weight-bold"> <?php echo $logToday; ?> </h1>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="footer-inner-wraper">
            <div class="d-sm-flex justify-content-center justify-content-sm-between py-2">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © www.visitalog.com 2022</span>
            </div>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../js/jquery.cookie.js" type="text/javascript"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../js/off-canvas.js"></script>
  <script src="../js/hoverable-collapse.js"></script>
  <script src="../js/misc.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page -->
  <!-- End custom js for this page -->
</body>
</html>
