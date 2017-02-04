<?php
session_start();
include("vendor/abeautifulsite/simple-php-captcha/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <script src="js/jquery-3.1.1.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script>
        function setCaretPosition(elemId, caretPos) {
            var elem = document.getElementById(elemId);
            if(elem != null) {
                if(elem.createTextRange) {
                    var range = elem.createTextRange();
                    range.move('character', caretPos);
                    range.select();
                }
                else {
                    if(elem.selectionStart) {
                        elem.focus();
                        elem.setSelectionRange(caretPos, caretPos);
                    }
                    else
                        elem.focus();
                }
            }
        }
        function insertTag(str) {
            var text=$("#text").val();
            var textLength=text.length;
            switch (str){
                case "italic":
                    $("#text").val(text+"<i></i>");
                    setCaretPosition("text",textLength+3);
                    break;
                case "strong":
                    $("#text").val(text+"<strong></strong>");
                    setCaretPosition("text",textLength+8);
                    break;
                case "strike":
                    $("#text").val(text+"<strike></strike>");
                    setCaretPosition("text",textLength+8);
                    break;
                case "code":
                    //$("#text").val($("#text").val()+"<a href=\" \" title=\" \"></a>");
                    $("#text").val(text+"<code></code>");
                    setCaretPosition("text",textLength+6);
                    break;
            }
        }
    </script>
    <style>
    </style>
</head>
<body>
<div class="container">
    <form role="form" id="addForm" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="form-control" name="username" id="username" placeholder="Username">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="email" id="email" placeholder="Email">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="homepage" id="homepage" placeholder="Homepage">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-ms-12">
                <div id="panelTags">
                    <a class="btn btn-default site-btn" role="button" href="javascript:void(0);" onclick="insertTag('italic')">[italic]</a>
                    <a class="btn btn-default site-btn" role="button" href="javascript:void(0);" onclick="insertTag('strike')">[strike]</a>
                    <a class="btn btn-default site-btn" role="button" href="javascript:void(0);" onclick="insertTag('strong')">[strong]</a>
                    <a class="btn btn-default site-btn" role="button" href="javascript:void(0);" onclick="insertTag('code')">[code]</a>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-md-12">
                <textarea class="form-control" name="text" id="text" placeholder="Your text"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <img src="<?= $_SESSION['captcha']['image_src'] ?>" id="captchaCode" alt="CAPTCHA code">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Captcha">
            </div>
        </div>
        <div class="form-group ">
            <label for="addTxtFile">Add txt file</label>
            <input type="checkbox" id="addTxtFile" name="addTxtFile"/>
        </div>
        <div class="form-group " id="addTxtFileBlock" style="display: none">
            <label for="userFile">File</label>
            <input name="userFile" id="userFile" type="file"/>
        </div>
        <div class="form-group ">
            <label for="addImageFile">Add image</label>
            <input type="checkbox" id="addImageFile" name="addImageFile"/>
        </div>
        <div class="form-group " id="addImageFileBlock" style="display: none">
            <label for="userImageFile">Image</label>
            <input name="userImageFile" id="userImageFile" type="file"/>
        </div>
        <input type="hidden" value=<?= $_SERVER['REMOTE_ADDR'] ?>>
        <input type="hidden" value=<?= $_SERVER['HTTP_USER_AGENT'] ?>>
        <input type="hidden" name="date" id="date" value="<?php echo date('Y-n-d'); ?>">
            <button type="submit" class="btn btn-default center-block site-btn">Submit</button>
    </form>
</div>
<script>
    $().ready(function () {
        function addNews() {

            var fd = new FormData();
            $("[type='file']").each(function(){
                var id=$(this).attr("id");
                fd.append(id, $( '#'+id )[0].files[0]);
            });
            var other_data = $("#addForm").serializeArray();
            $.each(other_data,function(key,input){
                fd.append(input.name,input.value);
            });
            $.ajax({
                url: 'addNews.php',
                data: fd,
                type: 'POST',
                contentType: false,
                processData: false,
                dataType:"json",
                success: function(data) {
                    $("#result").html(data.message);
                    $("#errorName").html(data.errorName);
                    $("#errorEmail").html(data.errorEmail);
                    $("#errorHomePage").html(data.errorHomePage);
                    $("#errorText").html(data.errorText);
                }
            });
        }


        $("#captchaCode").click(function () {
            $.ajax({
                url: 'reload-captcha.php',
                type: 'POST',
                dataType: 'html',
                data: {},
                success: function (data) {
                    $("#captchaCode").attr('src', data);
                }
            });
        });
        $("#addTxtFile").on('change', function () {
            var txtBlock = $("#addTxtFileBlock");
            txtBlock.toggle("slow");
            if (!$("#addTxtFile").is(":checked")) {
                txtBlock.val("");
            }
        });
        $("#addImageFile").on('change', function () {
            var imgBlock = $("#addImageFileBlock");
            imgBlock.toggle("slow");
            if (!$("#addImageFile").is(":checked")) {
                imgBlock.val("");
            }
        });

        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z0-9_-]+$/i.test(value);
        }, "Please use only a-z0-9_-");
        jQuery.validator.addMethod("fileSize", function (val, element) {
            var size = element.files[0].size;
            console.log(size);
            if (size > 100000)// checks the file more than 100 kb
            {
                console.log("returning false");
                return false;
            } else {
                console.log("returning true");
                return true;
            }
        }, "File size should not be larger than 100 kb");
        jQuery.validator.addMethod("fileType", function (val, element) {
            var f = $("#userFile").get(0).files[0];
            var name = f.name;
            var ext = name.split('.').pop();
            console.log(f);
            console.log(ext);
            if (ext == "txt")return true;
            return false;

        }, "File must be txt");
        jQuery.validator.addMethod("imageType", function (val, element) {
            var f = $("#userImageFile").get(0).files[0];
            var name = f.name;
            var ext = name.split('.').pop();
            console.log(f);
            console.log(ext);
            if (ext == "jpg" || ext == "gif" || ext == "png")return true;
            return false;
        }, "File must be jpg,gif,png");

        $("#addForm").validate({
            rules: {
                username: {
                    required: true,
                    minlength: 2,
                    maxlength: 10,
                    lettersonly: true
                },
                email: {
                    required: true,
                    email: true
                },
                homePage: {
                    required: true,
                    url: true
                },
                text: {
                    required: true,
                    remote: {
                        url: "check-tags.php",
                        type: "post"
                    }

                },
                captcha: {
                    required: true,
                    remote: {
                        url: "check-captcha.php",
                        type: "post"
                    }
                },
                userFile: {
                    required: true,
                    fileSize: true,
                    fileType: true
                },
                userImageFile: {
                    required: true,
                    imageType: true
                }
            },
            messages: {
                username: {
                    required: "This field is required",
                    minlength: "Name must be at least 2 characters",
                    maxlength: "Maximum number of characters - 10"
                },
                captcha: {
                    remote: "Enter the correct code"
                },
                text:{
                    remote:"Close all html tags!"
                }
            },

            submitHandler: function (form) {
                 addNews();
            }

        });


    });


</script>
</body>
</html>
