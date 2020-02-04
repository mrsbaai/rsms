<html>
   <body onload="window.setTimeout('document.getElementById(\'criimlaunch\').click();', 1000);">

      <script>
         var macroCode = '{{ $code }}';

         function launchMacro()
            {
            try
               {
                  if(!/^(?:chrome|https?|file)/.test(location))
                  {
                     alert('iMacros: Open webpage to run a macro.');
                     return;
                  }
			   
                  var macro = {}; 
                  macro.source = macroCode;
                  macro.name = 'EmbeddedMacro';
			   
                  var evt = document.createEvent('CustomEvent');
                  evt.initCustomEvent('iMacrosRunMacro', true, true, macro);
                  window.dispatchEvent(evt);
               }
            catch(e)
            {
               alert('iMacros Bookmarklet error: '+e.toString());
            };
         }
      </script>

      <a id="criimlaunch" href="javascript:launchMacro();">Launch iMacros</a>

   </body>
</html>