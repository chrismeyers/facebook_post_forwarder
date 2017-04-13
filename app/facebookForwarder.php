<?php

require_once __DIR__ . "/../autoload.php";
require_once __DIR__ . '/../vendor/autoload.php';

use Post\Forwarder\Config;
use Post\Forwarder\Util;

class FacebookForwarder {
  private $fb = NULL;
  private $util = NULL;

  public function __construct() {
    $this->fb = new Facebook\Facebook([
      'app_id' => Config::APP_ID,
      'app_secret' => Config::APP_SECRET,
      'default_graph_version' => Config::API_VERSION
    ]);

    $this->util = new Util();
  }

  public function getMe() {
    $this->util->fancy_print($this->fb->get('/me', Config::TOKEN));
  }

  public function getGroupFeed($groupId) {
    $response = $this->fb->get('/' . $groupId . '/feed', Config::TOKEN);
    $messages = $response->getDecodedBody()["data"];

    //$this->util->fancy_print($response);
    
    foreach($messages as $message) {
      echo $message["message"] . "<br />";

      echo "<ul>";
      $this->getComments($message["id"]);
      echo "</ul>";

      echo $message["updated_time"] . "<br />";
      echo "<br /><hr><br />";
    }
  }

  public function getComments($postId) {
    $response = $this->fb->get('/' . $postId . '/comments', Config::TOKEN);
    $comments = $response->getDecodedBody()["data"];

    //$this->util->fancy_print($response);

    foreach($comments as $comment) {
      echo "<li>";
      echo $comment["from"]["name"] . ": " . $comment["message"];
      echo "<ul>";
      $this->getReplies($comment["id"]);
      echo "</ul>";
      echo "</li>";
    }
  }

  public function getReplies($commentId) {
    $response = $this->fb->get('/' . $commentId . '/comments', Config::TOKEN);
    $replies = $response->getDecodedBody()["data"];

    //$this->util->fancy_print($response);

    foreach($replies as $reply) {
      echo "<li>";
      echo $reply["from"]["name"] . ": " . $reply["message"];
      echo "</li>";
    }
  }

}
