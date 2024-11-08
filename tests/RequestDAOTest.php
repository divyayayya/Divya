<?php
    use PHPUnit\Framework\TestCase;
    require_once __DIR__ . '/../model/RequestDAO.php';
    require_once __DIR__ . '/../model/ConnectionManager.php';

// paste into terminal: php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/RequestDAOTest.php

class RequestDAOTest extends TestCase {
    // RD 1: Positive test for retrieveRequestInfo()
    public function test_retrieveRequestInfo_positive() {
        $userID = 140002;
        $expectedEmployee = [
            [
                'Staff_ID' => 140002,
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

        $stmtMock->expects($this->once())
                 ->method('execute');
        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->willReturn($expectedEmployee);

        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with('SELECT * FROM employee_arrangement WHERE Staff_ID = :userID')
                ->willReturn($stmtMock);

        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);

        $requestDAO = new RequestDAO($connMock);

        $result = $requestDAO->retrieveRequestInfo($userID);
        // var_dump($result);

        $this->assertEquals($expectedEmployee, $result);
    }

    // RD 2: Negative test for retrieveRequestInfo()
    public function test_retrieveRequestInfo_negative() {
        $userID = 999999; // Assume this ID does not exist
        $expectedEmployee = []; // No employee data expected

        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        $stmtMock->expects($this->once())
                ->method('execute');
        $stmtMock->expects($this->once())
                ->method('fetchAll')
                ->willReturn($expectedEmployee);

        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with('SELECT * FROM employee_arrangement WHERE Staff_ID = :userID')
                ->willReturn($stmtMock);

        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                ->method('getConnection')
                ->willReturn($pdoMock);

        $requestDAO = new RequestDAO($connMock);

        $result = $requestDAO->retrieveRequestInfo($userID);

        $this->assertEquals($expectedEmployee, $result);
    }

    // RD 3: Positive testcase of the approveRequest() 
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

    // RD 4: Negative test for approveRequest()
    public function testApproveRequest_nonExistentRequest() {
        $requestID = 999; // Assume this request ID does not exist

        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        // Expect execute and rowCount to indicate no rows were updated
        $stmtMock->expects($this->once())
                ->method('execute')
                ->willReturn(true);
        $stmtMock->expects($this->once())
                ->method('rowCount')
                ->willReturn(0); // No rows affected

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
        $this->assertFalse($result); // Should return false when no rows are affected
    }

    // RD 5: Positive test for generateReqID()
    public function testGenerateReqID_ValidCase() {
        $expectedReqID = '68'; // Example of expected request ID
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        // Set up expectations for the statement
        $stmtMock->expects($this->once())
                ->method('execute');
        $stmtMock->expects($this->once())
                ->method('fetch')
                ->willReturn(['maxID' => '67']); // Simulate fetching the max request ID

        // Set up expectations for the PDO mock
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with('SELECT MAX(Request_ID) AS maxID FROM employee_arrangement') // Match actual query
                ->willReturn($stmtMock);

        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                ->method('getConnection')
                ->willReturn($pdoMock);

        // Inject the mock connection manager into RequestDAO
        $requestDAO = new RequestDAO($connMock);

        // Act
        $result = $requestDAO->generateReqID();

        // Assert
        $this->assertEquals($expectedReqID, $result);
    }

    // RD 6: Negative test for generateReqID()
    public function testGenerateReqID_NoRequests() {
        $expectedReqID = '1'; // Example of the first request ID when there are no existing requests
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for the statement
        $stmtMock->expects($this->once())
                 ->method('execute');
        $stmtMock->expects($this->once())
                 ->method('fetch')
                 ->willReturn(false); // Simulate no existing request IDs
    
        // Set up expectations for the PDO mock
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with('SELECT MAX(Request_ID) AS maxID FROM employee_arrangement') // Adjust the query here to match the implementation
                ->willReturn($stmtMock);
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Inject the mock connection manager into RequestDAO
        $requestDAO = new RequestDAO($connMock);
    
        // Act
        $result = $requestDAO->generateReqID();
    
        // Assert
        $this->assertEquals($expectedReqID, $result);
    }

    // RD 7: Positive test for RejectRequest()
    public function testRejectRequest_Success() {
        $requestID = 1;
        $reason = "Duplicate request";

        // Mocking PDO and PDOStatement
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        // Expect prepare to be called once
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);

        // Set expectations for the statement mock
        $stmtMock->expects($this->exactly(2))
                 ->method('bindParam')
                 ->withConsecutive(
                     [$this->equalTo(':requestID'), $this->equalTo($requestID), PDO::PARAM_INT],
                     [$this->equalTo(':reason'), $this->equalTo($reason), PDO::PARAM_STR]
                 );
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true); // Simulate successful execution
        $stmtMock->expects($this->once())
                 ->method('rowCount')
                 ->willReturn(1); // Simulate one row affected

        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);

        // Inject the mock connection manager into RequestDAO
        $requestDAO = new RequestDAO($connMock);

        // Act
        $result = $requestDAO->rejectRequest($requestID, $reason);

        // Assert
        $this->assertTrue($result);
    }

    // RD 8: Negative test for RejectRequest()
    public function testRejectRequest_NoAffectedRows() {
        $requestID = 2;
        $reason = "Request already processed";

        // Mocking PDO and PDOStatement
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        // Expect prepare to be called once
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);

        // Set expectations for the statement mock
        $stmtMock->expects($this->exactly(2))
                 ->method('bindParam')
                 ->withConsecutive(
                     [$this->equalTo(':requestID'), $this->equalTo($requestID), PDO::PARAM_INT],
                     [$this->equalTo(':reason'), $this->equalTo($reason), PDO::PARAM_STR]
                 );
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true); // Simulate successful execution
        $stmtMock->expects($this->once())
                 ->method('rowCount')
                 ->willReturn(0); // Simulate no rows affected

        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);

        // Inject the mock connection manager into RequestDAO
        $requestDAO = new RequestDAO($connMock);

        // Act
        $result = $requestDAO->rejectRequest($requestID, $reason);

        // Assert
        $this->assertFalse($result);
    }

    // RD 9: Positive test for RejectExpiredRequest()
    public function testRejectExpiredRequests_Success() {
        // Arrange
        $pdoMock = $this->createMock(PDO::class);
        $stmtMockSelect = $this->createMock(PDOStatement::class);
        $stmtMockUpdate = $this->createMock(PDOStatement::class);
    
        // Simulate expired requests
        $expiredRequests = [
            ['Request_ID' => 1],
            ['Request_ID' => 2],
        ];
    
        // Set up expectations for the SELECT statement
        $stmtMockSelect->expects($this->once())
                       ->method('execute');
        $stmtMockSelect->expects($this->once())
                       ->method('fetchAll')
                       ->willReturn($expiredRequests);
    
        // Expect the first call to prepare to return the SELECT statement mock
        $pdoMock->expects($this->exactly(2)) // First for SELECT, then for UPDATE
                ->method('prepare')
                ->will($this->onConsecutiveCalls($stmtMockSelect, $stmtMockUpdate));
    
        // Set up expectations for the UPDATE statement
        $stmtMockUpdate->expects($this->exactly(2)) // Expect to execute for each expired request
                       ->method('execute')
                       ->willReturn(true); // Simulate successful execution of update
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Inject the mock connection manager into RequestDAO
        $requestDAO = new RequestDAO($connMock);
    
        // Act
        $requestDAO->rejectExpiredRequests();
    
        // No assertion is necessary; if no exceptions are thrown, the test passes
    }
    
    // RD 10: Negative test for RejectExpiredRequest()
    public function testRejectExpiredRequests_NoExpiredRequests() {
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        
        // Create a mock for the PDOStatement for the SELECT query
        $stmtSelectMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for the SELECT statement
        $stmtSelectMock->expects($this->once())
                       ->method('execute');
        $stmtSelectMock->expects($this->once())
                       ->method('fetchAll')
                       ->willReturn([]); // Simulate no expired requests
    
        // Set up expectations for the PDO mock
        $pdoMock->expects($this->once()) // We expect prepare to be called exactly once for the SELECT
                ->method('prepare')
                ->with("SELECT Request_ID FROM employee_arrangement WHERE Request_Status = 'Pending' AND Arrangement_Date <= CURRENT_DATE")
                ->willReturn($stmtSelectMock);
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $requestDAO->rejectExpiredRequests();
    
        // Assert - No assertions needed, but you can assert that nothing unexpected occurred
        $this->assertTrue(true); // Mark the test as successful.
    }

    // RD 11: Positive test for deleteRequest() 
    public function testDeleteRequest_Success() {
        $requestId = 1;
        $staffId = 2;
        $arrangementDate = '2024-10-31';
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        
        // Create a mock for PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for the prepare method with the actual SQL string
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with($this->equalTo("DELETE FROM employee_arrangement \n" .
                                "                    WHERE Request_ID = :requestId \n" .
                                "                    AND Staff_ID = :staffId \n" .
                                "                    AND Arrangement_Date = :arrangementDate"))
                ->willReturn($stmtMock);

        // Set up expectations for the bindParam method
        $stmtMock->expects($this->exactly(3))
                 ->method('bindParam')
                 ->withConsecutive(
                     [$this->equalTo(':requestId'), $this->equalTo($requestId), PDO::PARAM_INT],
                     [$this->equalTo(':staffId'), $this->equalTo($staffId), PDO::PARAM_INT],
                     [$this->equalTo(':arrangementDate'), $this->equalTo($arrangementDate)]
                 );
    
        // Set up expectations for the execute method
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true); // Simulate successful execution
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->deleteRequest($requestId, $staffId, $arrangementDate);
    
        // Assert
        $this->assertTrue($result);
    }
    
    // RD 12: Negative test for deleteRequest() 
    public function testDeleteRequest_NonExistentRequest() {
        // Create a mock for the connection manager
        $mockConnectionManager = $this->createMock(ConnectionManager::class);
    
        // Create a mock for PDO
        $mockPdo = $this->createMock(PDO::class);
        $mockStmt = $this->createMock(PDOStatement::class);
        
        // Set expectations for the getConnection method
        $mockConnectionManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($mockPdo);
        
        // Update the expected SQL statement to match the actual one generated by your method
        $expectedSql = "DELETE FROM employee_arrangement \n" .
        "                    WHERE Request_ID = :requestId \n" .
        "                    AND Staff_ID = :staffId \n" .
        "                    AND Arrangement_Date = :arrangementDate";
    
        // Set expectations for the prepare method
        $mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo(trim($expectedSql))) // Use trim to avoid leading/trailing spaces
            ->willReturn($mockStmt);
        
        // Set expectations for bindParam calls
        $mockStmt->expects($this->exactly(3))
            ->method('bindParam')
            ->withConsecutive(
                [':requestId', $this->anything(), PDO::PARAM_INT],
                [':staffId', $this->anything(), PDO::PARAM_INT],
                [':arrangementDate', $this->anything()]
            );
        
        // Set expectation for execute method to return false (simulating non-existent request)
        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(false);
    
        // Create the DAO instance with the mocked connection manager
        $requestDAO = new RequestDAO($mockConnectionManager);
    
        // Act
        $result = $requestDAO->deleteRequest(9999, 1, '2024-01-01'); // assuming 9999 is a non-existent request ID
    
        // Assert
        $this->assertFalse($result); // Expecting false because the request does not exist
    }
    
    // RD 13: Exception Handling for deleteRequest() 
    public function testDeleteRequest_ThrowException() {
        $requestId = 1;
        $staffId = 2;
        $arrangementDate = '2024-10-31';
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        
        // Create a mock for PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for the prepare method to throw an exception
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->will($this->throwException(new Exception('Prepare failed')));
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act & Assert
        $requestDAO = new RequestDAO($connMock);
        
        // Assert that the exception is thrown
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Prepare failed');
        
        $requestDAO->deleteRequest($requestId, $staffId, $arrangementDate);
    }

    // RD 14: Positive tests for withdrawRequest() 
    public function testWithdrawRequest_Success() {
        $requestId = 1;
        $staffId = 2;
        $arrangementDate = '2024-10-31';
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for the prepare method
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
    
        // Set up expectations for bindParam calls
        $stmtMock->expects($this->exactly(3))
                 ->method('bindParam')
                 ->withConsecutive(
                     [$this->equalTo(':requestId'), $this->equalTo($requestId), PDO::PARAM_INT],
                     [$this->equalTo(':staffId'), $this->equalTo($staffId), PDO::PARAM_INT],
                     [$this->equalTo(':arrangementDate'), $this->equalTo($arrangementDate)]
                 );
    
        // Set up expectations for execute method
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->withdrawRequest($requestId, $staffId, $arrangementDate);
    
        // Assert
        $this->assertTrue($result);
    }
    
    // RD 15: Negative test for withdrawRequest() 
    public function testWithdrawRequest_NonExistentRequest() {
        $requestId = 9999;
        $staffId = 2;
        $arrangementDate = '2024-10-31';
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for the prepare and execute methods
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
        $stmtMock->expects($this->exactly(3))
                 ->method('bindParam');
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(false);
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->withdrawRequest($requestId, $staffId, $arrangementDate);
    
        // Assert
        $this->assertFalse($result);
    }
    
    // RD 16: Exception Handling for withdrawRequest() 
    public function testWithdrawRequest_ThrowsExceptionOnPrepare() {
        $requestId = 1;
        $staffId = 2;
        $arrangementDate = '2024-10-31';
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
    
        // Set up expectations for the prepare method to throw an exception
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->will($this->throwException(new Exception('Prepare failed')));
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act & Assert
        $requestDAO = new RequestDAO($connMock);
        
        // Assert that the exception is thrown
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Prepare failed');
        
        $requestDAO->withdrawRequest($requestId, $staffId, $arrangementDate);
    }
    
    // RD 17: Test for requests found for retrieveApprovedRequestByUserID() 
    public function testRetrieveApprovedRequests_Success() {
        $userID = 140001;
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Set up expectations for the prepare method
        $pdoMock->expects($this->once())
                 ->method('prepare')
                 ->willReturn($stmtMock);
    
        // Set up expectations for bindParam
        $stmtMock->expects($this->once())
                 ->method('bindParam')
                 ->with(':userID', $userID, PDO::PARAM_INT);
    
        // Set up expectations for execute and fetchAll
        $stmtMock->expects($this->once())
                 ->method('execute');
    
        $expectedResult = [
            ['Request_ID' => 1, 'Staff_ID' => 1, 'Request_Status' => 'Approved', 'Arrangement_Date' => '2024-10-15']
        ];
    
        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->willReturn($expectedResult);
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->retrieveApprovedRequestsByUserID($userID);
    
        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    // RD 18: Test for no approved requests found for retrieveApprovedRequestByUserID() 
    public function testRetrieveApprovedRequests_NoRequests() {
        $userID = 130002;
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Set up expectations for the prepare method
        $pdoMock->expects($this->once())
                 ->method('prepare')
                 ->willReturn($stmtMock);
    
        // Set up expectations for bindParam
        $stmtMock->expects($this->once())
                 ->method('bindParam')
                 ->with(':userID', $userID, PDO::PARAM_INT);
    
        // Set up expectations for execute and fetchAll
        $stmtMock->expects($this->once())
                 ->method('execute');
    
        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->willReturn([]); // No approved requests
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->retrieveApprovedRequestsByUserID($userID);
    
        // Assert
        $this->assertEquals([], $result);
    }
    
    // RD 19: Exception handling for retrieveApprovedRequestByUserID() 
    public function testRetrieveApprovedRequests_Exception() {
        $userID = 1;
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $connMock = $this->createMock(ConnectionManager::class);
        
        // Mock the connection manager to return the mocked PDO
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Set up expectations for the prepare method to throw an exception
        $pdoMock->expects($this->once())
                 ->method('prepare')
                 ->will($this->throwException(new Exception('Database error')));
    
        // Act & Assert
        $requestDAO = new RequestDAO($connMock);
    
        // Assert that the exception is thrown
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Database error');
    
        $requestDAO->retrieveApprovedRequestsByUserID($userID);
    }

    // RD 20: Positive test for SubmitLeaveRequest() 
    public function testSubmitLeaveRequest_SuccessfulSubmission() {
        $userID = 'U12345';
        $requestID = 1001;
        $dept = 'Sales';
        $leave_date = '2024-11-01';
        $leave_time = 'Full Day';
        $reason = 'Vacation';
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for the prepare and execute methods
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
    
        $stmtMock->expects($this->exactly(6)) // We expect six bindParam calls
                 ->method('bindParam');
    
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true); // Simulate successful execution
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->submitLeaveRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);
    
        // Assert
        $this->assertTrue($result);
    }

    // RD 21: Negative test for SubmitLeaveRequest() 
    public function testSubmitLeaveRequest_InvalidUserID() {
        $userID = ''; // Invalid user ID format (empty string)
        $requestID = 1002;
        $dept = 'Marketing';
        $leave_date = '2024-11-02';
        $leave_time = 'Full Day';
        $reason = 'Family Event';
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for the prepare method but simulate failure due to invalid user ID
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
    
        // We expect bindParam to be called, but `execute` should not be successful due to invalid input
        $stmtMock->expects($this->exactly(6)) // Expect six bindParam calls, regardless of input validity
                 ->method('bindParam');
    
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(false); // Simulate failure of execution due to invalid input
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->submitLeaveRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);
    
        // Assert
        $this->assertFalse($result);
    }

    // RD 22: Positive test for retrievePendingArrangements() 
    public function testRetrievePendingArrangements_SuccessfulRetrieval() {
        $staffID = '160290';
        $expectedResults = [
            [
                'Staff_ID' => '160290',
                'Request_Status' => 'Approved',
                'Arrangement_Date' => '2024-11-7',
                'Arrangement_Time' => 'Full Day',
                'Reason' => 'Take care of baby'
            ]
        ];
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for the prepare and execute methods
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
    
        $stmtMock->expects($this->once())
                 ->method('bindParam')
                 ->with($this->equalTo(':staffID'), $this->equalTo($staffID));
    
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);
    
        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->willReturn($expectedResults);
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->retrievePendingArrangements($staffID);
    
        // Assert
        $this->assertEquals($expectedResults, $result);
    }

    // RD 23: Negative test for retrievePendingArrangements() 
    public function testRetrievePendingArrangements_InvalidStaffID() {
        $staffID = '';
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for the prepare and execute methods
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
    
        $stmtMock->expects($this->once())
                 ->method('bindParam')
                 ->with($this->equalTo(':staffID'), $this->equalTo($staffID));
    
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);
    
        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->willReturn([]); // Simulate no results found for invalid staff ID
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->retrievePendingArrangements($staffID);
    
        // Assert
        $this->assertEmpty($result); // Expect an empty result set for an invalid staff ID
    }

    // RD 24: Positive test for submitRecurringWFHRequest() 
    public function testSubmitRecurringWFHRequest_SuccessfulSubmission() {
        $userID = 160290;
        $dept = 'HR';
        $startDate = '2024-11-01';
        $endDate = '2024-11-10';
        $recurring_days = ['Monday', 'Wednesday', 'Friday'];
        $time_slot = 'Full Day';
        $reason = 'Medical reasons';
    
        // Calculate the expected number of insertions based on the date range and recurring days
        $expectedInsertions = 0;
        $current_date = strtotime($startDate);
        $end_date = strtotime($endDate);
    
        while ($current_date <= $end_date) {
            $day_of_week = date('l', $current_date);
            if (in_array($day_of_week, $recurring_days)) {
                $expectedInsertions++;
            }
            $current_date = strtotime('+1 day', $current_date);
        }
    
        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // Set up expectations for prepare, bindParam, and execute methods
        $pdoMock->expects($this->exactly($expectedInsertions))
                ->method('prepare')
                ->willReturn($stmtMock);
    
        $stmtMock->expects($this->exactly($expectedInsertions * 5)) // 5 parameters per insertion
                 ->method('bindParam')
                 ->withConsecutive(
                     [$this->equalTo(':userID'), $this->equalTo($userID)],
                     [$this->equalTo(':dept'), $this->equalTo($dept)],
                     [$this->equalTo(':arrangement_date')],
                     [$this->equalTo(':time_slot'), $this->equalTo($time_slot)],
                     [$this->equalTo(':reason'), $this->equalTo($reason)]
                 );
    
        $stmtMock->expects($this->exactly($expectedInsertions))
                 ->method('execute')
                 ->willReturn(true); // Simulate successful insertions
    
        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);
    
        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->submitRecurringWFHRequest($userID, $dept, $startDate, $endDate, $recurring_days, $time_slot, $reason);
    
        // Assert
        $this->assertTrue($result);
    }

    // RD 25: Negative test for submitRecurringWFHRequest() 
    public function testSubmitRecurringWFHRequest_InsertionFailure() {
        // Arrange
        $userID = 160290;
        $dept = 'HR';
        $startDate = '2024-11-01'; // Start date (Friday)
        $endDate = '2024-11-05'; // End date (Tuesday)
        $recurring_days = ['Friday', 'Monday']; // Recurring days to match
        $time_slot = 'Full Day'; // Time slot
        $reason = 'Working from home'; // Reason for request

        // Create a mock for PDO
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        // Expect the prepare method to be called
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);

        // Expect bindParam to be called for each parameter being bound
        $stmtMock->expects($this->exactly(5)) // There are 5 bindParam calls
                 ->method('bindParam');

        // Expect execute to throw an exception to simulate an insertion failure
        $stmtMock->expects($this->once()) // Expect execute to be called once
                 ->method('execute')
                 ->will($this->throwException(new PDOException('Insert failed'))); // Simulate failure

        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);

        // Act
        $requestDAO = new RequestDAO($connMock);
        $result = $requestDAO->submitRecurringWFHRequest($userID, $dept, $startDate, $endDate, $recurring_days, $time_slot, $reason);

        // Assert
        $this->assertFalse($result); // Expecting false due to insertion failure
    }

    // RD 26: Positive test for submitWFHRequest()
    public function testSubmitWFHRequest_SuccessfulSubmission()
    {
        // Mock the connection manager
        $mockConnManager = $this->createMock(ConnectionManager::class);
        
        // Mock the PDO object
        $mockPdo = $this->createMock(PDO::class);
        
        // Mock the PDOStatement
        $mockStmt = $this->createMock(PDOStatement::class);
        
        // Set up the expected behavior for the PDO::prepare method
        $mockConnManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($mockPdo);
        
        $mockPdo->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStmt);
        
        // Set up the expected behavior for the PDOStatement::bindParam method
        $mockStmt->expects($this->exactly(6)) // Expect to be called 6 times for 6 parameters
            ->method('bindParam')
            ->willReturn(true);
        
        // Set up the expected behavior for the PDOStatement::execute method
        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true); // Simulate a successful execution

        // Instantiate the class that contains the submitWFHRequest method
        $requestDAO = new RequestDAO($mockConnManager);

        // Define the parameters for the method
        $userID = 1;
        $requestID = 1001;
        $dept = "IT";
        $leave_date = "2024-11-10";
        $leave_time = "Full Day";
        $reason = "Working from home";

        // Call the method under test
        $result = $requestDAO->submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);

        // Assert that the result is true
        $this->assertTrue($result);
    }

    // RD 27: Negative test for submitWFHRequest() 
    public function testSubmitWFHRequest_FailedSubmission()
    {
        // Mock the connection manager
        $mockConnManager = $this->createMock(ConnectionManager::class);
        
        // Mock the PDO object
        $mockPdo = $this->createMock(PDO::class);
        
        // Mock the PDOStatement
        $mockStmt = $this->createMock(PDOStatement::class);
        
        // Set up the expected behavior for the PDO::prepare method
        $mockConnManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($mockPdo);
        
        $mockPdo->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStmt);
        
        // Set up the expected behavior for the PDOStatement::bindParam method
        $mockStmt->expects($this->exactly(6)) // Expect to be called 6 times for 6 parameters
            ->method('bindParam')
            ->willReturn(true);
        
        // Simulate a failure in the execute method
        $mockStmt->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new PDOException("SQL error: Insert failed")));

        // Instantiate the class that contains the submitWFHRequest method
        $requestDAO = new RequestDAO($mockConnManager);

        // Define the parameters for the method
        $userID = 1;
        $requestID = 1001;
        $dept = "IT";
        $leave_date = "2024-11-10";
        $leave_time = "Full Day";
        $reason = "Working from home";

        // Call the method under test
        $result = $requestDAO->submitWFHRequest($userID, $requestID, $dept, $leave_date, $leave_time, $reason);

        // Assert that the result is false
        $this->assertFalse($result);
    }  
} 
