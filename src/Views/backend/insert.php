
  <h2>Vertical (basic) form</h2>
  <div id="result"></div>
  <form id="allData" action="<?php echo PATH_URL;?>/insert" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <input type="text" class="form-control" id="title" placeholder="Title" name="title">
    </div>
    <div class="form-group">
      <input type="text" class="form-control" id="slug" placeholder="Permalink" name="slug">
    </div>
    <div class="form-group">
      <textarea class="form-control" id="body" placeholder="Content" name="body"></textarea>
    </div>
    <div class="form-group">
      <input type="text" class="form-control" id="price" placeholder="Price" name="price">
    </div>
    <div class="form-group">
      <input type="file" class="form-control" id="thumbnail" placeholder="Thumbnail" name="thumbnail">
    </div>
    <div class="checkbox">
      <label><input type="checkbox" name="remember"> Remember me</label>
    </div>
    <button type="submit" data-url="<?php echo PATH_URL;?>/insert" id="btn-submit" name="btn-submit" class="btn btn-default">Submit</button>
  </form>
</div>
<script src="<?php echo APP_URL;?>/js/jquery.min.js"></script>
<script src="<?php echo APP_URL;?>/backend/ajax/insert_product.js"></script>
<script src="//cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script type="text/javascript">
CKEDITOR.replace('body',{
width: "100%",
height: "300px",
//filebrowserUploadMethod:"form",
//filebrowserUploadUrl:"posts_upload.php"
}
);</script>
