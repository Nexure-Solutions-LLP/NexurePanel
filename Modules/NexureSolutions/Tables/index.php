<?php

    // Emmi by Nexure Table Functions
    // Handles tables for accounts, blacklists, leads, campaigns, tasks, cases, users, etc.
    // Editing this file updates tables system-wide.

    if (!function_exists('getStatusBadge')) {

        function getStatusBadge($status) {
            $statusClasses = [
                "open" => "green",
                "active" => "green",
                "cleared" => "passive",
                "pending" => "yellow",
                "under review" => "yellow",
                "restricted" => "red-dark",
                "suspended" => "red",
                "terminated" => "red",
                "closed" => "passive",
                "completed" => "green",
                "in progress" => "yellow",
                "failed" => "red"
            ];

            $statusClass = $statusClasses[strtolower($status)] ?? "unknown";

            $status = ucfirst($status);

            return "<span class='account-status-badge {$statusClass}' style='margin-left:0;'>{$status}</span>";

        }

    }

    if (!function_exists('formatDateTime')) {

        function formatDateTime($dateValue) {

            if (empty($dateValue) || $dateValue == "0000-00-00" || $dateValue == "0000-00-00 00:00:00") {

                return "—";

            }

            $timestamp = strtotime($dateValue);

            if (!$timestamp) return "—";

            $hasTime = (strpos($dateValue, ':') !== false);

            return $hasTime
                ? date("F j Y g:i A", $timestamp)
                : date("F j Y g:i A", $timestamp);
        }

    }

    if (!function_exists('maskAccountNumber')) {

        function maskAccountNumber($number) {

            if (empty($number)) return "—";

            $last4 = substr($number, -4);

            return "•••••••• {$last4}";

        }

    }

    if (!function_exists('renderTableHeaders')) {

        function renderTableHeaders($headers, $columnWidths) {

            $headerHtml = '<tr>';

            foreach ($headers as $index => $header) {

                $width = $columnWidths[$index] ?? 'auto';

                $headerHtml .= "<th style='width:{$width};'>{$header}</th>";

            }

            $headerHtml .= '</tr>';

            return $headerHtml;
        }

    }

    if (!function_exists('renderActionUrls')) {

        function renderActionUrls($row, $actionUrls) {

            $actionHtml = '<td>';

            foreach ($actionUrls as $action => $urlTemplate) {

                $actionUrl = $urlTemplate;

                foreach ($row as $key => $value) {

                    $actionUrl = str_replace('{' . $key . '}', $value, $actionUrl);

                }

                if (strpos($actionUrl, "openModal(") !== false) {
                    
                    $actionHtml .= '<a onclick="' . $actionUrl . '" class="nexure-button secondary no-margin margin-10px-right font-12px" style="padding:6px 24px; margin-right:10px; margin-left:0;">' . $action . '</a>';
                
                } else {

                    $actionHtml .= "<a href='{$actionUrl}' class='nexure-button secondary no-margin margin-10px-right font-12px' style='padding:6px 24px; margin-right:10px; margin-left:0;'>{$action}</a>";
                }

            }

            $actionHtml .= '</td>';

            return $actionHtml;

        }

    }

    if (!function_exists('renderListingRow')) {

        function renderListingRow($row, $columns, $columnWidths, $actionUrls = [], $truncateTitle = false) {

            $rowHtml = '<tr>';

            foreach ($columns as $index => $column) {

                $width = $columnWidths[$index] ?? 'auto';

                $value = $row[$column] ?? '';

                switch (strtolower($column)) {

                    case 'accountnumber':
                        $value = maskAccountNumber($value);
                        break;
                    case 'orderdate':
                    case 'renderdate':
                    case 'created_at':
                        $value = $value ?: '—';
                        if ($truncateTitle && strlen($value) > 10) {
                            $value = substr($value, 0, 10) . '...';
                        }
                    case 'casecreatedate':
                    case 'caseclosedate':
                    case 'firstinteractiondate':
                    case 'lastinteractiondate':
                    case 'duedate':
                    case 'lastactivity':
                    case 'taskID':
                    case 'taskStartDate':
                        $value = formatDateTime($value);
                        break;
                    case 'taskEndDate':
                        $value = formatDateTime($value);
                        break;
                    case 'status':
                    case 'accountstatus':
                    case 'onlineaccessstatus':
                    case 'leadstatus':
                    case 'taskstatus':
                    case 'campaignstatus':
                    case 'casestatus':
                        $value = getStatusBadge($value);
                        break;
                    case 'displayname':
                    case 'leadname':
                    case 'contactname':
                    case 'campaignname':
                    case 'customername':
                    case 'casetitle':
                        $value = $value ?: 'Unknown';
                        break;
                    case 'title':
                        $value = $value ?: '—';
                        if ($truncateTitle && strlen($value) > 15) {
                            $value = substr($value, 0, 15) . '...';
                        }
                        break;
                    case 'email':
                    case 'leademail':
                    case 'contactemail':
                        $value = $value ?: '—';
                        break;
                    case 'phonenumber':
                    case 'leadphonenumber':
                    case 'contactphone':
                        $value = $value ?: '—';
                        break;
                    case 'creditlimit':
                    case 'amount':
                    case 'balance':
                        $value = is_numeric($value) ? '$' . number_format((float)$value, 2) : $value;
                        break;
                    case 'companyname':
                        $value = $value ?: '—';
                        break;
                    case 'type':
                        $value = ucfirst($value);
                    default:
                        $value = $value ?: '—';
                        break;

                }

                $rowHtml .= "<td style='width:{$width};word-wrap:break-word;'>{$value}</td>";

            }

            if ($actionUrls) {

                $rowHtml .= renderActionUrls($row, $actionUrls);

            }

            $rowHtml .= '</tr>';

            return $rowHtml;

        }

    }

    
    if (!function_exists('renderListingTable')) {

        function renderListingTable($con, $tableType, $headers, $columns, $columnWidths, $actionUrls = [], $account = null, $truncateTitle = false) {

            echo '<table class="nexure-table-plugin nexure-table-domains">';

            echo renderTableHeaders($headers, $columnWidths);

            $rows = [];

            switch (strtolower($tableType)) {

                case 'accounts':
                    if (!empty($account['userAccounts'])) {
                        $rows = $account['userAccounts'];
                    }
                    break;
                case 'account_services':
                    if (!empty($account['associatedServices'])) {
                        $rows = $account['associatedServices'];
                    }
                    break;
                case 'account_cases':
                    if (!empty($account['associatedCases'])) {
                        $rows = $account['associatedCases'];
                    }
                    break;
                case 'account_files':
                    if (!empty($account['associatedFiles'])) {
                        $rows = $account['associatedFiles'];
                    }
                    break;
                case 'account_users':
                    if (!empty($account['authorizedUsers'])) {
                        $rows = $account['authorizedUsers'];
                    }
                    break;
                case 'tasks':
                    $sql = "SELECT * FROM nexure_tasks ORDER BY taskStartDate DESC";
                    $result = mysqli_query($con, $sql);
                    if ($result) while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
                    break;
                case 'cases':
                    $sql = "SELECT * FROM nexure_cases ORDER BY created_at DESC";
                    $result = mysqli_query($con, $sql);
                    if ($result) while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
                    break;
                case 'leads':
                    $sql = "SELECT * FROM nexure_leads ORDER BY leadName ASC";
                    $result = mysqli_query($con, $sql);
                    if ($result) while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
                    break;
                case 'users':
                    $sql = "SELECT * FROM nexure_users ORDER BY displayName ASC";
                    $result = mysqli_query($con, $sql);
                    if ($result) while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
                    break;
                default:
                    echo "<tr><td colspan='" . count($headers) . "'>Unknown table type: {$tableType}</td></tr>";
                    echo '</table>';
                    return;
            }

            if (!empty($rows)) {

                foreach ($rows as $row) {

                    echo renderListingRow($row, $columns, $columnWidths, $actionUrls, $truncateTitle);

                }

            } else {

                echo "<tr><td colspan='" . count($headers) . "' style=''>There are no records</td></tr>";

            }

            echo '</table>';

        }
    }

?>
