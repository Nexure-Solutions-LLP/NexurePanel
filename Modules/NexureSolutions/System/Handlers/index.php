<?php

    // This is the Nexure Backend Middleware.
    // Author: Nexure Developers
    // Nexure Solutions LLP (C) 2025 - All rights reserved.

    // This is the start of the handlers for accounts, tasks, variables, etc.
    // Variable Definitions

    namespace NexureSolutions\Generic {
        
        use Exception;
        use Sentry;

        class VariableDefinitions
        {

            public $PanelConfigurationInformation;
            public $organizationLegalName;
            public $organizationShortName;
            public $organizationSquareLogo;
            public $organizationWideLogo;
            public $organizationWideLogoDark;
            public $organizationAddressLine1;
            public $organizationAddressLine2;
            public $organizationCity;
            public $organizationState;
            public $organizationCountry;
            public $organizationPostalCode;
            public $organizationSupportInfo;
            public $paymentDescriptor;
            public $licenseKey;
            public $activationDate;
            public $expirationDate;
            public $organizationID;
            public $registrationDisabledMessage;
            public $maintenanceEnabledMessage;
            public $maintenanceStatus;
            public $registrationStatus;
            public $enableFeeDisclosurePage;
            public $feeDisclosureLink;
            public $panelTheme;
            public $dataTimestamp;
            public $datedataOutput;

            private function fetchSingleRow(\mysqli $con, string $query, array $params = []): ?array
            {

                $stmt = $con->prepare($query);

                if (!$stmt) {

                    Sentry\captureException(new Exception("Prepare failed: " . $con->error));

                    throw new Exception("Prepare failed: " . $con->error);

                }

                if (!empty($params)) {

                    $types = str_repeat('s', count($params));

                    $stmt->bind_param($types, ...$params);

                }

                $stmt->execute();

                $result = $stmt->get_result();

                if (!$result) {

                    Sentry\captureException(new Exception("Query failed: " . $stmt->error));

                    throw new Exception("Query failed: " . $stmt->error);

                }

                $row = $result->fetch_assoc();

                $stmt->close();

                return $row ?: null;
            }

            public function GatherPanelConfiguration(\mysqli $con): void
            {
                $this->PanelConfigurationInformation = $this->fetchSingleRow(
                    $con,
                    "SELECT * FROM nexure_config WHERE 1"
                );

                $this->organizationLegalName = $this->PanelConfigurationInformation['organizationLegalName'] ?? null;

                $this->organizationShortName = $this->PanelConfigurationInformation['organizationShortName'] ?? null;

                $this->organizationSquareLogo = $this->PanelConfigurationInformation['organizationSquareLogo'] ?? null;

                $this->organizationWideLogo = $this->PanelConfigurationInformation['organizationWideLogo'] ?? null;

                $this->organizationWideLogoDark = $this->PanelConfigurationInformation['organizationWideLogoDark'] ?? null;

                $this->organizationAddressLine1 = $this->PanelConfigurationInformation['organizationAddressLine1'] ?? null;

                $this->organizationAddressLine2 = $this->PanelConfigurationInformation['organizationAddressLine2'] ?? null;

                $this->organizationCity = $this->PanelConfigurationInformation['organizationCity'] ?? null;

                $this->organizationState = $this->PanelConfigurationInformation['organizationState'] ?? null;

                $this->organizationCountry = $this->PanelConfigurationInformation['organizationCountry'] ?? null;

                $this->organizationPostalCode = $this->PanelConfigurationInformation['organizationPostalCode'] ?? null;

                $this->organizationSupportInfo = $this->PanelConfigurationInformation['organizationSupportInfo'] ?? null;

                $this->paymentDescriptor = $this->PanelConfigurationInformation['paymentDescriptor'] ?? null;

                $this->licenseKey = $this->PanelConfigurationInformation['licenseKey'] ?? null;

                $this->activationDate = $this->PanelConfigurationInformation['activationDate'] ?? null;

                $this->expirationDate = $this->PanelConfigurationInformation['expirationDate'] ?? null;

                $this->organizationID = $this->PanelConfigurationInformation['organizationID'] ?? null;

                $this->registrationDisabledMessage = $this->PanelConfigurationInformation['registrationDisabledMessage'] ?? null;

                $this->maintenanceEnabledMessage = $this->PanelConfigurationInformation['maintenanceEnabledMessage'] ?? null;

                $this->maintenanceStatus = $this->PanelConfigurationInformation['maintenanceStatus'] ?? null;

                $this->registrationStatus = $this->PanelConfigurationInformation['registrationStatus'] ?? null;

                $this->enableFeeDisclosurePage = $this->PanelConfigurationInformation['enableFeeDisclosurePage'] ?? null;

                $this->feeDisclosureLink = $this->PanelConfigurationInformation['feeDisclosureLink'] ?? null;
                
                $this->panelTheme = $this->PanelConfigurationInformation['panelTheme'] ?? null;

                $this->dataTimestamp = date("M d, Y \a\\t h:i A");

                $this->datedataOutput = "As of " . $this->dataTimestamp;

            }

        }

    }

    // Nexure Modules Component

    namespace NexureSolutions\Modules {

        class NexureModules
        {

            public $allModules = [];

            public $activeModules = [];

            public $inactiveModules = [];

            public function retrieveModules($con)
            {

                $query = mysqli_query($con, "SELECT * FROM nexure_modules ORDER BY moduleName ASC");

                while ($module = mysqli_fetch_assoc($query)) {

                    $this->allModules[] = $module;

                    if (strtolower($module['moduleStatus']) === 'active') {

                        $this->activeModules[] = $module;

                    } else {

                        $this->inactiveModules[] = $module;
                    }

                }

            }

            public function isModuleEnabled($code)
            {

                foreach ($this->activeModules as $module) {

                    if ((int)$module['moduleCode'] === (int)$code) {

                        return true;

                    }

                }

                return false;

            }

            public function getModuleByCode($code)
            {

                foreach ($this->allModules as $module) {

                    if ((int)$module['moduleCode'] === (int)$code) {
                        
                        return $module;

                    }

                }

                return null;

            }

        }

    }

    // Nexure Calendar Component

    namespace NexureSolutions\Calendar {

        class CalendarComponents
        {

            public $eventsresponse;

            public $accountnumber;

            public function eventsRetrive($con, $accountnumber) {

                $this->eventsresponse = mysqli_query($con, "SELECT eventName, eventDescription, eventTimeDate FROM nexure_events WHERE accountNumber = '$accountnumber' ORDER BY eventTimeDate DESC");

            }

        }

    }

    // Nexure Account System Component

    namespace NexureSolutions\Accounts {

        use DateTime;
        use Exception;
        use Sentry;

        class AccountHandler
        {

            public $nexureid;
            public $displayName;
            public $profileImage;
            public $OnlineAccessInformation;
            public $accessType;
            public $accessLevel;
            public $onlineAccessStatus;
            public $firstinteractiondateformattedfinal;
            public $lastinteractiondateformattedfinal;
            public $emailverifydate;
            public $emailverifydateformatted;
            public $emailverifydateformattedfinal;
            public $emailverifystatus;
            public $paymentID;
            public $userAccounts = [];
            public $riskScoreMonitoring;

            public $accountNumber;
            public $accountType;
            public $accountDisplayName;
            public $headerName;
            public $creditLimit;
            public $balance;
            public $minimumPayment;
            public $dueDate;
            public $accountStatus;
            public $accountServices = [];
            public $selectedAccountDetails;

            public array $associatedFiles = [];
            public array $associatedCases = [];
            public array $associatedServices = [];
            public array $duplicateAccounts = [];

            public $NexureRiskScore10;

            private function fetchSingleRow(\mysqli $con, string $query, array $params = []): ?array
            {

                $stmt = $con->prepare($query);

                if (!$stmt) {

                    Sentry\captureException(new Exception("Prepare failed: " . $con->error));

                    throw new Exception("Prepare failed: " . $con->error);

                }

                if (!empty($params)) {

                    $types = str_repeat('s', count($params));

                    $stmt->bind_param($types, ...$params);

                }

                $stmt->execute();

                $result = $stmt->get_result();

                if (!$result) {

                    Sentry\captureException(new Exception("Query failed: " . $stmt->error));

                    throw new Exception("Query failed: " . $stmt->error);

                }

                $row = $result->fetch_assoc();

                $stmt->close();

                return $row ?: null;
            }

            public function GatherOnlineAccessInformation(\mysqli $con, string $nexureid): void
            {

                $this->OnlineAccessInformation = $this->fetchSingleRow(
                    $con,
                    "SELECT * FROM nexure_users WHERE email = ? LIMIT 1",
                    [$nexureid]
                );

                $this->onlineAccessStatus = $this->OnlineAccessInformation['onlineAccessStatus'] ?? null;

                $this->displayName = $this->OnlineAccessInformation['displayName'] ?? 'User';

                $this->profileImage = $this->OnlineAccessInformation['profileImage'] ?? '';

                $this->paymentID = $this->OnlineAccessInformation['paymentID'] ?? '';

                $this->accessType = $this->OnlineAccessInformation['accessLevel'] ?? null;

                $this->accessLevel = $this->OnlineAccessInformation['accessType'] ?? null;

                $newInteractionDate = date('Y-m-d H:i:s');

                $updateStmt = $con->prepare("UPDATE nexure_users SET lastInteractionDate = ? WHERE email = ?");

                if ($updateStmt) {

                    $updateStmt->bind_param('ss', $newInteractionDate, $nexureid);

                    $updateStmt->execute();

                    $updateStmt->close();

                } else {

                    Sentry\captureException(new Exception("Prepare failed for update: " . $con->error));

                }

                $this->firstinteractiondateformattedfinal = $this->formatDate(

                    $this->OnlineAccessInformation['firstInteractionDate'] ?? null

                );

                $this->lastinteractiondateformattedfinal = $this->formatDate(

                    $newInteractionDate

                );

                $this->emailverifydateformattedfinal = $this->formatDate(

                    $this->OnlineAccessInformation['emailVerificationDate'] ?? null

                );

                $this->emailverifystatus = ucfirst($this->OnlineAccessInformation['emailStatus'] ?? 'Unknown');

                $this->riskScoreMonitoring = ucfirst($this->OnlineAccessInformation['riskScoreMonitoring'] ?? '——');

            }

            private function formatDate(?string $date): string
            {
                
                if (empty($date)) {

                    return 'Unknown';

                }

                try {

                    $dateTime = new DateTime($date);

                    return $dateTime->format('F j, Y g:i A');

                } catch (Exception $e) {

                    Sentry\captureException($e);

                    return 'Invalid Date';

                }

            }

            public function GatherUserAccounts(\mysqli $con, string $nexureid): void
            {

                $stmt = $con->prepare("SELECT accountNumber, accountType, accountStatus, openedDate FROM nexure_accounts WHERE email = ? ORDER BY openedDate DESC");

                if (!$stmt) {

                    \Sentry\captureException(new \Exception("Prepare failed: " . $con->error));

                    throw new \Exception("Prepare failed: " . $con->error);

                }

                $stmt->bind_param('s', $nexureid);

                $stmt->execute();

                $result = $stmt->get_result();

                if (!$result) {

                    \Sentry\captureException(new \Exception("Query failed: " . $stmt->error));

                    throw new \Exception("Query failed: " . $stmt->error);

                }

                $accounts = [];

                while ($row = $result->fetch_assoc()) {

                    $accounts[] = $row;

                }

                $stmt->close();

                $latestAccountNumber = $accounts[0]['accountNumber'] ?? null;

                foreach ($accounts as &$account) {

                    $accountNumber = $account['accountNumber'];

                    $accountType = $account['accountType'];

                    $stmt = $con->prepare("SELECT serviceName FROM nexure_services WHERE accountNumber = ? ORDER BY orderDate DESC LIMIT 1");

                    $stmt->bind_param('s', $accountNumber);

                    $stmt->execute();

                    $serviceResult = $stmt->get_result();

                    $accountDisplayName = ($serviceResult && $serviceResult->num_rows > 0)
                        ? $serviceResult->fetch_assoc()['serviceName']
                        : "Unnamed Service";

                    $stmt->close();

                    $stmt = $con->prepare("SELECT businessLegalName FROM nexure_businesses WHERE accountNumber = ? LIMIT 1");

                    $stmt->bind_param('s', $accountNumber);

                    $stmt->execute();

                    $businessResult = $stmt->get_result();

                    $businessName = ($businessResult && $businessResult->num_rows > 0)
                        ? $businessResult->fetch_assoc()['businessLegalName']
                        : null;

                    $stmt->close();

                    $stmt = $con->prepare("SELECT legalName FROM nexure_ownership WHERE accountNumber = ? LIMIT 1");

                    $stmt->bind_param('s', $accountNumber);

                    $stmt->execute();

                    $ownershipResult = $stmt->get_result();

                    $legalName = ($ownershipResult && $ownershipResult->num_rows > 0)
                        ? $ownershipResult->fetch_assoc()['legalName']
                        : "Personal Account";

                    $stmt->close();

                    $headerName = ($businessName && $accountNumber === $latestAccountNumber)
                        ? $businessName
                        : $legalName;

                    $gatewayStmt = $con->prepare("SELECT processorName FROM nexure_payments WHERE status = 'Active'");

                    $gatewayStmt->execute();

                    $gatewayResult = $gatewayStmt->get_result();

                    $processors = [];

                    while ($row = $gatewayResult->fetch_assoc()) {

                        $processors[] = $row['processorName'];

                    }

                    $gatewayStmt->close();

                    $balanceInfo = [
                        'credit' => 0.0,
                        'subscription' => 0.0
                    ];

                    $balanceDisplay = '&mdash;';
                    
                    $balance = 0.0;

                    foreach ($processors as $processor) {

                        $filePath = $_SERVER["DOCUMENT_ROOT"]."/Modules/{$processor}/Payments/Backend/index.php";

                        if (file_exists($filePath)) {

                            include_once $filePath;

                            $stripe = initStripe($con);

                            if (!empty($this->paymentID)) {
                                $creditBalance = getCreditBalance($stripe, $this->paymentID);
                                $balanceInfo['credit'] += (float)$creditBalance;
                            }

                            $balanceInfo['credit'] += $creditBalance;
                                
                        }

                    }

                    $credit = floatval($balanceInfo['credit']);

                    $balance = $credit;

                    if ($credit !== 0.0) {

                        $balanceDisplay = ($balance < 0)

                            ? "-" . number_format(abs($balance), 2)
                            : "" . number_format($balance, 2);

                    } elseif ($credit === 0.0) {

                        $balanceDisplay = "0.00";

                    }

                    $this->userAccounts[] = [
                        'accountNumber' => $accountNumber,
                        'accountType' => $accountType,
                        'accountStatus' => $account['accountStatus'],
                        'balance' => $balanceDisplay,
                        'dueDate' => $account['dueDate'] ?? 'N/A',
                        'accountDisplayName' => $accountDisplayName,
                        'headerName' => $headerName,
                    ];

                }

            }

            public function GatherSingleAccountDetails(\mysqli $con, string $accountNumber): void
            {

                $accountStmt = $con->prepare("SELECT * FROM nexure_accounts WHERE accountNumber = ? LIMIT 1");

                $accountStmt->bind_param("s", $accountNumber);

                $accountStmt->execute();

                $accountResult = $accountStmt->get_result();

                $accountDetails = $accountResult->fetch_assoc();

                $accountStmt->close();

                if (!$accountDetails) {

                    $this->selectedAccountDetails = null;

                    return;

                }

                $businessStmt = $con->prepare("SELECT businessLegalName FROM nexure_businesses WHERE accountNumber = ? LIMIT 1");

                $businessStmt->bind_param("s", $accountNumber);

                $businessStmt->execute();

                $businessResult = $businessStmt->get_result();

                $businessDetails = $businessResult->fetch_assoc();

                $businessStmt->close();

                $ownershipStmt = $con->prepare("SELECT legalName FROM nexure_ownership WHERE accountNumber = ? LIMIT 1");

                $ownershipStmt->bind_param("s", $accountNumber);

                $ownershipStmt->execute();

                $ownershipResult = $ownershipStmt->get_result();

                $ownershipDetails = $ownershipResult->fetch_assoc();

                $ownershipStmt->close();

                $headerName = $businessDetails['businessLegalName'] ?? ($ownershipDetails['legalName'] ?? 'Unknown');

                $gatewayStmt = $con->prepare("SELECT processorName FROM nexure_payments WHERE status = 'Active'");

                $gatewayStmt->execute();

                $gatewayResult = $gatewayStmt->get_result();

                $processors = [];

                while ($row = $gatewayResult->fetch_assoc()) {

                    $processors[] = $row['processorName'];

                }

                $gatewayStmt->close();

                $balanceInfo = [
                    'credit' => 0.0,
                    'subscription' => 0.0
                ];

                $balanceDisplay = '&mdash;';
                
                $balance = 0.0;

                $stmt = $con->prepare("SELECT email FROM nexure_accounts WHERE accountNumber = ? LIMIT 1");

                $stmt->bind_param("s", $accountNumber);

                $stmt->execute();

                $result = $stmt->get_result();
                
                $row = $result->fetch_assoc();

                $stmt->close();

                $userEmail = $row['email'] ?? null;

                if ($userEmail) {

                    $stmt = $con->prepare("SELECT paymentID FROM nexure_users WHERE email = ? LIMIT 1");

                    $stmt->bind_param("s", $userEmail);

                    $stmt->execute();

                    $result = $stmt->get_result();

                    $userRow = $result->fetch_assoc();

                    $stmt->close();

                    $this->paymentID = $userRow['paymentID'] ?? null;

                } else {

                    $this->paymentID = null;

                }

                foreach ($processors as $processor) {

                    $filePath = $_SERVER["DOCUMENT_ROOT"]."/Modules/{$processor}/Payments/Backend/index.php";

                    if (file_exists($filePath)) {

                        include_once $filePath;

                        $stripe = initStripe($con);

                        if (!empty($this->paymentID)) {
                            $creditBalance = getCreditBalance($stripe, $this->paymentID);
                            $balanceInfo['credit'] += (float)$creditBalance;
                        }

                        $balanceInfo['credit'] += $creditBalance;
                            
                    }

                }

                $credit = floatval($balanceInfo['credit']);

                $balance = $credit;

                if ($credit !== 0.0) {

                    $balanceDisplay = ($balance < 0)

                        ? "-" . number_format(abs($balance), 2)
                        : "" . number_format($balance, 2);

                } elseif ($credit === 0.0) {

                    $balanceDisplay = "0.00";

                }

                $minimumPayment = ($balance > 50.00) ? round($balance * 0.30, 2) : $balance;

                $servicesStmt = $con->prepare("SELECT * FROM nexure_services WHERE accountNumber = ?");

                $servicesStmt->bind_param("s", $accountNumber);

                $servicesStmt->execute();

                $servicesResult = $servicesStmt->get_result();

                $services = [];

                while ($row = $servicesResult->fetch_assoc()) {

                    $services[] = $row;

                }

                $servicesStmt->close();

                $serviceStmt = $con->prepare("SELECT serviceName FROM nexure_services WHERE accountNumber = ? ORDER BY orderDate DESC LIMIT 1");

                $serviceStmt->bind_param("s", $accountNumber);

                $serviceStmt->execute();

                $serviceResult = $serviceStmt->get_result();

                $serviceDetails = $serviceResult->fetch_assoc();

                $serviceStmt->close();

                $accountDisplayName = $serviceDetails['serviceName'] ?? 'Unknown';

                $accountType = $accountDetails['accountType'];

                $this->selectedAccountDetails = [
                    'accountNumber' => $accountNumber,
                    'accountType' => $accountType,
                    'accountDisplayName' => $accountDisplayName,
                    'headerName' => $headerName,
                    'creditLimit' => $accountDetails['creditLimit'] ?? 0,
                    'accountStatus' => $accountDetails['accountStatus'] ?? 'Unknown',
                    'balance' => $balanceDisplay,
                    'minimumPayment' => $minimumPayment,
                    'dueDate' => 'May 30 2025',
                    'services' => $services
                ];
                
            }

            public function loadRiskScore(\mysqli $con, string $nexureid): void
            {
                $row = $this->fetchSingleRow(
                    $con,
                    "SELECT scoreValue FROM nexure_riskscores WHERE email = ?",
                    [$nexureid]
                );

                if ($row && isset($row['scoreValue'])) {

                    $score = (int)$row['scoreValue'];

                    $this->NexureRiskScore10 = max(0, min(999, $score));

                } else {

                    $this->NexureRiskScore10 = 0;

                }

            }

            function fromUserRole(string $requestedUserRole): ?string {

                $roleEnum = \userRole::fromString($requestedUserRole);

                return $roleEnum?->name ?? null;

            }

            public function LoadFullCustomerAccount(\mysqli $con, string $accountNumber): void {

                $stmt = $con->prepare("SELECT email FROM nexure_accounts WHERE accountNumber = ? LIMIT 1");

                $stmt->bind_param("s", $accountNumber);

                $stmt->execute();

                $row = $stmt->get_result()->fetch_assoc();
                
                $stmt->close();

                $userEmail = $row['email'] ?? null;

                if ($userEmail) {

                    $this->GatherOnlineAccessInformation($con, $userEmail);

                    $this->loadRiskScore($con, $userEmail);

                    $this->GatherUserAccounts($con, $userEmail);

                }

                $this->GatherSingleAccountDetails($con, $accountNumber);

                $this->associatedFiles = [];

                $fileStmt = $con->prepare("SELECT * FROM nexure_files WHERE accountNumber = ?");

                $fileStmt->bind_param("s", $accountNumber);

                $fileStmt->execute();

                $fileResult = $fileStmt->get_result();

                while ($file = $fileResult->fetch_assoc()) {

                    $this->associatedFiles[] = $file;

                }

                $fileStmt->close();

                $this->associatedCases = [];

                $caseStmt = $con->prepare("SELECT * FROM nexure_cases WHERE accountNumber = ?");

                $caseStmt->bind_param("s", $accountNumber);

                $caseStmt->execute();

                $caseResult = $caseStmt->get_result();

                while ($case = $caseResult->fetch_assoc()) {

                    $this->associatedCases[] = $case;

                }

                $caseStmt->close();

                $this->associatedServices = [];

                $serviceStmt = $con->prepare("SELECT * FROM nexure_services WHERE accountNumber = ?");

                $serviceStmt->bind_param("s", $accountNumber);
                
                $serviceStmt->execute();

                $serviceResult = $serviceStmt->get_result();

                while ($service = $serviceResult->fetch_assoc()) {

                    $this->associatedServices[] = $service;

                }

                $serviceStmt->close();

                $accountInfo = $this->selectedAccountDetails ?? [];

                $ownerName      = $accountInfo['headerName'] ?? '';

                $businessName   = $accountInfo['headerName'] ?? '';
                
                $industry       = $accountInfo['accountType'] ?? '';

                $email          = $userEmail ?? '';

                $websiteDomain  = '';

                if (!empty($accountNumber) || !empty($ownerName) || !empty($businessName) || !empty($industry) || !empty($email) || !empty($websiteDomain)) {

                    $query = "
                        SELECT 
                            a.accountNumber,
                            u.email,
                            o.legalName,
                            b.businessLegalName,
                            b.businessDBAName,
                            b.businessIndustry,
                            w.domainName
                        FROM nexure_accounts a
                        LEFT JOIN nexure_users u ON a.email = u.email
                        LEFT JOIN nexure_ownership o ON a.accountNumber = o.accountNumber
                        LEFT JOIN nexure_businesses b ON a.accountNumber = b.accountNumber
                        LEFT JOIN nexure_websites w ON u.email = w.email
                        WHERE
                            (? != '' AND a.accountNumber = ?)
                            OR (? != '' AND o.legalName LIKE CONCAT('%', ?, '%'))
                            OR (? != '' AND b.businessLegalName LIKE CONCAT('%', ?, '%'))
                            OR (? != '' AND b.businessDBAName LIKE CONCAT('%', ?, '%'))
                            OR (? != '' AND b.businessIndustry = ? AND (b.businessLegalName LIKE CONCAT('%', ?, '%') OR b.businessDBAName LIKE CONCAT('%', ?, '%')))
                            OR (? != '' AND w.domainName LIKE CONCAT('%', ?, '%'))

                        UNION

                        SELECT 
                            a2.accountNumber,
                            u2.email,
                            o2.legalName,
                            b2.businessLegalName,
                            b2.businessDBAName,
                            b2.businessIndustry,
                            w2.domainName
                        FROM nexure_accounts a2
                        LEFT JOIN nexure_users u2 ON a2.email = u2.email
                        LEFT JOIN nexure_ownership o2 ON a2.accountNumber = o2.accountNumber
                        LEFT JOIN nexure_businesses b2 ON a2.accountNumber = b2.accountNumber
                        LEFT JOIN nexure_websites w2 ON u2.email = w2.email
                        WHERE
                            (? != '' AND o2.legalName LIKE CONCAT('%', ?, '%'))
                            OR (? != '' AND b2.businessLegalName LIKE CONCAT('%', ?, '%'))
                            OR (? != '' AND b2.businessDBAName LIKE CONCAT('%', ?, '%'))
                            OR (? != '' AND w2.domainName LIKE CONCAT('%', ?, '%'))
                    ";

                    $stmt = $con->prepare($query);

                    $stmt->bind_param(
                        "ssssssssssssssssssssss",
                        $accountNumber, $accountNumber,
                        $ownerName, $ownerName,
                        $businessName, $businessName,
                        $businessName, $businessName,
                        $industry, $industry, $businessName, $businessName,
                        $websiteDomain, $websiteDomain,
                        $ownerName, $ownerName,
                        $businessName, $businessName,
                        $businessName, $businessName,
                        $websiteDomain, $websiteDomain
                    );

                    $stmt->execute();

                    $result = $stmt->get_result();

                    $this->duplicateAccounts = [];

                    while ($row = $result->fetch_assoc()) {

                        $this->duplicateAccounts[] = $row;

                    }

                    $stmt->close();

                } else {

                    $this->duplicateAccounts = [];

                }
                
            }

        }

    }

    namespace NexureSolutions\Payroll {

        use Exception;

        class EmployeeHandler
        {
            public $department;
            public $position;
            public $pay_rate;
            public $hours_worked;
            public $hire_date;
            public $term_date;
            public $rehire_date;
            public $status;
            public $reason;
            public $employment_type;
            public $contract_link;
            public $extension;
            public $employee_id;
            public $system_flag;
            public $displayName;
            public $accessLevel;
            public $user_id;

            public function __construct() {
                // No DB in constructor (matches AccountHandler)
            }

            public function GatherEmployeeInformation(\mysqli $con, string $email): void
            {

                try {

                    $stmt = $con->prepare("
                        SELECT 
                            p.*, 
                            u.displayName,
                            u.accessLevel,
                            u.id AS user_id
                        FROM nexure_users u
                        INNER JOIN nexure_payroll p
                            ON p.email = u.email
                        WHERE u.email = ?
                        AND u.accessLevel != 'Customer'
                        LIMIT 1
                    ");

                    if (!$stmt) {

                        throw new Exception("Prepare failed: " . $con->error);

                    }

                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $stmt->close();

                    if (!$row) {

                        return;

                    }

                    // Assign each value to the class object like your AccountHandler

                    foreach (array_keys(get_object_vars($this)) as $prop) {

                        if (isset($row[$prop])) {

                            $this->$prop = $row[$prop];

                        }

                    }

                } catch (Exception $e) {

                    if (class_exists('\Sentry')) {

                        \Sentry\captureException($e);

                    }

                }

            }

        }

    }

    // ============= Start Additional Logic not relating to the middleware. ============= */

    namespace { 

        // Bring in the required files such as database connection.

        require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/EnvironmentFile/index.php');

        require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/Database/index.php');

        require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/System/Schemas/index.php');

        session_start();

        // Error Logging and Redirection


        // Account Number Prefix

        $ACCOUNT_NUMBER_PREFIX = $_ENV['ACCOUNTSTARTNUMBER'];

        // IP Address Checking and Banning

        $passableUserId = $_ENV['IPCHECKAPIUSER'];

        $passableApiKey = $_ENV['IPCHECKAPIKEY'];

        $blacklistIPStatus = $_ENV['BLACKLIST_IP_STATUS'] ?? "False";

        function getClientIp() {

            $keys = [
                'HTTP_CLIENT_IP', 
                'HTTP_X_FORWARDED_FOR', 
                'HTTP_X_FORWARDED', 
                'HTTP_FORWARDED_FOR', 
                'HTTP_FORWARDED', 
                'REMOTE_ADDR'
            ];

            foreach ($keys as $key) {

                if ($ipaddress = getenv($key)) {

                    return $ipaddress;

                }

            }

            return 'UNKNOWN';
        }

        $clientIp = getClientIp();

        function isIpBlocked($ip, $con) {

            $query = "SELECT COUNT(*) FROM nexure_networks WHERE ipAddress = ? AND listType = 'blacklist'";

            if ($stmt = $con->prepare($query)) {

                $stmt->bind_param('s', $ip);

                $stmt->execute();

                $result = $stmt->get_result();

                $count = $result->fetch_array()[0];
                
                $stmt->close();

                return $count > 0;

            }

            return false;

        }

        function isIpAllowed($ip, $con) {

            $query = "SELECT COUNT(*) FROM nexure_networks WHERE ipAddress = ? AND listType = 'whitelist'";

            if ($stmt = $con->prepare($query)) {

                $stmt->bind_param('s', $ip);

                $stmt->execute();

                $result = $stmt->get_result();

                $count = $result->fetch_array()[0];

                $stmt->close();

                return $count > 0;

            }

            return false;

        }

        function isIpBlacklistedOrProxyVpn($ip, $passableUserId, $passableApiKey) {

            $url = "https://neutrinoapi.net/ip-probe";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['ip' => $ip]));

            curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-ID: $passableUserId", "API-Key: $passableApiKey"]);

            $response = curl_exec($ch);

            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['is-hosting']) && $data['is-hosting']) {

                return true;

            }

            if (isset($data['is-proxy']) && $data['is-proxy']) {

                return true;

            }

            if (isset($data['is-vpn']) && $data['is-vpn']) {

                return true;

            }

            return false;

        }

        function hasAdBlocker() {

            if (!isset($_SESSION['ad_blocker_checked'])) {
                echo "<script>
                    var adBlockEnabled = false;
                    var testAd = document.createElement('div');
                    testAd.innerHTML = '&nbsp;';
                    testAd.className = 'adsbox';
                    document.body.appendChild(testAd);
                    window.setTimeout(function() {
                        if (testAd.offsetHeight === 0) {
                            adBlockEnabled = true;
                        }
                        testAd.remove();
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'check_ad_blocker.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.send('adBlockEnabled=' + adBlockEnabled);
                    }, 100);
                </script>";

                $_SESSION['ad_blocker_checked'] = true;

            }

            if (isset($_SESSION['adBlockEnabled']) && $_SESSION['adBlockEnabled']) {

                return true;

            }

            return false;

        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adBlockEnabled'])) {

            $_SESSION['adBlockEnabled'] = $_POST['adBlockEnabled'] == 'true' ? true : false;

            exit;

        }

        function isIPSpamListed($ip, $passableUserId, $passableApiKey) {

            $url = "https://neutrinoapi.net/host-reputation";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'host' => $ip,
                'list-rating' => '3',
                'zones' => ''
            ]));

            curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-ID: $passableUserId", "API-Key: $passableApiKey"]);

            $response = curl_exec($ch);

            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['is-listed']) && $data['is-listed']) {

                return true;

            }

            return false;
        }

        function banIp($ip) {

            header("Location: /ErrorHandling/ErrorPages/BannedUser");

            exit;

        }

        // Assuming $pdo is your PDO connection

        if (!isIpAllowed($clientIp, $con) && $blacklistIPStatus == "True") {

            if (isIpBlacklistedOrProxyVpn($clientIp, $passableUserId, $passableApiKey)) {

                banIp($clientIp);

            }

            if (isIPSpamListed($clientIp, $passableUserId, $passableApiKey)) {

                banIp($clientIp);

            }

            if (hasAdBlocker()) {

                banIp($clientIp);

            }

            if (isIpBlocked($clientIp, $con)) {

                banIp($clientIp);

            }

        }
    }

?>