API設計書

このAPIの原型はphalconのscaffold機能を用いたものです。

データベース

id 	name		manual		price	image		raw_data	extention
int 	txt(100)	txt(500)	int	txt(100)	longblob	txt
id	商品名		商品説明	値段	画像		画像のデータ	画像の拡張子
	
raw_dataは使用しない。

web上で入力するもの
id,name,manual,price,image
すべて必須項目

入力しなかった場合
(入力しなかったデータ) is required

画像のファイルをアップロードするとextentionに拡張子が入る
アップロードファイルはpublic\imgに保存される

検索結果が一件もない場合
The search did not find any products

作成に成功した場合
product was created successfully

編集に成功した場合
product was updated successfully

削除に成功した場合
product was deleted successfully
削除した場合、画像はPCからも消える




