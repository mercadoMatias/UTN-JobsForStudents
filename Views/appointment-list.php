?php 
        include_once('header.php');
        include_once('nav-bar.php');
?>
<center>
<div class="wrapper row3">
<table id="AppointmentsTable">
        <tr id="tableIndex">
                <td style='width: 10px;'>ID</td>
                <td>Student</td>
                <td>Job Offer</td>
                <td>Curriculum Vitae</td>
                <td>Date Appointment</td>
                <td>Reference URL</td>
                <td><button class="btn btn-primary" onclick="window.location.href='<?php echo FRONT_ROOT ?>Appointment/AddView'">Add</button>
        </tr>
        <?php
        foreach($AppointmentList as $eachAppointment){
                echo "<tr>
                        <td style='max-width: 10px; text-align: center;'>".$eachAppointment->getStudentId()."</td>
                        <td style='max-width: 10px; text-align: center;'>".$eachAppointment->getJobOfferId()."</td>
                        <td style='max-width: 100px;'>".$eachAppointment->getCv()."</td>
                        <td style='max-width: 100px;'>".$eachAppointment->getDateAppointment()."</td>
                        <td style='max-width: 230px;'>".$eachAppointment->getReferenceURL()."</td>"; ?>
                        <td><button class="btn btn-warning" onclick="window.location.href='<?php echo FRONT_ROOT ?>Appointment/ProfileView?AppointmentId=<?php echo $eachAppointment->getStudentId()?>'">Profile</button>                     
                </tr>
        <?php } ?>
        </table>
</div>
</center>
<?php
        include_once('footer.php');
?>