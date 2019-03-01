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

indexcontlloler
  loginAction
    ツイッター認証画面に飛ぶ
    アクセストークンを取得する
  callbackAction
    アクセストークンを使い、ID,Twitterでの名前,TwitterIDを取得する
    得た情報をDBに保管
    得た情報をすべてJWTでエンコードする
    エンコードしたものをセッションに保存
  logoutAction
    セッションの削除
  errorAction
    セッションの削除
  
productsContlloler
  indexAction
    セッションからJWTトークンを取得
    取得したトークンをデコードし、ユーザの情報を取得
    セッションがなければ初期画面に飛ぶ
    セッションがあればセッションを取得
    
    トークンが不正であればエラー画面へ
    トークンが不正でなければデコードする
