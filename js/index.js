var elm;
function isValidURL(u){
  if(!elm){
    elm = document.createElement('input');
    elm.setAttribute('type', 'url');
  }
  elm.value = u;
  return elm.validity.valid;
}

function Kopiraj(text) 
{
    navigator.clipboard.writeText(text);
}

function izbrisiLink(id)
{
    var podaci ="id="+id;
    $.ajax
    ({  
        type: "POST",  
        url: "funkcije/link_funkcije.php",  
        data: podaci,
        success: function(response)
        {
            if(response == 1)
            {
                location.reload();
            }
        }
    });
}
$(document).ready(function(){
    $("#id_pretraga").keyup(function() {

        var filter = $(this).val(),
          count = 0;
        $('.row').each(function() {
          if ($(this).text().search(new RegExp(filter, "gi")) < 0) {
            $(this).hide();
          } else {
            $(this).show();
            count++;
          }
  
        });
  
      });
    
});