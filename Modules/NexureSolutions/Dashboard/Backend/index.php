<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    // Import Files

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/System/Handlers/index.php');

    ob_clean();
    ob_start();

    // Plugin Imports

    use GuzzleHttp\Client;
    use IPLib\Factory;
    use Detection\MobileDetect;
    use Stripe\Stripe;

    $VariableDefinitionHandler = new \NexureSolutions\Generic\VariableDefinitions();
    $CurrentOnlineAccessAccount =  new \NexureSolutions\Accounts\AccountHandler($con);
    $CurrentAccountExamination = new \NexureSolutions\Accounts\AccountHandler($con);
    $PayrollHandler = new \NexureSolutions\Payroll\EmployeeHandler;
    $NexureModuleHandler = new \NexureSolutions\Modules\NexureModules;

    // Sentry Setup

    \Sentry\init([
        'dsn' => $_ENV['SENTRY_DSN'],
        'traces_sample_rate' => 1.0,
        'profiles_sample_rate' => 1.0,
    ]);

    // Variable Definitions

    $nexureid = $_SESSION['nexureid'];
    $sentryToken = $_ENV['SENTRY_TOKEN'];
    $sentryOrg = $_ENV['SENTRY_ORG'];
    $sentryProject = $_ENV['SENTRY_PROJECT'];
    $accountnumberlength = $_ENV["ACCOUNTNUMBERLENGTH"];

    // Middleware Calls

    $VariableDefinitionHandler->GatherPanelConfiguration($con);
    $NexureModuleHandler->retrieveModules($con);

    $CurrentOnlineAccessAccount->GatherOnlineAccessInformation($con, $nexureid);
    $CurrentOnlineAccessAccount->GatherUserAccounts($con, $nexureid);
    $CurrentOnlineAccessAccount->loadRiskScore($con, $nexureid);
    $PayrollHandler->GatherEmployeeInformation($con, $nexureid);

    $account = !empty($CurrentOnlineAccessAccount->userAccounts) ? $CurrentOnlineAccessAccount->userAccounts[0] : null;

    // Mobile Detection

    $detect = new MobileDetect();

    if ($detect->isMobile() || $detect->isTablet()) {

        header("Location: /ErrorHandling/ErrorPages/MobileExperience/");

        exit();

    }

    function isSelectedLang($lang_name) {

        $langPreference = "EN_US";

        if (isset($_SESSION["lang"])) {

            $langPreference = $_SESSION["lang"];

        }

        if ($langPreference == $lang_name) {

            return 'selected';

        } else {

            return '';

        }

    }

    if (isset($_POST['langPreference'])) {

        $_SESSION["lang"] = $_POST['langPreference'];

    }

    // Dashboard redirects based on access level

    $redirectMap = [
        "Client" => [
            "authorized user" => "/Dashboard/AuthorizedUser/",
            "partner" => "/Dashboard/Partners",
            "administrator" => "/Dashboard/Administration"
        ],
        "Administration" => [
            "authorized user" => "/Dashboard/AuthorizedUser/",
            "partner" => "/Dashboard/Partners",
            "customer" => "/Dashboard/Customer"
        ],
        "Authorized User" => [
            "administrator" => "/Dashboard/Administration",
            "partner" => "/Dashboard/Partners",
            "customer" => "/Dashboard/Customer"
        ],
        "Partner" => [
            "authorized user" => "/Dashboard/AuthorizedUser/",
            "administrator" => "/Dashboard/Administration",
            "customer" => "/Dashboard/Customer",
        ]
    ];

    $redirectUrl = $redirectMap[$PageType][strtolower($CurrentOnlineAccessAccount->accessType)] ?? null;

    if ($redirectUrl) {

        header("Location: $redirectUrl");
        exit();
        
    }

    

?>