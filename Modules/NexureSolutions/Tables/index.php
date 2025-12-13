<?php

    // Nexure Panel Table Handler
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

            if (empty($dateValue) || $dateValue === "0000-00-00" || $dateValue === "0000-00-00 00:00:00") {

                return "—";

            }

            $timestamp = strtotime($dateValue);
            if (!$timestamp) return "—";
            $hasTime = (strpos($dateValue, ':') !== false);
            return $hasTime
                ? date("l F j Y g:i A", $timestamp)
                : date("l F j Y", $timestamp);
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

        function renderListingRow($row, $columns, $columnWidths, $actionUrls = []) {

            $rowHtml = '<tr>';

            foreach ($columns as $index => $column) {

                $width = $columnWidths[$index] ?? 'auto';
                $value = $row[$column] ?? '';

                switch (strtolower($column)) {
                    case 'accountnumber':
                        $value = maskAccountNumber($value);
                        break;
                    case 'accountstatus':
                    case 'status':
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
                    case 'date':
                    case 'firstinteractiondate':
                    case 'lastinteractiondate':
                    case 'duedute':
                    case 'lastactivity':
                    case 'taskstartdate':
                    case 'taskduedate':
                    case 'casecreatedate':
                    case 'caseclosedate':
                        $value = formatDateTime($value);
                        break;
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

        function renderListingTable($con, $tableType, $headers, $columns, $columnWidths, $actionUrls = []) {

            try {

                switch (strtolower($tableType)) {
                    case 'accounts':
                        $sql = "SELECT a.*, u.displayName, u.accessLevel, u.onlineAccessStatus FROM nexure_accounts a LEFT JOIN nexure_users u ON a.email = u.email ORDER BY a.id DESC";
                        break;
                    case 'blacklists':
                        $sql = "SELECT * FROM nexure_blacklist ORDER BY id DESC";
                        break;
                    case 'leads':
                        $sql = "SELECT * FROM nexure_leads ORDER BY lastActivity DESC";
                        break;
                    case 'campaigns':
                        $sql = "SELECT * FROM nexure_campaigns ORDER BY lastActivity DESC";
                        break;
                    case 'users':
                        $sql = "SELECT * FROM nexure_users ORDER BY firstInteractionDate DESC";
                        break;
                    case 'tasks':
                        $sql = "SELECT * FROM nexure_tasks ORDER BY dueDate DESC";
                        break;
                    case 'cases':
                        $sql = "SELECT * FROM nexure_cases ORDER BY caseCreateDate DESC";
                        break;
                    default:
                        echo "<p>Unknown table type: {$tableType}</p>";
                        return;
                }

                $result = mysqli_query($con, $sql);

                echo '<table class="nexure-table-plugin nexure-table-domains">';
                echo renderTableHeaders($headers, $columnWidths);

                if ($result && mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                        echo renderListingRow($row, $columns, $columnWidths, $actionUrls);

                    }

                } else {

                    $colspan = count($headers) + ($actionUrls ? 1 : 0);
                    echo "<tr><td colspan='{$colspan}' style='text-align:center;'>There are no records</td></tr>";

                }

                echo '</table>';
                mysqli_free_result($result);

            } catch (\Throwable $exception) {

                if (class_exists('\Sentry')) {

                    \Sentry\captureException($exception);

                }

            }

        }

    }

?>
