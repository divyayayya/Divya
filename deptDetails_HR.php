<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.css" />
    <title>Department Details</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
        }

        /* Navbar Styling */
        .navbar {
            background-color: black;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
            padding: 0 20px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .navbar img {
            height: 60px;
            margin-top: 2px;
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
        }

        .navbar form {
            display: flex;
            align-items: center;
        }

        .navbar label {
            margin-right: 10px;
            font-size: 16px;
            color: #fff;
        }

        .navbar select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #fff;
            color: #333;
            font-size: 16px;
        }

        .navbar input[type='submit'] {
            padding: 10px 20px;
            background-color: #fff;
            color: #333;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .navbar input[type='submit']:hover {
            background-color: #ddd;
        }

        /* Table Styling */
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

        /* Calendar Section */
        #calendar {
            margin-top: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }

        .tooltip {
            position: absolute;
            background-color: #f9f9f9;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
    </style>
</head>
<body>

<?php
    $userID = $_SESSION['userID'];
    $userRole = $_SESSION['userRole'];

    $dao = new EmployeeDAO();
    $result = $dao->retrieveEmployeeInfo($userID);
    $employee = new Employee(
        $result['Staff_ID'], 
        $result['Staff_FName'], 
        $result['Staff_LName'], 
        $result['Dept'], 
        $result['Position'], 
        $result['Country'], 
        $result['Email'], 
        $result['Reporting_Manager'], 
        $result['Role']
    );

    # Fetch all departments in the company 
    $departments = $dao->getAllDepartments();
    # Fetch all positions in the company 
    $positions = $dao->getAllPositions(); 

    // If form is submitted, use the selected department and position to retrieve employees
    $selectedDept = $employee->getDept(); // Default to the user's department
    $selectedPosition = ''; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedDept = $_POST['department']; // Get the selected department from the form
        $selectedPosition = $_POST['position']; // Get the selected position from the form
    }

    echo "<div class='navbar'>";
    echo "<a href='home.php'><img src='images/logo.jpg' alt='Company Logo'></a>"; // Link to homepage 
    if ($userRole == 1){
        echo "<form method='POST' action='' >";
        
        // Department dropdown
        echo "<label for='dept'>Search Department: </label>";
        echo "<select id='dept' name='department'>";
        foreach ($departments as $dept) {
            $selected = ($dept['Dept'] == $selectedDept) ? 'selected' : ''; // Set the default selected option
            echo "<option value='" . htmlspecialchars($dept['Dept']) . "' $selected>" . htmlspecialchars($dept['Dept']) . "</option>";
        }
        echo "</select>";
        
        // Position dropdown
        echo "<label for='position'>Search Position: </label>";
        echo "<select id='position' name='position'>";
        echo "<option value=''>All Positions</option>"; // Add a default option for all positions
        foreach ($positions as $position) {
            $selected = ($position['Position'] == $selectedPosition) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($position['Position']) . "' $selected>" . htmlspecialchars($position['Position']) . "</option>";
        }
        echo "</select>";

        echo "<input type='submit' value='View'>";
        echo "</form>";
    }
    echo "</div>";

    // Retrieve employees in the selected department and position
    $employeesInSameDept = $dao->retrieveEmployeesByDeptAndPosition($selectedDept, $selectedPosition);

    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Position</th><th>Country</th><th>Email</th></tr>";
    foreach ($employeesInSameDept as $deptEmployee) {
        echo "<tr>";
        echo "<td>{$deptEmployee['Staff_ID']}</td>";
        echo "<td>{$deptEmployee['Staff_FName']} {$deptEmployee['Staff_LName']}</td>";
        echo "<td>{$deptEmployee['Position']}</td>";
        echo "<td>{$deptEmployee['Country']}</td>";
        echo "<td>{$deptEmployee['Email']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "</div>";

    echo "<br><h1>Calendar</h1>";

    //Retrieve Underling Requests
    $underlings = $dao->retrieveUnderlings($userID);
    $underlingCount = count($underlings);
    $requests = [];
    $dao_req = new RequestDAO;
    foreach($underlings as $underling){
        $single_request = $dao_req -> retrieveRequestInfo($underling['Staff_ID']);
        if(!empty($single_request)){
            $requests = array_merge($requests, $single_request);
        }
    }
    $data = ['requests' => $requests, 'underlingCount' => $underlingCount ];
    $data_json = json_encode($data);
?>  


?>  

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
    const data = <?php echo $data_json; ?>;

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        // Step 1: Group requests by date and Working_Arrangement
        const groupedEvents = {};

        data.requests.forEach(function(request) {
            if (request.Request_Status === 'Approved') {
                const eventDate = request.Arrangement_Date.substring(0, 10);  // Extract YYYY-MM-DD
                // Initialize the date if not already present
                if (!groupedEvents[eventDate]) {
                    groupedEvents[eventDate] = { TotalWFH: 0, WFH_AM: 0, Office_AM: 0, WFH_PM: 0, Office_PM: 0 };
                }

                // Count events based on Working_Arrangement
                if (request.Working_Arrangement === 'WFH' && request.Arrangement_Time === 'AM') {
                    groupedEvents[eventDate].TotalWFH++;
                    groupedEvents[eventDate].WFH_AM++;
                } else if (request.Working_Arrangement === 'WFH' && request.Arrangement_Time === 'PM') {
                    groupedEvents[eventDate].TotalWFH++;
                    groupedEvents[eventDate].WFH_PM++;
                } else if (request.Working_Arrangement === 'WFH' && request.Arrangement_Time === 'Full Day') {
                    groupedEvents[eventDate].TotalWFH++;
                    groupedEvents[eventDate].WFH_AM++;
                    groupedEvents[eventDate].WFH_PM++;
                }
            }
        });

        // Step 2: Create one event per Working_Arrangement per date
        const events = Object.keys(groupedEvents).reduce((result, date) => {
            const counts = groupedEvents[date];
            if (counts.TotalWFH > 0) {
                result.push({
                    title: 'WFH',  // Only show 'WFH' as the title
                    start: date,
                    extendedProps: {
                        wfhCount: counts.TotalWFH,
                        officeCount: data.underlingCount - counts.TotalWFH,
                        wfh_am: counts.WFH_AM,
                        office_am: data.underlingCount - counts.WFH_AM,
                        wfh_pm: counts.WFH_PM,
                        office_pm: data.underlingCount - counts.WFH_PM
                    }
                });
            }
            return result;
        }, []);

        // Initialize the FullCalendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            events: events,  // Add aggregated events here
            dateClick: function(info) {
                // Extract the clicked date
                var arrangement_date = info.dateStr;  // Date in YYYY-MM-DD format

                // Create a form
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "location_details.php";
                form.target = "_blank";  // Open in a new tab

                // Create a hidden input for arrangement_date
                var input = document.createElement("input");
                input.type = "hidden";
                input.name = "date";
                input.value = arrangement_date;

                // Append input to form and submit the form
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();

                // Remove the form after submission (optional)
                document.body.removeChild(form);
            },


            eventMouseEnter: function(info) {
                var tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                tooltip.innerHTML = `<table><tr><td>WFH (AM)</td> <td>${info.event.extendedProps.wfh_am}</td></tr> <tr><td>Office (AM)</td><td> ${info.event.extendedProps.office_am}</td></tr> <tr><td> WFH (PM) </td> <td>${info.event.extendedProps.wfh_pm}</td></tr> <tr><td> Office (PM)</td><td> ${info.event.extendedProps.office_pm}</td></tr></table>`;
                document.body.appendChild(tooltip);
                
                tooltip.style.position = 'absolute';
                tooltip.style.top = info.jsEvent.pageY + 'px';
                tooltip.style.left = info.jsEvent.pageX + 'px';
                tooltip.style.backgroundColor = '#f9f9f9';
                tooltip.style.padding = '5px';
                tooltip.style.border = '1px solid #ccc';
                tooltip.style.zIndex = '1000';
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
    </script>

    <div id='calendar'></div>
</body>
</html>

