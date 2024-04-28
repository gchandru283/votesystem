<style>
  @media only screen and (min-width: 1200px) {
  .navbar-custom-menu {
    width: 65%;
  }

  .nav.navbar-nav {
    width: 100%;
  }
}


</style>

<header class="main-header" style="position:fixed; width:100%">
  <!-- Logo -->
  <a href="home.php" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>VS</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>Voting System </span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account: style can be found in dropdown.less -->
        <li><a id="countdown">Session Expires in 2 minutes 3 seconds</a></li>
        
        <li class="dropdown user user-menu pull-right" style="width : fit-content">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img
              src="<?php echo (!empty ($user['photo'])) ? '../images/' . $user['photo'] : '../images/profile.jpg'; ?>"
              class="user-image" alt="User Image">
            <span class="hidden-xs">
              <?php echo $user['firstname'] . ' ' . $user['lastname']; ?>
            </span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <img
                src="<?php echo (!empty ($user['photo'])) ? '../images/' . $user['photo'] : '../images/profile.jpg'; ?>"
                class="img-circle" alt="User Image">

              <p>
                <?php echo $user['firstname'] . ' ' . $user['lastname']; ?>
                <small>Member since <?php echo date('M. Y', strtotime($user['created_on'])); ?></small>
              </p>
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <a href="#profile" data-toggle="modal" class="btn btn-default btn-flat" id="admin_profile">Update</a>
              </div>
              <div class="pull-right">
                <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>

<script>
   // Function to update the countdown
function updateCountdown() {
    // Get the initial timestamp from sessionStorage
    var initialTimestamp = sessionStorage.getItem('initialTimestamp');

    // Set the initial timestamp if it's not already set or if it's expired
    if (!initialTimestamp || Date.now() - initialTimestamp > 5 * 60 * 1000) {
        initialTimestamp = Date.now(); // Set to current time
        sessionStorage.setItem('initialTimestamp', initialTimestamp);
    }

    // Calculate the remaining time
    var currentTime = Date.now();
    var elapsedTime = currentTime - initialTimestamp;
    var remainingTime = 5 * 60 * 1000 - elapsedTime;

    // Update the countdown element
    var countdownElement = document.getElementById('countdown');
    if (remainingTime > 0) {
        var minutes = Math.floor(remainingTime / 60000); // Convert milliseconds to minutes
        var seconds = Math.floor((remainingTime % 60000) / 1000); // Convert remaining milliseconds to seconds
        
        // Check screen width
        if (window.innerWidth >= 768) {
            // For laptop width or wider
            countdownElement.textContent = "This session will expire in " + minutes + " minutes " + seconds + " seconds";
        } else {
            // For mobile width
            countdownElement.textContent = "Session ends in " + minutes + " Mins " + seconds + " Secs";
        }
    } else {
        countdownElement.textContent = "Session expired";
        // Clear the sessionStorage
        sessionStorage.removeItem('initialTimestamp');
        // Redirect to logout page or perform logout action
        window.location.href = 'logout.php';
    }
}

// Call updateCountdown function when the page loads
updateCountdown();

// Update countdown every second
setInterval(updateCountdown, 1000);

// Add event listeners to buttons that may trigger user actions
document.querySelectorAll('button').forEach(function(button) {
    button.addEventListener('click', function () {
        // Reset the session timer when a button is clicked
        sessionStorage.setItem('initialTimestamp', Date.now());
    });
});

// Add event listener for page refresh
window.addEventListener('beforeunload', function() {
    // Reset the session timer when the page is refreshed
    sessionStorage.setItem('initialTimestamp', Date.now());
});

// Add event listener to all links on the page
document.querySelectorAll('a').forEach(function(link) {
    link.addEventListener('click', function () {
        // Reset the session timer when a link is clicked
        sessionStorage.setItem('initialTimestamp', Date.now());
    });
});

</script>

<?php include 'includes/profile_modal.php'; ?>