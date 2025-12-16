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
                        'Credit Limit' => number_format((float)$account['creditLimit'], 2).'<a href="javascript:void(0);" onclick="openCreditModal()" class="brand-link"> (Change Limit)</a>',
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