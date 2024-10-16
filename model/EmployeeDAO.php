<?php
    Class EmployeeDAO{

        private $connManager;

        public function __construct($connManager = null) {
            // Use the provided connection manager or create a new one
            $this->connManager = $connManager ?? new ConnectionManager();
        }

        public function retrieveEmployeeInfo($userID){
            $pdo = $this->connManager->getConnection();  // Use the injected connection manager

            $sql = 'SELECT * FROM employee WHERE Staff_ID = :userID';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();

            $stmt = null;
            $pdo = null;

            return $result;
        }

        public function retrieveAllEmployees() {
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();

            $sql = 'SELECT * FROM employee';
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            // $stmt->setFetchMode(PDO::FETCH_ASSOC);
            // $results = $stmt->fetchAll(); // Fetch all employees in the department

            // $stmt = null;
            // $pdo = null;

            // return $results;
            $employees = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $employees[] = $row;
            }
            $stmt->closeCursor();
            $pdo = null;
        
            return $employees;
        }

        // New method to retrieve all employees in the same department
        public function retrieveEmployeesInSameDept($dept){
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();

            $sql = 'SELECT * FROM employee WHERE Dept = :dept';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dept', $dept, PDO::PARAM_STR);
            $stmt->execute();
            // $stmt->setFetchMode(PDO::FETCH_ASSOC);
            // $results = $stmt->fetchAll(); // Fetch all employees in the department

            // $stmt = null;
            // $pdo = null;

            // return $results;
            $employees = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $employees[] = $row;
            }
            $stmt->closeCursor();
            $pdo = null;
        
            return $employees;
        }

        public function retrieveUnderlings($userID){
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();

            $sql = 'SELECT * FROM employee WHERE Reporting_Manager = :userID';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $results = $stmt->fetchAll(); // Fetch all employees in the department

            $stmt = null;
            $pdo = null;

            return $results;
        }

        
        public function getAllDepartments() {
            // Database connection
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();
    
            // Query to get all unique departments
            $sql = 'SELECT DISTINCT Dept FROM employee ORDER BY Dept';
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            // Fetch all results
            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Clean up
            $stmt = null;
            $pdo = null;
    
            return $departments; // Return an array of departments
        }

        public function getAllPositions() {
            $conn = new ConnectionManager(); 
            $pdo = $conn ->getConnection(); 

            $sql = "SELECT DISTINCT Position FROM employee ORDER BY Position"; // Adjust the table name if it's different
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        
            $positions = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        
            $stmt = null;
            $pdo = null;
        
            return $positions; // Returns an array of distinct positions
        }

        public function retrieveEmployeesByDeptAndPosition($department, $position = '') {
            $conn = new ConnectionManager();
            $pdo = $conn ->getConnection(); 
        
            // Basic SQL query to retrieve employees by department
            $sql = "SELECT Staff_ID, Staff_FName, Staff_LName, Position, Country, Email 
                    FROM employee 
                    WHERE Dept = :department";
        
            // If position is provided, append a condition to the query
            if (!empty($position)) {
                $sql .= " AND Position = :position";
            }
        
            $stmt = $pdo->prepare($sql);
        
            // Bind parameters
            $stmt->bindParam(':department', $department, PDO::PARAM_STR);
        
            // If position is not empty, bind the position parameter
            if (!empty($position)) {
                $stmt->bindParam(':position', $position, PDO::PARAM_STR);
            }
        
            $stmt->execute();
        
            $employees = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $employees[] = $row;
            }
        
            $stmt->closeCursor();
            $conn = null;
        
            return $employees; // Returns an array of employees matching the filters
        }
        
        // public function retrieveArrangementDetailsByDate($userDept, $arrangement_date) {
        //     $conn = new ConnectionManager();
        //     $pdo = $conn->getConnection();
        
        //     $sql = 'SELECT Working_Location FROM employee_arrangement 
        //             WHERE Department = :userDept AND Arrangement_Date = :arrangement_date" 
        //             ORDER BY Arrangement_Date DESC LIMIT 1';
        //     $stmt = $pdo->prepare($sql);
        //     $stmt->bindParam(':userDept', $userDept, PDO::PARAM_STR);
        //     $stmt->bindParam(':arrangement_date', $arrangement_date, PDO::PARAM_STR);
        //     $stmt->execute();
        //     $stmt->setFetchMode(PDO::FETCH_ASSOC);
        //     $result = $stmt->fetch();  // Fetch the latest approved working arrangement
        
        //     $stmt = null;
        //     $pdo = null;
        
        //     return $result;
        // }

        public function retrieveArrangementDetailsByDate($staffID, $arrangement_date) {
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();
        
            $sql = 'SELECT Working_Location, Arrangement_Time 
                    FROM employee_arrangement 
                    WHERE Staff_ID = :staffID AND Arrangement_Date = :arrangement_date';
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':staffID', $staffID, PDO::PARAM_INT);
            $stmt->bindParam(':arrangement_date', $arrangement_date, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();  // Fetch the latest approved working arrangement
            
            $stmt = null;
            $pdo = null;
        
            return $result;
        }
        
        public function searchEmployee($sql){
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            
            if ($result == null){
                $errorMsg = "No employee found!";
                return $errorMsg;
            }
            return $result;

        }

        public function retrieveUnderlingsID($userID){
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();

            $sql = 'SELECT Staff_ID FROM employee WHERE Reporting_Manager = :userID ORDER BY Staff_ID';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $results = $stmt->fetchAll(); // Fetch all employees in the department

            $stmt = null;
            $pdo = null;

            $idArray = [];
            foreach ($results as $row){
                $id = $row['Staff_ID'];
                $idArray[] = $id;
            }

            return $idArray;
        }

        public function getStaffName($userID){
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();

            $sql = "SELECT CONCAT(Staff_FName, ' ', Staff_LName) AS staffName FROM employee WHERE Staff_ID = :userID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch(); // Fetch all employees in the department

            $stmt = null;
            $pdo = null;

            return $result ? $result['staffName'] : null;
        }

        
        
    }

?>
