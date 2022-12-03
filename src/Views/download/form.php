<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo ROOTURL;?>/public/assets/bootstrap/bootstrap.min.css">
  <script src="<?php echo ROOTURL;?>/public/assets/jquery.js"></script>
  <script src="<?php echo ROOTURL;?>/public/assets/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Form control: textarea</h2>
  <p>The form below contains a textarea for comments:</p>
  <form>
    <form action="<?php echo ROOTURL;?>/downloadfile" method="post">
      <div class="form-group">
        <label for="comment">Comment:</label>
        <input class="form-control" rows="5" id="comment" name="domain"></textarea>
      </div>
      <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea class="form-control" rows="5" id="comment" name="form-data"></textarea>
      </div>
      <button type="submit" name="btn-submit" class="btn btn-primary active">Submit</button>
    </form>
    
  </form>
</div>

</body>
</html>
