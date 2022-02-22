$(function() {

     $('form.ajax').submit(function(){
          if(this.id=='')
          {
          $(this).attr('id', 'ajaxForm'+Math.floor(Math.random()*1001));
          }
       
       var action = $(this).attr('action');
       var post = "ajax=1"; 
        $('#'+this.id+' input[name],textarea[name]').each(function(){
        post = post + "&" + encodeURIComponent(this.name) + "=" + encodeURIComponent($(this).val());
        });    
             $.ajax({
                    type: "POST",
                    url: action,
                    data: post,
                    dataType: "script",
                    success: function(msg){
                     // alert(msg);
                     }
                    });
      
      $(this)[0].reset();     
      return false;
      });


 });