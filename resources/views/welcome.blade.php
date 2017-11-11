<?php
$url = "http://127.0.0.1:8000";
?>
<html>
<head>
    <title>Github Api Project</title>
</head>
<body>
<center>
    <h3>Search Git Hub User</h3>

    <form action="<?php $url?>/search" method="" get>
        <input type="text" name="q" placeholder="Enter Username" class="q"/>
        <button name="submit" class="btn1">Search</button>
    </form>
</center>
<div id="div1">
    <?php
        if(isset($data)){
            foreach($data->items as $item){
               ?>
    <p>
        <img src="<?php echo $item->avatar_url?>" alt="" width="100px"/><br/>
        <b>User Name : </b> {{$item->login}}<br/>
        <b>GitHub Handle : </b> {{$item->html_url}}<br/>
        <b>Follower Count : </b> {{$item->singleData->followers}} <a href="{{$url}}/followers/{{$item->login}}" target="_blank">View List</a><br/>
    </p>
        <hr/>
        <?php
            }
        }
    ?>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $(".btn").click(function () {
            var q = $('.q').val();
            if (q.length == 0) {
                alert('sorry');
                //return false;
            }
            $.ajax({
                url: "http://127.0.0.1:8000//api/users?q=" + q, success: function (result) {
                    $("#div1").html(result);
                }
            });
        });
    });
</script>
</body>
</html>