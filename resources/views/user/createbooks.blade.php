@extends("template")
@section("content")
<style type="text/css">
    .twitter-typeahead{
width:100%;
}
.twitter-typeahead .tt-query,
.twitter-typeahead .tt-hint {
margin-bottom: 0;
}
.tt-dropdown-menu {
min-width: 160px;
margin-top: 2px;
padding: 5px 0;
background-color: #fff;
border: 1px solid #ccc;
border: 1px solid rgba(0,0,0,.2);
*border-right-width: 2px;
*border-bottom-width: 2px;
-webkit-border-radius: 6px;
    -moz-border-radius: 6px;
        border-radius: 6px;
-webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
    -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
        box-shadow: 0 5px 10px rgba(0,0,0,.2);
-webkit-background-clip: padding-box;
    -moz-background-clip: padding;
        background-clip: padding-box;
        width:100%;
}
.tt-suggestion {
display: block;
padding: 3px 20px;
}
.tt-suggestion.tt-is-under-cursor {
color: #fff;
background-color: #0081c2;
background-image: -moz-linear-gradient(top, #0088cc, #0077b3);
background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0077b3));
background-image: -webkit-linear-gradient(top, #0088cc, #0077b3);
background-image: -o-linear-gradient(top, #0088cc, #0077b3);
background-image: linear-gradient(to bottom, #0088cc, #0077b3);
background-repeat: repeat-x;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0077b3', GradientType=0)
}
.tt-suggestion.tt-is-under-cursor a {
color: #fff;
}
.tt-suggestion p {
margin: 0;
}
</style>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script src="{{url('/bootstrap/js/bootstrap.typeahead.js')}}"></script>
<script src="{{url('/bootstrap/js/jquery.form.min.js')}}"></script>
<br>
<div class="panel panel-default">
    <div class="panel-heading">
        Upload Books
    </div>
    <div class="panel-body">
        <div class="col-md-4 col-md-offset-4">
            <form action="" method="POST" role="form" enctype="multipart/form-data" id="uploadimage" onSubmit="return false">
                <fieldset>
                    <div class="form-group">
                        <label for="query">Code:</label>
                        <input class="form-control" name="code" id="code" placeholder="" type="text">
                    </div>
                    <div class="form-group"><center>
                        <div id="image_preview">
                            <img id="previewing" src="http://placehold.it/200x280?text=Sample+Image" class="img img-responsive img-thumbnail">
                        </div></center>
                    </div>
                    <div class="form-group">
                        <input type="file" name="image" id="image">
                    </div>
                    <div class="form-group">
                        <div class="progress" id="progressbox" style="display:none;">
                            <div class="progress-bar" id="progressbar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                <div id="statustxt">0%</div>
                            </div>
                        </div>
                        <div id="output"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="pull-right">
                                <a href="{{url('home')}}" class="btn btn-default">Cancel</a>
                                <button type="submit" id="submit-btn" class="btn btn-primary">Save</button>
                                <a href="{{url('editbook')}}" class="btn btn-primary ">Edit</a>
                                {!! csrf_field() !!}
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function (e) {
 
    var progressbox     = $('#progressbox');
    var progressbar     = $('#progressbar');
    var statustxt       = $('#statustxt');
    var completed       = '0%';
    
    var options = { 
            target:   '#output',   // target element(s) to be updated with server response 
            beforeSubmit:  beforeSubmit,  // pre-submit callback 
            uploadProgress: OnProgress,
            success:       afterSuccess,  // post-submit callback 
            resetForm: true        // reset the form after successful submit 
        }; 
        
     $('#uploadimage').submit(function() { 
            $(this).ajaxSubmit(options);            
            // return false to prevent standard browser submit and page navigation 
            return false; 
        });
    
//when upload progresses    
function OnProgress(event, position, total, percentComplete)
{
    //Progress bar
    progressbar.width(percentComplete + '%') //update progressbar percent complete
    statustxt.html(percentComplete + '%'); //update status text
    if(percentComplete>50)
        {
            statustxt.css('color','#fff'); //change status text to white after 50%
        }
}

//after succesful upload
function afterSuccess()
{
    $('#submit-btn').show(); //hide submit button
    $('#loading-img').hide(); //hide submit button

}

//function to check file size before uploading.
function beforeSubmit(){
    //check whether browser fully supports all File API
   if (window.File && window.FileReader && window.FileList && window.Blob)
    {

        if( !$('#image').val()) //check empty input filed
        {
            $("#output").html("File input is empty?");
            return false
        }
        
        var fsize = $('#image')[0].files[0].size; //get file size
        var ftype = $('#image')[0].files[0].type; // get file type
        
        //allow only valid image file types 
        switch(ftype)
        {
            case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
                break;
            default:
                $("#output").html("<b>"+ftype+"</b> Unsupported file type!");
                return false
        }
        
        //Allowed file size is less than 1 MB (1048576)
        if(fsize>10485760) 
        {
            $("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
            return false
        }
        
        //Progress bar
        progressbox.show(); //show progressbar
        progressbar.width(completed); //initial value 0% of progressbar
        statustxt.html(completed); //set status text
        statustxt.css('color','#000'); //initial color of status text

                
        $('#submit-btn').hide(); //hide submit button
        $('#loading-img').show(); //hide submit button
        $("#output").html("");  
    }
    else
    {
        //Output error to older unsupported browsers that doesn't support HTML5 File API
        $("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
        return false;
    }
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

    function OnProgress(event, position, total, percentComplete)
    {
        //Progress bar
        progressbar.width(percentComplete + '%') //update progressbar percent complete
        statustxt.html(percentComplete + '%'); //update status text
        if(percentComplete>50)
            {
                statustxt.css('color','#fff'); //change status text to white after 50%
            }
    }

    $("#").on('submit',(function(e) {
        e.preventDefault();
        $("#message").empty();
        $('#loading').show();
        $.ajax({
            url: "createbooks", // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
                $('#loading').hide();
                $("#message").html(data);
            }
        });
    }));

    // Function to preview image after validation
    $(function() {
        $("#image").change(function() {
            $("#message").empty(); // To remove the previous error message
            var file = this.files[0];
            var imagefile = file.type;
            var match= ["image/jpeg","image/png","image/jpg"];
            if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
            {
                $('#previewing').attr('src','noimage.png');
                $("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                return false;
            }
            else
            {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
    function imageIsLoaded(e) {
        $("#image").css("color","green");
        $('#image_preview').css("display", "block");
        $('#previewing').attr('src', e.target.result);
        $('#previewing').attr('width', '250px');
        $('#previewing').attr('height', '230px');
    };
});
</script>
@endsection