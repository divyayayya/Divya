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

        // retrieveUnderlings() --> ED_6 | ED_7 
        public function test_retrieveUnderlings_positive(){

            $userID = 140008;
            $expectedUnderlingCount = 11;
            $mockEmployees = array_fill(0, $expectedUnderlingCount, [
                'Staff_ID' => 123456,
                'Staff_FName' => 'Mike',
                'Staff_LName' => 'Oxmaul',
                'Dept' => 'Sales',
                'Position' => 'Sales Executive',
                'Country' => 'USA',
                'Email' => 'john.doe@example.com',
                'Reporting_Manager' => 140008,
                'Role' => 3
            ]);

            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Set up expectations for the statement
            $stmtMock->expects($this->once())
             ->method('execute');
    
            // Set up expectations for the PDO mock
            $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($mockEmployees); // Unpack the merged array
            
            $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM employee WHERE Reporting_Manager = :userID')
            ->willReturn($stmtMock);

            // Mock the connection manager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                     ->method('getConnection')
                     ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Act
            $result = $employeeDAO->retrieveUnderlings($userID);
    
            // Assert
            $this->assertCount(11, $result);
        }

        public function test_retrieveUnderlings_negative(){

            $userID = 140880;
            $expectedUnderlingCount = 0;
            $mockEmployees = array_fill(0, $expectedUnderlingCount, [
                'Staff_ID' => 123456,
                'Staff_FName' => 'Mike',
                'Staff_LName' => 'Oxmaul',
                'Dept' => 'Sales',
                'Position' => 'Sales Executive',
                'Country' => 'USA',
                'Email' => 'john.doe@example.com',
                'Reporting_Manager' => 140008,
                'Role' => 3
            ]);

            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Set up expectations for the statement
            $stmtMock->expects($this->once())
             ->method('execute');
    
            // Set up expectations for the PDO mock
            $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($mockEmployees); // Unpack the merged array
            
            $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM employee WHERE Reporting_Manager = :userID')
            ->willReturn($stmtMock);

            // Mock the connection manager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                     ->method('getConnection')
                     ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Act
            $result = $employeeDAO->retrieveUnderlings($userID);
    
            // Assert
            $this->assertCount(0, $result);
        }

        // getAllDepartments() --> ED_8
        public function test_getAllDepartments(){
            // Step 1: Set Up Mock Data
            $mockDepartments = [
                ['Dept' => 'CEO'],
                ['Dept' => 'Sales'],
                ['Dept' => 'Solutioning'],
                ['Dept' => 'Engineering'],
                ['Dept' => 'Finance'],
                ['Dept' => 'Consultancy'],
                ['Dept' => 'IT'],
            ];

            // Step 2: Mock Database Interactions
            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Step 3: Configure Mock Behavior
            $stmtMock->expects($this->once())
                        ->method('execute')
                        ->willReturn(true);
            $stmtMock->expects($this->once())
                        ->method('fetchAll')
                        ->willReturn($mockDepartments);
            $pdoMock->expects($this->once())
                    ->method('prepare')
                    ->with('SELECT DISTINCT Dept FROM employee ORDER BY Dept')
                    ->willReturn($stmtMock);
    
            // Mock the ConnectionManager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                        ->method('getConnection')
                        ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Step 4: Execute the Method Under Test
            $result = $employeeDAO->getAllDepartments();
    
            // Step 5: Assert the Results
            $this->assertEquals($mockDepartments, $result);
        }

        // getAllPositions() --> ED_9
        public function test_getAllPositions(){
            // Step 1: Set Up Mock Data

            $positionTypes = 17;
            $mockPositions = array_fill(0, $positionTypes, ['Position' => 'Manager']);

            // Step 2: Mock Database Interactions
            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Step 3: Configure Mock Behavior
            $stmtMock->expects($this->once())
                        ->method('execute')
                        ->willReturn(true);
            $stmtMock->expects($this->once())
                        ->method('fetchAll')
                        ->willReturn($mockPositions);
            $pdoMock->expects($this->once())
                    ->method('prepare')
                    ->with('SELECT DISTINCT Position FROM employee ORDER BY Position')
                    ->willReturn($stmtMock);
    
            // Mock the ConnectionManager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                        ->method('getConnection')
                        ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Step 4: Execute the Method Under Test
            $result = $employeeDAO->getAllPositions();
    
            // Step 5: Assert the Results
            $this->assertCount(17, $result);
        } 

        // retrieveEmployeesByDeptAndPositions() --> ED_10 | ED_11 | ED_12
        public function test_retrieveEmployeesByDeptAndPosition_positive1(){
            // Step 1: Set Up Mock Data
            $dept = 'Sales';
            $pos = 'Sales Manager';
            $mockEmployeesDeptPos = [
                [
                    'Staff_ID' => 140008,
                    'Staff_FName' => 'Jaclyn',
                    'Staff_LName' => 'Lee',
                    'Position' => 'Sales Manager',
                    'Country' => 'Singapore',
                    'Email' => 'Jaclyn.Lee@allinone.com.sg',
                ],
                [
                    'Staff_ID' => 140103,
                    'Staff_FName' => 'Sophia',
                    'Staff_LName' => 'Toh',
                    'Position' => 'Sales Manager',
                    'Country' => 'Singapore',
                    'Email' => 'Sophia.Toh@allinone.com.sg',
                ],
                [
                    'Staff_ID' => 140879,
                    'Staff_FName' => 'Siti',
                    'Staff_LName' => 'Abdullah',
                    'Position' => 'Sales Manager',
                    'Country' => 'Singapore',
                    'Email' => 'Siti.Abdullah@allinone.com.sg',
                ],
                [
                    'Staff_ID' => 140894,
                    'Staff_FName' => 'Rahim',
                    'Staff_LName' => 'Khalid',
                    'Position' => 'Sales Manager',
                    'Country' => 'Singapore',
                    'Email' => 'Rahim.Khalid@allinone.com.sg',
                ],
                [
                    'Staff_ID' => 140944,
                    'Staff_FName' => 'Yee',
                    'Staff_LName' => 'Lim',
                    'Position' => 'Sales Manager',
                    'Country' => 'Singapore',
                    'Email' => 'Yee.Lim@allinone.com.sg',
                ]
            ];

            // Step 2: Mock Database Interactions
            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
            
            // Step 3: Configure Mock Behavior
            $stmtMock->expects($this->once())
                ->method('execute')
                ->willReturn(true);

            // Set the fetchAll behavior to return the mock data
            $stmtMock->expects($this->once())
                ->method('fetchAll')
                ->willReturn($mockEmployeesDeptPos);

            // Configure the prepare method to return the mock statement
            $pdoMock->expects($this->once())
                ->method('prepare')
                ->with('SELECT Staff_ID, Staff_FName, Staff_LName, Position, Country, Email FROM employee WHERE Dept = :department AND Position = :position')
                ->willReturn($stmtMock);
            
            // Mock the ConnectionManager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                ->method('getConnection')
                ->willReturn($pdoMock);

            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);

            // Step 4: Execute the Method Under Test
            $result = $employeeDAO->retrieveEmployeesByDeptAndPosition($dept, $pos);

            // Step 5: Assert the Results
            $this->assertEquals($mockEmployeesDeptPos, $result);
        }

        public function test_retrieveEmployeesByDeptAndPosition_positive2(){
            // Step 1: Set Up Mock Data
            $dept = 'Consultancy';
            $mockEmployeesDeptPos = $employees = [
                [
                    'Staff_ID' => 180001,
                    'Staff_FName' => 'Ernst',
                    'Staff_LName' => 'Sim',
                    'Position' => 'Director',
                    'Country' => 'Singapore',
                    'Email' => 'Ernst.Sim@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180002,
                    'Staff_FName' => 'Rithy',
                    'Staff_LName' => 'Chong',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Rithy.Chong@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180003,
                    'Staff_FName' => 'Mani',
                    'Staff_LName' => 'Phalp',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Mani.Phalp@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180004,
                    'Staff_FName' => 'Sokunthea',
                    'Staff_LName' => 'Beng',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Sokunthea.Beng@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180005,
                    'Staff_FName' => 'Mani',
                    'Staff_LName' => 'Pheap',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Mani.Pheap@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180006,
                    'Staff_FName' => 'Somnang',
                    'Staff_LName' => 'Harun',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Somnang.Harun@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180010,
                    'Staff_FName' => 'Samsul',
                    'Staff_LName' => 'Rahman',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Samsul.Rahman@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180011,
                    'Staff_FName' => 'Bui Lui',
                    'Staff_LName' => 'Phan',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Bui Lui.Phan@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180012,
                    'Staff_FName' => 'Ji',
                    'Staff_LName' => 'Khung',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Ji.Khung@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180013,
                    'Staff_FName' => 'Rahim',
                    'Staff_LName' => 'Pich',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Rahim.Pich@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180014,
                    'Staff_FName' => 'Dewi',
                    'Staff_LName' => 'Hoang',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Dewi.Hoang@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180015,
                    'Staff_FName' => 'Sokha',
                    'Staff_LName' => 'Kumar',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Sokha.Kumar@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180019,
                    'Staff_FName' => 'Tuan',
                    'Staff_LName' => 'Le',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Tuan.Le@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180020,
                    'Staff_FName' => 'Bao',
                    'Staff_LName' => 'Seng',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Bao.Seng@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180021,
                    'Staff_FName' => 'Amara',
                    'Staff_LName' => 'Kesavan',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Amara.Kesavan@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180022,
                    'Staff_FName' => 'Srey',
                    'Staff_LName' => 'Ahmad',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Srey.Ahmad@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180023,
                    'Staff_FName' => 'Arifi',
                    'Staff_LName' => 'Sok',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Arifi.Sok@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180025,
                    'Staff_FName' => 'Chin',
                    'Staff_LName' => 'Pham',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Chin.Pham@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180026,
                    'Staff_FName' => 'Siti',
                    'Staff_LName' => 'Suon',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Siti.Suon@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180027,
                    'Staff_FName' => 'Siti',
                    'Staff_LName' => 'Vo',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Siti.Vo@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180029,
                    'Staff_FName' => 'Chandra',
                    'Staff_LName' => 'Nguyen',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Chandra.Nguyen@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180030,
                    'Staff_FName' => 'Rina',
                    'Staff_LName' => 'Singh',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Rina.Singh@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180032,
                    'Staff_FName' => 'Chandara',
                    'Staff_LName' => 'Yong',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Chandara.Yong@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180033,
                    'Staff_FName' => 'Heng Meng',
                    'Staff_LName' => 'Sou',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Heng Meng.Sou@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180034,
                    'Staff_FName' => 'Priya',
                    'Staff_LName' => 'Lim',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Priya.Lim@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180036,
                    'Staff_FName' => 'Ngoc',
                    'Staff_LName' => 'Sun',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Ngoc.Sun@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180037,
                    'Staff_FName' => 'Manoj',
                    'Staff_LName' => 'Mao',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Manoj.Mao@allinone.com.sg'
                ],
                [
                    'Staff_ID' => 180038,
                    'Staff_FName' => 'Somi',
                    'Staff_LName' => 'Seng',
                    'Position' => 'Counsultant',
                    'Country' => 'Singapore',
                    'Email' => 'Somi.Seng@allinone.com.sg'
                ]
            ];
            
            // Step 2: Mock Database Interactions
            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Step 3: Configure Mock Behavior
            $stmtMock->expects($this->once())
                        ->method('execute')
                        ->willReturn(true);
            $stmtMock->expects($this->once())
                        ->method('fetchAll')
                        ->willReturn($mockEmployeesDeptPos);
            $pdoMock->expects($this->once())
                    ->method('prepare')
                    ->with('SELECT Staff_ID, Staff_FName, Staff_LName, Position, Country, Email FROM employee WHERE Dept = :department')
                    ->willReturn($stmtMock);
    
            // Mock the ConnectionManager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                        ->method('getConnection')
                        ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Step 4: Execute the Method Under Test
            $result = $employeeDAO->retrieveEmployeesByDeptAndPosition($dept);
    
            // Step 5: Assert the Results
            $this->assertEquals($mockEmployeesDeptPos, $result);
        }

        public function test_retrieveEmployeesByDeptAndPosition_negative(){
            // Step 1: Set Up Mock Data
            $dept = 'Sales';
            $pos = 'Sales Overlord';
            $mockEmployeesDeptPos = [];

            // Step 2: Mock Database Interactions
            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
            
            // Step 3: Configure Mock Behavior
            $stmtMock->expects($this->once())
                ->method('execute')
                ->willReturn(true);

            // Set the fetchAll behavior to return the mock data
            $stmtMock->expects($this->once())
                ->method('fetchAll')
                ->willReturn($mockEmployeesDeptPos);

            // Configure the prepare method to return the mock statement
            $pdoMock->expects($this->once())
                ->method('prepare')
                ->with('SELECT Staff_ID, Staff_FName, Staff_LName, Position, Country, Email FROM employee WHERE Dept = :department AND Position = :position')
                ->willReturn($stmtMock);
            
            // Mock the ConnectionManager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                ->method('getConnection')
                ->willReturn($pdoMock);

            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);

            // Step 4: Execute the Method Under Test
            $result = $employeeDAO->retrieveEmployeesByDeptAndPosition($dept, $pos);

            // Step 5: Assert the Results
            $this->assertEquals($mockEmployeesDeptPos, $result);
        }

        // retrieveArrangementDetailsByDate() --> ED_13 | ED_14
        public function test_retrieveArrangementDetailsByDate_positive(){
            $staffID = 140008;
            $arrangementDate = '2024-11-30';

            $mockExpected = ['WFH', 'Full Day'];
            
            // Step 2: Mock Database Interactions
            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Step 3: Configure Mock Behavior
            $stmtMock->expects($this->once())
                        ->method('execute')
                        ->willReturn(true);
            $stmtMock->expects($this->once())
                        ->method('fetch')
                        ->willReturn($mockExpected);
            $pdoMock->expects($this->once())
                    ->method('prepare')
                    ->with('SELECT Working_Location, Arrangement_Time FROM employee_arrangement WHERE Staff_ID = :staffID AND Arrangement_Date = :arrangement_date')
                    ->willReturn($stmtMock);
    
            // Mock the ConnectionManager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                        ->method('getConnection')
                        ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Step 4: Execute the Method Under Test
            $result = $employeeDAO->retrieveArrangementDetailsByDate($staffID, $arrangementDate);

            // Step 5: Assert the Results
            $this->assertEquals($mockExpected, $result);           
        }

        public function test_retrieveArrangementDetailsByDate_negative(){
            $staffID = "lalala";
            $arrangementDate = 'hehe';

            $mockExpected = [];
            
            // Step 2: Mock Database Interactions
            $pdoMock = $this->createMock(PDO::class);
            $stmtMock = $this->createMock(PDOStatement::class);
    
            // Step 3: Configure Mock Behavior
            $stmtMock->expects($this->once())
                        ->method('execute')
                        ->willReturn(true);
            $stmtMock->expects($this->once())
                        ->method('fetch')
                        ->willReturn($mockExpected);
            $pdoMock->expects($this->once())
                    ->method('prepare')
                    ->with('SELECT Working_Location, Arrangement_Time FROM employee_arrangement WHERE Staff_ID = :staffID AND Arrangement_Date = :arrangement_date')
                    ->willReturn($stmtMock);
    
            // Mock the ConnectionManager to return the mocked PDO
            $connMock = $this->createMock(ConnectionManager::class);
            $connMock->expects($this->once())
                        ->method('getConnection')
                        ->willReturn($pdoMock);
    
            // Inject the mock connection manager into EmployeeDAO
            $employeeDAO = new EmployeeDAO($connMock);
    
            // Step 4: Execute the Method Under Test
            $result = $employeeDAO->retrieveArrangementDetailsByDate($staffID, $arrangementDate);

            // Step 5: Assert the Results
            $this->assertEquals($mockExpected, $result);           
        }

        // searchEmployee() --> ED_15 | ED_16
        
// paste into terminal: php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/EmployeeDAOTest.php
    }
?>