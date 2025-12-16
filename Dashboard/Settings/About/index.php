<?php

    $PageTitle = "About EMMIE®";
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
                                    <h4 class="text-bold font-20px no-padding" style="padding-bottom:0px; padding-top:5px;">About Emmie®</h4>
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
                       <div class="padding-top-10px">
                            <img src="<?php echo $VariableDefinitionHandler->organizationWideLogo; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo light-mode">
                            <img src="<?php echo $VariableDefinitionHandler->organizationWideLogoDark; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo dark-mode">
                        </div>
                        <div style="padding-left:5px; padding-right:5px; width:70%;">
                            <div>
                                <h3 style="font-size:20px; margin-top:30px; margin-bottom:4%;"><?php echo $PANEL_ABOUT_TITLE_PRODUCT_NAME ?></h3>
                                <p style="margin-top:20px; font-size:14px;"><?php echo $PANEL_ABOUT_INFO ?></p>
                                <p style="margin-top:20px; font-size:14px;"><?php echo $PANEL_ABOUT_LICENSE_DISCLAIMER ?></p>
                            </div>
                            <div>
                                <br>
                                <div class="nexure-horizantal-line"></div>
                                <br>
                            </div>
                            <div>
                                <p style="margin-top:10px; font-size:14px;">Software Name: Enterprise Management, Monetary & Intelligence Engine (Emmie)® by Nexure</p>
                                <p style="margin-top:10px; font-size:14px;">Release Date: 12/14/2025 9:40 AM (Eastern Time)</p>
                                <p style="margin-top:10px; font-size:14px;">Edition: Emmie® Developer Edition</p>
                                <?php

                                    echo "<p style='margin-top:10px; font-size:14px;'>Current PHP Version: " . phpversion() . "</p>";
                                    echo "<p style='margin-top:10px; font-size:14px;'>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
                                    echo "<p style='margin-top:10px; font-size:14px;'>Operating System: " . php_uname('s') . " " . php_uname('r') . "</p>";

                                ?>
                                <p style="margin-top:10px; font-size:14px;">Languages: HTML, CSS, JS, PHP and MySQL</p>
                                <p style="margin-top:1%; font-size:14px; margin-bottom:3%;">Developer: Nexure Solutions LLP.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>