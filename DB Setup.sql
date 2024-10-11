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
LOAD DATA INFILE "C:\\wamp64\\www\\SPM\\Divya\\employeenew.csv"
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
    Department VARCHAR(256) NOT NULL,
    Request_ID INT NOT NULL,
    Arrangement_Date DATE NOT NULL,
    Working_Arrangement VARCHAR(50) NOT NULL,
    Reason VARCHAR(255) NULL,
    Request_Status VARCHAR(50) NOT NULL,
    Working_Location VARCHAR(50) NOT NULL,
    Rejection_Reason VARCHAR(255) NULL,
    CONSTRAINT employee_arrangement_pk PRIMARY KEY (Staff_ID, Request_ID, Arrangement_Date),
    CONSTRAINT employee_arrangement_fk FOREIGN KEY (Staff_ID) REFERENCES employee(Staff_ID) ON DELETE CASCADE
);

INSERT INTO employee_arrangement (Staff_ID, Department, Request_ID, Arrangement_Date, Working_Arrangement, Reason, Request_Status, Working_Location, Rejection_Reason)
VALUES 
(140001, 'Sales', 1, '2024-10-15', 'WFH', 'Need to take care of family', 'Approved', 'Home', NULL),
(140002, 'Sales', 2, '2024-10-16', 'WFH', 'Working on a special project', 'Pending', 'Home', NULL),
(140003, 'Sales', 3, '2024-10-17', 'WFH', 'Need to concentrate on tasks', 'Approved', 'Home', NULL),
(140008, 'Sales', 4, '2024-10-18', 'WFH', 'Prefer to work from home', 'Pending', 'Home', NULL),
(140894, 'Sales', 5, '2024-10-19', 'WFH', 'Medical appointment', 'Approved', 'Home', NULL),
(140880, 'Sales', 6, '2024-10-20', 'WFH', 'Need to focus on training', 'Pending', 'Home', NULL),
(140888, 'Sales', 7, '2024-10-21', 'WFH', '', 'Rejected', 'In-Office', 'Team needs physical presence'),
(140890, 'Sales', 8, '2024-10-22', 'WFH', 'Family commitment', 'Approved', 'Home', NULL),
(140879, 'Sales', 9, '2024-10-23', 'WFH', 'Personal reasons', 'Pending', 'Home', NULL),
(140891, 'Sales', 10, '2024-10-24', 'WFH', 'Need to take care of pet', 'Approved', 'Home', NULL),
(140894, 'Sales', 11, '2024-10-25', 'WFH', 'Focus on personal project', 'Pending', 'Home', NULL),
(140901, 'Sales', 12, '2024-10-26', 'WFH', 'Family gathering', 'Approved', 'Home', NULL),
(140901, 'Sales', 13, '2024-10-27', 'WFH', 'Home renovations', 'Pending', 'Home', NULL),
(140904, 'Sales', 14, '2024-10-28', 'WFH', 'Need to focus', 'Approved', 'Home', NULL),
(140905, 'Sales', 15, '2024-10-29', 'WFH', 'Have a visitor', 'Rejected', 'In-Office', 'Not Valid'),
(140905, 'Sales', 16, '2024-10-30', 'WFH', 'Working on urgent tasks', 'Approved', 'Home', NULL),
(140905, 'Sales', 17, '2024-10-31', 'WFH', 'Child care', 'Approved', 'Home', NULL),
(140908, 'Sales', 18, '2024-11-01', 'WFH', 'Need quiet time', 'Pending', 'Home', NULL),
(140909, 'Sales', 19, '2024-11-02', 'WFH', 'Health reasons', 'Approved', 'Home', NULL),
(140910, 'Sales', 20, '2024-11-03', 'WFH', 'Need to rest', 'Pending', 'Home', NULL),
(140911, 'Sales', 21, '2024-11-04', 'WFH', 'Personal matters', 'Approved', 'Home', NULL),
(140912, 'Sales', 22, '2024-11-05', 'WFH', 'Emergency', 'Rejected', 'In-Office', 'Not Valid'),
(140912, 'Sales', 23, '2024-11-06', 'WFH', 'Family event', 'Pending', 'Home', NULL),
(140911, 'Sales', 24, '2024-11-07', 'WFH', 'Special family gathering', 'Approved', 'Home', NULL),
(140911, 'Sales', 25, '2024-11-08', 'WFH', 'Need to focus', 'Pending', 'Home', NULL),
(140912, 'Sales', 26, '2024-11-09', 'WFH', 'Medical reasons', 'Approved', 'Home', NULL),
(140917, 'Sales', 27, '2024-11-10', 'WFH', 'Taking care of a relative', 'Pending', 'Home', NULL),
(140918, 'Sales', 28, '2024-11-11', 'WFH', 'Home repairs', 'Approved', 'Home', NULL),
(140919, 'Sales', 29, '2024-11-12', 'WFH', 'Need quiet environment', 'Rejected', 'In-Office', 'Not Valid'),
(140919, 'Sales', 30, '2024-11-13', 'WFH', 'Working on presentation', 'Pending', 'Home', NULL),
(140919, 'Sales', 31, '2024-11-14', 'WFH', 'Health appointment', 'Approved', 'Home', NULL),
(140917, 'Sales', 32, '2024-11-15', 'WFH', 'Need to manage home affairs', 'Pending', 'Home', NULL),
(140918, 'Sales', 33, '2024-11-16', 'WFH', 'Family commitments', 'Approved', 'Home', NULL),
(140924, 'Sales', 34, '2024-11-17', 'WFH', 'Child school project', 'Pending', 'Home', NULL),
(140925, 'Sales', 35, '2024-11-18', 'WFH', 'Major family issue', 'Approved', 'Home', NULL),
(140926, 'Sales', 36, '2024-11-19', 'WFH', 'Need to work on deadlines', 'Pending', 'Home', NULL),
(140927, 'Sales', 37, '2024-11-20', 'WFH', 'Quiet work environment needed', 'Approved', 'Home', NULL),
(140928, 'Sales', 38, '2024-11-21', 'WFH', 'Need to reflect', 'Rejected', 'In-Office', 'Team collaboration required'),
(140929, 'Sales', 39, '2024-11-22', 'WFH', 'Urgent project work', 'Approved', 'Home', NULL),
(140929, 'Sales', 40, '2024-11-23', 'WFH', 'Children at home', 'Pending', 'Home', NULL),
(140928, 'Sales', 41, '2024-11-24', 'WFH', 'Important family meeting', 'Approved', 'Home', NULL),
(140927, 'Sales', 42, '2024-11-25', 'WFH', 'Home situation', 'Pending', 'Home', NULL),
(140933, 'Sales', 43, '2024-11-26', 'WFH', 'Need to help family', 'Approved', 'Home', NULL),
(140934, 'Sales', 44, '2024-11-27', 'WFH', 'Moving houses', 'Pending', 'Home', NULL),
(140935, 'Sales', 45, '2024-11-28', 'WFH', 'Working on a big project', 'Approved', 'Home', NULL),
(140933, 'Sales', 46, '2024-11-29', 'WFH', 'Child care duties', 'Rejected', 'In-Office', 'Team needs physical presence'),
(140933, 'Sales', 47, '2024-11-30', 'WFH', 'Health issues', 'Approved', 'Home', NULL),
(140938, 'Sales', 48, '2024-12-01', 'WFH', 'Quiet time needed', 'Pending', 'Home', NULL),
(140938, 'Sales', 49, '2024-12-02', 'WFH', 'Need to prepare for meetings', 'Approved', 'Home', NULL),
(140940, 'Sales', 50, '2024-12-03', 'WFH', 'Urgent family matters', 'Pending', 'Home', NULL),
(140941, 'Sales', 51, '2024-12-04', 'WFH', 'Working on reports', 'Approved', 'Home', NULL),
(140942, 'Sales', 52, '2024-12-05', 'WFH', 'Child care', 'Pending', 'Home', NULL),
(140942, 'Sales', 53, '2024-12-06', 'WFH', 'Need to stay home', 'Approved', 'Home', NULL),
(140942, 'Sales', 54, '2024-12-07', 'WFH', 'Family obligation', 'Pending', 'Home', NULL),
(140945, 'Sales', 55, '2024-12-08', 'WFH', 'Need focus time', 'Approved', 'Home', NULL),
(150148, 'Engineering', 56, '2024-01-01', 'WFH', "", 'Approved','Home', NULL) ,
(150148, 'Engineering', 57, '2024-01-08', 'WFH', "", 'Approved','Home', NULL) ,
(150148, 'Engineering', 58, '2024-01-15', 'WFH', '', 'Pending','Home', NULL) ,
(150148, 'Engineering', 59, '2024-01-22', 'WFH', 'want to sleep more', 'Rejected','In-Office', 'Not Valid'),
(150148, 'Engineering', 60, '2024-01-29', 'WFH', 'Carousel deal at home', 'Withdrawn','In-Office', 'Canceled'),
(140878, 'Sales', 61, '2024-10-15', 'WFH', 'Take care of baby', 'Approved','Home', NULL),
(140880, 'Sales', 62, '2024-10-31', 'WFH', 'Family', 'Pending', 'Home', NULL),
(140881, 'Sales', 63, '2024-11-03', 'WFH', 'Take care of baby', 'Pending', 'Home', NULL),
(140882, 'Sales', 64, '2024-11-07', 'WFH', 'Go out with gf', 'Pending', 'Home', NULL),
(140883, 'Sales', 65, '2024-12-05', 'WFH', 'Lazy to work in office', 'Pending', 'Home', NULL),
(140887, 'Sales', 66, '2024-12-01', 'WFH', 'Take care of baby', 'Pending', 'Home', NULL),
(140890, 'Sales', 67, '2024-12-24', 'WFH', 'Family', 'Pending', 'Home', NULL),
(140891, 'Sales', 68, '2024-12-25', 'WFH', 'Family', 'Pending', 'Home', NULL);


SET FOREIGN_KEY_CHECKS=1;