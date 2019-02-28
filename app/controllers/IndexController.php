<?php
use Abraham\TwitterOAuth\TwitterOAuth;
use \Firebase\JWT\JWT;

class IndexController extends ControllerBase
{

    public function indexAction()
    {

    }
    public function loginAction()
    {
        $connection = new TwitterOAuth(CK, CKS);
        $request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => CBURL));
        
        //リクエストトークンはcallback.phpでも利用するのでセッションに保存する
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
         
        // Twitterの認証画面へリダイレクト
        $url = $connection->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));
        header('Location: ' . $url);
    }
    public function callbackAction()
    {
        if(isset($_GET['oauth_token'])) {
            $oauth_token=$_GET['oauth_token'];
            $oauth_token_secret=$_GET['oauth_verifier'];

            $connection = new TwitterOAuth(CK, CKS, $oauth_token, $oauth_token_secret);
	        $access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $_GET['oauth_verifier'], 'oauth_token'=> $_GET['oauth_token']));
        
            $user_connection = new TwitterOAuth(CK, CKS, $access_token['oauth_token'], $access_token['oauth_token_secret']);
            $this->view->connection = $user_connection;
            $user_info = $user_connection->get('account/verify_credentials');

            $user = new User();
            $user->twitter_id = $user_info->screen_name;
            $user->name = $user_info->name;
            $user->id = $user_info->id;

            $jwt = JWT::encode($user, KEY);
            $user->jwt = $jwt;

            $this->session->set("jwt", $jwt);

            if ($user->save() === false) {
                echo "できない：\n";
            
                $messages = $user->getMessages();
            
                foreach ($users as $user) {
                    echo $message, "\n";
                }
            } else {
                echo 'セーブに成功した';
            }

            $this->persistent->jwt = $jwt;
            $this->view->jwt = $jwt;
            $decoded = JWT::decode($jwt, KEY, array('HS256'));
            $this->view->decoded = $decoded;
        }
    }
    public function logoutAction()
    {
        header("Content-type: text/html; charset=utf-8");
 
        //セッション変数を全て解除
        $_SESSION = array();
         
        //セッションクッキーの削除
        if (isset($_COOKIE["PHPSESSID"])) {
            setcookie("PHPSESSID", '', time() - 1800, '/');
        }
    }
    public function errorAction()
    {
        header("Content-type: text/html; charset=utf-8");
 
        //セッション変数を全て解除
        $_SESSION = array();
         
        //セッションクッキーの削除
        if (isset($_COOKIE["PHPSESSID"])) {
            setcookie("PHPSESSID", '', time() - 1800, '/');
        }
    }
}