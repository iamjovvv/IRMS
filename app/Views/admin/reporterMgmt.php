<?php $_SESSION['role']='admin'; ?>
<div class="with-sidebar">
    

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page--new-reports">

        <header class= "page__header">

            <h1  class="page__title">Reporter Account Management</h1>

        </header>


        <div class="page__container page__container-reports">

             <div class="table__actions">

            <button class="btn__action  btn__action--export">

                <i class="fa-solid fa-file-export"></i> Export
                
            </button>

            <button class="btn__action btn__action--add" id="openCreateUser">

                <i class="fa-solid fa-plus"></i> Add Account
            </button>

            </div>
            
            
            <div class="table__selection">

                 <div class="table__search">
                <i class="fa-solid fa-magnifying-glass table__search-icon"></i>
                <input 
                    type="text"
                    class="table__search-input"
                    placeholder="Search staff..."
                >
                </div>

                <select class="table__select">
                    <option value="">All Roles</option>
                    <option value="faculty">Faculty</option>
                      <option value="other">others, please specify</option>

                </select>

                <select class="table__select">

                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="banned">Banned</option>
                    <option value="suspended">Suspended</option>

                </select>
                
                

                <select class="table__select">
                    <option value="">All Departments</option>
                    <option value="College of Science">College of Science</option>
                    <option value="Veterinary of Medicine">Veterinary of Medicine</option>

                </select>

            </div>




            <table class="report-table">

                <thead>

                    <tr class="report-table__row report-table__row--head">

                        <th class="report-table__cell">ID</th>
                        <th class="report-table__cell">First Name</th>
                        <th class="report-table__cell">Last Name</th>
                        <th class="report-table__cell">Middle Name</th>
                        <th class="report-table__cell">ID No.</th>
                        <th class="report-table__cell">Birthday</th>
                        <th class="report-table__cell">Status</th>
                        <th class="report-table__cell">Department</th>
                        <th class="report-table__cell">Role</th>
                        <th class="report-table__cell">Action</th>

                    </tr>

                </thead>

                <tbody>

                    <tr class="report-table__row">

                        <td class="report-table__cell">1</td>
                        <td class="report-table__cell">Jove</td>
                        <td class="report-table__cell">Batlangao</td>
                        <td class="report-table__cell">Espelimbergo</td>
                        <td class="report-table__cell">223152</td>
                        <td class="report-table__cell">08/05/2004</td>
                        <td class="report-table__cell report-table--status">Active</td>
                        <td class="report-table__cell">College of Science</td>
                        <td class="report-table__cell">Student</td>
                        <td class="report-table__cell">
                            <a class="table__action" href="/RMS/public/index.php?url=report-review">Edit</a>
                        </td>

                    </tr>

                </tbody>

            </table>



        </div>


    
    </main>

     

</div>


<!-- CREATE USER MODAL -->
<div class="modal" id="createUserModal">
    <div class="modal__overlay"></div>

    <div class="modal__content">
        <h2>Create Account</h2>

        <form method="POST" action="/RMS/public/index.php?url=admin/users/store">

            <label>Role</label>
            <select name="role" id="roleSelect" required>
                <option value="">Select role</option>
                <option value="reporter">Reporter</option>
                <option value="staff">Staff</option>
            </select>

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <!-- Reporter fields -->
            <div class="role-fields" id="reporterFields">
                <label>First Name</label>
                <input type="text" name="first_name">

                <label>Last Name</label>
                <input type="text" name="last_name">

                <label>ID Number</label>
                <input type="text" name="id_number">
            </div>

            <!-- Staff fields -->
            <div class="role-fields" id="staffFields">
                <label>Position</label>
                <input type="text" name="position">

                <label>Office</label>
                <input type="text" name="office">
            </div>

            <div class="modal__actions">
                <button type="submit">Create</button>
                <button type="button" id="closeCreateUser">Cancel</button>
            </div>

        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {

    const openBtn = document.getElementById('openCreateUser');
    const closeBtn = document.getElementById('closeCreateUser');
    const modal = document.getElementById('createUserModal');

    const roleSelect = document.getElementById('roleSelect');
    const reporterFields = document.getElementById('reporterFields');
    const staffFields = document.getElementById('staffFields');

    openBtn.addEventListener('click', () => {
        modal.classList.add('active');
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.remove('active');
    });

    roleSelect.addEventListener('change', () => {
        reporterFields.style.display = 'none';
        staffFields.style.display = 'none';

        if (roleSelect.value === 'reporter') {
            reporterFields.style.display = 'block';
        }

        if (roleSelect.value === 'staff') {
            staffFields.style.display = 'block';
        }
    });

});
</script>
