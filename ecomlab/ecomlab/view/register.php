<!--
    view/register.php — the Customer Registration page.
    Flow: user fills form → js/customer.js registerCustomer() validates →
    POSTs to actions/customer_register_action.php → JSON response →
    JS shows message and resets the form on success.
-->
<?php
require_once "../core/core.php";

$pageTitle = "Register Customer";
$navRoot   = "../";
$cssRoot   = "../";
include "layout/header.php";
?>

    <div class="card">
        <h1>Customer Registration</h1>

        <!--
            Each input's "name" attribute matches a column in the `customer`
            table (see classes/CustomerClass.php) and is what PHP reads via
            $_POST in actions/customer_register_action.php. The "id" attribute
            is what js/customer.js uses to read the value in the browser.
        -->
        <form id="registerForm">
            <div>
                <label for="customer_name">Name</label>
                <input type="text" name="customer_name" id="customer_name" autocomplete="name">
            </div>
            <div>
                <label for="customer_email">Email</label>
                <input type="email" name="customer_email" id="customer_email" autocomplete="email">
            </div>
            <div>
                <label for="customer_pass">Password</label>
                <input type="password" name="customer_pass" id="customer_pass" autocomplete="new-password">
            </div>
            <div>
                <label for="customer_country">Country</label>
                <input type="text" name="customer_country" id="customer_country">
            </div>
            <div>
                <label for="customer_city">City</label>
                <input type="text" name="customer_city" id="customer_city">
            </div>
            <div>
                <label for="customer_contact">Contact</label>
                <input type="text" name="customer_contact" id="customer_contact">
            </div>
            <div>
                <label for="customer_image">Image URL (optional)</label>
                <input type="text" name="customer_image" id="customer_image">
            </div>
            <div>
                <!--
                    type="button" (not "submit") stops the browser from
                    trying to reload the page. Instead, clicking it calls
                    the registerCustomer() function defined in js/customer.js.
                -->
                <button type="button" onclick="registerCustomer()">Register</button>
            </div>
        </form>

        <!-- Validation/success/error messages get written into here by customer.js -->
        <p id="formMessage" class="msg"></p>

        <p style="margin-top:1rem; font-size:.875rem;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>

    <!-- Loads the shared JavaScript file that contains registerCustomer() -->
    <script src="../js/customer.js"></script>

<?php include "layout/footer.php"; ?>
