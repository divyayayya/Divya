<?php
    Class RequestDAO{

        private $connManager;

        public function __construct($connManager = null) {
            // Use the provided connection manager or create a new one
            $this->connManager = $connManager ?? new ConnectionManager();
        }

        public function retrieveRequestInfo($userID){
            $pdo = $this->connManager->getConnection();

            $sql = 'SELECT * FROM employee_arrangement WHERE Staff_ID = :userID';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null;
            $pdo = null;

            return $result;
        }

        public function generateReqID() {
            try {
                $pdo = $this->connManager->getConnection();
        
                // Query to get the max Request_ID
                $sql = "SELECT MAX(Request_ID) AS maxID FROM employee_arrangement";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
                // Increment the max Request_ID by 1 to get the new ID
                $newRequestID = $row['maxID'] + 1;
        
                return $newRequestID;
        
            } catch (PDOException $e) {
                echo "SQL error: " . $e->getMessage();
                return false;
            }
        }
        
        public function submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason) {
            try {
                $pdo = $this->connManager->getConnection();
        
                // Prepare the SQL statement to insert the new leave request
                $sql = "INSERT INTO employee_arrangement (Staff_ID, Department, Request_ID, Arrangement_Date, Working_Arrangement, Arrangement_Time, Reason, Request_Status, Working_Location, Rejection_Reason)
                        VALUES (:userID, :dept, :requestID, :leave_date, 'WFH', :leave_time, :reason, 'Pending', 'Home', NULL)";
        
                $stmt = $pdo->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
                $stmt->bindParam(':dept', $dept, PDO::PARAM_STR);
                $stmt->bindParam(':requestID', $requestID, PDO::PARAM_INT);
                $stmt->bindParam(':leave_date', $leave_date);
                $stmt->bindParam(':leave_time', $leave_time);
                $stmt->bindParam(':reason', $reason);
        
                // Execute the statement
                $result = $stmt->execute();
        
                // Close the statement and connection
                $stmt = null;
                $pdo = null;
        
                // Return the result of the execution
                return $result;
            } catch (PDOException $e) {
                echo "SQL error: " . $e->getMessage();
                return false;
            }
        }
        
        public function submitRecurringWFHRequest($userID, $dept, $startDate, $endDate, $recurring_days, $time_slot, $reason) {
            try {
                $pdo = $this->connManager->getConnection();        
                // Convert start and end dates to timestamps
                $current_date = strtotime($startDate);
                $end_date = strtotime($endDate);
        
                // Loop through each day in the range
                while ($current_date <= $end_date) {
                    $day_of_week = date('l', $current_date); // Get day of the week (e.g., Monday)
        
                    // If this day is in the selected recurring days, insert the request
                    if (in_array($day_of_week, $recurring_days)) {
                        $sql = "INSERT INTO employee_arrangement 
                                (Staff_ID, Department, Arrangement_Date, Working_Arrangement, Arrangement_Time, Reason, Request_Status, Working_Location, Rejection_Reason)
                                VALUES (:userID, :dept, :arrangement_date, 'WFH', :time_slot, :reason, 'Pending', 'Home', NULL)";
        
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                        $stmt->bindParam(':dept', $dept);
                        $arrangement_date = date('Y-m-d', $current_date);
                        $stmt->bindParam(':arrangement_date', $arrangement_date);
                        $stmt->bindParam(':time_slot', $time_slot);
                        $stmt->bindParam(':reason', $reason);
        
                        $stmt->execute();
                    }
        
                    // Move to the next day
                    $current_date = strtotime('+1 day', $current_date);
                }
        
                $stmt = null;
                $pdo = null;
        
                return true;
        
            } catch (PDOException $e) {
                echo "SQL error: " . $e->getMessage();
                return false;
            }
        }        
                 
        
        // Submit a new leave request
    
        public function submitLeaveRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason) {
            try {
                $pdo = $this->connManager->getConnection();
        
                // Prepare the SQL statement to insert the new leave request
                $sql = "INSERT INTO employee_arrangement (Staff_ID, Department, Request_ID, Arrangement_Date, Working_Arrangement, Arrangement_Time, Reason, Request_Status, Working_Location, Rejection_Reason)
                        VALUES (:userID, :dept, :requestID, :leave_date, 'On Leave', :leave_time, :reason, 'Pending', 'Not Working', NULL)";
        
                $stmt = $pdo->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
                $stmt->bindParam(':dept', $dept, PDO::PARAM_STR);
                $stmt->bindParam(':requestID', $requestID, PDO::PARAM_INT);
                $stmt->bindParam(':leave_date', $leave_date);
                $stmt->bindParam(':leave_time', $leave_time);
                $stmt->bindParam(':reason', $reason);
        
                // Execute the statement
                $result = $stmt->execute();
        
                // Close the statement and connection
                $stmt = null;
                $pdo = null;
        
                // Return the result of the execution
                return $result;
            } catch (PDOException $e) {
                echo "SQL error: " . $e->getMessage();
                return false;
            }
        }
    
        public function deleteRequest($requestId, $staffId, $arrangementDate) {
            $pdo = $this->connManager->getConnection();
        
            // Prepare the SQL statement
            $sql = "DELETE FROM employee_arrangement 
                    WHERE Request_ID = :requestId 
                    AND Staff_ID = :staffId 
                    AND Arrangement_Date = :arrangementDate";
        
            $stmt = $pdo->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);
            $stmt->bindParam(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->bindParam(':arrangementDate', $arrangementDate);
        
            // Execute the statement and return the result
            return $stmt->execute();
        }

        public function retrievePendingArrangements($staffID){
            $pdo = $this->connManager->getConnection();
            
            $sql = "SELECT * FROM employee_arrangement WHERE Staff_ID = :staffID AND Request_Status = 'Pending' AND Arrangement_Date > CURRENT_DATE ORDER BY Arrangement_Date";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':staffID', $staffID, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $results = $stmt->fetchAll(); // Fetch all employees in the department
            
            $stmt = null;
            $pdo = null;

            return $results;
        }
        
        public function approveRequest($requestID){
            $pdo = $this->connManager->getConnection();

            $sql = "UPDATE employee_arrangement SET Request_Status = 'Approved' WHERE Request_ID = :requestID AND Request_Status = 'Pending'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':requestID', $requestID, PDO::PARAM_INT);
            $stmt->execute();
            $affectedRows = $stmt->rowCount();

            $stmt = null;
            $pdo = null;

            if ($affectedRows == 1){
                return true;
            }else{
                return false;
            }
        }

        public function rejectRequest($requestID, $reason){
            $pdo = $this->connManager->getConnection();

            $sql = "UPDATE employee_arrangement SET Request_Status = 'Rejected', Rejection_Reason = :reason WHERE Request_ID = :requestID AND Request_Status = 'Pending'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':requestID', $requestID, PDO::PARAM_INT);
            $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
            $stmt->execute();
            $affectedRows = $stmt->rowCount();

            $stmt = null;
            $pdo = null;

            if ($affectedRows == 1){
                return true;
            }else{
                return false;
            }
        }

        public function retrieveByReqID($requestID){
            $pdo = $this->connManager->getConnection();

            $sql = 'SELECT * FROM employee_arrangement WHERE Request_ID = :requestID';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':requestID', $requestID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = null;
            $pdo = null;

            return $result;
        }
        
        public function rejectExpiredRequests(){
            $pdo = $this->connManager->getConnection();

            $sql = "SELECT Request_ID FROM employee_arrangement WHERE Request_Status = 'Pending' AND Arrangement_Date <= CURRENT_DATE";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $expired = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null;

            $sql = "UPDATE employee_arrangement SET Request_Status = 'Rejected', Rejection_Reason = 'Not Approved past deadline' WHERE Request_ID = :reqID";
            $stmt = $pdo->prepare($sql);
            foreach ($expired as $req){
                $reqID = $req['Request_ID'];
                
                $stmt->bindValue(':reqID', $reqID, PDO::PARAM_INT);
                $stmt->execute();
            }

            $stmt = null;
            $pdo = null;
        }

        public function retrieveApprovedRequestsByUserID($userID){
            $pdo = $this->connManager->getConnection();

            $sql = "SELECT * FROM employee_arrangement WHERE Staff_ID = :userID AND Request_Status = 'Approved' AND Arrangement_Date > Current_Date ORDER BY Arrangement_Date";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null;
            $pdo = null;

            return $result;
        }

        public function withdrawRequest($requestId, $staffId, $arrangementDate) {
            $pdo = $this->connManager->getConnection();
        
            // Prepare the SQL statement
            $sql = "UPDATE employee_arrangement SET Request_Status = 'Withdrawn'WHERE Request_ID = :requestId AND Staff_ID = :staffId AND Arrangement_Date = :arrangementDate";
        
            $stmt = $pdo->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);
            $stmt->bindParam(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->bindParam(':arrangementDate', $arrangementDate);
        
            // Execute the statement and return the result
            return $stmt->execute();
        }

    }
?>