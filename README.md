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
  <t>loginAction<br>
    <t><t>ツイッター認証画面に飛ぶ<br>
    <t><t>アクセストークンを取得する<br>
  <t>callbackAction<br>
    <t><t>アクセストークンを使い、ID,Twitterでの名前,TwitterIDを取得する<br>
    <t><t>得た情報をDBに保管<br>
    <t><t>得た情報をすべてJWTでエンコードする<br>
    <t><t>エンコードしたものをセッションに保存<br>
  <t>logoutAction<br>
    <t><t>セッションの削除<br>
 <t> errorAction<br>
    <t><t>セッションの削除<br>
  
productsContlloler<br>
  <t>indexAction<br>
    <t><t>セッションからJWTトークンを取得<br>
    <t><t>取得したトークンをデコードし、ユーザの情報を取得<br>
    <t><t>セッションがなければ初期画面に飛ぶ<br>
    <t><t>セッションがあればセッションを取得<br>
    <t><t>トークンが不正であればエラー画面へ<br>
    <t><t>トークンが不正でなければデコードする<br>
