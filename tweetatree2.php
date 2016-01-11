<?php

require_once('TwitterAPIExchange.php');

$settings = array(
    'oauth_access_token' => "32311090-ARf4wFxaGgzK5EVuc62TdWU6oYTtqyKSCh0Dmo",
    'oauth_access_token_secret' => "Rm5vWH9kYvCoLAmoY58Upb9KaL2fJRdEPoIxikL4",
    'consumer_key' => "Aw8UuSjja5hFJEjfH4VvbA",
    'consumer_secret' => "QuvH9IJpFBRFb8idKBsO7Cq2hZ8X7BFBoqnnMmrEKQ"
);

$url = 				'https://api.twitter.com/1.1/search/tweets.json';
$getfield = 		'?q=%40tweetatree&include_entities=false';
$requestMethod = 	'GET';

$twitter = new TwitterAPIExchange($settings);
$response = $twitter->setGetfield($getfield)
					->buildOauth($url, $requestMethod)
					->performRequest();


//$json = file_get_contents("http://search.twitter.com/search.json?q=%23wwe&since_id=331969064274624512&callback=?", true); //getting the file content
$decode = json_decode($response, true); //getting the file content as array

$con=mysqli_connect("localhost","ldicarro_tree","TweetATree!","ldicarro_tweetatree");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


$res = mysqli_fetch_array(mysqli_query($con,"SELECT id,tweet_id FROM tweets ORDER BY id DESC LIMIT 1"));

if($res['tweet_id'] == $decode['statuses'][0][id_str])
{
	// carry on
	echo '';
}
else
{
	mysqli_query($con,"INSERT INTO tweets (song,tweet_id,screen_name,name,created_at) VALUES ('".html_entity_decode($decode['statuses'][0]['text'])."','".$decode['statuses'][0][id_str]."','".$decode['statuses'][0]['user']['screen_name']."','".$decode['statuses'][0]['user']['name']."','".$decode['statuses'][0]['created_at']."')");
}

mysqli_close($con);

echo html_entity_decode($decode['statuses'][0]['text'])."\n";
echo $decode['statuses'][0][id_str];
// echo "@".$decode['statuses'][0]['user']['screen_name'];
// echo "<br /><br />";
// echo html_entity_decode($decode['statuses'][0]['text'])." ";
// echo $decode['statuses'][0][id_str]." ";
// echo "@".$decode['statuses'][0]['user']['screen_name']." ";
// echo $decode['statuses'][0]['user']['name']." ";
// echo $decode['statuses'][0]['created_at'];
// echo "<br /><br />";
// var_dump($decode); //getting the file content as array
?>
