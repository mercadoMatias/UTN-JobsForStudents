<?php
    namespace Controllers;

    use DAO\AppointmentDAO as AppointmentDAO;
    use \Exception as Exception;
    use DAO\Connection as Connection;
    use DAO\JobOfferDAO;
    use Helpers\SessionHelper as SessionHelper;
    use Models\Appointment as Appointment;
    use Models\CV;

class AppointmentController
    {
        private $appointmentDAO;

        public function __construct(){
            $this->appointmentDAO = new AppointmentDAO();
        }

        public function AddView($jobOfferId){
            $currentUser = (new SessionHelper())->getCurrentUser();
            $jobOffer = (new JobOfferDAO)->FindById($jobOfferId);
            require_once(VIEWS_PATH."add-appointment.php");
        }

        public function ViewDetails($jobOfferId){

            $currentStudent = (new SessionHelper())->getCurrentUser();

            if($currentStudent->getAppointment()){
                $appointment = $this->appointmentDAO->FindById($currentStudent->getUserId(), $jobOfferId);
                $jobOffer = (new JobOfferDAO)->FindById($jobOfferId);

                require_once(VIEWS_PATH."appointment-viewDetail.php");
            }else 
                header("location:".FRONT_ROOT."Home/Index");
        }

        public function ListView(){
            $isAdmin = (new SessionHelper())->isAdmin();
            $appointmentList = $this->appointmentDAO->getAll();

            //require_once(VIEWS_PATH."appointment-list.php");
        }

        public function Add($studentId, $jobOfferId, $file, $referenceURL, $comments){
            $currentStudent = (new SessionHelper)->getCurrentUser();        
            $appointmentList = $this->appointmentDAO->GetAll();

            $found = false;

            foreach($appointmentList as $eachAppointment){
                if($eachAppointment->getStudentId() == $studentId &&
                   $eachAppointment->getJobOfferId() == $jobOfferId)
                    $found = true;
            }

            if(!$found){
                $appointment = new Appointment();

                $appointment->setStudentId($studentId);
                $appointment->setJobOfferId($jobOfferId);
                $appointment->setCV($file);
                $appointment->setDateAppointment(date("c"));
    
                if(str_contains($referenceURL, "https://") !== true){
                    $referenceURL = "https://".$referenceURL;
                }
    
                $appointment->setReferenceURL($referenceURL);  
                $appointment->setComments($comments);  
                $appointment->setActive(true);  
    
                $this->appointmentDAO->Add($appointment);
                $appointmentList = $this->appointmentDAO->GetAll();

                $currentStudent->setAppointment($appointment);
                //$this->Upload($file, $studentId, $jobOfferId);
            }else
                ?> <script>alert('You´re already registered for this job offer!')</script> <?php           

            (new HomeController)->Index();
        }

        public function HistoryView(){
            $studentId = (new SessionHelper)->getCurrentUser()->getUserId();
            $isAdmin = (new SessionHelper())->isAdmin();
            $appointmentList = $this->appointmentDAO->HistoryById($studentId);
            
            require_once(VIEWS_PATH."appointment-list.php");
        }

        public function Remove($studentId, $jobOfferId){
            $this->appointmentDAO->CancelApplyById($studentId, $jobOfferId);
            (new HomeController)->Index();
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