<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../model/RequestDAO.php';
require_once __DIR__ . '/../model/ConnectionManager.php';

class requestdaotest1 extends TestCase {
    // Negative test for retrieveRequestInfo()
    public function test_RetrieveRequestInfo_negative() {
        $staffID = 999999;
        $expectedRequest = [false];

        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        // Set up expectations for the statement
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with($this->equalTo([
                     ':userID' => $staffID,
                 ]));
        $stmtMock->expects($this->once())
                 ->method('fetchAll')
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
        var_dump($result);
        // Assert
        $this->assertEquals($expectedRequest, $result);
    }

}
