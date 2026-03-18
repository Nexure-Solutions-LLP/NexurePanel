<?php

    $PageTitle = "Customer Accounts";
    $PageType = "Administration";

    unset($_SESSION['verification_code']);

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");
    include($_SERVER["DOCUMENT_ROOT"].'/Modules/NexureSolutions/Tables/index.php');

?>

    <title>Emmie® by <?php echo $VariableDefinitionHandler->organizationShortName; ?> | <?php echo $PageTitle; ?></title>


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
                                    <p class="no-padding font-14px">Profiles</p>
                                    <h4 class="text-bold font-20px no-padding" style="padding-bottom:0px; padding-top:5px;">List Profiles</h4>
                                </div>
                            </div>
                            <div style="margin-top:-5px;">
                                <a href="/Dashboard/Administration/Profiles/CreateProfile/" class="nexure-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create Profile</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            <?php

                                renderListingTable(
                                    $con,
                                    'users',
                                    ['Name', 'Email', 'Type', 'Risk Monitoring', 'Status', 'Date', 'Actions'],
                                    ['displayName', 'email',  'accessLevel', 'riskScoreMonitoring', 'onlineAccessStatus', 'firstInteractionDate'],
                                    ['20%', '20%', '15%', '10%', '10%', '10%'],
                                    [
                                        'View' => "/Dashboard/Administration/Profiles/ManageProfile/?email={email}",
                                        'Edit' => "/Dashboard/Administration/Profiles/EditProfile/?email={email}",
                                        'Delete' => "openModal('deleteProfile({email})')"
                                    ]
                                );   

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="profileModal" class="modal">
        <div class="modal-content">
            <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Delete customer's profile?</h6>
            <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">What you are about to do is permanent and can't be undone. Are you sure you would like to delete this customer. You will need to remake their profile if you would like to restore it.</p>
            <div style="display:flex; align-items:right; justify-content:right;">
                <a id="deleteLink" href="#" class="nexure-button secondary red" style="margin-right:20px;">Delete Profile</a>
                <button class="nexure-button primary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        var modal = document.getElementById("profileModal");

        function openModal(email) {
            deleteLink.href = "/Dashboard/Administration/Profiles/DeleteProfile/?email=" + encodeURIComponent(email);
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }
    </script>


<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>