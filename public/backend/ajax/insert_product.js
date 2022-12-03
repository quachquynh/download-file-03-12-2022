$(document).ready(function(){
  $("#btn-submit").click( function(e) {
    e.preventDefault();

    var url = $(this).attr("data-url");
    var title = $("#title").val();
    var slug = $("#slug").val();
    //var body = $("#body").val();
    var body = CKEDITOR.instances["body"].getData();
    var price = $("#price").val();
    var thumbnail = $('#thumbnail')[0].file;

    var formData = new FormData($("#allData")[0]);

    formData.append("title", title);
    formData.append("slug", slug);
    formData.append("body", body);
    formData.append("price", price);
    formData.append('thumbnail', $('#thumbnail')[0].files[0]);
    
    if(!formData)
    {
      alert("Please Fill All Fields");
    }
    else
    {
    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      cache: false,
      contentType: false,
      processData: false, // jQuery - Illegal invocation
      success: function(result){
        $('#result').html("<div class='alert alert-success'>"+result+"</div>");
      }

    });
  }
return false;
});
});
