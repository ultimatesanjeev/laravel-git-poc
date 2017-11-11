<?php
$name=$_GET['name'];
$page=$_GET['page'];
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.github.com/users/".$name."/followers?per_page=10&page=".$page,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "postman-token: cf2a420b-9901-e211-7a33-ae9462d9a5a4",
        "user-agent: node.js"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "No More Follower";
} else {
    $data=json_decode($response);
    foreach($data as $item)
    {
        ?>
        <div>
            <img src="<?php echo $item->avatar_url?>" alt="" width="100px"/><br/>
            <b>User Name : </b> <?php echo $item->login;?><br/>
            <b>GitHub Handle : </b> <?php echo $item->html_url?><br/>
        </div>
        <hr/>
    <?php
    }
}