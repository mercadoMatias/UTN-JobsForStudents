<?php
    namespace Controllers;

    use DAO\appointmentDAO as appointmentDAO;
    use \Exception as Exception;
    use DAO\Connection as Connection;
    use DAO\JobOfferDAO;
    use Models\appointment as appointment;
    use Models\CV;

class appointmentController
    {
        private $appointmentDAO;

        public function __construct(){
            $this->appointmentDAO = new AppointmentDAO();
        }

        public function AddView($jobOfferId){
            require_once(VIEWS_PATH."add-appointment.php");
        }

        public function ViewDetails(){
            $currentStudent = $_SESSION['currentUser'];
            
            if($currentStudent->getAppointment()){
                $jobOffer = (new JobOfferDAO)->FindById($currentStudent->getAppointment()->getJobOfferId());
                require_once(VIEWS_PATH."appointment-viewDetail.php");
            }else   
                header("location:".FRONT_ROOT."Home/Index");
        }

        public function ListView(){
            
            $appointmentList = $this->appointmentDAO->getAll();

            require_once(VIEWS_PATH."appointment-list.php");
        }

        public function Add($studentId, $jobOfferId, $file, $referenceURL){
            $found = false;
            $currentStudent = $_SESSION['currentUser'];        
            $appointmentList = $this->appointmentDAO->GetAll();

            if($appointmentList)
                foreach($appointmentList as $eachappointment) 
                    if($eachappointment->getStudentId() == $studentId)
                        $found = true;

            if(!$found){
                $appointment = new Appointment();

                $appointment->setStudentId($studentId);
                $appointment->setJobOfferId($jobOfferId);
                $appointment->setCV($file);
                $appointment->setDateAppointment(date("c"));
                $appointment->setReferenceURL($referenceURL);  

                $this->appointmentDAO->Add($appointment);
                $appointmentList = $this->appointmentDAO->GetAll();

                if($appointmentList)
                    foreach($appointmentList as $eachAppointment)
                        if($eachAppointment->getStudentId() == $currentStudent->getStudentId())
                            $currentStudent->setAppointment($eachAppointment);
                //$this->Upload($file, $studentId, $jobOfferId);
            }else {
                ?>
                     <script>alert('The student is already on a job offer or the student does not exist!');</script>
                 <?php
            }
            
            (new HomeController)->Index();
        }

        public function Remove($removeId){  //<--------------------------------------- Podriamos crear una variable en student que se llame jobOfferActive para utilizarlo como booleanos y asi podes limitarlo a 1 solo jobOffer por estudiante
            $this->appointmentDAO->DeleteById($removeId);
            $this->ListView();
        }

        public function Upload($file, $studentId, $jobOfferId)
        {
            try
            {
                $fileName = $file["name"];
                $tempFileName = $file["tmp_name"];
                $type = $file["type"];
                
                $filePath = UPLOADS_PATH.basename($fileName);            

                $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                if (move_uploaded_file($tempFileName, $filePath))
                {
                    $cv = new CV();
                    $cv->setName($fileName);
                    $this->appointmentDAO->addCV($cv, $studentId, $jobOfferId);
                    $message = "CV successfully uploaded!";
                }
                else
                    $message = "There was an error adding the CV!";
            }
            catch(Exception $ex)
            {
                $message = $ex->getMessage();
            }
        }    
    }
?>