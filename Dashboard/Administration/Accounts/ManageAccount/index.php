<?php

    $PageTitle = "Customer Accounts";
    $PageType = "Administration";

    unset($_SESSION['verification_code']);

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");
    include($_SERVER["DOCUMENT_ROOT"].'/Modules/NexureSolutions/Tables/index.php');

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
                                    <h4 class="text-bold font-20px no-padding" style="padding-bottom:0px; padding-top:5px;">Nexure Solutions LLP - 801100000000</h4>
                                </div>
                            </div>
                            <div style="margin-top:-5px;">
                                <a href="/Dashboard/Administration/Accounts/EditAccount/" class="nexure-button primary no-margin margin-5px-right" style="padding:6px 24px;">Edit</a>
                                <a href="/Dashboard/Administration/Accounts/AlterBalance/" class="nexure-button secondary no-margin margin-5px-right" style="padding:6px 24px;">Alter Balance</a>
                                <a href="/Dashboard/Administration/Accounts/ChargeCustomer/" class="nexure-button secondary no-margin margin-5px-right" style="padding:6px 24px;">Pay on Account</a>
                                <a href="/Dashboard/Administration/Accounts/ViewAsOwner/" class="nexure-button secondary no-margin margin-5px-right" style="padding:6px 24px;">View as Owner</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            
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
                                <p class="no-padding font-14px">Current Services</p>
                                <a href="/Dashboard/Administration/Accounts/OrderServices/index.php?account_number=" class="nexure-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Order Service</a>
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
                        <div class="card-body">
                            <div class="dashboard-table">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="nexure-card">
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
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>