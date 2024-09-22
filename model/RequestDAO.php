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
     // Submit a new work-from-home request
        public function submitWFHRequest($userID, $wfh_date, $reason) {
        $conn = new ConnectionManager();
        $pdo = $conn->getConnection();

        // Insert the WFH request into the employee_arrangement table
        $sql = "INSERT INTO employee_arrangement (Staff_ID, Arrangement_Date, Working_Arrangement, Request_Status, Reason)
                VALUES (:userID, :wfh_date, 'WFH', 'Pending', :reason)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':wfh_date', $wfh_date);
        $stmt->bindParam(':reason', $reason);

        // Execute the insert and return the result (true if success, false if failure)
        $result = $stmt->execute();

        $stmt = null;
        $pdo = null;

        return $result;
    }

    }

?>
