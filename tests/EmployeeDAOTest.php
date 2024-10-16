<?php
// Test for EmployeeDAO;
    use PHPUnit\Framework\TestCase;
    require_once __DIR__ . '/../model/EmployeeDAO.php';
    require_once __DIR__ . '/../model/ConnectionManager.php';

    class EmployeeDAOTest extends TestCase {

        public function testRetrieveEmployeeInfo() {
            $userID = 140008;
            $expectedEmployee = [
                'Staff_ID' => 140008,
                'Staff_FName' => 'Jaclyn',
                'Staff_LName' => 'Lee',
                'Dept' => 'Sales',
                'Position' => 'Sales Manager',
                'Country' => 'Singapore',
                'Email' => 'Jaclyn.Lee@allinone.com.sg',
                'Reporting_Manager' => 140001,
                'Role' => 3
            ];
    
            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Set up expectations for the statement
            $stmtMock->expects($this->once())
                     ->method('execute');
            $stmtMock->expects($this->once())
                     ->method('fetch')
                     ->willReturn($expectedEmployee);
    
            // Set up expectations for the PDO mock
            $pdoMock->expects($this->once())
                    ->method('prepare')
                    ->with('SELECT * FROM employee WHERE Staff_ID = :userID')
                    ->willReturn($stmtMock);
    
            // Mock the connection manager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                     ->method('getConnection')
                     ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Act
            $result = $employeeDAO->retrieveEmployeeInfo($userID);
    
            // Assert
            $this->assertEquals($expectedEmployee, $result);
        }
    }
?>