<?php
    // Test for EmployeeDAO;
    use PHPUnit\Framework\TestCase;
    require_once __DIR__ . '/../model/RequestDAO.php';
    require_once __DIR__ . '/../model/ConnectionManager.php';

// paste into terminal: php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/RequestDAOTest.php

class RequestDAOTest extends TestCase {

    // Positive test for retrieveRequestInfo()
    public function test_retrieveRequestInfo_positive() {
        $userID = 140002;
        $expectedEmployee = [
            [
                'Staff_ID' => 140002, // As strings to match database output
                'Department' => 'Sales',
                'Request_ID' => 2,
                'Arrangement_Date' => '2024-10-16',
                'Working_Arrangement' => 'WFH',
                'Arrangement_Time' => 'AM',
                'Reason' => 'Working on a special project',
                'Request_Status' => 'Rejected',
                'Working_Location' => 'Home',
                'Rejection_Reason' => 'Not Approved past deadline'
            ]
        ];

        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        // Set up expectations for the statement
        $stmtMock->expects($this->once())
                 ->method('execute');
        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->willReturn($expectedEmployee);

        // Set up expectations for the PDO mock
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with('SELECT * FROM employee_arrangement WHERE Staff_ID = :userID')
                ->willReturn($stmtMock);

        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);

        // Inject the mock connection manager into RequestDAO
        $requestDAO = new RequestDAO($connMock);

        // Act
        $result = $requestDAO->retrieveRequestInfo($userID);
        // var_dump($result);

        // Assert
        $this->assertEquals($expectedEmployee, $result);
    }
   
<<<<<<< Updated upstream
}   
=======

    public function testApproveRequest() {
        $requestID = 1;
    
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Expect execute and rowCount to indicate one row was updated
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);
        $stmtMock->expects($this->once())
                 ->method('rowCount')
                 ->willReturn(1);
    
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
    
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        $requestDAO = new RequestDAO($connMock);
    
        // Act
        $result = $requestDAO->approveRequest($requestID);
    
        // Assert
        $this->assertTrue($result);
    }

    public function testDeleteRequest() {
        $requestID = 1;
        $staffID = 12345;
        $arrangementDate = '2024-11-01';
    
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up the statement to expect execution
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);
    
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
    
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        $requestDAO = new RequestDAO($connMock);
    
        // Act
        $result = $requestDAO->deleteRequest($requestID, $staffID, $arrangementDate);
    
        // Assert
        $this->assertTrue($result);
    }

    public function testSubmitWFHRequest() {
        $userID = 12345;
        $requestID = 6;
        $dept = 'HR';
        $leaveDate = '2024-11-01';
        $leaveTime = 'AM';
        $reason = 'Doctor appointment';
    
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up the statement to expect execution
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);
    
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
    
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        $requestDAO = new RequestDAO($connMock);
    
        // Act
        $result = $requestDAO->submitWFHRequest($userID, $requestID, $dept, $leaveDate, $leaveTime, $reason);
    
        // Assert
        $this->assertTrue($result);
    }

    public function testGenerateReqID() {
        // Arrange
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up the statement's behavior
        $stmtMock->expects($this->once())
                 ->method('execute');
        $stmtMock->expects($this->once())
                 ->method('fetch')
                 ->willReturn(['maxID' => 5]);
    
        // Set up the PDO mock to return the statement mock
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
    
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        $requestDAO = new RequestDAO($connMock);
    
        // Act
        $newRequestID = $requestDAO->generateReqID();
    
        // Assert
        $this->assertEquals(6, $newRequestID); // Expected ID is maxID + 1
    }

    public function testSubmitWFHRequestWithInvalidDataTypes() {
        $userID = 1; // Valid user ID
        $requestID = 'not_a_number'; // Invalid request ID
        $dept = "HR";
        $leave_date = "2024-10-30";
        $leave_time = "09:00";
        $reason = "Personal reasons";
    
        $dao = new RequestDAO();
        $result = $dao->submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);
        assert($result === false, "Expected false for invalid request ID.");
    }

    public function testSubmitWFHRequestWithNonExistingDepartment() {
        $userID = 1; // Valid user ID
        $requestID = 123; // Assuming this request ID is valid
        $dept = "NonExistingDept"; // Invalid department
        $leave_date = "2024-10-30";
        $leave_time = "09:00";
        $reason = "Personal reasons";
    
        $dao = new RequestDAO();
        $result = $dao->submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);
        assert($result === false, "Expected false for non-existing department.");
    }

    public function testDeleteRequestWithNonExistingRequest() {
        $requestId = 99999; // Non-existing request ID
        $staffId = 1; // Valid staff ID
        $arrangementDate = "2024-10-30"; // Valid date
    
        $dao = new RequestDAO();
        $result = $dao->deleteRequest($requestId, $staffId, $arrangementDate);
        assert($result === false, "Expected false for deleting non-existing request.");
    }

    public function testApproveRequestAlreadyApproved() {
        $requestID = 1; // Assuming this request ID is already approved
    
        $dao = new RequestDAO();
        $result = $dao->approveRequest($requestID);
        assert($result === false, "Expected false when approving already approved request.");
    }

    public function testRejectRequestWithNonExistingRequest() {
        $requestID = 99999; // Non-existing request ID
        $reason = "Not valid";
    
        $dao = new RequestDAO();
        $result = $dao->rejectRequest($requestID, $reason);
        assert($result === false, "Expected false for rejecting non-existing request.");
    }
    
    
    
    
    
    
    
    
    



