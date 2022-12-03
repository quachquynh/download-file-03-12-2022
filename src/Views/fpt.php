<input type="submit" value="Insert" id="Submit1" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
<script>
  $(document).ready(function(){
    $("#Submit1").click(function(){
      var password = $("#password").val();
      var dataString = 'username='+ username + '&password='+ password;
      if(username==''||password=='')
      {
        alert("Please Fill All Fields");
      }
      else
      {
      $.ajax({
        type: "POST",
        url: "insert_data.php",
        data: dataString,
        cache: false,
        success: function(result){
          alert(result);
        }
      });
    }
  return false;
  });
});
</script>