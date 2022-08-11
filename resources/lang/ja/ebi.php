<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'app_name' => 'エビ養殖 IoT System',
    'photo_resolution' => '推奨解像度は200x200pxです',
    '測定データ' =>'測定データ',
    'Excelファイル (.xls）' => 'Excelファイル (.xls）',
    'アップロード' => 'アップロード',
    'pagination_footer' => '全:total件::from～:to件目',
    'ファイル名' => 'ファイル名',
    "作業者" => "作業者",
    "取込日時" => "取込日時",
    'msg_overlapped_pond_by_gps_without_delta' => '登録エラー：養殖池の範囲が:pond_namesと重複しています。',
    'msg_overlapped_pond_by_gps_with_delta' => '登録エラー：測定範囲のバッファが:pond_namesと重複しています。',
    "取込日時" => "取込日時",
    'msg_tag_over_maxlength' => 'タグ は12文字以下で指定してください。',
    'tag_registration_title' => '測定地点のタグ登録(全養殖池共通)',
    'Measurement_point' => '測定地点',
    'Tag_registration' => 'タグ登録',
    'North_west' => '北西',
    'North_east' => '北東',
    'South_west' => '南西',
    'South_east' => '南東',
    'msg_unmapping_pond_state' => 'どこの池にも属さないレコードが:count_unmapping件インポートされました',
    'mail_alert_subject' => '【エビIoTシステム】アラート検知:farm_name',
    'mail_alert_pond_state_pond_name' => '<b>測定日時：:time_alert</b> <br /><b>池名…:pond_name</b> <br />',
    'mail_alert_pond_state_body' => ':user_name様<br /> <br />'
                                    .'エビIoTシステムです。<br /> <br />'
                                    .':farm_nameにて、アラートを検知しました。<br /> <br />'
                                    . ':ponds_state_body <br /> '
                                    . '以上です。',
    'btn_alert_confirm'=>'アラート確認',
    'alert_shrimp_migration_success' => 'エビ移行が成功しました。',
    'alert_shrimp_migration_failed' => 'エビ移行が失敗しました。',
    'max_size' => '添付ファイルが最大サイズは ',

    //pondstates_import_done
    'インポートした結果'=>'インポートした結果',
    '行目'=>'行目',
    'エラー内容'=>'エラー内容',
    'エラーが発生しました。'=>'エラーが発生しました。',
    'インポートが完了しました。'=>'インポートが完了しました。',
    '合計'=>'合計',
    '成功件数'=>'成功件数',
    '失敗件数'=>'失敗件数',
    'エラー発生行：'=>'エラー発生行：',
    '行目_GPS設定範囲外'=>'行目：GPS設定範囲外',
    '行目_GPS不正'=>'行目：GPS不正',
    '戻る'=>'戻る',

    //sidebar.blade
    'プロファイル'=>'プロファイル',
    'ユーザー情報'=>'ユーザー情報',
    '新規ユーザー登録'=>'新規ユーザー登録',
    '水質基準値設定'=>'水質基準値設定',
    '養殖環境情報'=>'養殖環境情報',
    '基本情報'=>'基本情報',

    //nav
    '養殖履歴検索'=>'養殖履歴検索',
    '収支管理'=>'収支管理',
    'シミュレーション'=>'シミュレーション',
    '設備管理'=>'設備管理',
    '収支管理'=>'収支管理',
    'メールアドレス'=>'メールアドレス',
    'パスワード'=>'パスワード',

    //admin_template_with_left_sidebar.blade.blade
    '養殖池状況'=>'養殖池状況',
    '水質管理'=>'水質管理',
    'エビ管理'=>'エビ管理',

    //frontend_sidebar.blade
    '養殖環境'=>'養殖環境',
    '養殖開始日'=>'養殖開始日',
    '養殖方法'=>'養殖方法',
    '出荷日'=>'出荷日',
    '出荷予定日'=>'出荷予定日',
    '稚エビ数(匹)'=>'稚エビ数(匹)',
    '現在値'=>'現在値',
    '消費電力総量(Wh)'=>'消費電力総量(Wh)',
    '餌総量(kg)'=>'餌総量(kg)',
    '餌撒方法'=>'餌撒方法（回/日）',
    
    //farm_map.blade
    '正常時'=>'正常時',
    '異常検出'=>'異常検出',
    '養殖池比較'=>'養殖池比較',
    
    //shrimp_measure.blade
    '最終出荷状況'=>'最終出荷状況',
    'サイズ別出荷数'=>'サイズ別出荷数',
    '匹'=>'匹',
    'エビ総数'=>'エビ総数',
    '生存率'=>'生存率',
    '測定日'=>'測定日',
    'サイズ'=>'サイズ（cm)',
    '重量'=>'重量（g)',
    '餌総量'=>'餌総量（g)',
    'エビ写真'=>'エビ写真',

    //view_pond.blade
    '表表示'=>'表表示',
    '計測項目'=>'計測項目',
    '水質状況'=>'水質状況',
    '日付'=>'日付',
    'エビ状況'=>'エビ状況',
    'エビ成長度'=>'エビ成長度',
    '養殖日数（日）'=>'養殖日数（日）',
    '最終測定日'=>'最終測定日',
    'エビ総量'=>'エビ総量（匹)',
    
    //water_quality_detail.blade
    '一覧へ戻る'=>'一覧へ戻る',
    '地点A'=>'地点A',
    '地点A+'=>'地点A+',
    '地点B'=>'地点B',
    '地点B+'=>'地点B+',
    '地点C'=>'地点C',
    '地点C+'=>'地点C+',
    '地点D'=>'地点D',
    '地点D+'=>'地点D+',
    'データを更新します。'=>'データを更新します。',
    'よろしいですか？'=>'よろしいですか？',
    'グラフ表示'=>'グラフ表示',
    '計測地点'=>'計測地点',
    '養殖池'=>'養殖池',
    '養殖環境新規追加'=>'養殖環境新規追加',

    //AdminAquaculturesController
    '養殖場名'=>'養殖場名',
    '池名'=>'池名',
    '養殖期間'=>'養殖期間',
    '養殖場'=>'養殖場',
    '目標サイズ'=>'目標サイズ(cm)',
    '溶存酸素'=>'溶存酸素',
    '目標重量'=>'目標重量(g)',
    '餌費用' => '餌費用',
    '電力費用' => '電力費用',
    '餌量'=>'餌量(kg/日)',
    '消費電力'=>'消費電力(Wh/日)',
    '稚エビ量'=>'稚エビ量(匹)',
    'エビ出荷数'=>'エビ出荷数',
    'エビ出荷規格'=>'エビ出荷規格',
    '養殖環境登録'=>'養殖環境登録',
    
    //AdminCmsUsersController
    'ユーザ名'=>'ユーザ名',
    '画像'=>'画像',
    'パスワードの確認'=>'パスワードの確認',
    'アラートのメール通知'=>'アラートのメール通知',
    'パスワードを変更する場合は、入力してください'=>'パスワードを変更する場合は、入力してください',
    
    //AdminMinmaxController
    '国ID'=>'国ID',
    '国名'=>'国名',
    '国'=>'国',
    'ユーザ'=>'ユーザ',
    '発効日'=>'発効日',
    '下限'=>'（下限）',
    '上限'=>'（上限）',
    '酸化還元電位'=>'酸化還元電位',
    '導電率'=>'導電率',
    '絶対EC分析単位'=>'絶対EC分析単位',
    '低効率'=>'低効率',
    '全溶解度'=>'全溶解度',
    '塩分'=>'塩分',
    '海水比重'=>'海水比重',
    '濁度'=>'濁度 FNU',
    '水温'=>'水温',
    '気圧'=>'気圧',
    
    //AdminPondsController
    '養殖池名'=>'養殖池名',
    '水量'=>'水量',
    '横幅'=>'横幅',
    '水深'=>'水深',
    '縦幅'=>'縦幅',
    '緯度経度'=>'緯度経度',
    'タグ'=>'タグ',
    '地図コード'=>'地図コード',
    '測定範囲のバッファ'=>'測定範囲のバッファ',
    'サイズ'=>'サイズ(cm)',
    'エビ状況'=>'エビ状況',
    '北西'=>'（北西）',
    '北東'=>'（北東）',
    '南西'=>'（南西）',
    '南東'=>'（南東）',
    'レースウェイ'=>'レースウェイ',
    'クラシック'=>'クラシック',
    'バケツ'=>'バケツ',
    'バイオフロック'=>'バイオフロック',
    

    //
    '作業ID'=>'作業ID',
    '作業名'=>'作業名',
    '備考'=>'備考',
    '削除フラグ'=>'削除フラグ',

    //menu
    'エビ状況 '=>'エビ状況 ',
    '国管理'=>'国管理',
    '養殖場管理'=>'養殖場管理',
    'ユーザ管理'=>'ユーザ管理',
    '作業マスター'=>'作業マスター',
    '養殖池管理'=>'養殖池管理',
    '基準値'=>'基準値',
    '養殖環境管理'=>'養殖環境管理',

    '測定日が有りません'=>'測定日が有りません',
    '正しい形式の養殖池名を指定してください'=>'正しい形式の養殖池名を指定してください',
    '正しい形式の測定範囲のバッファを指定してください'=>'正しい形式の測定範囲のバッファを指定してください',
    'このファイル名を既にインポートされました。'=>'このファイル名を既にインポートされました。',
    'インポート'=>'インポート',
    'ファイル名と池が一致しません。'=>'ファイル名と池が一致しません。',
    '正しい形式のユーザ名を指定してください。'=>'正しい形式のユーザ名を指定してください。',
    '正しい形式のパスワードを指定してください。'=>'正しい形式のパスワードを指定してください。',
    '養殖開始日が有りません'=>'養殖開始日が有りません',
    '出荷予定日が有りません'=>'出荷予定日が有りません',
    '既に登録済の養殖開始日です。'=>'既に登録済の養殖開始日です。',

    'mV'=>'mV',
    '絶対EC分解単位'=>'絶対EC分解単位',
    '濁度_helpper'=>'濁度',
    'pH'=>'pH',
    '養殖池に関する基本情報'=>'養殖池に関する基本情報',
    
    '水質異常値検出'=>'水質異常値検出',
    'その他'=>'その他',
    '地点'=>'地点',
    '日本語'=>'日本語',
    '基本情報登録'=>'基本情報登録',
    '基本情報設定'=>'基本情報設定',

    //AdminDefaultPondsController
    '養殖場id'=>'養殖場id',
    '稚エビ投入数'=>'稚エビ投入数',
    '稚エビ種類'=>'稚エビ種類',
    '池用途'=>'池用途',
    'デフォルト値設定'=>'デフォルト値設定',
    '池別'=>'池別',
    '養殖場別設定'=>'養殖場別設定',
    'エビ設定'=>'エビ設定',
    '餌、薬単価設定'=>'餌、薬単価設定',
    '養殖開始設定'=>'養殖開始設定',
    '池別設定'=>'池別設定',
    '図面の配置番号'=>'図面の配置番号',
    
    // AdminEbiBaitInventoriesController
    '発注管理' => '発注管理',
    '餌,薬名称'=> '餌,薬名称',
    '在庫数'=> '在庫数',
    'ステータス'=> 'ステータス',
    '1袋辺り餌量'=> '1袋辺り餌量',
    '次回購入予定数'=> '次回購入予定数',
    '発注ステータス'=> '発注ステータス',
    '発注数'=> '発注数',
    '発注日'=> '発注日',
    '到着予定日'=> '到着予定日',
    '分類(餌/薬)'=> '分類(餌/薬)',
    '餌' => '餌',
    '薬'=> '薬',
    '袋'=> '袋',
    '正常'=> '正常',
    '在庫不足'=> '在庫不足',
    '餌ID'=> '餌ID',
    '検索条件'=> '検索条件',
    '検索'=> '検索',
    '新規登録'=> '新規登録',
    '発注履歴検索'=> '発注履歴検索',
    '基本情報登録' => '基本情報登録',
    '履歴検索' => '履歴検索',
    '発注登録' => '発注登録',
    '在庫不足閾値'=> '在庫不足閾値',
    "発注状況" => '発注状況',
    '未発注'=> '未発注',
    '発注済'=> '発注済',

    //Shrimp setting
    '削除'=>'削除',
    'エビ種類' => 'エビ種類',
    '1kg辺りの価格(ペソ)' => '1kg辺りの価格(ペソ)',
    '1匹辺りの重さ(g)' => '1匹辺りの重さ(g)',
    'エビ設定' => 'エビ設定',
    '養殖方法登録' => '養殖方法登録',
    '単価登録' => '単価登録',
    'エビ登録' => 'エビ登録',
    '養殖方法' => '養殖方法',
    '移行回数' => '移行回数',
    '削除' => '削除',
    'text_max_length' => 'テキストが最大長を超えています。',
    'number_max_value' => '数値が最大値を超えています。',
    'duplicate_method' => '養殖方法は複製できません！',
    'duplicate_kind' => 'エビ名に紐づく養殖方法が既に登録されました。',
    '育成日数は０以上記入してください。' => '育成日数は０以上記入してください。',


    //Aquacultures
    'デフォルト' => 'デフォルト',
    '養殖サイクルデフォルト' => '養殖サイクルデフォルト',
    '目標サイズ（㎝)' => '目標サイズ（㎝)',
    '目標重量（g)' => '目標重量（g)',
    '餌総費用（円)' => '餌総費用（円)',
    '消費電力（円)' => '消費電力（円)',
    '生存率(%)' => '生存率(%)',
    '薬総費用(円)' => '薬総費用(円)',
    '稚エビ数' => '稚エビ数',
    '餌撒方法' => '餌撒方法',
    '稚エビ種類' => '稚エビ種類',
    '養殖方法' => '養殖方法',
    'タブ追加' => 'タブ追加',
    '登録' => '登録',

    //ebi_kind
    'エビ名' => 'エビ名',
    '育成日数' => '育成日数',
    '単価' => '単価',
    '育成日数は０以上を記入してください。' => '育成日数は０以上を記入してください。',

    //feed_price
    '餌、薬名' => '餌、薬名',
    '単価(一袋辺り)' => '単価(一袋辺り)',
    '量(一袋辺り(kg))' => '量(一袋辺り(kg))',

    //setting farm
    'add_Farm' => '登録',
    'btn_add' => '追加',
    'confirm_add_pond' => '池を追加してよろしいですか？',
    'confirm_delete_pond' => '池を削除してよろしいですか？',
    'validate_required'=> '養殖池は必須項目です。',
    '養殖中なので、削除できません。' => '養殖中なので、削除できません。',
    '使用中の養殖場は削除できません。' => '使用中の養殖場は削除できません。',
    '養殖場は池が養殖中なので、削除できません。' => '養殖場は池が養殖中なので、削除できません。',
    '養殖場が削除しました' => '養殖場が削除しました',
    '養殖場が削除失敗しました' => '養殖場が削除失敗しました',
    '養殖場の最大数は6つです、もっと追加できません。' => '養殖場の最大数は6つです、もっと追加できません。',
    '使用されている餌/薬は削除できません。' => '使用されている餌/薬は削除できません。',

    //farm map
    '池状況(養殖場全体)' => '池状況(養殖場全体)',
    '養殖場収支' => '養殖場収支',
    '餌運用' => '餌運用',
    '餌状況' => '餌状況',
    '収支' => '収支',
    '特別池（この池が選択不可）' => '特別池（この池が選択不可）',
    '他の養殖場に紐づく池' => '他の養殖場に紐づく池',
    'この養殖場に紐づく池' => 'この養殖場に紐づく池',
    '養殖停止' => '養殖停止',
    '養殖中' => '養殖中',
    '池を選択可能' => '池を選択可能',
    '登録されない池' =>'登録されない池',
    '特別池' => '特別池',
    '池が登録されました' => '池が登録されました',
    'このタグが複製してはいけません' => 'このタグが複製してはいけません',
    '餌をあげてからの経過時間' => '餌をあげてからの経過時間',
    '作業内容' => '作業内容',
    '晴れ' => '晴れ',
    '曇り' => '曇り',
    '雨' => '雨',
    '雪' => '雪',
    '台風' => '台風',
    '編集' => '編集',
    '解決' => '解決',
    'solution' => '解決',
    '解決の登録' => '解決の登録',
    '解決の変更' => '解決の変更',

    //feed graph

    '餌種類' => '餌種類',
    '累計' => '累計',
    '餌合計' => '餌合計',
    '薬種類' => '薬種類',
    '薬合計' => '薬合計',
    'エビ(1m3)' => 'エビ(1m3)',

    '気温' => '気温',
    '天気' => '天気',

    '理想餌量' => '理想餌量',
    '実績餌量' => '実績餌量',
    '累計投与餌量' => '累計投与餌量',

    '収支' => '収支',

    // start aquaculture
    '初期投入養殖池' => '初期投入養殖池',
    '薬費用' => '薬費用',
    'エビ移行登録' => 'エビ移行登録',
    '出荷ステータス' => '出荷ステータス',
    '養殖中' => '養殖中',
    '完了' => '完了',
    'エビ残数' => 'エビ残数（匹）',

    //import_bait
    '池名が存在しません' => '池名が存在しません',
    'データが不明です' => 'データが不明です',
    '日付を入力しなければなりません' =>'日付を入力しなければなりません',
    '養殖' => '養殖',
    'が存在しません。' => 'が存在しません。',
    'この行のDOCは' => 'この行のDOCは',
    'です。' =>'です。',
    'ファイルを選択してください。' => 'ファイルを選択してください。',
    '以下にしてください。' => 'B 以下にしてください。',
    'ファイルサイズが大きすぎで、' => 'ファイルサイズが大きすぎで、',

    // Frontend sidebar
    '予想売上' => '予想売上',
    '総重量'  => '総重量',
    '売上' => '売上',
    '理想との比較' => '理想との比較',

    '銅イオン濃度'=> '銅イオン濃度',
    '気温(℃)'=> '気温(℃)',
    'アンモニア'=> 'アンモニア',
    '測定データファイル'=> '測定データファイル',
    '測定データアップロード'=> '測定データアップロード',
    '養殖状況登録'=> '養殖状況登録',
    'エビ状況登録' => 'エビ状況登録',
    'エビ価格表' => 'エビ価格表',
    '現在の単価' => 'エビ価格表',
    'アクション'=> 'アクション',
    '平均単価' => '平均単価',
    '平均成長度' => '平均成長度',
    '日数' => '日数',

    '円' => '円',
    '餌、薬運用' => '餌、薬運用',
    '薬運用' => '薬運用',
    'date_validation' => '測定日は養殖開始日より未来でなければなりません',
    '水質詳細' => '水質詳細',
    '餌詳細' => '餌詳細',
    'エビ詳細' => 'エビ詳細',
    'から' => 'から',
    'まで' => 'まで',
    '以上' => '以上',
    '以下' => '以下',
    '目標重量(g)' => '目標重量(g)',
    '稚エビ量(匹)' => '稚エビ量(匹)',
    'エビ種類' => 'エビ種類',
    '結果' => '結果',
    '計算' => '計算',
    '費用名' => '費用名',
    '金額' => '金額',
    '総収支' => '総収支',
    '総売上' => '総売上',
    '養殖場名' => '養殖場名',
    '目標収支' => '目標収支',
    '収支実績' => '収支実績',
    '売上実績' => '売上実績',
    '達成率' => '達成率',
    'その他費用' => 'その他費用',
    '費用登録' => '費用登録',
    '稚エビ費用' => '稚エビ費用',
    '年度検索' => '年度検索',
    '月別検索' => '月別検索',
    '現在' => '現在',
    '出荷量(kg)' => '出荷重量(kg)',
    'エビ重量(g)' => 'エビ重量(g)',
    '売上登録' => '売上登録',
    '費用' => '費用',
    '金額' => '金額',
    '保存' => '保存',
    '冷凍在庫登録' => '冷凍在庫登録',
    '冷凍保存' => '冷凍保存',
    '輸出' => '輸出',
    '冷凍在庫' => '冷凍在庫',
    'エビ重量' => 'エビ重量',
    'ストック' => 'ストック',
    'ナノバブル' => 'ナノバブル',
    '無' => '無',
    '有' => '有',

];