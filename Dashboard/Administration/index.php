<?php

    $PageTitle = "Dashboard";
    $PageType = "Administration";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");

?>

    <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | <?php echo $PageTitle; ?></title>


    <section class="section dashboard margin-bottom-60px">
        <div class="container nexure-container">

            <div class="nexure-grid nexure-three-grid gap-row-spacing-30">
                <div class="nexure-card">
                    <h4 class="font-18px text-bold no-padding">Engage with your Customers</h4>
                </div>
                <div class="nexure-card">
                    <h4 class="font-18px text-bold no-padding">Manage and Close Deals</h4>
                </div>
                <div class="nexure-card">
                    <h4 class="font-18px text-bold no-padding">Build your Pipeline</h4>
                </div>
            </div>

            <?php

                $accessLevel = $PayrollHandler->system_flag;

                $cardsByAccessLevel = [
                    'Executive' => [
                        [
                            'title' => 'Sales Person Activity',
                            'icon' => 'salesicon.png',
                            'bg' => '#f5e6fe',
                            'type' => 'graph',
                            'graphCallType' => 'Sales Person Activity',
                            'graphStyle' => 'bargraph'
                        ],
                        [
                            'title' => 'All Opportunities',
                            'icon' => 'opportunityicon.png',
                            'bg' => '#e3f8fa',
                            'type' => 'static',
                            'content' => '<div style="margin-left:auto; margin-right:auto; text-align:center; padding-bottom:4%;">
                                            <img src="/Assets/img/SystemImages/VectorImages/opportunitiesNoContent.svg" style="width:40%; height:20vh; margin-top:2%;" alt="Pie Chart Not Found Graphic Vector">
                                            <p style="margin-top:4%; font-size:14px;">Track progress as you find opportunities.</p>
                                        </div>'
                        ],
                        [
                            'title' => 'Leads by Source',
                            'icon' => 'leadsbysourceicon.png',
                            'bg' => '#ffe6e2',
                            'type' => 'graph',
                            'graphCallType' => 'Deals by Segment',
                            'graphStyle' => 'piechart'
                        ],
                        [
                            'title' => "Today's Tasks",
                            'icon' => 'tasksicon.png',
                            'bg' => '#e3f8fa',
                            'type' => 'table',
                            'graphCallType' => 'Dashboard Tasks Table'
                        ],
                        [
                            'title' => 'All Cases',
                            'icon' => 'cases.png',
                            'bg' => '#ffe6e2',
                            'type' => 'table',
                            'graphCallType' => 'Dashboard Cases Table'
                        ],
                        [
                            'title' => 'All Leads',
                            'icon' => 'leadsicon.png',
                            'bg' => '#fff9dd',
                            'type' => 'table',
                            'graphCallType' => 'Dashboard Leads Table'
                        ],
                    ],
                    'Manager' => [
                        [
                            'title' => 'Team Sales Activity',
                            'icon' => 'salesicon.png',
                            'bg' => '#f5e6fe',
                            'type' => 'graph',
                            'graphCallType' => 'Sales Person Activity',
                            'graphStyle' => 'bargraph'
                        ],
                        [
                            'title' => 'Managed Opportunities',
                            'icon' => 'opportunityicon.png',
                            'bg' => '#e3f8fa',
                            'type' => 'static',
                            'content' => '<div style="margin-left:auto; margin-right:auto; text-align:center; padding-bottom:4%;">
                                            <img src="/Assets/img/SystemImages/VectorImages/opportunitiesNoContent.svg" style="width:40%; height:20vh; margin-top:2%;" alt="Pie Chart Not Found Graphic Vector">
                                            <p style="margin-top:4%; font-size:14px;">Track progress as you find opportunities.</p>
                                        </div>'
                        ],
                        [
                            'title' => 'Lead Sources Overview',
                            'icon' => 'leadsbysourceicon.png',
                            'bg' => '#ffe6e2',
                            'type' => 'graph',
                            'graphCallType' => 'Deals by Segment',
                            'graphStyle' => 'piechart'
                        ],
                        [
                            'title' => "Today's Team Tasks",
                            'icon' => 'tasksicon.png',
                            'bg' => '#e3f8fa',
                            'type' => 'table',
                            'graphCallType' => 'Dashboard Tasks Table'
                        ],
                        [
                            'title' => 'All Open Cases',
                            'icon' => 'cases.png',
                            'bg' => '#ffe6e2',
                            'type' => 'table',
                            'graphCallType' => 'Dashboard Cases Table'
                        ],
                        [
                            'title' => 'Managed Leads',
                            'icon' => 'leadsicon.png',
                            'bg' => '#fff9dd',
                            'type' => 'table',
                            'graphCallType' => 'Dashboard Leads Table'
                        ],
                    ],
                    'Default' => [
                        [
                            'title' => 'Your Sales Activity',
                            'icon' => 'salesicon.png',
                            'bg' => '#f5e6fe',
                            'type' => 'graph',
                            'graphCallType' => 'Employee Only Sales Activity',
                            'graphStyle' => 'bargraph'
                        ],
                        [
                            'title' => 'Your Opportunities',
                            'icon' => 'opportunityicon.png',
                            'bg' => '#e3f8fa',
                            'type' => 'static',
                            'content' => '<div style="margin-left:auto; margin-right:auto; text-align:center; padding-bottom:4%;">
                                            <img src="/Assets/img/SystemImages/VectorImages/opportunitiesNoContent.svg" style="width:40%; height:20vh; margin-top:2%;" alt="Pie Chart Not Found Graphic Vector">
                                            <p style="margin-top:4%; font-size:14px;">Track progress as you find opportunities.</p>
                                        </div>'
                        ],
                        [
                            'title' => 'Your Lead Sources',
                            'icon' => 'leadsbysourceicon.png',
                            'bg' => '#ffe6e2',
                            'type' => 'graph',
                            'graphCallType' => 'Deals by Segment',
                            'graphStyle' => 'piechart'
                        ],
                        [
                            'title' => "Today's Tasks",
                            'icon' => 'tasksicon.png',
                            'bg' => '#e3f8fa',
                            'type' => 'table',
                            'graphCallType' => 'Dashboard Tasks Table Employee Only'
                        ],
                        [
                            'title' => 'Assigned Cases',
                            'icon' => 'cases.png',
                            'bg' => '#ffe6e2',
                            'type' => 'table',
                            'graphCallType' => 'Dashboard Cases Table Employee Only'
                        ],
                        [
                            'title' => 'Your Leads',
                            'icon' => 'leadsicon.png',
                            'bg' => '#fff9dd',
                            'type' => 'table',
                            'graphCallType' => 'Dashboard Leads Table Employee Only'
                        ],
                    ],
                ];

                $cards = $cardsByAccessLevel[$accessLevel] ?? $cardsByAccessLevel['Default'];
                
            ?>

                <div class="nexure-grid nexure-three-grid gap-row-spacing-30">
                    <?php foreach ($cards as $card): ?>
                        <div class="nexure-card padding-10px">
                            <div class="card-header">
                                <div class="display-flex align-center padding-bottom-10px">
                                    <div class="no-padding icon-size-formatted" style="height:35px; width:35px; margin-right:10px;">
                                        <img src="/Assets/img/SystemImages/Icons/<?= $card['icon'] ?>" alt="Icon" style="background-color:<?= $card['bg'] ?>;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <p class="no-padding"><strong><?= htmlspecialchars($card['title']) ?></strong></p>
                                </div>
                            </div>
                            <div class="card-body">

                                <?php

                                    if ($card['type'] === 'graph') {

                                        unset($_SESSION['graphCallType']);
                                        $_SESSION['graphCallType'] = $card['graphCallType'];
                                        include($_SERVER["DOCUMENT_ROOT"] . "/Modules/NexureSolutions/GraphQL/{$card['graphStyle']}.php");

                                    } elseif ($card['type'] === 'table') {

                                        unset($_SESSION['graphCallType']);
                                        $_SESSION['graphCallType'] = $card['graphCallType'];
                                        include($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Tables/dashboard.php');

                                    } elseif ($card['type'] === 'static') {

                                        echo $card['content'];

                                    }
                                    
                                ?>

                            </div>
                            <div class="card-footer">
                                <div class="display-flex align-center justify-content-space-between padding-top-10px">
                                    <a href="" class="brand-link">View Report</a>
                                    <p class="no-padding"><?php echo $VariableDefinitionHandler->datedataOutput; ?> UTC</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

        </div>
    </section>

    <section class="nexure-pricing-bar">
        <div class="container nexure-container catalog-container">
            <div class="width-100 display-flex align-center justify-content-space-between">
                <p class="font-14px no-padding no-margin">Licensed To: <?php echo $VariableDefinitionHandler->organizationLegalName; ?></p>
                <p class="font-14px no-padding no-margin">Version 25.0.5</p>
            </div>
        </div>
    </section>


<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>