<?php
// view/customers.php — lists every customer in the database.
// Flow: include functions file → getAllCustomersList() → controller →
// model → SELECT query → returns array → rendered in the table below.
require_once "../core/core.php";

// Access guard: only logged-in users may see the customer list.
// If not logged in, require_login() redirects to login.php and stops.
require_login();

require_once "../functions/customer_functions.php";

// $customers is an array of associative arrays, one per customer row.
$customers = getAllCustomersList();

$pageTitle = "All Customers";
$navRoot   = "../";
$cssRoot   = "../";
include "layout/header.php";
?>

    <h1 style="margin-bottom:1rem;">All Customers</h1>

    <?php if (empty($customers)): ?>
        <p>No customers registered yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <!--
                        htmlspecialchars() converts special characters
                        (like <, >, &) into safe HTML entities before
                        printing — this prevents XSS attacks.
                    -->
                    <tr>
                        <td><?php echo htmlspecialchars($customer['customer_id']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_country']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_city']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_contact']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

<?php include "layout/footer.php"; ?>
