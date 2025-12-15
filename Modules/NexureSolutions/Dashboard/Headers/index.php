<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Backend/index.php");

?>

<!-- 
        
         _   __                             _____       __      __  _                 
        / | / /__  _  ____  __________     / ___/____  / /_  __/ /_(_)___  ____  _____
       /  |/ / _ \| |/_/ / / / ___/ _ \    \__ \/ __ \/ / / / / __/ / __ \/ __ \/ ___/
      / /|  /  __/>  </ /_/ / /  /  __/   ___/ / /_/ / / /_/ / /_/ / /_/ / / / (__  ) 
     /_/ |_/\___/_/|_|\__,_/_/   \___/   /____/\____/_/\__,_/\__/_/\____/_/ /_/____/  
                                                                                 

    This site was created by Nexure Solutions LLP. http://www.nexuresolutions.com
    Last Published: Aug 07 2025 at 09:33:56 PM (Eastern Time)

    Creator/Developer: Nexure Development Team

    Images and content used on this website may come from third-party sources. Credits go
    to the respective owners of that content.

    Contact Information:
        Phone: +1-855-537-3591
        Email: support@nexuresolutions.com

    Note from Developer: 

        What a year we had for 2025. It has passed just as fast as it has come and it left us all with a little
        something to remember, some good, some bad, and everything in between. 2025 was a year that created
        memories and it should be a year that isn't forgotten.

        This is the one year anniversary update for Nexure since its creation an idea that was made exactly 
        one year ago. I want to thank everyone who has supported us entirely during this mission.

        I want to thank Mikey, Logan, Tiquio, Alexis, Trey, and lastly Emma for supporting us 
        on this journey while Nexure has been created, Alexis with Nexure's original idea during our 
        relationship, Mikey for taking it to the next level with assets and operational expertise, Logan for 
        expanding our systems, infrastructure and breaking the rules on boundaries to make Nexure an innovative 
        company rather one that just stays stuck in the past, Tiquio for investing money, time and guidance 
        even when things got hard for us, Trey for getting Nexure out of those times where we thought "oh no
        we have done it now" moments. Before I end off this big thank you, there is someone we forgot...

        Emma, while we haven't known each other long, the moment I met you, you knew everything about this company,
        you researched it, learned about it and remembered it, while I may have known you for a short time, and
        while we haven't talked much, in the few interactions we have had, you have shown while you can be serious
        and protective, you are also intelligent, resourceful and genuinely a kind and caring person, even if you
        sometimes may not want to show it, or may not be able to show it, and for that, you earned a special 
        message in these developer notes. You will go on to do amazing things, and everyone here at Nexure is
        sure of it. Love Nick.

        Finally before I close this long message off, I want to remind everyone, with the right people supporting
        you and the courage to learn, you can build your dream, chase after what you want, build something that's
        of value, where you can look back and say "I built that". That's the power of freedom.

    Designed and Developed by Nexure in Pennsylvania.

    Dear rule breakers, questioners, straight-A students who skipped class: We want you.
    https://nexuresolutions.com/careers.
    

