<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Tables/index.php");

    switch ($_SESSION['graphCallType'] ?? '') {
        case "Dashboard Tasks Table":
            $headers = ["Task Name", "Task Start Date", "Task Due Date", "Status"];
            $columns = ['taskName', 'taskStartDate', 'taskDueDate', 'status'];
            $columnWidths = ['30%', '20%', '20%', '15%'];
            renderListingTable($con, 'tasks', $headers, $columns, $columnWidths);
            break;

        case "Dashboard Tasks Table Employee Only":
            $headers = ["Task Name", "Task Start Date", "Task Due Date", "Status"];
            $columns = ['taskName', 'taskStartDate', 'taskDueDate', 'status'];
            $columnWidths = ['30%', '20%', '20%', '15%'];
            renderListingTable($con, 'tasks', $headers, $columns, $columnWidths);
            break;

        case "Dashboard Cases Table":
            $headers = ["Customer Name", "Case Created", "Case Closed", "Status"];
            $columns = ['customerName', 'caseCreateDate', 'caseCloseDate', 'caseStatus'];
            $columnWidths = ['30%', '20%', '20%', '15%'];
            renderListingTable($con, 'cases', $headers, $columns, $columnWidths);
            break;

        case "Dashboard Cases Table Employee Only":
            $headers = ["Customer Name", "Case Title", "Case Created", "Case Closed", "Status"];
            $columns = ['customerName', 'caseTitle', 'caseCreateDate', 'caseCloseDate', 'caseStatus'];
            $columnWidths = ['25%', '25%', '15%', '15%', '10%'];
            renderListingTable($con, 'cases', $headers, $columns, $columnWidths);
            break;

        case "Dashboard Leads Table":
            
            renderListingTable(
                $con,
                'leads',
                ['Title', 'Name', 'Email', 'Status'],
                ['leadTitle', 'leadName', 'leadEmail', 'leadStatus'],
                ['15%', '15%', '15%', '10%', '10%', '15%']
            );

            break;

        case "Dashboard Leads Table Employee Only":

            renderListingTable(
                $con,
                'leads',
                ['Title', 'Name', 'Email', 'Status'],
                ['leadTitle', 'leadName', 'leadEmail', 'leadStatus'],
                ['15%', '15%', '15%', '10%', '10%', '15%']
            );

            break;

        default:
            echo "<p>No dashboard data available for this view.</p>";
            break;
    }

?>