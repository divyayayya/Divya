<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../model/RequestDAO.php';
require_once __DIR__ . '/../model/ConnectionManager.php';

class RequestDAOTest extends TestCase {

    // Positive test for retrieveRequestInfo()
    public function test_RetrieveRequestInfo_positive() {
        $staffID = 140002;
        $requestID = 2;
        $arrangementDate = '2024-10-16';

        $expectedRequest = [
            'Staff_ID' => 140002,
            'Department' => 'Sales',
            'Request_ID' => 2,
            'Arrangement_Date' => '2024-10-15',
            'Working_Arrangement' => 'WFH',
            'Arrangement_Time' => 'AM',
            'Reason' => 'Working on a special project',
            'Request_Status' => 'Rejected',
            'Working_Location' => 'Home',
            'Rejection_Reason' => 'Not Approved past deadline'
        ];

        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        // Set up expectations for the statement
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with($this->equalTo([
                     ':userID' => $staffID,
                 ]));
        $stmtMock->expects($this->once())
                 ->method('fetch')
                 ->willReturn($expectedRequest);

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
        $result = $requestDAO->retrieveRequestInfo($staffID);

        // Assert
        $this->assertEquals($expectedRequest, $result);
    }
}   
    // Negative test for retrieveRequestInfo()
//     public function test_RetrieveRequestInfo_negative() {
//         $staffID = 999999; // Assuming this is an invalid ID
//         $requestID = 999; // Assuming this is an invalid request ID
//         $arrangementDate = '2024-12-01'; // Assuming this date has no entry
//         $expectedRequest = false;

//         $pdoMock = $this->createMock(PDO::class);
//         $stmtMock = $this->createMock(PDOStatement::class);

//         // Set up expectations for the statement
//         $stmtMock->expects($this->once())
//                  ->method('execute')
//                  ->with($this->equalTo([
//                      ':staffID' => $staffID,
//                      ':requestID' => $requestID,
//                      ':arrangementDate' => $arrangementDate
//                  ]));
//         $stmtMock->expects($this->once())
//                  ->method('fetch')
//                  ->willReturn($expectedRequest);

//         // Set up expectations for the PDO mock
//         $pdoMock->expects($this->once())
//                 ->method('prepare')
//                 ->with('SELECT * FROM employee_arrangement WHERE Staff_ID = :staffID AND Request_ID = :requestID AND Arrangement_Date = :arrangementDate')
//                 ->willReturn($stmtMock);

//         // Mock the connection manager to return the mocked PDO
//         $connMock = $this->createMock(ConnectionManager::class);
//         $connMock->expects($this->once())
//                  ->method('getConnection')
//                  ->willReturn($pdoMock);

//         // Inject the mock connection manager into RequestDAO
//         $requestDAO = new RequestDAO($connMock);

//         // Act
//         $result = $requestDAO->retrieveRequestInfo($staffID, $requestID, $arrangementDate);

//         // Assert
//         $this->assertEquals($expectedRequest, $result);
//     }
    
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
// }




