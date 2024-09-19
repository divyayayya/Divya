<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- calendar css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.css" />
    <title>HomePage</title>
</head>
<body>

<?php

    #Display User Details
    $userID = $_SESSION['userID'];
    $userRole = $_SESSION['userRole'];

    $dao = new EmployeeDAO;
    $result = $dao->retrieveEmployeeInfo($userID);
    $employee = new Employee($result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], $result['Dept'], $result['Position'], $result['Country'], $result['Email'], $result['Reporting_Manager'], $result['Role']);
    $dao_req = new RequestDAO;
    $requests = $dao_req -> retrieverequestInfo($userID);
    // $arrangement = new Request($arrangements['Staff_ID'],$arrangements['Arrangement_Date'], $arrangements['Working_Arrangement'], $arrangements);
    echo "<h2 style='display: inline-block; margin-right: 20px;'>User Details</h2><a href='login.php' style='display: inline-block; vertical-align: middle;'>Sign Out</a>";

    echo "<table border=1>";
    echo "<tr><th>ID</th><th>Name</th><th>Department</th><th>Position</th><th>Country</th><th>Email</th></tr>";
    echo "<tr><td>{$employee->getID()}</td><td>{$employee->getStaffName()}</td><td>{$employee->getDept()}</td><td>{$employee->getPosition()}</td><td>{$employee->getCountry()}</td><td>{$employee->getEmail()}</td></tr></table>";

    // TESTING RETRIEVAL OF ARRANGEMENTS
    // echo "<br><br>";
    //     if(count($requests) > 1){
    //         echo "<table border=1>";
    //         echo "<tr><th>ID</th><th>Request ID</th><th>Date</th><th>Arrangement</th><th>Status</th></tr>";    
    //         foreach($requests as $request){
    //             echo "<tr><td>{$request['Staff_ID']}</td><td>{$request['Request_ID']}</td><td>{$request['Arrangement_Date']}</td><td>{$request['Working_Arrangement']}</td><td>{$request['Request_Status']}</td></tr>";
    //         }
    //     }
    //     else{
    //         echo '<p style="color: red;">No Requests Found</p>';
    //     }

    // echo "</table>";
    // END OF TESTING


    echo "</br>";
    $deptDetails = '';
    $deptRequests = '';

    if ($userRole != 2){
        $deptDetails = "<a href='deptDetails.php'>Department Details</a>";
        $deptRequests = "<a href='pendingRequests'>Pending Requests</a>";
    }
    echo "<table style='border-collapse: separate; border-spacing: 20px;'><tr><td><a href='my_requests.php'>My Requests</a></td><td>{$deptDetails}</td><td>{$deptRequests}</td></tr></table>";


    echo "</br></br><h1>Calendar</h1>";
    // echo "@CALENDAR PPL PLS PUT IT HERE TYVM";
    // echo "<div id='calendar'></div>"
    $requests_json = json_encode($requests);
?>
    <!-- calendar js -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
    const requests = <?php echo $requests_json; ?>;

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
             // Convert requests data into FullCalendar event objects
            const events = requests.map(function(request) {
                return {
                    title: request.Working_Arrangement, // Display the arrangement type as the title
                    start: request.Arrangement_Date, // Use the Arrangement_Date as the event's start date
                    // Add other fields if needed, e.g., for hover effects
                };
            });
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                // nowIndicator: true,
                // Gotta change the events part to be dynamic and pull from employee_arrangement db
                events: events,
                dateClick: function() {
                    // alert('a day has been clicked!');
                    window.open('location_details.php', target='_blank');
                },
                eventMouseEnter: function(info) {
                    // Show hover effect or tooltip with event details
                    var tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.innerHTML = info.event.title + "<br>" + info.event.start;
                    document.body.appendChild(tooltip);
                    
                    tooltip.style.position = 'absolute';
                    tooltip.style.top = info.jsEvent.pageY + 'px';
                    tooltip.style.left = info.jsEvent.pageX + 'px';
                    tooltip.style.backgroundColor = '#f9f9f9';
                    tooltip.style.padding = '5px';
                    tooltip.style.border = '1px solid #ccc';
                },
                eventMouseLeave: function() {
                    // Remove the tooltip when the mouse leaves the event
                    var tooltip = document.querySelector('.tooltip');
                    if (tooltip) {
                        tooltip.remove();
                    }
                }
            });
            calendar.render();
        });

//         var calendar = new Calendar(calendarEl, {
//   dateClick: function() {
//     alert('a day has been clicked!');
//   }
// });
    </script>
</body>

<body>
    <div id='calendar'></div>
</body>