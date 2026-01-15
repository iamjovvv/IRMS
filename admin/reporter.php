<?php
define ('APP_STARTED', true);
session_start();

$page_css = [
    'topnavbar.css',
    'staff.css'

];

$_SESSION['role']= 'admin';
require '../app/Views/layouts/header.php'
?>

table

<main class="staff">
    <header class="staff__header">
        <h2 class="staff__title">Reporter Account Management</h2>

        <section class="staff__searchbar">
            <div class="staff__search">
                <i class="fa-solid fa-magnifying-glass staff__search-icon"></i>
                <input 
                    type="text"
                    class="staff__search-input"
                    placeholder="Search staff..."
                >
            </div>

            <select class="staff__filter">
                <option value="">All Roles</option>
                <option value="student">Student</option>
                <option value="faculty">Faculty</option>
            </select>

            <select class="staff__filter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="banned">Banned</option>
                <option value="suspended">Suspended</option>
            </select>

            <select class="staff__filter">
                <option value="">All Departments</option>
                <option value="science">College of Science</option>
                <option value="vetmed">Veterinary Medicine</option>
            </select>
        </section>
    </header>

    <section class="staff__content">
        <table class="staff__table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Name</th>
                    <th>ID No.</th>
                    <th>Birthday</th>
                    <th>Status</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Jove</td>
                    <td>Batlangao</td>
                    <td>Espelimbergo</td>
                    <td>222451</td>
                    <td>08/05/2004</td>
                    <td class="staff__status staff__status--active">Active</td>
                    <td>College of Science</td>
                    
                    <td>Student</td>
                    <td>
                        <button class="staff__action">Edit</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="staff__actions">
            <button class="staff__btn staff__btn--export">
                <i class="fa-solid fa-file-export"></i> Export
            </button>
            <button class="staff__btn staff__btn--add">
                <i class="fa-solid fa-plus"></i> Add Account
            </button>
        </div>
    </section>
</main>


<?php require '../app/Views/layouts/footer.php' ?>