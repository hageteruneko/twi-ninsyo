# twi-ninsyo

twitter+JWT認証

認証方法<br>
ツイッターでログインする<br>
↓<br>
ツイッターIDとユーザー名とIDを取得<br>
↓<br>
取得した配列をまとめてトークンにし、セッションに保存<br>

↓<br>
トークンをデコードし、できるなら通し、できないならエラーページに飛ばす<br>

indexcontlloler<br>
  loginAction<br>
    ツイッター認証画面に飛ぶ<br>
    アクセストークンを取得する<br>
  callbackAction<br>
    アクセストークンを使い、ID,Twitterでの名前,TwitterIDを取得する<br>
    得た情報をDBに保管<br>
    得た情報をすべてJWTでエンコードする<br>
    エンコードしたものをセッションに保存<br>
  logoutAction<br>
    セッションの削除<br>
  errorAction<br>
    セッションの削除<br>
  
productsContlloler<br>
  indexAction<br>
    セッションからJWTトークンを取得<br>
    取得したトークンをデコードし、ユーザの情報を取得<br>
    セッションがなければ初期画面に飛ぶ<br>
    セッションがあればセッションを取得<br>
    
    トークンが不正であればエラー画面へ<br>
    トークンが不正でなければデコードする<br>
