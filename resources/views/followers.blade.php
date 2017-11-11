<html>
<head>
    <title>Follower List</title>
</head>
<body>
<h3>Follower List</h3>
<?php
if(isset($data))
{
foreach($data as $item)
{
?>
<div>
    <img src="<?php echo $item->avatar_url?>" alt="" width="100px"/><br/>
    <b>User Name : </b> {{$item->login}}<br/>
    <b>GitHub Handle : </b> {{$item->html_url}}<br/>
</div>
<hr/>
<?php
}
        ?>
<div id="div1">

</div>
<div class="append">
    <input type="hidden" value="{{$currentPage+1}}" name="page" class="page"/>
    <input type="hidden" value="{{$name}}" name="{{$name}}" class="name"/>
    <button class="btn">Load More...</button>
</div>
<?php
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $(".btn").click(function () {
            var name = $('.name').val();
            var page=$('.page').val();

            $.ajax({
                url: "http://127.0.0.1:8000/a.php?name=" + name+"&page="+page, success: function (result) {
                    $("#div1").append(result);
                    $('.page').val(+page+1);
            }
            });
        });
    });
</script>
</body>
</html>