<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Tables/index.php");

    switch ($_SESSION['graphCallType'] ?? '') {
        case "Dashboard Tasks Table":
            
            renderListingTable(
                $con, 
                'tasks', 
                ['Task Name', 'Task Start Date', 'Task End Date', 'Status'], 
                ['taskTitle', 'taskStartDate', 'taskEndDate', 'status'], 
                ['30%', '20%', '20%', '15%']
            );

            break;

        case "Dashboard Tasks Table Employee Only":

            renderListingTable(
                $con, 
                'tasks', 
                ['Task Name', 'Task Start Date', 'Task End Date', 'Status'], 
                ['taskTitle', 'taskStartDate', 'taskEndDate', 'status'], 
                ['30%', '20%', '20%', '15%']
            );

            break;

        case "Dashboard Cases Table":
            
            renderListingTable(
                $con, 
                'cases', 
                ['Case Title', 'Case Created', 'Type', 'Status'], 
                ['title', 'created_at','type', 'status'], 
                ['25%', '35%', '10%', '10%'],
                [],
                null,
                true 
            );

            break;

        case "Dashboard Cases Table Employee Only":

            renderListingTable(
                $con, 
                'cases', 
                ['Case Title', 'Case Created', 'Type', 'Status'], 
                ['title', 'created_at','type', 'status'], 
                ['30%', '20%', '20%', '15%'],
                [],
                null,
                true
            );

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