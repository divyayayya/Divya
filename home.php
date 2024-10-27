<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.css" />
    <title>HomePage</title>

    <style>
        /* Navbar Styling */
        .navbar {
            background-color: black;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 10vh; /* Adjust height relative to viewport */
            padding: 0 5vw; /* Padding adjusted for responsiveness */
            margin-bottom: 20px;
            border-radius: 5px;
            position: relative;
        }

        .navbar img {
            height: 8vh; /* Responsive height */
        }

        .dropdown {
            float: left;
            overflow: visible;
        }

        .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 20px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 100px;
            max-width: 150px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
            color: black;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .content {
            padding: 20px;
            margin-top: 30px;
        }

        /* Table Styling */
        .table-responsive {
        max-width: 100%;
            overflow-x: auto; /* Allows horizontal scrolling on small screens */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
            font-size: 16px;
        }

        th {
            background-color: #f1f1f1;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .tooltip {
            font-size: 14px;
            color: #333;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 3px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            position: absolute;
            z-index: 1000;
            pointer-events: none;
        }

        /* Adjust padding and font size for smaller screens */
        @media (max-width: 768px) {
            th, td {
                padding: 10px;
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            th, td {
                padding: 8px;
                font-size: 12px;
            }
        }
        @media screen and (max-width: 480px) {
            th, td {
                font-size: 4vw;
            }

            .navbar img {
                height: 5vh;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <a href="login.php"><img src="images/logo.jpg" alt="Company Logo"></a> <!-- Link to homepage -->
        <div class="dropdown">
            <button class="dropbtn">Actions</button>
            <div class="dropdown-content">
                <a href="my_requests.php">My Requests</a>
                <?php
                    // Display department-specific links based on user role and department
                    $userID = $_SESSION['userID'];
                    $userRole = $_SESSION['userRole'];

                    $dao = new EmployeeDAO;
                    $result = $dao->retrieveEmployeeInfo($userID);   
                    $employee = new Employee($result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], $result['Dept'], $result['Position'], $result['Country'], $result['Email'], $result['Reporting_Manager'], $result['Role']);
                    $userDept = $employee->getDept();
                    $dao_req = new RequestDAO;
                    $dao_req->rejectExpiredRequests();
                    $requests = $dao_req->retrieverequestInfo($userID);

                    if ($userRole != 2) {
                        if ($userDept == "HR" || $userDept == "CEO") { 
                            echo "<a href='deptDetails_HR.php'>Department Details</a>";
                            echo "<a href='pendingRequests.php'>Pending Requests</a>";
                        } else { 
                            echo "<a href='deptDetails.php'>Department Details</a>";
                            echo "<a href='pendingRequests.php'>Pending Requests</a>";
                        }
                    }
                ?>
                <a href="withdraw_wfh.php">Withdraw Approved</a>
                <a href="location_details.php">View where your colleagues are working</a>
            </div>
        </div>
    </div>

    <?php
        #Display User Details
        echo "<table border=1>";
        echo "<tr><th>ID</th><th>Name</th><th>Department</th><th>Position</th><th>Country</th><th>Email</th></tr>";
        echo "<tr><td>{$employee->getID()}</td><td>{$employee->getStaffName()}</td><td>{$employee->getDept()}</td><td>{$employee->getPosition()}</td><td>{$employee->getCountry()}</td><td>{$employee->getEmail()}</td></tr></table>";

        echo "</br></br><h1>Calendar</h1>";
        $requests_json = json_encode($requests);
    ?>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        const requests = <?php echo $requests_json; ?>;

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Convert requests data into FullCalendar event objects
            const events = requests.map(function(request) {
                if (request.Request_Status === 'Approved' || request.Request_Status === 'Pending') {
                    return {
                        title: request.Working_Arrangement,
                        start: request.Arrangement_Date,
                        time: request.Arrangement_Time,
                        reason: request.Reason,
                        status: request.Request_Status,
                        backgroundColor: (request.Request_Status === 'Pending') ? '#edb95e' : '',
                        extendedProps: {
                            time: request.Arrangement_Time,
                            reason: request.Reason,
                            status: request.Request_Status
                        }
                    };
                }
            }).filter(event => event !== undefined);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                // initialView: 'multiMonthFourMonth',
                // views: {
                //     multiMonthFourMonth: {
                //     type: 'multiMonth',
                //     duration: { months: 3 }
                //     }
                // },
                initialView: 'dayGridMonth',
                selectable: true,
                events: events, 
                dateClick: function() {
                    window.open('location_details.php', target='_blank');
                },
                eventMouseEnter: function(info) {
                    var tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.innerHTML = info.event.title + "<br>" + info.event.extendedProps.time + "<br>" + "Reason: " + info.event.extendedProps.reason + "<br>" + "Status: " + info.event.extendedProps.status;

                    document.body.appendChild(tooltip);
                    tooltip.style.position = 'absolute';
                    tooltip.style.top = info.jsEvent.pageY + 'px';
                    tooltip.style.left = info.jsEvent.pageX + 'px';
                    tooltip.style.backgroundColor = '#f9f9f9';
                    tooltip.style.padding = '5px';
                    tooltip.style.border = '1px solid #ccc';
                },
                eventMouseLeave: function() {
                    var tooltip = document.querySelector('.tooltip');
                    if (tooltip) {
                        tooltip.remove();
                    }
                }
            });
            calendar.render();
        });
    </script>
    <div id='calendar'></div>
</body>
</html>
