<?php

    $PageTitle = "Customer Accounts";
    $PageType = "Administration";

    unset($_SESSION['verification_code']);

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");
    include($_SERVER["DOCUMENT_ROOT"].'/Modules/NexureSolutions/Tables/index.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

        mysqli_begin_transaction($con);

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {

            function clean($val) {
                return trim(htmlspecialchars($val, ENT_QUOTES, 'UTF-8'));
            }

            $name          = clean($_POST['name']);
            $email         = clean($_POST['email']);
            $password      = $_POST['password'];
            $role          = clean($_POST['role']);
            $status        = clean($_POST['status']);
            $paymentStatus = clean($_POST['paymentstatus']);
            $accountType   = clean($_POST['accounttype']);
            $creditLimit   = clean($_POST['creditlimit']);
            $phone         = clean($_POST['phonenumber']);
            $hashedPassword = hash('sha512', $password);

            $checkUser = $con->prepare(
                "SELECT id FROM nexure_users WHERE email = ? LIMIT 1"
            );
            $checkUser->bind_param("s", $email);
            $checkUser->execute();
            $checkUser->store_result();

            if ($checkUser->num_rows === 0) {

                $insertUser = $con->prepare("
                    INSERT INTO nexure_users (
                        displayName, email, password,
                        accessLevel, onlineAccessStatus,
                        emailStatus, accountStatusReason,
                        accountStatusDate, emailVerificationDate,
                        firstInteractionDate, lastInteractionDate,
                        oAuthID, paymentID, profileImage,
                        riskScoreMonitoring
                    ) VALUES (?, ?, ?, ?, 'Active', 'Unverified', '',
                            NOW(), NULL, NOW(), NOW(),
                            '', '', '', 'false')
                ");

                $insertUser->bind_param(
                    "ssss",
                    $name,
                    $email,
                    $hashedPassword,
                    $role
                );

                $insertUser->execute();
            }

            do {
                $randomPart   = str_pad(random_int(0, 9999999), 8, '0', STR_PAD_LEFT);
                $accountNumber = $ACCOUNT_NUMBER_PREFIX . $randomPart;

                $checkAcc = $con->prepare(
                    "SELECT id FROM nexure_accounts WHERE accountNumber = ?"
                );
                $checkAcc->bind_param("s", $accountNumber);
                $checkAcc->execute();
                $checkAcc->store_result();

            } while ($checkAcc->num_rows > 0);

            $insertAccount = $con->prepare("
                INSERT INTO nexure_accounts (
                    email, accountNumber, accountStatus,
                    accountStatusReason, accountType,
                    creditLimit, paymentStatus,
                    openedDate, restrictedDate, closedDate
                ) VALUES (?, ?, ?, '', ?, ?, ?, CURDATE(), NULL, NULL)
            ");

            $insertAccount->bind_param(
                "ssssss",
                $email,
                $accountNumber,
                $status,
                $accountType,
                $creditLimit,
                $paymentStatus
            );

            $insertAccount->execute();

            if ($accountType === 'Business') {

                $insertBusiness = $con->prepare("
                    INSERT INTO nexure_businesses (
                        accountNumber, businessLegalName,
                        businessDBAName, businessIndustry,
                        businessDescription, businessRevenue,
                        businessLegalStructure, businessType,
                        businessLine1, businessLine2,
                        businessCity, businessState,
                        businessCountry, businessPostalCode,
                        businessTIN
                    ) VALUES (?, ?, ?, '', '', '',
                            ?, ?, ?, ?, ?, ?, ?, '', ?)
                ");

                $insertBusiness->bind_param(
                    "ssssssssssss",
                    $accountNumber,
                    clean($_POST['businessname']),
                    clean($_POST['businessdba']),
                    clean($_POST['businessstructure']),
                    clean($_POST['businesstype']),
                    clean($_POST['addressline1']),
                    clean($_POST['addressline2']),
                    clean($_POST['city']),
                    clean($_POST['state']),
                    clean($_POST['country']),
                    clean($_POST['businesstaxid'])
                );

                $insertBusiness->execute();
            }

            $insertOwnership = $con->prepare("
                INSERT INTO nexure_ownership (
                    accountNumber, legalName, mobileNumber,
                    addressLine1, addressLine2,
                    city, state, country,
                    postalCode, socialSecurityNumber
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, '', '')
            ");

            $insertOwnership->bind_param(
                "ssssssss",
                $accountNumber,
                $name,
                $phone,
                clean($_POST['addressline1']),
                clean($_POST['addressline2']),
                clean($_POST['city']),
                clean($_POST['state']),
                clean($_POST['country'])
            );

            $insertOwnership->execute();

            mysqli_commit($con);

            header("Location: /Dashboard/Administration/Accounts/");
            exit;

        } catch (Exception $e) {

            mysqli_rollback($con);
            error_log("Account Creation Failed: " . $e->getMessage());

            header("Location: ?error=account_creation_failed");
            exit;

        }

    }

?>

    <title>Emmie® by <?php echo $VariableDefinitionHandler->organizationShortName; ?> | <?php echo $PageTitle; ?></title>


    <section class="section dashboard">
        <div class="container nexure-container">
            <div class="nexure-grid nexure-one-grid no-row-gap">
                <form action="" method="POST">
                    <div class="nexure-card">
                        <div class="card-header">
                            <div class="display-flex justify-content-space-between align-center padding-bottom-10px">
                                <div class="display-flex align-center padding-bottom-10px">
                                    <div class="no-padding margin-right-20px icon-size-formatted">
                                        <img src="/Assets/img/SystemImages/Icons/accountsicon.png" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <div>
                                        <p class="no-padding font-14px">Accounts</p>
                                        <h4 class="text-bold font-20px no-padding" style="padding-bottom:0px; padding-top:5px;">Open Account</h4>
                                    </div>
                                </div>
                                <div style="margin-top:-5px;">
                                    <button type="submit" name="submit" class="nexure-button primary no-margin margin-right-20px" style="padding:6px 24px; margin-left:0; margin-right:10px;">Submit account</button>
                                    <a href="/Dashboard/Administration/Accounts/OpenAccount" class="nexure-button secondary no-margin margin-right-20px" style="padding:6px 24px; margin-left:0; margin-right:10px;">Reset</a>
                                    <a href="/Dashboard/Administration/Accounts/" class="nexure-button secondary no-margin margin-right-20px" style="padding:6px 24px; margin-left:0; margin-right:10px;">Return</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="background-grey-100 margin-bottom-20px">
                                <p class="font-12px text-uppercase text-bold">Basic Information</p>
                            </div>
                            <div class="nexure-grid nexure-three-grid no-row-gap width-100">
                                <div>
                                    <div classs="form-control">
                                        <label for="name">Legal Name</label>
                                        <input class="nexure-textbox" name="name" type="text" placeholder="John Doe" required="" />
                                    </div>
                                    <br>
                                    <div classs="form-control padding-top-10px">
                                        <label for="role">Role</label>
                                        <select class="nexure-textbox" name="role">
                                            <option>Customer</option>
                                            <option>Partner</option>
                                            <option>Authorized User</option>
                                            <option>Employee</option>
                                            <option>Manager</option>
                                            <option>Administrator</option>
                                        </select>
                                    </div>
                                    <br>
                                    <div classs="form-control padding-top-10px">
                                        <label for="paymentstatus">Payment Status</label>
                                        <select class="nexure-textbox" name="paymentstatus">
                                            <optgroup label="Account Status Codes">
                                                <option value="11">11 – Current Account</option>
                                                <option value="13">13 – Paid or Closed Account / Zero Balance</option>
                                                <option value="05">05 – Account Transferred to Another Office</option>
                                                <option value="DA">DA – Delete Entire Account (Non-Fraud)</option>
                                                <option value="DF">DF – Delete Entire Account (Confirmed Fraud)</option>
                                            </optgroup>

                                            <optgroup label="Payment Status Report Codes">
                                                <option value="71">71 – Account 30 Days Past Due</option>
                                                <option value="78">78 – Account 60 Days Past Due</option>
                                                <option value="80">80 – Account 90 Days Past Due</option>
                                                <option value="82">82 – Account 120 Days Past Due</option>
                                                <option value="83">83 – Account 150 Days Past Due</option>
                                                <option value="84">84 – Account 180 Days or More Past Due</option>
                                                <option value="93">93 – Account Seriously Past Due / Sent to Collections</option>
                                            </optgroup>

                                            <optgroup label="Paid Derogatory Accounts">
                                                <option value="61">61 – Paid in Full, Voluntary Surrender</option>
                                                <option value="62">62 – Paid in Full, Collection / Insurance / Government Claim</option>
                                                <option value="63">63 – Paid in Full, Repossession</option>
                                                <option value="64">64 – Paid in Full, Charge-Off</option>
                                                <option value="65">65 – Paid in Full, Foreclosure Started</option>
                                            </optgroup>

                                            <optgroup label="Mortgage, Foreclosure & Asset Recovery">
                                                <option value="88">88 – Government Claim Filed on Defaulted Loan</option>
                                                <option value="89">89 – Deed in Lieu of Foreclosure</option>
                                                <option value="94">94 – Foreclosure / Collateral Sold to Settle Mortgage</option>
                                                <option value="95">95 – Voluntary Surrender (Not for Lease Terminations)</option>
                                                <option value="96">96 – Merchandise Repossessed; Balance May Remain</option>
                                                <option value="97">97 – Unpaid Balance Reported as a Loss (Charge-Off)</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div>   
                                    <div classs="form-control">
                                        <label for="email">Email</label>
                                        <input class="nexure-textbox" name="email" type="email" placeholder="me@example.com" required="" />
                                    </div>
                                    <br>
                                    <div classs="form-control padding-top-10px">
                                        <label for="status">Status</label>
                                        <select class="nexure-textbox" name="status">
                                            <option>Active</option>
                                            <option>Under Review</option>
                                            <option>Suspended</option>
                                            <option>Terminated</option>
                                            <option>Restricted</option>
                                            <option>Closed</option>
                                        </select>
                                    </div>
                                    <br>
                                    <div classs="form-control padding-top-10px">
                                        <label for="accounttype">Type</label>
                                        <select class="nexure-textbox" name="accounttype">
                                            <option>Personal</option>
                                            <option>Business</option>
                                        </select>
                                    </div>
                                </div>
                                <div>   
                                    <div classs="form-control">
                                        <label for="phonenumber">Phone Number</label>
                                        <input class="nexure-textbox" name="phonenumber" type="text" placeholder="1123456789" required="" />
                                    </div>
                                    <br>
                                    <div classs="form-control padding-top-10px">
                                        <label for="creditlimit">Credit Limit</label>
                                        <input class="nexure-textbox" name="creditlimit" type="text" placeholder="0.00" required="" />
                                    </div>
                                    <br>
                                    <div classs="form-control" style="padding-top-10px">
                                        <label for="catagorization">Catagorization</label>
                                        <select class="nexure-textbox" name="catagorization">
                                            <option>Corporate Treasury</option>
                                            <option>Line of Credit</option>
                                            <option>Credit Card</option>
                                            <option>Loan</option>
                                            <option>Checking Account</option>
                                            <option>Checking Account with Overdraft</option>
                                            <option>Savings Account</option>
                                            <option>Merchant Proccessing</option>
                                            <option>Service Account</option>
                                            <option>Utility</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="background-grey-100 margin-bottom-20px margin-top-30px">
                                <p class="font-12px text-uppercase text-bold">Business Information</p>
                            </div>
                            <div class="nexure-grid nexure-three-grid no-row-gap width-100">
                                <div>
                                    <div classs="form-control">
                                        <label for="businessname">Business Name</label>
                                        <input class="nexure-textbox" name="businessname" type="text" placeholder="Little Internet Widgets" />
                                    </div>
                                    <br>
                                    <div classs="form-control padding-top-10px">
                                        <label for="addressline1">Business Line 1</label>
                                        <input class="nexure-textbox" name="addressline1" type="text" placeholder="123 Main Street" />
                                    </div>
                                    <br>
                                    <div classs="form-control padding-top-10px">
                                        <label for="state">State</label>
                                        <input class="nexure-textbox" name="state" type="text" placeholder="AS" />
                                    </div>
                                    <br> 
                                    <div class="form-control padding-top-10px">
                                        <label for="businessstructure">Business Legal Structure</label>
                                        <select class="nexure-textbox" name="businessstructure" id="businessstructure">
                                            <option value="">Select Legal Structure</option>
                                            <option value="sole-proprietorship">Sole Proprietorship</option>
                                            <option value="general-partnership">General Partnership</option>
                                            <option value="limited-partnership">Limited Partnership (LP)</option>
                                            <option value="limited-liability-partnership">Limited Liability Partnership (LLP)</option>
                                            <option value="limited-liability-company">Limited Liability Company (LLC)</option>
                                            <option value="corporation">Corporation</option>
                                            <option value="benefit-corporation">Benefit Corporation (B-Corp)</option>
                                            <option value="nonprofit-corporation">Non-Profit Corporation</option>
                                            <option value="professional-corporation">Professional Corporation (PC)</option>
                                            <option value="joint-stock-company">Joint Stock Company</option>
                                            <option value="cooperative">Cooperative Association</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <div classs="form-control">
                                        <label for="businessdba">Business DBA</label>
                                        <input class="nexure-textbox" name="businessdba" type="text" placeholder="Little Internet" />
                                    </div>
                                    <br>
                                    <div classs="form-control padding-top-10px">
                                        <label for="addressline2">Business Line 2</label>
                                        <input class="nexure-textbox" name="addressline2" type="text" />
                                    </div>
                                    <div class="form-control padding-top-20px">
                                        <label for="country">Country</label>
                                        <select class="nexure-textbox" name="country" id="country">
                                            <option value="">Select Country</option>
                                            <option value="AF">Afghanistan</option>
                                            <option value="AL">Albania</option>
                                            <option value="DZ">Algeria</option>
                                            <option value="AS">American Samoa</option>
                                            <option value="AD">Andorra</option>
                                            <option value="AO">Angola</option>
                                            <option value="AI">Anguilla</option>
                                            <option value="AQ">Antarctica</option>
                                            <option value="AG">Antigua and Barbuda</option>
                                            <option value="AR">Argentina</option>
                                            <option value="AM">Armenia</option>
                                            <option value="AW">Aruba</option>
                                            <option value="AU">Australia</option>
                                            <option value="AT">Austria</option>
                                            <option value="AZ">Azerbaijan</option>
                                            <option value="BS">Bahamas</option>
                                            <option value="BH">Bahrain</option>
                                            <option value="BD">Bangladesh</option>
                                            <option value="BB">Barbados</option>
                                            <option value="BY">Belarus</option>
                                            <option value="BE">Belgium</option>
                                            <option value="BZ">Belize</option>
                                            <option value="BJ">Benin</option>
                                            <option value="BM">Bermuda</option>
                                            <option value="BT">Bhutan</option>
                                            <option value="BO">Bolivia</option>
                                            <option value="BA">Bosnia and Herzegovina</option>
                                            <option value="BW">Botswana</option>
                                            <option value="BR">Brazil</option>
                                            <option value="IO">British Indian Ocean Territory</option>
                                            <option value="BN">Brunei Darussalam</option>
                                            <option value="BG">Bulgaria</option>
                                            <option value="BF">Burkina Faso</option>
                                            <option value="BI">Burundi</option>
                                            <option value="CV">Cabo Verde</option>
                                            <option value="KH">Cambodia</option>
                                            <option value="CM">Cameroon</option>
                                            <option value="CA">Canada</option>
                                            <option value="KY">Cayman Islands</option>
                                            <option value="CF">Central African Republic</option>
                                            <option value="TD">Chad</option>
                                            <option value="CL">Chile</option>
                                            <option value="CN">China</option>
                                            <option value="CO">Colombia</option>
                                            <option value="KM">Comoros</option>
                                            <option value="CG">Congo</option>
                                            <option value="CD">Congo (Democratic Republic)</option>
                                            <option value="CR">Costa Rica</option>
                                            <option value="HR">Croatia</option>
                                            <option value="CU">Cuba</option>
                                            <option value="CY">Cyprus</option>
                                            <option value="CZ">Czech Republic</option>
                                            <option value="DK">Denmark</option>
                                            <option value="DJ">Djibouti</option>
                                            <option value="DM">Dominica</option>
                                            <option value="DO">Dominican Republic</option>
                                            <option value="EC">Ecuador</option>
                                            <option value="EG">Egypt</option>
                                            <option value="SV">El Salvador</option>
                                            <option value="GQ">Equatorial Guinea</option>
                                            <option value="ER">Eritrea</option>
                                            <option value="EE">Estonia</option>
                                            <option value="SZ">Eswatini</option>
                                            <option value="ET">Ethiopia</option>
                                            <option value="FJ">Fiji</option>
                                            <option value="FI">Finland</option>
                                            <option value="FR">France</option>
                                            <option value="GA">Gabon</option>
                                            <option value="GM">Gambia</option>
                                            <option value="GE">Georgia</option>
                                            <option value="DE">Germany</option>
                                            <option value="GH">Ghana</option>
                                            <option value="GR">Greece</option>
                                            <option value="GD">Grenada</option>
                                            <option value="GT">Guatemala</option>
                                            <option value="GN">Guinea</option>
                                            <option value="GW">Guinea-Bissau</option>
                                            <option value="GY">Guyana</option>
                                            <option value="HT">Haiti</option>
                                            <option value="HN">Honduras</option>
                                            <option value="HK">Hong Kong</option>
                                            <option value="HU">Hungary</option>
                                            <option value="IS">Iceland</option>
                                            <option value="IN">India</option>
                                            <option value="ID">Indonesia</option>
                                            <option value="IR">Iran</option>
                                            <option value="IQ">Iraq</option>
                                            <option value="IE">Ireland</option>
                                            <option value="IL">Israel</option>
                                            <option value="IT">Italy</option>
                                            <option value="JM">Jamaica</option>
                                            <option value="JP">Japan</option>
                                            <option value="JO">Jordan</option>
                                            <option value="KZ">Kazakhstan</option>
                                            <option value="KE">Kenya</option>
                                            <option value="KI">Kiribati</option>
                                            <option value="KR">Korea (Republic of)</option>
                                            <option value="KW">Kuwait</option>
                                            <option value="KG">Kyrgyzstan</option>
                                            <option value="LA">Lao People's Democratic Republic</option>
                                            <option value="LV">Latvia</option>
                                            <option value="LB">Lebanon</option>
                                            <option value="LS">Lesotho</option>
                                            <option value="LR">Liberia</option>
                                            <option value="LY">Libya</option>
                                            <option value="LI">Liechtenstein</option>
                                            <option value="LT">Lithuania</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="MO">Macao</option>
                                            <option value="MG">Madagascar</option>
                                            <option value="MW">Malawi</option>
                                            <option value="MY">Malaysia</option>
                                            <option value="MV">Maldives</option>
                                            <option value="ML">Mali</option>
                                            <option value="MT">Malta</option>
                                            <option value="MH">Marshall Islands</option>
                                            <option value="MR">Mauritania</option>
                                            <option value="MU">Mauritius</option>
                                            <option value="MX">Mexico</option>
                                            <option value="FM">Micronesia (Federated States of)</option>
                                            <option value="MD">Moldova</option>
                                            <option value="MC">Monaco</option>
                                            <option value="MN">Mongolia</option>
                                            <option value="ME">Montenegro</option>
                                            <option value="MA">Morocco</option>
                                            <option value="MZ">Mozambique</option>
                                            <option value="MM">Myanmar</option>
                                            <option value="NA">Namibia</option>
                                            <option value="NR">Nauru</option>
                                            <option value="NP">Nepal</option>
                                            <option value="NL">Netherlands</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="NI">Nicaragua</option>
                                            <option value="NE">Niger</option>
                                            <option value="NG">Nigeria</option>
                                            <option value="NO">Norway</option>
                                            <option value="OM">Oman</option>
                                            <option value="PK">Pakistan</option>
                                            <option value="PW">Palau</option>
                                            <option value="PS">Palestine, State of</option>
                                            <option value="PA">Panama</option>
                                            <option value="PG">Papua New Guinea</option>
                                            <option value="PY">Paraguay</option>
                                            <option value="PE">Peru</option>
                                            <option value="PH">Philippines</option>
                                            <option value="PL">Poland</option>
                                            <option value="PT">Portugal</option>
                                            <option value="QA">Qatar</option>
                                            <option value="RO">Romania</option>
                                            <option value="RU">Russian Federation</option>
                                            <option value="RW">Rwanda</option>
                                            <option value="KN">Saint Kitts and Nevis</option>
                                            <option value="LC">Saint Lucia</option>
                                            <option value="VC">Saint Vincent and the Grenadines</option>
                                            <option value="WS">Samoa</option>
                                            <option value="SM">San Marino</option>
                                            <option value="ST">Sao Tome and Principe</option>
                                            <option value="SA">Saudi Arabia</option>
                                            <option value="SN">Senegal</option>
                                            <option value="RS">Serbia</option>
                                            <option value="SC">Seychelles</option>
                                            <option value="SL">Sierra Leone</option>
                                            <option value="SG">Singapore</option>
                                            <option value="SK">Slovakia</option>
                                            <option value="SI">Slovenia</option>
                                            <option value="SB">Solomon Islands</option>
                                            <option value="SO">Somalia</option>
                                            <option value="ZA">South Africa</option>
                                            <option value="ES">Spain</option>
                                            <option value="LK">Sri Lanka</option>
                                            <option value="SD">Sudan</option>
                                            <option value="SR">Suriname</option>
                                            <option value="SE">Sweden</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="SY">Syrian Arab Republic</option>
                                            <option value="TW">Taiwan</option>
                                            <option value="TJ">Tajikistan</option>
                                            <option value="TZ">Tanzania</option>
                                            <option value="TH">Thailand</option>
                                            <option value="TL">Timor-Leste</option>
                                            <option value="TG">Togo</option>
                                            <option value="TO">Tonga</option>
                                            <option value="TT">Trinidad and Tobago</option>
                                            <option value="TN">Tunisia</option>
                                            <option value="TR">Türkiye (Turkey)</option>
                                            <option value="TM">Turkmenistan</option>
                                            <option value="TV">Tuvalu</option>
                                            <option value="UG">Uganda</option>
                                            <option value="UA">Ukraine</option>
                                            <option value="AE">United Arab Emirates</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="US">United States</option>
                                            <option value="UY">Uruguay</option>
                                            <option value="UZ">Uzbekistan</option>
                                            <option value="VU">Vanuatu</option>
                                            <option value="VE">Venezuela</option>
                                            <option value="VN">Viet Nam</option>
                                            <option value="YE">Yemen</option>
                                            <option value="ZM">Zambia</option>
                                            <option value="ZW">Zimbabwe</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <div classs="form-control">
                                        <label for="">Business Tax ID</label>
                                        <input class="nexure-textbox" name="businesstaxid" type="text" placeholder="123456789" />
                                    </div>
                                    <br>
                                    <div classs="form-control padding-top-10px">
                                        <label for="city">City</label>
                                        <input class="nexure-textbox" name="city" type="text" placeholder="Anycity" />
                                    </div>
                                    <div class="form-control padding-top-20px">
                                        <label for="businesstype">Business Type</label>
                                        <select class="nexure-textbox" name="businesstype" id="businesstype">
                                            <option value="">Select Business Type</option>
                                            <option value="sole-proprietor">Sole Proprietor</option>
                                            <option value="partnership">Partnership</option>
                                            <option value="limited-liability">Limited Liability (LLC)</option>
                                            <option value="corporation">Corporation (Inc.)</option>
                                            <option value="s-corporation">S-Corporation</option>
                                            <option value="c-corporation">C-Corporation</option>
                                            <option value="cooperative">Cooperative</option>
                                            <option value="nonprofit">Non-Profit Organization</option>
                                            <option value="trust">Trust</option>
                                            <option value="joint-venture">Joint Venture</option>
                                            <option value="franchise">Franchise</option>
                                            <option value="government-entity">Government Entity</option>
                                            <option value="private-company">Private Company</option>
                                            <option value="public-company">Public Company</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="background-grey-100 margin-bottom-20px margin-top-30px">
                                <p class="font-12px text-uppercase text-bold">Online Information</p>
                            </div>
                            <div class="nexure-grid nexure-three-grid no-row-gap width-100">
                                <div>
                                    <div classs="form-control">
                                        <label for="password">Password</label>
                                        <input class="nexure-textbox" name="password" type="password" placeholder="Super Secret Password" required="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>