<?php

    $PageTitle = "Task Management";
    $PageType = "Administration";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");
    include($_SERVER["DOCUMENT_ROOT"].'/Modules/NexureSolutions/Tables/index.php');

    $taskID = $_GET['task_id'];

    if (!$taskID) {

        header("location: /Dashboard/Administration/Tasks");
        
        exit;

    }

?>

    <title>Emmie® by <?php echo $VariableDefinitionHandler->organizationShortName; ?> | <?php echo $PageTitle; ?></title>

    <section class="section dashboard">
        <div class="container nexure-container">
            <?php include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Menus/Header/Tasks/index.php"); ?>
            <div class="nexure-grid nexure-one-grid no-row-gap margin-top-30px">
                <div>
                    <div class="nexure-card">
                        <p>{description_undefined}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>