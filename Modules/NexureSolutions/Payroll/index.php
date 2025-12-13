<?php

    $PageTitle = "Employee Management";
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
                                    <img src="/Assets/img/SystemImages/Icons/accountsicon.png" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Payroll</p>
                                    <h4 class="text-bold font-20px no-padding" style="padding-bottom:0px; padding-top:5px;">List Employees</h4>
                                </div>
                            </div>
                            <div style="margin-top:-5px;">
                                <a href="/Modules/NexureSolutions/Payroll/NewHire/" class="nexure-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create Employee</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            <?php

                                renderListingTable(
                                    $con,
                                    'accounts',
                                    ['Account Number', 'Name', 'Email', 'Balance', 'Credit Limit', 'Type', 'Status', 'Date', 'Actions'],
                                    ['accountNumber', 'displayName', 'email', 'balance', 'creditLimit', 'accountType', 'accountStatus', 'date'],
                                    ['15%', '15%', '20%', '10%', '10%', '10%', '7%', '10%'],
                                    [
                                        'View' => "/Modules/NexureSolutions/Payroll/ManageHire/?account_number={employee_id}",
                                        'Edit' => "/Modules/NexureSolutions/Payroll/EditHire/?account_number={employee_id}",
                                        'Delete' => "openModal('deleteHire({employee_id})')"
                                    ]
                                );   

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="accountModal" class="modal">
        <div class="modal-content">
            <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Delete customer's account?</h6>
            <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">What you are about to do is permanent and can't be undone. Are you sure you would like to delete this customer. You will need to remake their account if you would like to restore it.</p>
            <div style="display:flex; align-items:right; justify-content:right;">
                <a id="deleteLink" href="#" class="caliweb-button secondary red" style="margin-right:20px;">Delete Account</a>
                <button class="caliweb-button primary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        var modal = document.getElementById("accountModal");

        function openModal(employee_id) {
            deleteLink.href = "/Modules/NexureSolutions/Payroll/DeleteHire/?employee_id=" + encodeURIComponent(employee_id);
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }
    </script>


<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>