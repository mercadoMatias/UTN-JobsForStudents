<?php
  if(!isset($_SESSION['currentUser'])){
    header('location: '.FRONT_ROOT.'Student/LogInView');
  }
?>
<div class="wrapper row2">
  <nav id="navbar">
    <!-- <ul> -->
    <a id="link" href="<?php echo FRONT_ROOT ?>Student/LogInView"><li><i class="fa fa-share"></i></li></a>
      <a id="link" href="<?php echo FRONT_ROOT ?>Company/ShowListView"><li>Companies</li></a>
      <a id="link" href=""><li>Job Offers</li></a>
      <a id="link" href="<?php echo FRONT_ROOT ?>Student/List"><li>Students List</li></a>
      <a id="link" href=""><i id="notificationBell" id="icon" class="fa fa-bell"></i></a>
    </ul>
  </nav>
</div>
<div class="container">