<?php
return [
    'greeting'     => ':name 様、',
    'view_product' => '商品を見る',
    
    'subjects' => [
        'price'            => 'NexusGearでお気に入り商品が値下げされました！',
        'stock_bajo'       => 'お気に入り商品の残り在庫がわずかです！',
        'stock_agotado'    => '商品が一時的に売り切れました',
        'stock_disponible' => 'お気に入り商品が再入荷しました！',
    ],
    
    'messages' => [
        'price'            => '商品「:product」が :price € に値下げされました。',
        'stock_bajo'       => 'お急ぎください！「:product」の在庫は残り :stock 個です。',
        'stock_agotado'    => '商品「:product」は在庫切れとなりました。',
        'stock_disponible' => '嬉しいお知らせです！「:product」が再入荷され、現在 :stock 個の在庫があります。',
    ],
];