>>>>>>> Stashed changes
    // Negative test for retrieveRequestInfo()
    // public function test_RetrieveRequestInfo_negative() {
    //     $staffID = 999999; // Assuming this is an invalid ID
    //     $expectedRequest = false;

    //     $pdoMock = $this->createMock(PDO::class);
    //     $stmtMock = $this->createMock(PDOStatement::class);

    //     // Set up expectations for the statement
    //     $stmtMock->expects($this->once())
    //              ->method('execute')
    //              ->with($this->equalTo([
    //                  ':userID' => $staffID,
    //              ]));
    //     $stmtMock->expects($this->once())
    //              ->method('fetch')
    //              ->willReturn($expectedRequest);

    //     // Set up expectations for the PDO mock
    //     $pdoMock->expects($this->once())
    //             ->method('prepare')
    //             ->with('SELECT * FROM employee_arrangement WHERE Staff_ID = :userID')
    //             ->willReturn($stmtMock);

    //     // Mock the connection manager to return the mocked PDO
    //     $connMock = $this->createMock(ConnectionManager::class);
    //     $connMock->expects($this->once())
    //              ->method('getConnection')
    //              ->willReturn($pdoMock);

    //     // Inject the mock connection manager into RequestDAO
    //     $requestDAO = new RequestDAO($connMock);

    //     // Act
    //     $result = $requestDAO->retrieveRequestInfo($staffID);

    //     // Assert
    //     $this->assertEquals($expectedRequest, $result);
    // }

//     // Positive test for submitWFHRequest()
//     public function test_SubmitWFHRequest_positive() {
//         $userID = 140878;
//         $requestID = 61;
//         $dept = 'Sales';
//         $leave_date = '2024-10-15';
//         $leave_time = 'AM';
//         $reason = 'Take care of baby';

//         $pdoMock = $this->createMock(PDO::class);
//         $stmtMock = $this->createMock(PDOStatement::class);

//         // Expect the statement to execute successfully
//         $stmtMock->expects($this->once())
//                  ->method('execute')
//                  ->willReturn(true);

//         // Expect the PDO mock to prepare the correct SQL statement
//         $pdoMock->expects($this->once())
//                 ->method('prepare')
//                 ->with($this->stringContains('INSERT INTO employee_arrangement'))
//                 ->willReturn($stmtMock);

//         // Mock the connection manager to return the mocked PDO
//         $connMock = $this->createMock(ConnectionManager::class);
//         $connMock->expects($this->once())
//                  ->method('getConnection')
//                  ->willReturn($pdoMock);

//         // Inject the mock connection manager into RequestDAO
//         $requestDAO = new RequestDAO($connMock);

//         // Act
//         $result = $requestDAO->submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);

//         // Assert
//         $this->assertTrue($result);
//     }

//     // Negative test for submitWFHRequest() - Database execution failure
//     public function test_SubmitWFHRequest_negative_db_error() {
//         $userID = 140001;
//         $requestID = 1;
//         $dept = 'Sales';
//         $leave_date = '2024-10-15';
//         $leave_time = 'PM';
//         $reason = 'Need to take care of family';

//         $pdoMock = $this->createMock(PDO::class);
//         $stmtMock = $this->createMock(PDOStatement::class);

//         // Expect the statement execution to fail
//         $stmtMock->expects($this->once())
//                  ->method('execute')
//                  ->willReturn(false);

