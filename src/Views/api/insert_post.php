<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
  <div id="data-table"></div>
  <form action="" method="post">
    <div class="form-group">
      <label for="email">Title:</label>
      <input type="text" class="form-control" placeholder="Enter email" id="title" name="title">
    </div>
    <div class="form-group">
      <label for="pwd">Slug:</label>
      <input type="text" class="form-control" placeholder="Enter slug" id="slug" name="slug">
    </div>
    <div class="form-group">
      <label for="pwd">Content:</label>
      <input type="text" class="form-control" placeholder="Enter slug" id="content" name="content">
    </div>
      <div class="form-group">
      <label for="pwd">Status:</label>
      <input type="text" class="form-control" placeholder="Enter slug" id="status" name="status">
    </div>
      <div class="form-group">
      <label for="pwd">Category:</label>
      <input type="text" class="form-control" placeholder="Enter slug" id="category" name="category">
    </div>
    <button type="submit" class="btn btn-primary" name="btn-submit" id="btn-submit">Submit</button>
  </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
<script>
  $(document).ready(function(){

    $("#btn-submit").click(function(e){
        e.preventDefault();
      var title = $("#title").val();
      var slug = $("#slug").val();
      var content = $("#content").val();
      var status = $("#status").val();
      var category = $("#category").val();

      var dataString = 'title='+ title + '&slug='+ slug + '&content='+ content + '&status='+ status + '&category='+ category;

      if(title==''||slug=='')
      {
        alert("Please Fill All Fields");
      }
      else
      {

      $.ajax({
        type: "POST",
        url: "<?php ROOTURL;?>/download-file/wordpress/insert",
        data: dataString,
        cache: false,
        success: function(data){                    
            $("#data-table").html(data); 
           
        }
      });
    }
  return false;
  });
});
</script>

</body>
</html>