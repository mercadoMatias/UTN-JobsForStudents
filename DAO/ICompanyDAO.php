<?php
    namespace DAO;

    use Models\Company as Company;

    interface ICompanyDAO
    {
        function Add(Company $company);
        function GetAll();
        function DeleteById($companyId);
        function FindById($companyId);
        function ModifyById($addingCompany);
    }
?>