//         // Expect the PDO mock to prepare the correct SQL statement
//         $pdoMock->expects($this->once())
//                 ->method('prepare')
//                 ->with($this->stringContains('INSERT INTO employee_arrangement'))
//                 ->willReturn($stmtMock);

//         // Mock the connection manager to return the mocked PDO
//         $connMock = $this->createMock(ConnectionManager::class);
//         $connMock->expects($this->once())
//                  ->method('getConnection')
//                  ->willReturn($pdoMock);

//         // Inject the mock connection manager into RequestDAO
//         $requestDAO = new RequestDAO($connMock);

//         // Act
//         $result = $requestDAO->submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);

//         // Assert
//         $this->assertFalse($result);
//     }

//     // Negative test for submitWFHRequest() - Invalid User ID
//     public function test_SubmitWFHRequest_negative_invalid_userID() {
//         $userID = 0; // Invalid User ID
//         $requestID = 1;
//         $dept = 'Sales';
//         $leave_date = '2024-10-15';
//         $leave_time = 'PM';
//         $reason = 'Need to take care of family';

//         $pdoMock = $this->createMock(PDO::class);
//         $stmtMock = $this->createMock(PDOStatement::class);

//         // Expect the statement execution to fail (you can adjust the expectation based on your specific implementation)
//         $stmtMock->expects($this->once())
//                  ->method('execute')
//                  ->willReturn(false);

//         // Expect the PDO mock to prepare the correct SQL statement
//         $pdoMock->expects($this->once())
//                 ->method('prepare')
//                 ->with($this->stringContains('INSERT INTO employee_arrangement'))
//                 ->willReturn($stmtMock);

//         // Mock the connection manager to return the mocked PDO
//         $connMock = $this->createMock(ConnectionManager::class);
//         $connMock->expects($this->once())
//                  ->method('getConnection')
//                  ->willReturn($pdoMock);

//         // Inject the mock connection manager into RequestDAO
//         $requestDAO = new RequestDAO($connMock);

//         // Act
//         $result = $requestDAO->submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);

//         // Assert
//         $this->assertFalse($result);
//     }

//     // Negative test for submitWFHRequest() - Invalid Department
//     public function test_SubmitWFHRequest_negative_invalid_dept() {
//         $userID = 140001;
//         $requestID = 1;
//         $dept = 'InvalidDept'; // Invalid Department;
//         $leave_date = '2024-10-15';
//         $leave_time = 'PM';
//         $reason = 'Need to take care of family';

//         $pdoMock = $this->createMock(PDO::class);
//         $stmtMock = $this->createMock(PDOStatement::class);

//         // Expect the statement execution to fail (you can adjust the expectation based on your specific implementation)
//         $stmtMock->expects($this->once())
//                  ->method('execute')
//                  ->willReturn(false);

//         // Expect the PDO mock to prepare the correct SQL statement
//         $pdoMock->expects($this->once())
//                 ->method('prepare')
//                 ->with($this->stringContains('INSERT INTO employee_arrangement'))
//                 ->willReturn($stmtMock);

//         // Mock the connection manager to return the mocked PDO
//         $connMock = $this->createMock(ConnectionManager::class);
//         $connMock->expects($this->once())
//                  ->method('getConnection')
//                  ->willReturn($pdoMock);

//         // Inject the mock connection manager into RequestDAO
//         $requestDAO = new RequestDAO($connMock);

//         // Act
//         $result = $requestDAO->submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);

//         // Assert
//         $this->assertFalse($result);
//     }

//     // Negative test for submitWFHRequest() - Invalid Leave Date
//     public function test_SubmitWFHRequest_negative_invalid_leave_date() {
//         $userID = 140001;
//         $requestID = 1;
//         $dept = 'Sales';
//         $leave_date = '2024-10-15'; // Invalid past date
//         $leave_time = 'PM';
//         $reason = 'Need to take care of family';

//         $pdoMock = $this->createMock(PDO::class);
//         $stmtMock = $this->createMock(PDOStatement::class);

//         // Expect the statement execution to fail (you can adjust the expectation based on your specific implementation)
//         $stmtMock->expects($this->once())
//                  ->method('execute')
//                  ->willReturn(false);

//         // Expect the PDO mock to prepare the correct SQL statement
//         $pdoMock->expects($this->once())
//                 ->method('prepare')
//                 ->with($this->stringContains('INSERT INTO employee_arrangement'))
//                 ->willReturn($stmtMock);

