<?php

    $PageTitle = "Customer Accounts";
    $PageType = "Administration";

    unset($_SESSION['verification_code']);

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");
    include($_SERVER["DOCUMENT_ROOT"].'/Modules/NexureSolutions/Tables/index.php');

    $email = $_GET['email'] ?? '';

    if (!$email) {

        header("location: /Dashboard/Administration/Profiles");
        
        exit;

    }

    // These sets of calls pulls account data from the database including customer risk score.

    $CurrentAccountExamination->LoadFullCustomerProfile($con, $email);

    $riskScore = $CurrentAccountExamination->NexureRiskScore10;
    $category  = RiskScoreCategory::fromScore($riskScore);

    $account = $CurrentAccountExamination->selectedUserProfile;
    $identity = $CurrentAccountExamination->identity[0] ?? [];

?>

    <title>Emmie® by <?php echo $VariableDefinitionHandler->organizationShortName; ?> | <?php echo $PageTitle; ?></title>


    <section class="section dashboard">
        <div class="container nexure-container">
            <?php include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Menus/Header/Profile/index.php"); ?>
            <div class="nexure-grid nexure-two-grid no-row-gap margin-top-30px account-grid-modified">
                <div>
                    <div class="margin-bottom-20px">
                        <div class="nexure-grid nexure-five-grid align-center no-column-gap no-row-gap">
                            <a href="/Dashboard/Administration/Profiles/ManageProfile/?email=<?php echo $account["email"]; ?>">
                                <div class="text-center border-selected">
                                    <p class="font-12px padding-bottom-10px">Nexure Accounts</p>
                                </div>
                            </a>
                            <a href="/Dashboard/Administration/Profiles/ManageProfile/PaymentMethods/?email=<?php echo $account["email"]; ?>">
                                <div class="text-center border-netural">
                                    <p class="font-12px padding-bottom-10px">Payment Methods</p>
                                </div>
                            </a>
                            <a href="/Dashboard/Administration/Profiles/ManageProfile/Relationships/?email=<?php echo $account["email"]; ?>">
                                <div class="text-center border-netural">
                                    <p class="font-12px padding-bottom-10px">Relationships</p>
                                </div>
                            </a>
                            <a href="/Dashboard/Administration/Profiles/ManageProfile/Goals/?email=<?php echo $account["email"]; ?>">
                                <div class="text-center border-netural">
                                    <p class="font-12px padding-bottom-10px">Goals</p>
                                </div>
                            </a>
                            <a href="/Dashboard/Administration/Profiles/ManageProfile/Related/?email=<?php echo $account["email"]; ?>">
                                <div class="text-center border-netural">
                                    <p class="font-12px padding-bottom-10px">Related</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="nexure-card margin-bottom-20px">
                        <div class="display-flex align-center">
                            <div class="no-padding margin-right-20px icon-size-formatted">
                                <img src="/Assets/img/SystemImages/Icons/duplicate.png" style="background-color:#fff9dd;" class="client-business-andor-profile-logo" />
                            </div>
                            <h6 class="no-margin no-padding" style="font-size:18px; font-weight:700; margin-top:0px;">We found no potential duplicates of this profile.</h6>
                        </div>
                    </div>
                    <div class="nexure-card">
                        <div class="card-header">
                            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                                <p class="no-padding font-14px">Nexure Accounts</p>
                                <a href="/Dashboard/Administration/Accounts/OpenAccount/index.php?email=<?php echo $account["email"]; ?>" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Open Account</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dashboard-table">
                                <?php

                                    renderListingTable(
                                        $con,
                                        'accounts',
                                        ['Account Number', 'Balance', 'Credit Limit', 'Type', 'Status', 'Date', 'Actions'],
                                        ['accountNumber', 'balance', 'creditLimit', 'accountType', 'accountStatus', 'date'],
                                        ['18%', '14%', '15%', '15%', '10%', '10%'],
                                        [
                                            'View' => "/Dashboard/Administration/Accounts/ManageAccount/?account_number={accountNumber}",
                                            'Edit' => "/Dashboard/Administration/Accounts/EditAccount/?account_number={accountNumber}",
                                            'Delete' => "openModal('deleteAccount({accountNumber})')"
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
                                <p class="no-padding font-14px">Identities</p>
                                <a href="/Dashboard/Administration/Contacts/CreateContact/index.php?email=<?php echo $account["email"]; ?>" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create Identity</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dashboard-table">
                                <?php

                                    

                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="nexure-card margin-top-20px">
                        <div class="card-header">
                            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                                <p class="no-padding font-14px">Businesses</p>
                                <a href="/Dashboard/Administration/Contacts/CreateContact/index.php?email=<?php echo $account["email"]; ?>" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Create Business</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dashboard-table">
                                <?php

                                    

                                ?>
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
                                    <p style="font-size:12px; margin-bottom:10px;">Customer Since</p>
                                    <?php

                                        $registrationYear = date('Y', strtotime($CurrentAccountExamination->firstinteractiondateformattedfinal));

                                    ?>
                                    <p style="font-size:16px; font-weight:800; font-family: Mona Sans, sans-serif;"><?php echo $registrationYear; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nexure-card margin-bottom-20px">
                        <div class="card-header">
                            <div class="display-flex align-center margin-bottom-10px" style="justify-content:space-between;">
                                <p class="no-padding">Notes and Activity</p>
                                <a href="/Dashboard/Administration/Accounts/ManageAccount/PlaceNote/?email=<?php echo $account["email"]; ?>" class="nexure-button secondary no-margin" style="padding:6px 24px;">Place Note</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="font-14px no-padding">No notes have been made for this account.</p>
                        </div>
                    </div>
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
            <form method="POST" action="/Dashboard/Administration/Accounts/ChargeCustomer/?email=<?php echo $account["email"]; ?>">
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
            <form method="POST" action="/Dashboard/Administration/Accounts/IncreaseLimit/?email=<?php echo $account["email"]; ?>">
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
            <form method="POST" action="/Dashboard/Administration/Accounts/AlterBalance/?email=<?php echo $account["email"]; ?>">
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