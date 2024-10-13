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
    Arrangement_Time VARCHAR(50) NOT NULL,
    Reason VARCHAR(255) NULL,
    Request_Status VARCHAR(50) NOT NULL,
    Working_Location VARCHAR(50) NOT NULL,
    Rejection_Reason VARCHAR(255) NULL,
    CONSTRAINT employee_arrangement_pk PRIMARY KEY (Staff_ID, Request_ID, Arrangement_Date),
    CONSTRAINT employee_arrangement_fk FOREIGN KEY (Staff_ID) REFERENCES employee(Staff_ID) ON DELETE CASCADE
);

INSERT INTO employee_arrangement (Staff_ID, Department, Request_ID, Arrangement_Date, Working_Arrangement, Arrangement_Time, Reason, Request_Status, Working_Location, Rejection_Reason)
VALUES 
/*
-- Query: select * from employee_arrangement order by request_id asc
LIMIT 0, 2000

-- Date: 2024-10-13 21:44
*/
VALUES (140001,'Sales',1,'2024-10-15','WFH','PM','Need to take care of family','Approved','Home',NULL),
VALUES (140002,'Sales',2,'2024-10-16','WFH','AM','Working on a special project','Pending','Home',NULL),
VALUES (140003,'Sales',3,'2024-10-17','WFH','PM','Need to concentrate on tasks','Approved','Home',NULL),
VALUES (140008,'Sales',4,'2024-10-18','WFH','AM','Prefer to work from home','Pending','Home',NULL),
VALUES (140894,'Sales',5,'2024-10-19','WFH','AM','Medical appointment','Approved','Home',NULL),
VALUES (140880,'Sales',6,'2024-10-20','WFH','PM','Need to focus on training','Pending','Home',NULL),
VALUES (140888,'Sales',7,'2024-10-21','WFH','AM','','Rejected','In-Office','Team needs physical presence'),
VALUES (140890,'Sales',8,'2024-10-22','WFH','AM','Family commitment','Approved','Home',NULL),
VALUES (140879,'Sales',9,'2024-10-23','WFH','AM','Personal reasons','Pending','Home',NULL),
VALUES (140891,'Sales',10,'2024-10-24','WFH','PM','Need to take care of pet','Approved','Home',NULL),
VALUES (140894,'Sales',11,'2024-10-25','WFH','AM','Focus on personal project','Pending','Home',NULL),
VALUES (140901,'Sales',12,'2024-10-26','WFH','AM','Family gathering','Approved','Home',NULL),
VALUES (140901,'Sales',13,'2024-10-27','WFH','AM','Home renovations','Pending','Home',NULL),
VALUES (140904,'Sales',14,'2024-10-28','WFH','PM','Need to focus','Approved','Home',NULL),
VALUES (140905,'Sales',15,'2024-10-29','WFH','AM','Have a visitor','Rejected','In-Office','Not Valid'),
VALUES (140905,'Sales',16,'2024-10-30','WFH','AM','Working on urgent tasks','Approved','Home',NULL),
VALUES (140905,'Sales',17,'2024-10-31','WFH','AM','Child care','Approved','Home',NULL),
VALUES (140908,'Sales',18,'2024-11-01','WFH','PM','Need quiet time','Pending','Home',NULL),
VALUES (140909,'Sales',19,'2024-11-02','WFH','AM','Health reasons','Approved','Home',NULL),
VALUES (140910,'Sales',20,'2024-11-03','WFH','PM','Need to rest','Pending','Home',NULL),
VALUES (140911,'Sales',21,'2024-11-04','WFH','AM','Personal matters','Approved','Home',NULL),
VALUES (140912,'Sales',22,'2024-11-05','WFH','AM','Emergency','Rejected','In-Office','Not Valid'),
VALUES (140912,'Sales',23,'2024-11-06','WFH','AM','Family event','Pending','Home',NULL),
VALUES (140911,'Sales',24,'2024-11-07','WFH','AM','Special family gathering','Approved','Home',NULL),
VALUES (140911,'Sales',25,'2024-11-08','WFH','PM','Need to focus','Pending','Home',NULL),
VALUES (140912,'Sales',26,'2024-11-09','WFH','AM','Medical reasons','Approved','Home',NULL),
VALUES (140917,'Sales',27,'2024-11-10','WFH','AM','Taking care of a relative','Pending','Home',NULL),
VALUES (140918,'Sales',28,'2024-11-11','WFH','AM','Home repairs','Approved','Home',NULL),
VALUES (140919,'Sales',29,'2024-11-12','WFH','PM','Need quiet environment','Rejected','In-Office','Not Valid'),
VALUES (140919,'Sales',30,'2024-11-13','WFH','AM','Working on presentation','Pending','Home',NULL),
VALUES (140919,'Sales',31,'2024-11-14','WFH','AM','Health appointment','Approved','Home',NULL),
VALUES (140917,'Sales',32,'2024-11-15','WFH','PM','Need to manage home affairs','Pending','Home',NULL),
VALUES (140918,'Sales',33,'2024-11-16','WFH','AM','Family commitments','Approved','Home',NULL),
VALUES (140924,'Sales',34,'2024-11-17','WFH','AM','Child school project','Pending','Home',NULL),
VALUES (140925,'Sales',35,'2024-11-18','WFH','AM','Major family issue','Approved','Home',NULL),
VALUES (140926,'Sales',36,'2024-11-19','WFH','PM','Need to work on deadlines','Pending','Home',NULL),
VALUES (140927,'Sales',37,'2024-11-20','WFH','AM','Quiet work environment needed','Approved','Home',NULL),
VALUES (140928,'Sales',38,'2024-11-21','WFH','PM','Need to reflect','Rejected','In-Office','Team collaboration required'),
VALUES (140929,'Sales',39,'2024-11-22','WFH','AM','Urgent project work','Approved','Home',NULL),
VALUES (140929,'Sales',40,'2024-11-23','WFH','AM','Children at home','Pending','Home',NULL),
VALUES (140928,'Sales',41,'2024-11-24','WFH','AM','Important family meeting','Approved','Home',NULL),
VALUES (140927,'Sales',42,'2024-11-25','WFH','AM','Home situation','Pending','Home',NULL),
VALUES (140933,'Sales',43,'2024-11-26','WFH','PM','Need to help family','Approved','Home',NULL),
VALUES (140934,'Sales',44,'2024-11-27','WFH','AM','Moving houses','Pending','Home',NULL),
VALUES (140935,'Sales',45,'2024-11-28','WFH','AM','Working on a big project','Approved','Home',NULL),
VALUES (140933,'Sales',46,'2024-11-29','WFH','AM','Child care duties','Rejected','In-Office','Team needs physical presence'),
VALUES (140933,'Sales',47,'2024-11-30','WFH','AM','Health issues','Approved','Home',NULL),
VALUES (140938,'Sales',48,'2024-12-01','WFH','AM','Quiet time needed','Pending','Home',NULL),
VALUES (140938,'Sales',49,'2024-12-02','WFH','PM','Need to prepare for meetings','Approved','Home',NULL),
VALUES (140940,'Sales',50,'2024-12-03','WFH','AM','Urgent family matters','Pending','Home',NULL),
VALUES (140941,'Sales',51,'2024-12-04','WFH','AM','Working on reports','Approved','Home',NULL),
VALUES (140942,'Sales',52,'2024-12-05','WFH','AM','Child care','Pending','Home',NULL),
VALUES (140942,'Sales',53,'2024-12-06','WFH','PM','Need to stay home','Approved','Home',NULL),
VALUES (140942,'Sales',54,'2024-12-07','WFH','AM','Family obligation','Pending','Home',NULL),
VALUES (140945,'Sales',55,'2024-12-08','WFH','PM','Need focus time','Approved','Home',NULL),
VALUES (150148,'Engineering',56,'2024-01-01','WFH','Full Day','Visiting Father in Azerbaijan','Approved','Home',NULL),
VALUES (150148,'Engineering',57,'2024-01-08','WFH','Full Day','Visiting Mother in Liechtenstein','Approved','Home',NULL),
VALUES (150148,'Engineering',58,'2024-01-15','WFH','Full Day','Visiting Husband in Hawaii','Approved','Home',NULL),
VALUES (150148,'Engineering',59,'2024-01-22','WFH','AM','want to sleep more','Rejected','In-Office','Not Valid'),
VALUES (150148,'Engineering',60,'2024-01-29','WFH','AM','Carousel deal at home','Withdrawn','In-Office','Canceled'),
VALUES (140878,'Sales',61,'2024-10-15','WFH','AM','Take care of baby','Approved','Home',NULL),
VALUES (140880,'Sales',62,'2024-10-31','WFH','AM','Family','Pending','Home',NULL),
VALUES (140881,'Sales',63,'2024-11-03','WFH','AM','Take care of baby','Pending','Home',NULL),
VALUES (140882,'Sales',64,'2024-11-07','WFH','AM','Go out with gf','Pending','Home',NULL),
VALUES (140883,'Sales',65,'2024-12-05','WFH','AM','Lazy to work in office','Pending','Home',NULL),
VALUES (140887,'Sales',66,'2024-12-01','WFH','AM','Take care of baby','Pending','Home',NULL),
VALUES (140890,'Sales',67,'2024-12-24','WFH','AM','Family','Pending','Home',NULL),
VALUES (140891,'Sales',68,'2024-12-25','WFH','AM','Family','Pending','Home',NULL),
VALUES (150065,'Engineering',69,'2024-10-31','WFH','Full Day','Illegally trading diamonds in Congo','Approved','Home/ OOF',NULL),
VALUES (150148,'Engineering',70,'2024-10-15','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-10-16','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-10-22','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-10-23','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-10-29','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-10-30','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-11-05','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-11-06','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-11-12','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-11-13','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-11-19','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-11-20','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-11-26','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-11-27','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-12-03','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-12-04','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-12-10','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-12-11','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-12-17','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-12-18','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-12-24','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-12-25','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',70,'2024-12-31','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-10-14','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-10-21','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-10-22','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-10-28','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-10-29','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-11-04','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-11-05','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-11-11','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-11-12','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-11-18','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-11-19','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-11-25','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-11-26','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-12-02','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-12-03','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-12-09','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-12-10','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-12-16','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-12-17','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-12-23','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-12-24','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (140001,'Sales',93,'2024-12-30','WFH','Full Day','Government Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-10-16','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-10-17','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-10-23','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-10-24','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-10-30','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-10-31','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-11-06','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-11-07','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-11-13','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-11-14','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-11-20','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-11-21','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-11-27','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-11-28','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-12-04','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-12-05','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-12-11','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-12-12','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-12-18','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-12-19','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-12-25','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (180002,'Consultancy',115,'2024-12-26','WFH','Full Day','Govt Protocol','Approved','Home',NULL),
VALUES (150148,'Engineering',137,'2024-10-24','WFH','PM','Going to meet Cooper Koch','Pending','Home/ OOF',NULL),


SET FOREIGN_KEY_CHECKS=1;