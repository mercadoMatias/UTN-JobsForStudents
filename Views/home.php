<?php 
  include_once('header.php');
?>
<div class="container">

<center>
<div>
  <form action="<?php echo FRONT_ROOT ?>Student/List" method="POST">
    <table id="loginForm">
      <tr>
        <th colspan="2"><center><h4>Log In</h4></center></th>
      </tr>
      <tr>
        <td><label for="userName">Username</label></td>
        <td><input class="inputText" type="text" name="userName" required></td>
      </tr>
      <tr>
        <td><label for="userPassword">Password</label></td>
        <td><input class="inputText" type="password" name="userPassword" required></td>
      </tr>
      <tr>
      <td colspan="2"><center><button id="buttonSubmit" type="submit" class="btn btn-primary">Log In</button></center></td>
      </tr>
    </table>
    
  </form>
</div>
</center>
<?php
  include_once('footer.php');
?>