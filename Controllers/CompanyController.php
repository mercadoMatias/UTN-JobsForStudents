<?php
    namespace Controllers;

    use DAO\CompanyDAO as CompanyDAO;
    use Models\Company as Company;
    use Models\Administrator as Administrator;
    use Models\Industry as Industry;

    class CompanyController{
        private $companyDAO;

        public function __construct(){
            $this->companyDAO = new CompanyDAO();
        }

        public function ShowAddView(){
            $industryList = Industry::GetAll();
            require_once(VIEWS_PATH."add-company.php");
        }

        public function ShowListView($searchedCompany = ""){
            $companyList = $this->companyDAO->GetAll();

            if(!$companyList) {
                $companyList = new Company();
            }

            require_once(VIEWS_PATH."company-list.php");
        }

        public function Add($name, $cuit, $description, $website, $street, $number_street, $aboutUs, $isActive, $industry){
            $company = new Company();

            $companyExist = false;
            
            $companyList = $this->companyDAO->GetAll();
            
            if($companyList){
                foreach($companyList as $eachCompany) {
                    if($eachCompany->getName() == $name || $eachCompany->getCuit() == $cuit){
                        $companyExist = true;
                    }
                }

                if($companyExist == false){
                    $company = $this->setCompany($name, $cuit, $description, $website, $street, $number_street, $aboutUs, $isActive, $industry);
                    
                    $this->companyDAO->Add($company);
    
                } else {
                    ?>
                        <script>alert('The company already exists!');</script>
                    <?php
                }

            } else {
                $company = $this->setCompany($name, $cuit, $description, $website, $street, $number_street, $aboutUs, $isActive, $industry);
                
                $this->companyDAO->Add($company);
            }

            $this->ShowAddView();
        }

        private function setCompany($name, $cuit, $description, $website, $street, $number_street, $aboutUs, $isActive, $industry) {
            $company = new Company();

            $company->setName($name);
            $company->setCuit($cuit);
            $company->setDescription($description);
            $company->setWebsite($website);
            $company->setStreet($street);
            $company->setNumber($number_street);
            $company->setAboutUs($aboutUs);
            $company->setActive($isActive);
            $company->setIndustry($industry);

            return $company;
        }

        public function Remove($removeId){
            $this->companyDAO->DeleteById($removeId);
            $this->ShowListView();
        }

        public function ModifyView($modifyId){
            $company = $this->companyDAO->FindById($modifyId);

            require_once(VIEWS_PATH."modify-company.php");
        }

        public function ModifyACompany($companyId, $name, $cuit, $description, $website, $street, $number, $aboutUs, $active, $industry){
            $isActive = $this->activeToBoolean($active);
            $this->companyDAO->ModifyById($companyId, $name, $cuit, $description, $website, $street, $number, $aboutUs, $isActive, $industry);
            
            $this->ShowListView();
        }

        private function setIdByLastId($companyList, $company){
            if(empty($companyList)){
                $company->setCompanyId(1); 
             } else {
                 $lastId = end($companyList)->getCompanyId();
                 $company->setCompanyId($lastId + 1);
             }
        }

        private function activeToBoolean($active){
            if($active == "true"){
                $active = true;
            } else {
                $active = false;
            }
            return $active;
        }

        private function showCompany($company) {
            ?>
            <tr>
              <td><?php echo $company->getName() ?></td>
              <td><?php echo $company->getCuit() ?></td>
              <td><?php echo $company->getDescription() ?></td>
              <td><a style="text-decoration: none; color:black;" href="<?php echo $company->getWebsite() ?>"><?php echo $company->getWebsite() ?></a></td>
              <td><?php echo $company->getStreet() ?></td>
              <td><?php echo $company->getNumber() ?></td>
              <td><?php echo $company->getAboutUs() ?></td>
              <td><?php echo $company->getIndustry() ?></td>
            <?php
                if($this->isAdmin()) {
                ?>
                    <td><button class="btn btn-danger" onclick="window.location.href='<?php echo FRONT_ROOT ?>Company/Remove?removeId=<?php echo $company->getCompanyId() ?>'">Remove</button></td>
                    <td><button class="btn btn-danger" onclick="window.location.href='<?php echo FRONT_ROOT ?>Company/ModifyView?modifyId=<?php echo $company->getCompanyId() ?>'">Modify</button></td>
                <?php
                }
                ?>
              </tr>
            <?php
        } 

        public function companyFilter($searchedCompany, $companyList) {
            $i = 0;
            if($searchedCompany != ""){
                foreach($companyList as $company){
                    if(strpos($company->getName(), $searchedCompany) !== false && $company->getActive() == 0){
                        $i++;
                        $this->showCompany($company);
                    }
                }
            }else{
                foreach($companyList as $company){
                    if($company->getActive() == 0){
                        $i++;
                        $this->showCompany($company);
                    }     
                }   
            }
            echo "<br><b>There are ".$i." Result/s!</b>";
        }

        public function isAdmin() {
            $isAdmin = false;

            if($_SESSION['currentUser'] instanceof Administrator) {
                $isAdmin = true;
            }

            return $isAdmin;
        }
    }
?>