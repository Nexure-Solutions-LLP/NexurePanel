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

    $riskScore = $CurrentAccountExamination->NexureRiskScore10;
    $category  = RiskScoreCategory::fromScore($riskScore);

    $account = $CurrentAccountExamination->selectedAccountDetails;
    $accountDetails = $CurrentAccountExamination->OnlineAccessInformation;

?>

    <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | <?php echo $PageTitle; ?></title>


    <section class="section dashboard">
        <div class="container nexure-container">
            <div class="nexure-grid nexure-one-grid no-row-gap">
                <div class="nexure-card">
                    <div class="card-header">
                        <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                            <div class="display-flex align-center padding-bottom-10px">
                                <div class="no-padding margin-right-20px icon-size-formatted">
                                    <img src="/Assets/img/SystemImages/Icons/CustomerBusinessLogos/defaultstore.png" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Account</p>
                                    <?php
                                        $mostRecentAccount = $CurrentOnlineAccessAccount->userAccounts[0] ?? null;
                                        $headerName = $mostRecentAccount['headerName'] ?? $VariableDefinitionHandler->organizationShortName.' ACCOUNT';
                                    ?>
                                    <h4 class="text-bold font-20px no-padding" style="padding-bottom:0px; padding-top:5px;"><?= htmlspecialchars($headerName) ?> - <?php echo $accountnumber; ?></h4>
                                </div>
                            </div>
                            <div style="margin-top:-5px;">
                                <a href="/Dashboard/Administration/Accounts/EditAccount/" class="nexure-button primary no-margin margin-5px-right" style="padding:6px 24px;">Edit</a>
                                <a href="javascript:void(0)" onclick="openBalanceModal()" class="nexure-button secondary no-margin margin-5px-right" style="padding:6px 24px;">Alter Balance</a>
                                <a href="javascript:void(0)" onclick="openPaymentModal()" class="nexure-button secondary no-margin margin-5px-right" style="padding:6px 24px;">Pay on Account</a>
                                <a href="/Dashboard/Administration/Accounts/ViewAsOwner/" class="nexure-button secondary no-margin margin-5px-right" style="padding:6px 24px;">View as Owner</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding-bottom:0;">
                        <div class="display-flex align-center width-100 padding-20px">
                            <?php

                                $details = [
                                    'Type' => ($accountDetails['accessLevel'] === 'Customer') ? 'Customer - Direct' : (($accountDetails['accessLevel'] === 'Partner') ? 'Partner - Affiliate' : '——'),
                                    'Owner' => $CurrentAccountExamination->displayName,
                                    'Credit Limit' => $account['creditLimit'].'<a href="javascript:void(0);" onclick="openCreditModal()" class="brand-link"> (Increase Limit)</a>',
                                    'First Interaction' => $CurrentAccountExamination->firstinteractiondateformattedfinal,
                                    'Last Interaction' => $CurrentAccountExamination->lastinteractiondateformattedfinal
                                ];
                                
                                foreach ($details as $label => $value) {

                                    echo "<div style='width:75%;'><p class='no-padding font-14px'>{$label}</p><p class='margin-top-10px font-14px' style='margin-bottom:0; padding-bottom:0;'>{$value}</p></div>";
                                
                                }

                            ?>
                            <div class="width-100">
                                <p class="no-padding font-14px">Industry</p>
                                <p class="no-padding font-14px"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nexure-grid nexure-two-grid no-row-gap margin-top-30px account-grid-modified">
                <div>
                    <div class="nexure-card">
                        <div class="card-header">
                            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                                <p class="no-padding font-14px">Authorized Users</p>
                                <a href="/Dashboard/Administration/Accounts/CreateAuthorizedUser/index.php?account_number=" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create User</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dashboard-table">
                                
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
                                <a href="/Dashboard/Administration/Accounts/OrderServices/index.php?account_number=" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Order Service</a>
                            </div>
                        </div>
                        <div class="card-body" style="padding-top:0; padding-bottom:0;">
                            <div class="dashboard-table">
                                 <div class="nexure-table-container" style="margin-bottom:0;">
                                    <table class="nexure-table-plugin nexure-table-domains">
                                        <thead>
                                            <tr>
                                                <th><?php if (strtolower($account['accountType']) != "service account"): ?>Transaction<?php else: ?>Service<?php endif; ?> Name</th>
                                                <th>Amount</th>
                                                <th><?php if (strtolower($account['accountType']) != "service account"): ?>Posted<?php else: ?>Ordered<?php endif; ?></th>
                                                <?php if (strtolower($account['accountType']) != "service account"): ?><?php else: ?><th>Rendered</th><?php endif; ?>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($account['services']) && is_array($account['services'])): ?>
                                                <?php foreach ($account['services'] as $service): ?>
                                                    <tr>
                                                        <td class="width-30"><?= htmlspecialchars($service['serviceName']) ?></td>
                                                        <td class="width-20">$<?= number_format($service['amount'], 2) ?></td>
                                                        <td class="width-20"><?= date('F j Y', strtotime($service['orderDate'])) ?></td>
                                                        <?php if (strtolower($account['accountType']) != "service account"): ?><?php else: ?><td class="width-20"><?= date('F j Y', strtotime($service['renderDate'])) ?></td><?php endif; ?>
                                                        <td class="width-20"><span class="account-status-badge <?= in_array(strtolower($service['status']), ['active', 'posted']) ? 'green' : 'red' ?>"><?= htmlspecialchars($service['status']) ?></span></td>
                                                        <td class="width-40"><a href="" class="nexure-button primary" style="padding: 4px 24px;">View</a><a href="" class="nexure-button secondary" style="padding: 4px 24px;">Invoice</a></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6">No services available</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nexure-card margin-top-20px">
                        <div class="card-header">
                            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                                <p class="no-padding font-14px">Files and Documents</p>
                                <a href="/Modules/NexureSolutions/System/Upload/index.php?account_number=" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Upload File</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dashboard-table">
                                
                            </div>
                        </div>
                    </div>
                    <div class="nexure-card margin-top-20px">
                        <div class="card-header">
                            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                                <p class="no-padding font-14px">Cases</p>
                                <a href="/Dashboard/Administration/Cases/OpenCase/index.php?account_number=" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Open Case</a>
                            </div>
                        </div>
                        <div class="card-body" style="padding-top:0; padding-bottom:0;">
                            <div class="dashboard-table">
                                <div class="dashboard-table">
                                    <div class="nexure-table-container" style="margin-bottom:0;">
                                        <table class="nexure-table-plugin nexure-table-domains">
                                            <thead>
                                                <tr>
                                                    <th>Case ID</th>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>Opened</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    
                                                </tr>
                                            </tbody>
                                        </table>
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
                                    <p style="font-size:12px; color:grey;">Balance</p>
                                    <p style="font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;">

                                        <?php echo '$'.strtolower($account['accountStatus']) === 'restricted' ? '— —' : '$' . $account['balance']; ?>
                                    
                                    </p>
                                </div>
                                <div>
                                    <p style="font-size:12px; color:grey;">Due Date</p>
                                    <?php

                                        $dueDate =  strtolower($account['accountStatus']) === 'restricted' ? '— —' : ($account['dueDate'] ? date('F j, Y', strtotime($account['dueDate'])) : '—');

                                        echo "<p style='font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;'>$dueDate</p>";

                                    ?>
                                </div>
                                <div>
                                    <p style="font-size:12px; color:grey;">Customer Since</p>
                                    <?php

                                        $registrationYear = date('Y', strtotime($CurrentAccountExamination->firstinteractiondateformattedfinal));

                                    ?>
                                    <p style="font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;"><?php echo $registrationYear; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($account['accountStatus'] == "Under Review" || $account['accountStatus'] == "under review") : ?>
                        <div class="nexure-card margin-bottom-20px">
                            <div class="card-header">
                                <div class="display-flex align-center margin-bottom-10px" style="justify-content:space-between;">
                                    <p class="no-padding">Important Notice</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="font-14px display-flex align-center"><img src="/Assets/img/SystemImages/Icons/infoicon.png" class="filter-white-on-dark" style="margin-right:20px; width:30px; height:30px;" /> <span>This account is currently pending and has not been approved automatically. Please take action or request the client to finish onboarding.</span></p>
                            </div>
                            <div class="card-footer">
                                <div class="display-flex align-center margin-top-15px">
                                    <a href="/Dashboard/Administration/Accounts/ApproveAccount/index.php?account_number=<?php echo $accountnumber; ?>" class="nexure-button primary no-margin margin-10px-right" style="padding:6px 24px;">Approve</a>
                                    <a href="/Dashboard/Administration/Accounts/RejectAccount/index.php?account_number=<?php echo $accountnumber; ?>" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Reject</a>
                                    <a href="/Dashboard/Administration/Accounts/TransferAccount/index.php?account_number=<?php echo $accountnumber; ?>" class="nexure-button secondary no-margin" style="padding:6px 24px;">Transfer</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($NexureModuleHandler->isModuleEnabled(75) && $CurrentOnlineAccessAccount->riskScoreMonitoring == 'True'): ?>
                        <div class="nexure-card margin-bottom-20px">
                            <div class="card-header">
                                <div class="display-flex align-center margin-bottom-10px" style="justify-content:space-between;">
                                    <p class="no-padding"><strong><?php echo $VariableDefinitionHandler->organizationShortName; ?> Risk Score 1.0®</strong></p>
                                </div>
                            </div>
                            <div class="card-body no-padding no-margin" style="padding-top:0px; padding-bottom:5px;">
                                <div class="score-value display-flex align-center no-margin no-padding"><?php echo isset($riskScore) ? (string)$riskScore : '——'; ?> <?php echo '<div class="score-label ' . $category->colorClass() . '">' . $category->label() . '</div>'; ?></div>
                            </div>
                            <div class="card-footer">
                                <div class="score-container margin-top-30px">
                                    <div class="score-bar">
                                        <div class="score-indicator" id="score-indicator"></div>
                                    </div>
                                    <div class="score-range">
                                        <span>0</span>
                                        <span>299</span>
                                        <span>499</span>
                                        <span>699</span>
                                        <span>849</span>
                                        <span>999</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <div id="paybalanceModal" class="modal">
        <div class="modal-content">
            <form method="POST" action="/Dashboard/Administration/Accounts/ChargeCustomer/?account_number=<?php echo $accountnumber; ?>">
                <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Pay customer account balance?</h6>
                <div style="font-size:14px; padding-top:30px; padding-bottom:30px;">
                    <div class="form-control">
                        <span class="margin-right-10px">$</span> <input class="nexure-textbox grey-400" id="balanceNumber" type="numeric" maxlenghth="10" name="balanceNumber" style="width:25%;" placeholder="65.00" />
                    </div>
                </div>
                <p style="font-size:14px; padding-bottom:30px;">Please do not include the currency, simply type the numeric value. This will deduct the balance on the account.</p>
                <div style="display:flex; align-items:right; justify-content:right;">
                    <button class="nexure-button primary" type="submit" name="submit">Submit Payment</button>
                    <a class="nexure-button secondary" href="javascript:void(0)" onclick="closePaymentModal()">Close</a>
                </div>
            </form>
        </div>
    </div>

    <div id="creditLimitModal" class="modal">
        <div class="modal-content">
            <form method="POST" action="/Dashboard/Administration/Accounts/IncreaseLimit/?account_number=<?php echo $accountnumber; ?>">
                <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Increase customer's credit limit?</h6>
                <div style="font-size:14px; padding-top:30px; padding-bottom:30px;">
                    <div class="form-control">
                        <span class="margin-right-10px">$</span> <input class="nexure-textbox grey-400" id="creditLimitNumber" type="numeric" maxlenghth="10" name="creditLimitNumber" style="width:25%;" placeholder="65.00" />
                    </div>
                </div>
                <p style="font-size:14px; padding-bottom:30px;">Please do not include the currency, simply type the numeric value. This will increase the credit limit on the account.</p>
                <div style="display:flex; align-items:right; justify-content:right;">
                    <button class="nexure-button primary" type="submit" name="submit">Submit Credit Line Increase</button>
                    <a class="nexure-button secondary" href="javascript:void(0)" onclick="closeCreditModal()">Close</a>
                </div>
            </form>
        </div>
    </div>

    <div id="setbalanceModal" class="modal">
        <div class="modal-content">
            <form method="POST" action="/Dashboard/Administration/Accounts/AlterBalance/?account_number=<?php echo $accountnumber; ?>">
                <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Increase customer account balance?</h6>
                <div style="font-size:14px; padding-top:30px; padding-bottom:30px;">
                    <div class="form-control">
                        <span class="margin-right-10px">$</span> <input class="nexure-textbox grey-400" id="balanceNumber" type="numeric" maxlenghth="10" name="balanceNumber" style="width:25%;" placeholder="65.00" />
                    </div>
                </div>
                <p style="font-size:14px; padding-bottom:30px;">Please do not include the currency, simply type the numeric value. This will increase the balance on the account.</p>
                <div style="display:flex; align-items:right; justify-content:right;">
                    <button class="nexure-button primary" type="submit" name="submit">Submit Balance Change</button>
                    <a class="nexure-button secondary" href="javascript:void(0)" onclick="closeBalanceModal()">Close</a>
                </div>
            </form>
        </div>
    </div>

    <script>

        var modalChangeBalance = document.getElementById("setbalanceModal");
        var modalPayBalance = document.getElementById("paybalanceModal");
        var modalCreditBalance = document.getElementById("creditLimitModal");

        function openBalanceModal() {
            modalChangeBalance.style.display = "block";
        }

        function closeBalanceModal() {
            modalChangeBalance.style.display = "none";
        }

        function openPaymentModal() {
            modalPayBalance.style.display = "block";
        }

        function closePaymentModal() {
            modalPayBalance.style.display = "none";
        }

        function openCreditModal() {
            modalCreditBalance.style.display = "block";
        }

        function closeCreditModal() {
            modalCreditBalance.style.display = "none";
        }

        window.nexureRiskScore = <?php echo isset($riskScore) ? (int)$riskScore : 0; ?>;
    </script>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>