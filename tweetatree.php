<?php
/**
*
* Attach to Twitter and search for the desired terms.
* Parse returning JSON, send the ID of the last tweet to 
* the requestor.
*
* @author     Louis DiCarro
* @version    2.0
*/


require_once('TwitterAPIExchange.php');

$settings = array(
    'oauth_access_token'        => "access_token",
    'oauth_access_token_secret' => "secret_token",
    'consumer_key'              => "key",
    'consumer_secret'           => "secret"
);

          $url = 'https://api.twitter.com/1.1/search/tweets.json';
     $getfield = '?q=%40tweetatree&include_entities=false';
$requestMethod = 	'GET';

$twitter = new TwitterAPIExchange($settings);
$response = $twitter->setGetfield($getfield)
					->buildOauth($url, $requestMethod)
					->performRequest();


$decode = json_decode($response, true); //getting the file content as array

// Connect to mysql and store result
$con=mysqli_connect("localhost","ldicarro_tree","TweetATree!","ldicarro_tweetatree");

// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


// Get the last tweet id from the database
$res = mysqli_fetch_array(mysqli_query($con,"SELECT id,tweet_id FROM tweets ORDER BY id DESC LIMIT 1"));

if($res['tweet_id'] == $decode['statuses'][0][id_str])
{
	// If the tweet ids match, return null
	echo '';
}
else
{
	// If the tweet ids do not match, insert the tweet into the database
	mysqli_query($con,"INSERT INTO tweets (song,tweet_id,screen_name,name,created_at) VALUES ('".html_entity_decode($decode['statuses'][0]['text'])."','".$decode['statuses'][0][id_str]."','".$decode['statuses'][0]['user']['screen_name']."','".$decode['statuses'][0]['user']['name']."','".$decode['statuses'][0]['created_at']."')");
}

mysqli_close($con);

// Return the data (if any)
echo html_entity_decode($decode['statuses'][0]['text'])."\n";
echo $decode['statuses'][0][id_str];
?>
