<?php
// Test for EmployeeDAO;
    use PHPUnit\Framework\TestCase;
    require_once __DIR__ . '/../model/EmployeeDAO.php';
    require_once __DIR__ . '/../model/ConnectionManager.php';

// paste into terminal: php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/EmployeeDAOTest.php

    class EmployeeDAOTest extends TestCase {

        // retrieveEmployeeInfo() --> ED_1 | ED_2
        public function test_RetrieveEmployeeInfo_positive() {
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

        public function test_RetrieveEmployeeInfo_negative() {
            $userID = 1;
            $expectedEmployee = false;
    
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

        // retrieveAllEmployees() --> ED_3
        public function test_retrieveAllEmployees() {
            
            $totalEmployeeCount = 554;
            $mockEmployees = array_fill(0, $totalEmployeeCount, [
                'Staff_ID' => 123456,
                'Staff_FName' => 'John',
                'Staff_LName' => 'Doe',
                'Dept' => 'Sales',
                'Position' => 'Sales Executive',
                'Country' => 'USA',
                'Email' => 'john.doe@example.com',
                'Reporting_Manager' => 78910,
                'Role' => 3
            ]);

            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Set up expectations for the statement
            $stmtMock->expects($this->once())
             ->method('execute');
    
            // Set up expectations for the PDO mock
            $fetchReturns = array_merge($mockEmployees, [false]); // Merging mock employees and false
            $stmtMock->expects($this->exactly($totalEmployeeCount + 1)) // +1 for the final false return
             ->method('fetch')
             ->willReturnOnConsecutiveCalls(...$fetchReturns); // Unpack the merged array
            
            $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM employee')
            ->willReturn($stmtMock);

            // Mock the connection manager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                     ->method('getConnection')
                     ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Act
            $result = $employeeDAO->retrieveAllEmployees();
            // Assert
            $this->assertCount(554, $result);
        }

        // retrieveEmployeesInSameDept() --> ED_4 | ED_5
        public function test_retrieveEmployeesInSameDept_positive(){

            $totalEmployeeCount = 64;
            $dept = 'Sales';
            $mockEmployees = array_fill(0, $totalEmployeeCount, [
                'Staff_ID' => 123456,
                'Staff_FName' => 'John',
                'Staff_LName' => 'Doe',
                'Dept' => 'Sales',
                'Position' => 'Sales Executive',
                'Country' => 'USA',
                'Email' => 'john.doe@example.com',
                'Reporting_Manager' => 78910,
                'Role' => 3
            ]);

            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Set up expectations for the statement
            $stmtMock->expects($this->once())
             ->method('execute');
    
            // Set up expectations for the PDO mock
            $fetchReturns = array_merge($mockEmployees, [false]); // Merging mock employees and false
            $stmtMock->expects($this->exactly($totalEmployeeCount + 1)) // +1 for the final false return
             ->method('fetch')
             ->willReturnOnConsecutiveCalls(...$fetchReturns); // Unpack the merged array
            
            $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM employee WHERE Dept = :dept')
            ->willReturn($stmtMock);

            // Mock the connection manager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                     ->method('getConnection')
                     ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Act
            $result = $employeeDAO->retrieveEmployeesInSameDept($dept);
    
            // Assert
            $this->assertCount(64, $result);
        }

        public function test_retrieveEmployeesInSameDept_negative(){

            $totalEmployeeCount = 0;
            $dept = 'Kendo';
            $mockEmployees = array_fill(0, $totalEmployeeCount, [
                'Staff_ID' => 123456,
                'Staff_FName' => 'John',
                'Staff_LName' => 'Doe',
                'Dept' => 'Sales',
                'Position' => 'Sales Executive',
                'Country' => 'USA',
                'Email' => 'john.doe@example.com',
                'Reporting_Manager' => 78910,
                'Role' => 3
            ]);

            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Set up expectations for the statement
            $stmtMock->expects($this->once())
             ->method('execute');
    
            // Set up expectations for the PDO mock
            $fetchReturns = array_merge($mockEmployees, [false]); // Merging mock employees and false
            $stmtMock->expects($this->exactly($totalEmployeeCount + 1)) // +1 for the final false return
             ->method('fetch')
             ->willReturnOnConsecutiveCalls(...$fetchReturns); // Unpack the merged array
            
            $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM employee WHERE Dept = :dept')
            ->willReturn($stmtMock);

            // Mock the connection manager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                     ->method('getConnection')
                     ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Act
            $result = $employeeDAO->retrieveEmployeesInSameDept($dept);
    
            // Assert
            $this->assertCount(0, $result);
        }

        // retrieveUnderlings() --> ED_6 | ED_7 | ED_8
        

// paste into terminal: php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/EmployeeDAOTest.php
    }
?>