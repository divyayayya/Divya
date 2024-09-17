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
    }

?>