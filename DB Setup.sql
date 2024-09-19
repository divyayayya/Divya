#Employee
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
#Load Data from employee.csv into DB
LOAD DATA INFILE "C:\\wamp64\\www\\GitHub\\Divya\\employeenew.csv"
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
    Arrangement_Date DATE NOT NULL,
    Working_Arrangement VARCHAR(50) NOT NULL,
    CONSTRAINT employee_arrangement_pk PRIMARY KEY (Staff_ID, Arrangement_Date),
    CONSTRAINT employee_arrangement_fk FOREIGN KEY (Staff_ID) REFERENCES employee(Staff_ID) ON DELETE CASCADE
);

Select * from employee_arrangement;