//         // Mock the connection manager to return the mocked PDO
//         $connMock = $this->createMock(ConnectionManager::class);
//         $connMock->expects($this->once())
//                  ->method('getConnection')
//                  ->willReturn($pdoMock);

//         // Inject the mock connection manager into RequestDAO
//         $requestDAO = new RequestDAO($connMock);

//         // Act
//         $result = $requestDAO->submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);

//         // Assert
//         $this->assertFalse($result);
//     }

//     // Negative test for submitWFHRequest() - Invalid Leave Time
//     public function test_SubmitWFHRequest_negative_invalid_leave_time() {
//         $userID = 140001;
//         $requestID = 1;
//         $dept = 'Sales';
//         $leave_date = '2024-10-15';
//         $leave_time = 'InvalidTime'; // Invalid time
//         $reason = 'Need to take care of family';

//         $pdoMock = $this->createMock(PDO::class);
//         $stmtMock = $this->createMock(PDOStatement::class);
//         // Expect the statement execution to fail (you can adjust the expectation based on your specific implementation)
//         $stmtMock->expects($this->once())
//                  ->method('execute')
//                  ->willReturn(false);

//         // Expect the PDO mock to prepare the correct SQL statement
//         $pdoMock->expects($this->once())
//                 ->method('prepare')
//                 ->with($this->stringContains('INSERT INTO employee_arrangement'))
//                 ->willReturn($stmtMock);

//         // Mock the connection manager to return the mocked PDO
//         $connMock = $this->createMock(ConnectionManager::class);
//         $connMock->expects($this->once())
//                  ->method('getConnection')
//                  ->willReturn($pdoMock);

//         // Inject the mock connection manager into RequestDAO
//         $requestDAO = new RequestDAO($connMock);

//         // Act
//         $result = $requestDAO->submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);

//         // Assert
//         $this->assertFalse($result);
//     }

//     // Positive test for submitRecurringWFHRequest()
//     public function test_SubmitRecurringWFHRequest_positive() {
//         $userID = 150148;
//         $dept = 'Engineering';
//         $startDate = '2024-10-15';
//         $endDate = '2024-10-30';
//         $recurring_days = ['Tuesday', 'Thursday'];
//         $time_slot = 'Full Day';
//         $reason = 'Government Protocol';

//         $pdoMock = $this->createMock(PDO::class);
//         $stmtMock = $this->createMock(PDOStatement::class);

//         // Expect the statement to execute successfully multiple times
//         $stmtMock->expects($this->exactly(8))
//                  ->method('execute')
//                  ->willReturn(true);

//         // Expect the PDO mock to prepare the correct SQL statement multiple times
//         $pdoMock->expects($this->exactly(8))
//                 ->method('prepare')
//                 ->with($this->stringContains('INSERT INTO employee_arrangement'))
//                 ->willReturn($stmtMock);

//         // Mock the connection manager to return the mocked PDO
//         $connMock = $this->createMock(ConnectionManager::class);
//         $connMock->expects($this->once())
//                  ->method('getConnection')
//                  ->willReturn($pdoMock);

//         // Inject the mock connection manager into RequestDAO
//         $requestDAO = new RequestDAO($connMock);

//         // Act
//         $result = $requestDAO->submitRecurringWFHRequest($userID, $dept, $startDate, $endDate, $recurring_days, $time_slot, $reason);

//         // Assert
//         $this->assertTrue($result);
//     }

//     // Negative test for submitRecurringWFHRequest() - Invalid date range
//     public function test_SubmitRecurringWFHRequest_negative() {
//         $userID = 140001;
//         $dept = 'Sales';
//         $startDate = '2024-10-07';
//         $endDate = '2024-10-01'; // Invalid range
//         $recurring_days = ['Monday', 'Wednesday'];
//         $time_slot = 'AM';
//         $reason = 'Family obligations';

//         $pdoMock = $this->createMock(PDO::class);

//         // Mock the connection manager to return the mocked PDO
//         $connMock = $this->createMock(ConnectionManager::class);
//         $connMock->expects($this->once())
//                  ->method('getConnection')
//                  ->willReturn($pdoMock);

//         // Inject the mock connection manager into RequestDAO
//         $requestDAO = new RequestDAO($connMock);

//         // Act
//         $result = $requestDAO->submitRecurringWFHRequest($userID, $dept, $startDate, $endDate, $recurring_days, $time_slot, $reason);

//         // Assert
//         $this->assertFalse($result);
//     }
}