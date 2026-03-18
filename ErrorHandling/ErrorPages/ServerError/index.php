<?php

    $PageTitle = "500 Server Error";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Headers/index.php");

?>

<section class="section generic-system-pages">
    <div class="container nexure-container">
        <div style="display:flex; align-items:center;">
            <div>
                <img src="<?php echo $VariableDefinitionHandler->organizationWideLogo; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo light-mode" style="margin-top:12%; width:12%;">
                <img src="<?php echo $VariableDefinitionHandler->organizationWideLogoDark; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo dark-mode" style="margin-top:12%; width:12%;">
                <h6 class="font-bold" style="font-size:25px; margin:0; padding:0; margin-top:4%; margin-bottom:3%;"><?php echo $LANG_SERVERERROR_TITLE; ?></h6>
                <p class="nexure-login-sublink license-text-dark width-80" style="margin-bottom:3%;"><?php echo $LANG_SERVERERROR_SUBTEXT; ?></p>
                <p class="nexure-login-sublink license-text-dark width-80" style="margin-bottom:3%;">Reference ID: <?php echo $NexureUUID ?></p>
            </div>
        </div>
    </div>
</section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Footers/index.php");

?>