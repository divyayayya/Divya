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

        private Function generateReqID(){
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();

            $sql = 'SELECT MAX(Request_ID) AS max_request_id FROM employee_arrangement';
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $newRequestID = $result['max_request_id']+1;
            
            $stmt = null;
            $pdo = null;

            return $newRequestID;
        }


        // Submit a new work-from-home request
        public function submitWFHRequest($userID, $wfh_date, $reason) {
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();
            
            $requestID = $this->generateReqID();
            // Insert the WFH request into the employee_arrangement table
            $sql = "INSERT INTO employee_arrangement (Staff_ID, Request_ID, Arrangement_Date, Working_Arrangement, Request_Status, Reason)
                    VALUES (:userID, :requestID, :wfh_date, 'WFH', 'Pending', :reason)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':requestID', $requestID, PDO::PARAM_INT);
            $stmt->bindParam(':wfh_date', $wfh_date);
            $stmt->bindParam(':reason', $reason);
        
            $result = $stmt->execute();
        
            $stmt = null;
            $pdo = null;
        
            return $result;
        }
        

            public function submitLeaveRequest($userID, $leave_date, $reason) {
                $conn = new ConnectionManager();
                $pdo = $conn->getConnection();
                
                $requestID = $this->generateReqID();
                // Insert the WFH request into the employee_arrangement table
                $sql = "INSERT INTO employee_arrangement (Staff_ID, Request_ID, Arrangement_Date, Working_Arrangement, Request_Status, Reason)
                        VALUES (:userID, :leave_date, 'Leave', 'Pending', :reason)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->bindParam(':leave_date', $leave_date);
                $stmt->bindParam(':reason', $reason);
    
                // Execute the insert and return the result (true if success, false if failure)
                $result = $stmt->execute();
    
                $stmt = null;
                $pdo = null;
    
                return $result;
        }
    

    }

?>
