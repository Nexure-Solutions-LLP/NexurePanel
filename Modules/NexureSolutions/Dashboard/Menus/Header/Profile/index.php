<div class="nexure-grid nexure-one-grid no-row-gap">
    <div class="nexure-card">
        <div class="card-header">
            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                <div class="display-flex align-center padding-bottom-10px">
                    <div class="no-padding margin-right-20px icon-size-formatted">
                        <img src="<?php echo $account['profileImage']; ?>" style="background-color:#fff;" class="client-business-andor-profile-logo" />
                    </div>
                    <div>
                        <p class="no-padding font-12px">Profile</p>
                        <span class="display-flex align-center">
                            <h4 class="text-bold font-20px no-padding" style="padding-bottom:0px; padding-top:5px;"><?php echo $account['displayName']; ?></h4>
                            <?php

                                $statusClasses = [
                                    "Active" => "green",
                                    "Suspended" => "red",
                                    "Terminated" => "red-dark",
                                    "Under Review" => "yellow",
                                    "Disabled" => "passive",
                                    "Restricted" => "red-dark",
                                ];
                                
                                $statusClass = $statusClasses[ucwords(strtolower($account['onlineAccessStatus']))] ?? 'default';
                                echo "<span class='account-status-badge not-rounded $statusClass'>{$account['onlineAccessStatus']}</span>";

                            ?>
                        </span>
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
                        'Type' => 'Online Profile',
                        'Owner' => $identity['legalName'],
                        'First Interaction' => $CurrentAccountExamination->firstinteractiondateformattedfinal,
                        'Last Interaction' => $CurrentAccountExamination->lastinteractiondateformattedfinal
                    ];
                    
                    foreach ($details as $label => $value) {

                        echo "<div style='width:75%;'><p class='no-padding font-12px'>{$label}</p><p class='margin-top-10px font-14px' style='margin-bottom:0; padding-bottom:0;'>{$value}</p></div>";
                    
                    }

                ?>
            </div>
        </div>
    </div>
</div>