<?php
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>





<main class="page page-sendwith">

    <section class="form-wrapper">

    <div id="toast-container"></div>
   

        <form class="form form--compact"
              method="POST"
              action="/RMS/public/index.php?url=reporter/submitReport">

            <h2 class="form__title">Send With </h2>


             <?php if (!empty($errors)): ?>
                <script>
                window.toastErrors = <?= json_encode(array_values($errors)) ?>;
                </script>
            <?php endif; ?>


            <!-- auth method -->
          <div class="form__field">

            <label class="form__label">Choose authentication method</label>

              <select name="auth_method"
                      class="form__select"
                      required>
                      <!-- name is required so PHP can read it -->

                <option value="">Choose method</option>
                <option value="org_id">Institutional ID</option>
                <!-- <option value="google">Google</option> -->
                <option value="phone">Phone</option>

              </select>
            
          </div>

          <!-- institutional login -->

           <div class="form__field form__field--institutional">
        

            <!-- separate field for institutional auth -->
            <p class="form__subtitle">[Institutional ID (staff/ student)]</p>

            <label class="form__label">Username</label>

            <input type="text"
                   name="username"
                   class="form__input"
                   required>

           </div>

           <div class="form__field form__field--institutional">

                <label class="form__label">Password</label>

                <input type="password"
                       name="password"
                       class="form__input"
                       required>

           </div>

           <!-- Phone auth -->

            <div class="form__field form__field--phone">

            <p class="form__subtitle">[With your Phone Number]</p>

                <label class="form__label">Phone Number</label>

                <input type="text"
                       name="phone"
                       class="form__input"
                       placeholder="09XXXXXXXXX">
            </div>




            <!-- Google Auth (informational only) -->

           

            <div class="form__field form__field--google">

                <p class="form__subtitle">[With your Google Account]</p>

                <p class="form__google">

                <i class="fa-brands fa-google"></i>oogle Account

                </p>

            </div>

            <button 

                class="btn btn--primary btn--block" 
                type="submit">SUBMIT

            </button>



        </form>

    </section>


</main>


