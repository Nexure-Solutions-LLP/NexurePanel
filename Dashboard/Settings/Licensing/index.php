<?php

    $PageTitle = "Licensing";
    $PageType = "Administration";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");

?>

    <title>Emmie® by <?php echo $VariableDefinitionHandler->organizationShortName; ?> | <?php echo $PageTitle; ?></title>

    <style>body, html {overflow:hidden !important;}</style> <!-- Disable scrolling on the main page -->

    <section class="section dashboard">
        <div class="container nexure-container">
            <div class="nexure-grid nexure-one-grid no-row-gap">
                <div class="nexure-card">
                    <div class="no-margin no-padding">
                        <div class="display-flex justify-content-space-between align-center">
                            <div class="display-flex align-center">
                                <div class="no-padding margin-right-20px icon-size-formatted">
                                    <img src="/Assets/img/SystemImages/Icons/settingsicon.png" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">System Settings</p>
                                    <h4 class="text-bold font-20px no-padding" style="padding-bottom:0px; padding-top:5px;">Licensing</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nexure-grid nexure-two-grid no-row-gap margin-top-20px grid-sidebar">
                <div>
                    <?php include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Menus/Sidebar/Settings/index.php"); ?>
                </div>
                <div>
                    <div class="nexure-card" style="overflow-y:scroll; height:66vh;">
                       
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>