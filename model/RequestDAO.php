<?php
    Class RequestDAO{
        public function retrieveRequestInfo($userID){
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();

            $sql = 'SELECT * FROM employee_arrangement WHERE Staff_ID = :userID';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null;
            $pdo = null;

            return $result;
        }

        private function generateReqID() {
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();
        
            $sql = 'SELECT MAX(Request_ID) AS max_request_id FROM employee_arrangement';
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            // Use fetch instead of fetchAll to get a single row
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // Handle the case where the table is empty (NULL result)
            $newRequestID = ($result['max_request_id'] !== null) ? $result['max_request_id'] + 1 : 1;
        
            $stmt = null;
            $pdo = null;
        
            return $newRequestID;
        }
        


        // Submit a new work-from-home request
        public function submitWFHRequest($userID, $wfh_date, $wfh_time, $reason) {
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();
            
            $requestID = $this->generateReqID();
            $time_slot = $this->getTimeSlot($wfh_time); // Get the time range (AM, PM, or full day)
            
            // Insert the WFH request into the employee_arrangement table
            $sql = "INSERT INTO employee_arrangement (Staff_ID, Request_ID, Arrangement_Date, Working_Arrangement, Request_Status, Reason)
                    VALUES (:userID, :requestID, :wfh_date, :time_slot, 'Pending', :reason)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':requestID', $requestID, PDO::PARAM_INT);
            $stmt->bindParam(':wfh_date', $wfh_date);
            $stmt->bindParam(':time_slot', $time_slot);
            $stmt->bindParam(':reason', $reason);

            $result = $stmt->execute();

            $stmt = null;
            $pdo = null;

            return $result;
        }

        // Submit a new leave request
        public function submitLeaveRequest($userID, $leave_date, $leave_time, $reason) {
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();
            
            $requestID = $this->generateReqID();
            $time_slot = $this->getTimeSlot($leave_time); // Get the time range (AM, PM, or full day)
            
            // Insert the leave request into the employee_arrangement table
            $sql = "INSERT INTO employee_arrangement (Staff_ID, Request_ID, Arrangement_Date, Working_Arrangement, Request_Status, Reason)
                    VALUES (:userID, :requestID, :leave_date, :time_slot, 'Pending', :reason)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':requestID', $requestID, PDO::PARAM_INT);
            $stmt->bindParam(':leave_date', $leave_date);
            $stmt->bindParam(':time_slot', $time_slot);
            $stmt->bindParam(':reason', $reason);

            $result = $stmt->execute();

            $stmt = null;
            $pdo = null;

            return $result;
        }

        // Helper function to get time slot based on AM/PM/full day selection
        private function getTimeSlot($time_selection) {
            switch($time_selection) {
                case 'AM':
                    return 'AM (9:00 AM - 1:00 PM)';
                case 'PM':
                    return 'PM (1:00 PM - 6:00 PM)';
                case 'full_day':
                default:
                    return 'Full Day (9:00 AM - 6:00 PM)';
            }
        }
    }

?>
