<?php
// Test for RequestDAO
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../model/RequestDAO.php';
require_once __DIR__ . '/../model/ConnectionManager.php';

// Command for running this test: php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/RequestDAOTest.php

class RequestDAOTest extends TestCase {

    // Positive test for retrieveRequestInfo()
    public function test_RetrieveRequestInfo_positive() {
        $staffID = 140001;
        $requestID = 1;
        $arrangementDate = '2024-10-15';

        $expectedRequest = [
            'Staff_ID' => 140001,
            'Department' => 'Sales',
            'Request_ID' => 1,
            'Arrangement_Date' => '2024-10-15',
            'Working_Arrangement' => 'WFH',
            'Arrangement_Time' => 'PM',
            'Reason' => 'Need to take care of family',
            'Request_Status' => 'Approved',
            'Working_Location' => 'Home',
            'Rejection_Reason' => null
        ];

        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        // Set up expectations for the statement
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with($this->equalTo([
                     ':staffID' => $staffID,
                     ':requestID' => $requestID,
                     ':arrangementDate' => $arrangementDate
                 ]));
        $stmtMock->expects($this->once())
                 ->method('fetch')
                 ->willReturn($expectedRequest);

        // Set up expectations for the PDO mock
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with('SELECT * FROM employee_arrangement WHERE Staff_ID = :staffID AND Request_ID = :requestID AND Arrangement_Date = :arrangementDate')
                ->willReturn($stmtMock);

        // Mock the connection manager to return the mocked PDO
        $connMock = $this->createMock(ConnectionManager::class);
        $connMock->expects($this->once())
                 ->method('getConnection')
                 ->willReturn($pdoMock);

        // Inject the mock connection manager into RequestDAO
        $requestDAO = new RequestDAO($connMock);

        // Act
        $result = $requestDAO->retrieveRequestInfo($staffID, $requestID, $arrangementDate);

        // Assert
        $this->assertEquals($expectedRequest, $result);
    }

    // Negative test for retrieveRequestInfo()
    // public function test_RetrieveRequestInfo_negative() {
    //     $staffID = 999999; // Assuming this is an invalid ID
    //     $requestID = 999; // Assuming this is an invalid request ID
    //     $arrangementDate = '2024-12-01'; // Assuming this date has no entry
    //     $expectedRequest = false;

    //     $pdoMock = $this->createMock(PDO::class);
    //     $stmtMock = $this->createMock(PDOStatement::class);

    //     // Set up expectations for the statement
    //     $stmtMock->expects($this->once())
    //              ->method('execute')
    //              ->with($this->equalTo([
    //                  ':staffID' => $staffID,
    //                  ':requestID' => $requestID,
    //                  ':arrangementDate' => $arrangementDate
    //              ]));
    //     $stmtMock->expects($this->once())
    //              ->method('fetch')
    //              ->willReturn($expectedRequest);

    //     // Set up expectations for the PDO mock
    //     $pdoMock->expects($this->once())
    //             ->method('prepare')
    //             ->with('SELECT * FROM employee_arrangement WHERE Staff_ID = :staffID AND Request_ID = :requestID AND Arrangement_Date = :arrangementDate')
    //             ->willReturn($stmtMock);

    //     // Mock the connection manager to return the mocked PDO
    //     $connMock = $this->createMock(ConnectionManager::class);
    //     $connMock->expects($this->once())
    //              ->method('getConnection')
    //              ->willReturn($pdoMock);

    //     // Inject the mock connection manager into RequestDAO
    //     $requestDAO = new RequestDAO($connMock);

    //     // Act
    //     $result = $requestDAO->retrieveRequestInfo($staffID, $requestID, $arrangementDate);

    //     // Assert
    //     $this->assertEquals($expectedRequest, $result);
    // }
}
