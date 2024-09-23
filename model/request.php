<?php
    Class Request{
        private $staffID;
        private $reqID;
        private $date;
        private $arrangement;
        private $status;
        private $reason;

        public function __construct($staffID, $reqID, $date, $arrangement, $status, $reason){
            $this->staffID = $staffID;
            $this->reqID = $reqID;
            $this->date = $date;
            $this->arrangement = $arrangement;
            $this->status = $status;
            $this->reason = $reason;
        }

        public function getStaffID(){
            return $this->staffID;
        }

        public function getReqID(){
            return $this->reqID;
        }

        public function getDate(){
            return $this->date;
        }

        public function getArrangement(){
            return $this->arrangement;
        }

        public function getStatus(){
            return $this->status;
        }

        public function getReason(){
            return $this->reason;
        }
    }

    
?>