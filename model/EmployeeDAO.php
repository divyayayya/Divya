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
    }

?>
