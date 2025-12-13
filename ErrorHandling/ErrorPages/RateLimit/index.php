<?php

    $PageTitle = " You have been rate limited.";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Headers/index.php");

?>

    <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | <?php echo $PageTitle; ?></title>

    <section class="section generic-system-pages">
        <div class="container nexure-container">
            <div style="display:flex; align-items:center; margin-top:12%;">
                <div class="margin-right-40px">
                    <img src="/Assets/img/SystemImages/Icons/banned.webp" style="height:30px; width:30px;" />
                </div>
                <div>
                    <h6 class="secondary-font font-bold" style="font-weight:300; font-size:25px; margin:0; padding:0; margin-top:4%; margin-bottom:3%;"><?php echo $LANG_RATELIMIT_ERROR_TITLE; ?></h6>
                    <p class="nexure-login-sublink license-text-dark width-80" style="margin-bottom:3%;"><?php echo $LANG_RATELIMIT_ERROR_TEXT; ?></p>
                    <p class="nexure-login-sublink license-text-dark width-80" style="margin-bottom:3%;">Reference ID: <?php echo $NexureUUID ?></p>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Footers/index.php");

?>