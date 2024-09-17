<?php
    Class Employee{
        private $staffID;
        private $staffFName;
        private $staffLName;
        private $dept;
        private $position;
        private $country;
        private $email;
        private $reportingManager;
        private $role;

        public function __construct($staffID, $staffFName, $staffLName, $dept, $position, $country, $email, $reportingManager, $role){
            $this->staffID = $staffID;
            $this->staffFName = $staffFName;
            $this->staffLName = $staffLName;
            $this->dept = $dept;
            $this->position = $position;
            $this->country = $country;
            $this->email = $email;
            $this->reportingManager = $reportingManager;
            $this->role = $role;
        }

        public function getID(){
            return $this->staffID;
        }

        public function getStaffName(){
            return $this->staffFName. " " . $this->staffLName;
        }

        public function getDept(){
            return $this->dept;
        }

        public function getPosition(){
            return $this->position;
        }

        public function getCountry(){
            return $this->country;
        }

        public function getEmail(){
            return $this->email;
        }

        public function getManager(){
            return $this->reportingManager;
        }

        public function getRole(){
            return $this->role;
        }
    }

    
?>