
DROP SCHEMA IF EXISTS employeeDB;
CREATE SCHEMA employeeDB;
USE employeeDB;

#create tables
CREATE TABLE employee (
    Staff_ID INT NOT NULL,
    Staff_FName VARCHAR(50) NOT NULL,
    Staff_LName VARCHAR(50) NOT NULL,
    Dept VARCHAR(50) NOT NULL,
    Position VARCHAR(50) NOT NULL,
    Country VARCHAR(50) NOT NULL,
    Email VARCHAR(50) NOT NULL,
    Reporting_Manager INT NOT NULL,
    Role INT NOT NULL,
    CONSTRAINT employee_pk PRIMARY KEY (Staff_ID),
    CONSTRAINT employee_fk FOREIGN KEY (Reporting_Manager) REFERENCES employee(Staff_ID)
);

SET FOREIGN_KEY_CHECKS=0;
#NOTE THAT U WILL NEED TO CHANGE THE PATH FILE TO UR OWN PATH
#Load Data from employeenew.csv into DB
LOAD DATA INFILE "C:/wamp64/www/GitHub/Divya/employeenew.csv"
INTO TABLE employee
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"' 
LINES TERMINATED BY '\r\n' -- On Windows, the line endings are often '\r\n'
IGNORE 1 LINES -- Ignore the header row if it exists
(Staff_ID, Staff_FName, Staff_LName, Dept, Position, Country, Email, Reporting_Manager, Role);

SET FOREIGN_KEY_CHECKS=1;


#Create Employee_Arrangement Table
CREATE TABLE employee_arrangement (
    Staff_ID INT NOT NULL,
    Request_ID INT NOT NULL,
    Arrangement_Date DATE NOT NULL,
    Working_Arrangement VARCHAR(50) NOT NULL,
    Reason VARCHAR(255) NULL,
    Request_Status VARCHAR(50) NOT NULL,
    Working_Location VARCHAR(50) NOT NULL,
    CONSTRAINT employee_arrangement_pk PRIMARY KEY (Staff_ID, Request_ID, Arrangement_Date),
    CONSTRAINT employee_arrangement_fk FOREIGN KEY (Staff_ID) REFERENCES employee(Staff_ID) ON DELETE CASCADE
);

INSERT INTO employee_arrangement (Staff_ID, Request_ID, Arrangement_Date, Working_Arrangement, Reason, Request_Status, Working_Location)
VALUES 
(150148, 1, '2024-01-01', 'WFH', "", 'Approved','Home') ,
(150148, 1, '2024-01-08', 'WFH', "", 'Approved','Home') ,
(150148, 2, '2024-01-15', 'WFH', '', 'Pending','Home') ,
(150148, 3, '2024-01-22', 'WFH', 'want to sleep more', 'Rejected','In-Office'),
(150148, 4, '2024-01-29', 'WFH', 'Carousel deal at home', 'Withdrawn','In-Office'),
(140878, 5, '2024-10-15', 'WFH', 'Take care of baby', 'Approved','Home');

CREATE TABLE deletion_request (
    Deletion_ID INT AUTO_INCREMENT PRIMARY KEY,
    Staff_ID INT NOT NULL,                     
    Request_ID INT NOT NULL,                   
    Deletion_Date DATE NOT NULL,               
    Reason TEXT NOT NULL,                      
    Status VARCHAR(50) NOT NULL DEFAULT 'Pending', -- Status of the request (Pending/Approved/Rejected)
    CONSTRAINT deletion_request_fk_staff FOREIGN KEY (Staff_ID) REFERENCES employee(Staff_ID) ON DELETE CASCADE,
    CONSTRAINT deletion_request_fk_arrangement FOREIGN KEY (Request_ID) REFERENCES employee_arrangement(Request_ID) ON DELETE CASCADE
);
