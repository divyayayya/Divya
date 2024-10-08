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

        public function submitWFHRequest($userID, $wfh_date, $wfh_time, $reason) {
            try {
                $conn = new ConnectionManager();
                $pdo = $conn->getConnection();
        
                $requestID = $this->generateReqID();
                $time_slot = $this->getTimeSlot($wfh_time); // Get the time range (AM, PM, or full day)
        
                $sql = "INSERT INTO employee_arrangement (Staff_ID, Request_ID, Arrangement_Date, Working_Arrangement, Request_Status, Reason, Working_Location)
                        VALUES (:userID, :requestID, :wfh_date, :time_slot, 'Pending', :reason, 'Home')";
        
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
        
            } catch (PDOException $e) {
                echo "SQL error: " . $e->getMessage();
                return false;
            }
        }
        
        
        public function submitRecurringWFHRequest($userID, $start_date, $end_date, $recurring_days, $wfh_time, $reason) {
            try {
                $conn = new ConnectionManager();
                $pdo = $conn->getConnection();
        
                $time_slot = $this->getTimeSlot($wfh_time); // Get the time range (AM, PM, or full day)
        
                // Convert start and end dates to timestamps
                $current_date = strtotime($start_date);
                $end_date = strtotime($end_date);
        
                // Loop through each day in the range
                while ($current_date <= $end_date) {
                    $day_of_week = date('l', $current_date); // Get day of the week (e.g., Monday)
        
                    // If this day is in the selected recurring days, insert the request
                    if (in_array($day_of_week, $recurring_days)) {
                        // Generate a new Request_ID for each request
                        $requestID = $this->generateReqID();
        
                        $sql = "INSERT INTO employee_arrangement (Staff_ID, Request_ID, Arrangement_Date, Working_Arrangement, Request_Status, Reason, Working_Location)
                                VALUES (:userID, :requestID, :arrangement_date, :time_slot, 'Pending', :reason, 'Home')";
        
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                        $stmt->bindParam(':requestID', $requestID, PDO::PARAM_INT);
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
    
            // Function to submit a new leave request
            public function submitLeaveRequest($userID, $leave_date, $time, $reason, $status) {
                $sql = "INSERT INTO leave_requests (Staff_ID, Leave_Date, Time, Reason, Status)
                        VALUES (:userID, :leave_date, :time, :reason, :status)";
                
                $conn = new ConnectionManager();
                $pdo = $conn->getConnection();
                
                $stmt = $pdo->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':userID', $userID);
                $stmt->bindParam(':leave_date', $leave_date);
                $stmt->bindParam(':time', $time);
                $stmt->bindParam(':reason', $reason);
                $stmt->bindParam(':status', $status);
                
                // Try to execute and check for success
                try {
                    if ($stmt->execute()) {
                        return true;
                    } else {
                        // Output error information
                        $errorInfo = $stmt->errorInfo();
                        echo "SQLSTATE error code: " . $errorInfo[0] . "<br>";
                        echo "Driver-specific error code: " . $errorInfo[1] . "<br>";
                        echo "Driver-specific error message: " . $errorInfo[2] . "<br>";
                        return false;
                    }
                } catch (PDOException $e) {
                    echo "Database error: " . $e->getMessage();
                    return false;
                }
            }
            
        
            // You may also have other functions to fetch available leave days, etc.
        

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

        public function deleteRequest($requestId) {
            try {
                $conn = new ConnectionManager();
                $pdo = $conn->getConnection();
        
                // Prepare the SQL statement
                $sql = "DELETE FROM employee_arrangement WHERE Request_ID = :requestId";
        
                $stmt = $pdo->prepare($sql);
                // Bind the request ID parameter
                $stmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);
        
                // Execute the statement
                if ($stmt->execute()) {
                    return true; // Deletion successful
                } else {
                    return false; // Deletion failed
                }
            } catch (PDOException $e) {
                echo "Error deleting request: " . $e->getMessage();
                return false; // Error occurred
            }
        }
        
    }
?>
