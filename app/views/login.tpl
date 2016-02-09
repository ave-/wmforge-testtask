<div class="window">
   <div class="wrapper">
      <form action="" method="post">
         <div class="header center-children">
            <input type="hidden" name="__translator_lang" value="[@__translator_lang]" id="__translator_lang">
            <span id="tr_title" class="hcenter">Login</span>
            <span class="lang button [@__ru_lang_active]" id="ru_tr" onclick="applyTranslate('ru')">ru</span>
            <span class="lang button [@__en_lang_active]" id="en_tr" onclick="applyTranslate('en')">en</span>
         </div>
         <div class="body">
            <div class="hcenter" style="width: 100%">
               <fieldset>
                  <label id="tr_login" for="login">Login:</label>
                  <input type="text" name="login" id="login" value="[@login]" required>
               </fieldset>
               <fieldset>
                  <label id="tr_password" for="password">Password:</label>
                  <input type="password" name="password" id="password" required>
               </fieldset>
            </div>
         </div>
         <div class="footer center-children">
            <div class="hcenter">
               <button class="button default" type="submit" id="tr_login_button">Login</button>
               <span id="tr_or">OR</span>
               <a class="button another" href="/register" id="tr_register_button">Register</a>
            </div>
         </div>
      </form>
   </div>
</div>

<div class="error [@login_error]" id="tr_login_error">Login or password incorrect. Please try again.</div>