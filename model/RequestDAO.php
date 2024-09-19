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

    }

?>
