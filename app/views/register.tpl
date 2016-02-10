<div class="window">
   <div class="wrapper">
      <form action="" method="post" enctype="multipart/form-data">
         <div class="header center-children">
            <input type="hidden" name="__translator_lang" value="[@__translator_lang]" id="__translator_lang">
            <span id="tr_title" class="hcenter">Register</span>
            <span class="lang button [@__ru_lang_active]" id="ru_tr" onclick="applyTranslate('ru')">ru</span>
            <span class="lang button [@__en_lang_active]" id="en_tr" onclick="applyTranslate('en')">en</span>
         </div>
         <div class="body">
            <div class="hcenter" style="width: 100%">
               <fieldset>
                  <label id="tr_login" for="login">Login:</label>
                  <input type="text" name="login" id="login" value="[@login]" required>
                  <div class="center-children">
                     <small class="form-error [@login_missing_error]" id="tr_login_missing_error">This field is required</small>
                     <small class="form-error [@login_exists_error]" id="tr_login_exists_error">This login already exists</small>
                  </div>
               </fieldset>
               <fieldset>
                  <label id="tr_password" for="password">Password:</label>
                  <input type="password" name="password" id="password" required>
                  <div class="center-children">
                     <small class="form-error [@password_missing_error]" id="tr_password_missing_error">This field is required</small>
                  </div>
               </fieldset>
               <fieldset>
                  <label id="tr_retype_password" for="retype_password">Retype password:</label>
                  <input type="password" name="retype_password" id="retype_password" required>
                  <div class="center-children">
                     <small class="form-error [@retype_password_error]" id="tr_retype_password_error">Passwords does not match</small>
                  </div>
               </fieldset>
               <fieldset>
                  <label id="tr_email" for="email">E-mail:</label>
                  <input type="email" name="email" id="email" value="[@email]" required>
                  <div class="center-children">
                     <small class="form-error [@email_missing_error]" id="tr_email_missing_error">This field is required</small>
                     <small class="form-error [@email_exists_error]" id="tr_email_exists_error">This email is already used</small>
                     <small class="form-error [@wrong_email_error]" id="tr_wrong_email_error">Your email is probably not valid. It must at least contain an @ symbol and something before and after it.</small>
                  </div>
               </fieldset>
               <fieldset>
                  <label id="tr_name" for="name">Your real name and surname:</label>
                  <input type="text" name="name" id="name" required>
                  <div class="center-children">
                     <small class="form-error [@name_missing_error]" id="tr_name_missing_error">This field is required</small>
                  </div>
               </fieldset>
               <fieldset>
                  <label id="tr_sex" for="sex">Sex:</label>
                  <select id="sex" name="sex">
                     {{sex}}
                  </select>
                  <div class="center-children">
                     <small class="form-error [@sex_error]" id="tr_sex_error">Something is wrong. Sex can be either male or female</small>
                  </div>
               </fieldset>
               <fieldset>
                  <label id="tr_birth_date" for="birth_date_day">Birth date:</label>
                  <select name="birth_date_day" id="birth_date_day">
                     {{birth_date_days}}
                  </select>-
                  <select name="birth_date_month">
                     {{birth_date_months}}
                  </select>
                  <select name="birth_date_year">
                     {{birth_date_years}}
                  </select>
                  <div class="center-children">
                     <small class="form-error [@birth_date_error]" id="tr_birth_date_error">Date is invalid</small>
                  </div>
               </fieldset>
               <fieldset>
                  <label for="other_info" id="tr_other_info">Other info:</label>
                  <textarea name="other_info" id="other_info">[@other_info]</textarea>
               </fieldset>
               <fieldset>
                  <label for="picture" id="tr_picture">Picture:</label>
                  <input type="file" name="picture" id="picture" accept="image/jpeg,image/png,image/gif">
                  <div class="center-children">
                     <small class="form-error [@picture_extension_error]" id="tr_picture_extension_error">Wrong extension. Only jpeg/jpg/gif/png are allowed</small>
                     <small class="form-error [@picture_generic_error]" id="tr_picture_generic_error">Error while uploading file. Please try another</small>
                  </div>
               </fieldset>
            </div>
         </div>
         <div class="footer center-children">
            <div class="hcenter">
               <button class="button default" type="submit" id="tr_register_button">Register</button>
               <span id="tr_or">OR</span>
               <a class="button another" href="/login" id="tr_login_button">Login</a>
            </div>
         </div>
      </form>
   </div>
</div>