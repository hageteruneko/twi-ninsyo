API設計書

このAPIの原型はphalconのscaffold機能を用いたものです。

データベース

id 	name		manual		price	image		raw_data	extention
int 	txt(100)	txt(500)	int	txt(100)	longblob	txt
id	商品名		商品説明	値段	画像		画像のデータ	画像の拡張子
	
raw_dataは使用しない

web上で入力するもの
id,name,manual,price,image
すべて必須項目

画像のファイルをアップロードするとextentionに拡張子が入る
アップロードファイルはpublic\imgに保存される