-->

    <html>

        <header>
            <meta charset="utf-8" />
            <meta name="author" content="Nexure Development Team">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta property="og:image" content="https://nexuresolutions.com/assets/img/opengraphimage/opengraphimage.webp" />
            <meta property="og:type" content="website" />
            <meta content="summary_large_image" name="twitter:card" />
            <meta content="width=device-width, initial-scale=1" name="viewport" />
            <meta content="NexureSolutions" name="generator" />
            <meta name="languageCode" content="en"/>
            <meta name="countryCode" content="us"/>
            <meta name="focusArea" content="No Contact Module"/>
            <link rel="canonical" href="https://nexuresolutions.com/"/>
            <link href="https://cdn.nexuresolutions.com/content/assets/css/v2/2025-15-06-styling.css" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" href="/Assets/css/2025-dashboard-css-v2.css" />
            
            <?php 
            
                if ($VariableDefinitionHandler->panelTheme != "NexureDefault") {
                    
                    echo '<link rel="stylesheet" href="/Themes/'.$VariableDefinitionHandler->panelTheme.'/Assets/css/style.css" />';

                }

            ?>

            <link rel="apple-touch-icon" sizes="180x180" href="https://cdn.nexuresolutions.com/content/images/favicon/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="https://cdn.nexuresolutions.com/content/images/favicon/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="https://cdn.nexuresolutions.com/content/images/favicon/favicon-16x16.png">
            <link rel="manifest" href="https://cdn.nexuresolutions.com/content/images/favicon/site.webmanifest">

            <script type="text/javascript">   
                window.antiFlicker = {
                    active: true,
                    timeout: 3000
                }           
            </script>
            <script src="https://nexuresolutions.com/assets/js/darkmode.js" type="text/javascript"></script>
            <script type="text/javascript">
                var languageCode = document.getElementsByName('languageCode')[0].content;
                var countryCode = document.getElementsByName('countryCode')[0].content;
                var focusArea = document.getElementsByName('focusArea')[0].content;
                /* Define digital data object based on _appInfo object */
                window.digitalData = {
                    page: {
                        category: {
                            primaryCategory: '',
                        },
                        pageInfo: {
                            language: languageCode + '-' + countryCode,
                            NexureSolutions: {
                                siteID: 'MarketingAEM',
                                country: countryCode,
                                messaging: {
                                    routing: {
                                        focusArea: focusArea,
                                        languageCode: languageCode,
                                        regionCode: countryCode
                                    },
                                    translation: {
                                        languageCode: languageCode,
                                        regionCode: countryCode
                                    }
                                },
                                sections: 0,
                                patterns: 0
                            }
                        }
                    }
                };
            </script>
        </header>

        <body>

            <div class="nexure-header" id="nexure-header-js">
                <div class="background-darker-300">
                    <div class="container nexure-container nexure-nav-container">
                        <div class="display-flex align-center">
                            <div class="nexure-branding">
                                <a href="https://nexuresolutions.com/">
                                    <img src="<?php echo $VariableDefinitionHandler->organizationWideLogo; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo light-mode">
                                    <img src="<?php echo $VariableDefinitionHandler->organizationWideLogoDark; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo dark-mode">
                                </a>
                            </div>
                        </div>
                        <div class="search-container width-50">
                            <form method="POST" action="" class="no-margin no-padding">
                                <input class="nexure-textbox searchbar" name="nexuresearch" id="nexuresearch" placeholder="Search all of <?php echo $VariableDefinitionHandler->organizationShortName; ?>" />
                            </form>
                        </div>
                        <div class="display-flex align-center">
                            <a href="/Dashboard/Settings/Account" class="profile-link display-flex align-center">
                                <?php if ($CurrentOnlineAccessAccount->profileImage != "" || $CurrentOnlineAccessAccount->profileImage != NULL): ?> 
                                    <img src="<?php echo $CurrentOnlineAccessAccount->profileImage; ?>" class="image-fluid profile-image" />
                                <?php else: ?>
                                    <img src="/Assets/img/ProfileImages/Default.png" class="image-fluid profile-image" />
                                <?php endif; ?>
                                <span class="secondary-font font-14px margin-left-10px"><?php echo $CurrentOnlineAccessAccount->displayName; ?></span>
                            </a>
                            <span class="toggle-container">
                                <span class="lnr lnr-sun" class="toggle-input" id="lightModeIcon"></span>
                                <span class="lnr lnr-moon"  class="toggle-input" id="darkModeIcon"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="border-top-grey-300">
                    <div class="container display-flex align-center justify-content-space-between">
                        <?php include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Menus/Header/index.php"); ?>
                        <div class="systemLoads display-flex align-center">
                            <p class="font-14px">
                                <?php if ($PageTitle == "Admin Center") {

                                    $loads = sys_getloadavg();

                                    $rounded_loads = array_map(function ($load) {

                                        return number_format($load, 2);
                                    }, $loads);

                                    echo "System Loads: " . implode(", ", $rounded_loads);

                                } else { ?>

                                    <span class="font-14px" id="userSystemTime"></span>

                                <?php } ?>
                            </p>
                            <button style="background-color:transparent; border:none; outline:0;" href="javascript:void(0);" class="nexure-menu-icon" aria-label="Mobile Menu" onclick="responsiveMenu()">
                                <img src="https://nexuresolutions.com/assets/img/systemicons/menu.svg" loading="lazy" width="24" alt="" class="menu-icon">
                            </button>
                        </div>
                    </div>
                </div>
            </div>

<?php 
    
    if (isset($_SESSION["lang"])) {

        if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/Language/'.$_SESSION["lang"].'.php')) {

            $_SESSION["lang"] = 'EN_US';

        }

        include($_SERVER["DOCUMENT_ROOT"].'/Language/'.$_SESSION["lang"].'.php');

    } else {

        include($_SERVER["DOCUMENT_ROOT"]."/Language/EN_US.php");

    }

?>
