<?php
/**
 * 
 * News_Posts_Plugin Common Settings .
 * 
 * @package News_Posts_Plugin
 * @version 1.0
 */

if ( !class_exists('News_Posts') ) {
    exit;
}

$NewsPosts = new News_Posts;

class News_Posts {
    
    public function __construct() {
        add_action( 'init', array($this, 'my_post_type'));
        add_filter( 'post_type_link', array($this, 'my_post_type_link'), 1, 2 );
        add_filter( 'rewrite_rules_array', array($this,'my_rewrite_rules_array') );
        add_action( 'pre_get_posts', array($this, 'add_my_post_types_to_query') );
    }

    //カスタム投稿タイプを登録
    function my_post_type() {
        $post_name = 'お知らせ';
        $label = 'news';
        register_post_type(
            $label,//投稿タイプ名（識別子）
            array(
            'label' => $post_name,
            'labels' => array(
                'add_new_item' => $post_name . 'を追加',
                'edit_item' => $post_name . 'を編集',
                'view_item' => $post_name . 'を表示',
                'search_items' => $post_name . 'を検索',
            ),
            'public' => true,// 管理画面に表示しサイト上にも表示する
            'hierarchicla' => false,//コンテンツを階層構造にするかどうか(投稿記事と同様に時系列に)
            'has_archive' => true,//trueにすると投稿した記事のアーカイブページを生成
            'supports' => array(//記事編集画面に表示する項目を配列で指定することができる
                'title',//タイトル
                'editor',//本文（の編集機能）
                'thumbnail',//アイキャッチ画像
                'custom-fields',
                'excerpt'//抜粋
            ),
            'menu_position' => 5//「投稿」の下に追加
            )
        );
    
        register_taxonomy(
            $label . '_cat',
            $label,
            array(
            'label' =>  $post_name . 'カテゴリー',
            'labels' => array(
                'popular_items' => 'よく使う' . $post_name . 'カテゴリー',
                'edit_item' => $post_name . 'カテゴリーを編集',
                'add_new_item' => '新規' . $post_name . 'カテゴリーを追加',
                'search_items' =>  $post_name . 'カテゴリーを検索',
            ),
            'public' => true,
            'hierarchical' => true,
            'rewrite' => array('slug' => $label . '/cat')  //events_cat の代わりに events/cat でアクセス（URL)
            )
        );
        
        register_taxonomy(
            $label . '_tag',
            $label,
            array(
            'label' => $post_name . 'タグ',
            'labels' => array(
                'popular_items' =>  'よく使う' . $post_name . 'タグ',
                'edit_item' => $post_name . 'タグを編集',
                'add_new_item' => '新規' . $post_name . 'タグを追加',
                'search_items' =>  $post_name . 'タグを検索',
            ),
            'public' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => $label . '/tag')
            )
        );
        flush_rewrite_rules();
    }

    // パーマリンクに数字を使う
    function my_post_type_link( $link, $post ){
        if ( 'news' === $post->post_type ) {
            return home_url( '/archives/news/' . $post->ID );
        } else {
            return $link;
        }
    }
 
    function my_rewrite_rules_array( $rules ) {
        $new_rules = array( 
            'archives/news/([0-9]+)/?$' => 'index.php?post_type=news&p=$matches[1]',
        );
        
        return $new_rules + $rules;
    }
    

    function add_my_post_types_to_query( $query ) {
        if ( is_home() && $query->is_main_query() )
            $query->set( 'post_type', array( 'post', 'page', 'news' ) );
        return $query;
    }
}