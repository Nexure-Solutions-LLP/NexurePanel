<?php

    $PageTitle = "Customer Accounts";
    $PageType = "Administration";

    unset($_SESSION['verification_code']);

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");
    include($_SERVER["DOCUMENT_ROOT"].'/Modules/NexureSolutions/Tables/index.php');

    $accountnumber = $_GET['account_number'] ?? '';

    if (!$accountnumber) {

        header("location: /Dashboard/Administration/Accounts");
        
        exit;

    }

    // These sets of calls pulls account data from the database including customer risk score.

    $CurrentAccountExamination->LoadFullCustomerAccount($con, $accountnumber);

    $account = $CurrentAccountExamination->selectedAccountDetails;
    $identity = $CurrentAccountExamination->identity[0] ?? [];
    $business = $CurrentAccountExamination->businessProfile[0] ?? [];

    $accountDetails = $CurrentAccountExamination->OnlineAccessInformation;

?>

    <title>Emmie® by <?php echo $VariableDefinitionHandler->organizationShortName; ?> | <?php echo $PageTitle; ?></title>


    <section class="section dashboard">
        <div class="container nexure-container">
            <?php include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Menus/Header/Account/index.php"); ?>
            <div class="nexure-grid nexure-two-grid no-row-gap margin-top-30px account-grid-modified">
                <div>
                    <div class="margin-bottom-20px">
                        <div class="nexure-grid nexure-four-grid align-center no-column-gap no-row-gap">
                            <a href="/Dashboard/Administration/Accounts/ManageAccount/?account_number=<?php echo $accountnumber; ?>">
                                <div class="text-center border-selected">
                                    <p class="font-12px padding-bottom-10px">Details</p>
                                </div>
                            </a>
                            <a href="/Dashboard/Administration/Accounts/ManageAccount/PaymentMethods/?account_number=<?php echo $accountnumber; ?>">
                                <div class="text-center border-netural">
                                    <p class="font-12px padding-bottom-10px">Payment Methods</p>
                                </div>
                            </a>
                            <a href="/Dashboard/Administration/Accounts/ManageAccount/Related/?account_number=<?php echo $accountnumber; ?>">
                                <div class="text-center border-netural">
                                    <p class="font-12px padding-bottom-10px">Related</p>
                                </div>
                            </a>
                            <a href="/Dashboard/Administration/Profiles/ManageProfile/?email=<?php echo $accountDetails["email"]; ?>">
                                <div class="text-center border-netural">
                                    <p class="font-12px padding-bottom-10px">Profile</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="nexure-card">
                        <div class="card-header">
                            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                                <p class="no-padding font-14px">Authorized Users</p>
                                <a href="/Dashboard/Administration/Accounts/CreateAuthorizedUser/index.php?account_number=<?php echo $accountnumber; ?>" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create User</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dashboard-table">
                                <?php

                                    renderListingTable(
                                        $con,
                                        'account_users',
                                        ['Name', 'Email', 'Access Level', 'Status', 'Actions'],
                                        ['displayName', 'email', 'accessLevel', 'onlineAccessStatus'],
                                        ['25%', '30%', '20%', '15%'],
                                        [
                                            'View' => "/Dashboard/Administration/Profiles/ManageProfile/?email={email}",
                                            'Edit' => "/Dashboard/Administration/Accounts/EditAccount/?account_number={accountNumber}"
                                        ],
                                        (array)$CurrentAccountExamination
                                    );

                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="nexure-card margin-top-20px">
                        <div class="card-header">
                            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                                <?php if (strtolower($account['accountType']) != "service account"): ?>
                                    <p class="no-padding font-14px">Transactions</p>
                                <?php else: ?>
                                    <p class="no-padding font-14px">Services</p>
                                <?php endif; ?>
                                <a href="/Dashboard/Administration/Accounts/OrderServices/index.php?account_number=<?php echo $accountnumber; ?>" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Order Service</a>
                            </div>
                        </div>
                        <div class="card-body" style="padding-top:0; padding-bottom:0;">
                            <div class="dashboard-table">
                                 <div class="nexure-table-container" style="margin-bottom:0;">
                                    <?php

                                        renderListingTable(
                                            $con,
                                            'account_services',
                                            ['Service Name', 'Ordered', 'Rendered', 'Amount', 'Status', 'Actions'],
                                            ['serviceName', 'orderDate', 'renderDate', 'amount', 'status'],
                                            ['25%', '20%', '20%', '15%', '10%'],
                                            [
                                                'View' => "/Dashboard/Administration/Accounts/Services/View/?service_id={id}"
                                            ],
                                            (array)$CurrentAccountExamination
                                        );

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nexure-card margin-top-20px">
                        <div class="card-header">
                            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                                <p class="no-padding font-14px">Files and Documents</p>
                                <a href="/Modules/NexureSolutions/System/Upload/index.php?account_number=<?php echo $accountnumber; ?>" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Upload File</a>
                            </div>
                        </div>
                        <div class="card-body" style="padding-top:0; padding-bottom:0;">
                            <div class="dashboard-table">
                                <div class="dashboard-table">
                                    <div class="nexure-table-container" style="margin-bottom:0;">
                                        <?php

                                            renderListingTable(
                                                $con,
                                                'account_files',
                                                ['File Name', 'Description', 'Status', 'Actions'],
                                                ['fileName', 'fileDescription', 'status'],
                                                ['30%', '40%', '15%'],
                                                [
                                                    'Download' => "/Modules/NexureSolutions/System/Download/?id={id}",
                                                    'Delete' => "openModal('deleteFile({id})')"
                                                ],
                                                (array)$CurrentAccountExamination
                                            );

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nexure-card margin-top-20px">
                        <div class="card-header">
                            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                                <p class="no-padding font-14px">Cases</p>
                                <a href="/Dashboard/Administration/Cases/OpenCase/index.php?account_number=<?php echo $accountnumber; ?>" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Open Case</a>
                            </div>
                        </div>
                        <div class="card-body" style="padding-top:0; padding-bottom:0;">
                            <div class="dashboard-table">
                                <div class="dashboard-table">
                                    <div class="nexure-table-container" style="margin-bottom:0;">
                                        <?php

                                            renderListingTable(
                                                $con,
                                                'account_cases',
                                                ['Case #', 'Title', 'Description', 'Opened', 'Status', 'Actions'],
                                                ['case_id', 'title', 'description', 'created_at', 'status'],
                                                ['15%', '25%', '25%', '20%', '5%'],
                                                [
                                                    'View' => "/Dashboard/Administration/Cases/View/?case={caseNumber}"
                                                ],
                                                (array)$CurrentAccountExamination
                                            );

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="nexure-card margin-bottom-20px">
                        <div class="card-header">
                            <div class="display-flex align-center margin-bottom-10px" style="justify-content:space-between;">
                                <p class="no-padding">Account Insights</p>
                            </div>
                        </div>
                        <div class="card-body" style="margin-bottom:0; padding-bottom:0;">
                            <div class="nexure-grid nexure-three-grid no-row-gap">
                                <div>
                                    <p style="font-size:12px; margin-bottom:10px;">Balance</p>
                                    <p style="font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;">

                                        <?php echo '$'.strtolower($account['accountStatus']) === 'restricted' ? '— —' : '$' . $CurrentAccountExamination->formattedBalance; ?>
                                    
                                    </p>
                                </div>
                                <div>
                                    <p style="font-size:12px; margin-bottom:10px;">Due Date</p>
                                    <?php

                                        $dueDate =  strtolower($account['accountStatus']) === 'restricted' ? '— —' : ($CurrentAccountExamination->dueDate ? date('F j, Y', strtotime($CurrentAccountExamination->dueDate)) : '—');

                                        echo "<p style='font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;'>$dueDate</p>";

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="paybalanceModal" class="modal">
        <div class="modal-content">
            <form method="POST" action="/Dashboard/Administration/Accounts/ChargeCustomer/?account_number=<?php echo $accountnumber; ?>">
                <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;"><?php echo $LANG_DECREASE_BALANCE_TITLE; ?></h6>
                <div style="font-size:14px; padding-top:30px; padding-bottom:30px;">
                    <div class="form-control">
                        <span class="margin-right-10px">$</span> <input class="nexure-textbox grey-400" id="balanceNumber" type="numeric" maxlenghth="10" name="balanceNumber" style="width:25%;" placeholder="65.00" />
                    </div>
                </div>
                <p style="font-size:14px; padding-bottom:30px;"><?php echo $LANG_DECREASE_BALANCE_CURRENCY_DISCLAIMER; ?></p>
                <div style="display:flex; align-items:right; justify-content:right;">
                    <button class="nexure-button primary" type="submit" name="submit"><?php echo $LANG_DECREASE_BALANCE_BUTTON; ?></button>
                    <a class="nexure-button secondary" href="javascript:void(0)" onclick="closePaymentModal()"><?php echo $LANG_CLOSE_MODAL_BUTTON; ?></a>
                </div>
            </form>
        </div>
    </div>

    <div id="creditLimitModal" class="modal">
        <div class="modal-content">
            <form method="POST" action="/Dashboard/Administration/Accounts/IncreaseLimit/?account_number=<?php echo $accountnumber; ?>">
                <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;"><?php echo $LANG_CHANGE_CREDITLIMIT_TITLE ?></h6>
                <div style="font-size:14px; padding-top:30px; padding-bottom:30px;">
                    <div class="form-control">
                        <span class="margin-right-10px">$</span> <input class="nexure-textbox grey-400" id="creditLimitNumber" type="numeric" maxlenghth="10" name="creditLimitNumber" style="width:25%;" placeholder="65.00" />
                    </div>
                </div>
                <p style="font-size:14px; padding-bottom:30px;"><?php echo $LANG_CHANGE_CRDLMNT_CURRENCY_DISCLAIMER ?></p>
                <div style="display:flex; align-items:right; justify-content:right;">
                    <button class="nexure-button primary" type="submit" name="submit"><?php echo $LANG_CHANGE_CREDITLIMIT_BUTTON; ?></button>
                    <a class="nexure-button secondary" href="javascript:void(0)" onclick="closeCreditModal()"><?php echo $LANG_CLOSE_MODAL_BUTTON; ?></a>
                </div>
            </form>
        </div>
    </div>

    <div id="setbalanceModal" class="modal">
        <div class="modal-content">
            <form method="POST" action="/Dashboard/Administration/Accounts/AlterBalance/?account_number=<?php echo $accountnumber; ?>">
                <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;"><?php echo $LANG_INCREASE_BALANCE_TITLE; ?></h6>
                <div style="font-size:14px; padding-top:30px; padding-bottom:30px;">
                    <div class="form-control">
                        <span class="margin-right-10px">$</span> <input class="nexure-textbox grey-400" id="balanceNumber" type="numeric" maxlenghth="10" name="balanceNumber" style="width:25%;" placeholder="65.00" />
                    </div>
                </div>
                <p style="font-size:14px; padding-bottom:30px;"><?php echo $LANG_INCREASE_BALNCE_CURRENCY_DISCLAIMER; ?></p>
                <div style="display:flex; align-items:right; justify-content:right;">
                    <button class="nexure-button primary" type="submit" name="submit"><?php echo $LANG_INCREASE_BALANCECHANGE_BUTTON; ?></button>
                    <a class="nexure-button secondary" href="javascript:void(0)" onclick="closeBalanceModal()"><?php echo $LANG_CLOSE_MODAL_BUTTON; ?></a>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.nexureRiskScore = <?php echo isset($riskScore) ? (int)$riskScore : 0; ?>;
    </script>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>