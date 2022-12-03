<!DOCTYPE html>
<html>
   <head>
      <title>Bootstrap Forms</title>
      <meta name = "viewport" content="width = device-width, initial-scale = 1">
      <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
   </head>
   <body>
      <div class="container">
         <form action="<?php echo ROOTURL;?>/getfiles" role = "form" method="POST">
            <div class = "form-group">
               <label for = "name">Player Details</label>
               <input class = "form-control" name="domain" placeholder = "Player Details"/>
            </div>
            <div class = "form-group">
               <label for = "name">Rank</label>
               <input type = "submit" name="btn-submit" class = "form-control" placeholder = "Player Rank">
            </div>
         </form>
      </div>
   </body>
</html>