function applyTranslate(lang)
{
   for (var key in dictionary[lang])
   {
      var elem = document.getElementById('tr_' + key);
      if (elem)
      {
         elem.innerHTML = dictionary[lang][key];
      }
   }
   document.title = dictionary[lang]['title'];

   langbuttons = document.getElementsByClassName('lang');
   for (var idx=0;idx<langbuttons.length;idx++)
   {
      langbuttons[idx].className = 'lang button';//reset translated class
   }
   document.getElementById('__translator_lang').value = lang;
   document.getElementById(lang + '_tr').className = 'lang button translated';
}
window.onload = function()
{
   applyTranslate(document.getElementById('__translator_lang').value);
};
