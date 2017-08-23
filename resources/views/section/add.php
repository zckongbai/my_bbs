<!DOCTYPE HTML>
<html>
<head>
    <script
        src="http://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g="
        crossorigin="anonymous"></script>
</head>
<body>

<form id="sectionAddFm"  action="<?php echo url('section/add');?>" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    板块名称: <input type="name" id="secName" name="name" /><span id="inputNameError">
        <?php echo isset($error) ? $error->name[0] : ''; ?></span><br />
    <input type="submit" value="添加" />
</form>

<script>
    $(function () {
       $('#sectionAddFm').submit(function () {
           if ($('#secName').val() == ''){
               $('#secName').focus();
               $('#inputNameError').html('不能为空')
               return false;
           }
           return false;
       })
    });

</script>
<span name="formError"><?php echo isset($errorMsg) ? $errorMsg : '';?></span>

</body>
</html>

