<div class="modal-overlay" id="createUserModal">

    <div class="modal">

        <div class="modal__header">
            <h2>Create Account</h2>
            <button class="modal__close" id="closeModal">&times;</button>
        </div>

        <div class="modal__body">

            <form method="POST"
                  action="/RMS/public/index.php?url=admin/users/store"
                  class="form">

                <div class="form__field">
                    <label>Account Type</label>
                    <select name="role" id="roleSelect" required>
                        <option value="">Select role</option>
                        <option value="reporter">Student Reporter</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <fieldset>
                    <legend>Login Credentials</legend>

                    <div class="form__field">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="form__field">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                </fieldset>

                <!-- STUDENT -->
                <fieldset id="reporterFields" style="display:none">
                    <legend>Student Info</legend>

                    <input type="text" name="first_name" placeholder="First name">
                    <input type="text" name="last_name" placeholder="Last name">
                    <input type="text" name="id_number" placeholder="Student ID">
                </fieldset>

                <!-- STAFF -->
                <fieldset id="staffFields" style="display:none">
                    <legend>Staff Info</legend>

                    <input type="text" name="position" placeholder="Position">
                    <input type="text" name="office" placeholder="Office">
                </fieldset>

                <button class="btn btn--primary btn--block">
                    Create Account
                </button>

            </form>

        </div>

    </div>

</div>
