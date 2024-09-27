<?php
    Class EmployeeDAO{
        public function retrieveEmployeeInfo($userID){
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();

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

        // New method to retrieve all employees in the same department
        public function retrieveEmployeesInSameDept($dept){
            $conn = new ConnectionManager();
            $pdo = $conn->getConnection();

            $sql = 'SELECT * FROM employee WHERE Dept = :dept';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dept', $dept, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $results = $stmt->fetchAll(); // Fetch all employees in the department

            $stmt = null;
            $pdo = null;

            return $results;
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
        
        
    }

?>

?>
