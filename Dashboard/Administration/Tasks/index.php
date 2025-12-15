<?php

    $PageTitle = "Tasks";
    $PageType = "Administration";

    unset($_SESSION['verification_code']);

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");
    include($_SERVER["DOCUMENT_ROOT"].'/Modules/NexureSolutions/Tables/index.php');

?>

    <title>Emmie® by <?php echo $VariableDefinitionHandler->organizationShortName; ?> | <?php echo $PageTitle; ?></title>


    <section class="section dashboard">
        <div class="container nexure-container">
            <div class="nexure-grid nexure-one-grid no-row-gap">
                <div class="nexure-card">
                    <div class="card-header">
                        <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                            <div class="display-flex align-center padding-bottom-10px">
                                <div class="no-padding margin-right-20px icon-size-formatted">
                                    <img src="/Assets/img/SystemImages/Icons/tasksicon.png" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Tasks</p>
                                    <h4 class="text-bold font-20px no-padding" style="padding-bottom:0px; padding-top:5px;">List Tasks</h4>
                                </div>
                            </div>
                            <div style="margin-top:-5px;">
                                <a href="/Dashboard/Administration/Tasks/CreateTask/" class="nexure-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create Task</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">

                            <?php

                                renderListingTable(
                                    $con,
                                    'tasks',
                                    ['Title', 'Description', 'Actions'],
                                    ['taskTitle', 'taskDescription', 'status'],
                                    ['15%', '15%', '15%', '15%'],
                                    [
                                        'View' => "/dashboard/administration/tasks/manageTask/?task_id={taskID}",
                                        'Edit' => "/dashboard/administration/tasks/editTask/?task_id={taskID}",
                                        'Delete' => "openModal('deleteTask({taskID})')"
                                    ]
                                );

                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>