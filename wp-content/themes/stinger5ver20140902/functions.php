<?php
function register_jq_script() {
	if (!is_admin()) {
		$script_dir = get_template_directory_uri();
		wp_deregister_script( 'jquery' );
		wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js',array(), false, false);
	}
}
add_action('wp_enqueue_scripts','register_jq_script');

//カスタム背景
$custom_bgcolor_defaults = array(
        'default-color' => '#f2f2f2',
);
add_theme_support( 'custom-background', $custom_bgcolor_defaults );


//カスタムヘッダー
$custom_header = array(
 'random-default' => false,
 'width' => 980,
 'height' => 250,
 'flex-height' => true,
 'flex-width' => false,
 'default-text-color' => '',
 'header-text' => false,
 'uploads' => true,
 'default-image' => get_template_directory_uri() . '/images/stinger5.png',
);
add_theme_support( 'custom-header', $custom_header );

// 抜粋の長さを変更する
function custom_excerpt_length( $length ) {
     return 40;	
}	
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// 文末文字を変更する
function custom_excerpt_more($more) {
	return ' ... ';
}
add_filter('excerpt_more', 'custom_excerpt_more');

//スマホ表示分岐
function is_mobile(){
    $useragents = array(
        'iPhone', // iPhone
        'iPod', // iPod touch
        'Android.*Mobile', // 1.5+ Android *** Only mobile
        'Windows.*Phone', // *** Windows Phone
        'dream', // Pre 1.5 Android
        'CUPCAKE', // 1.5+ Android
        'blackberry9500', // Storm
        'blackberry9530', // Storm
        'blackberry9520', // Storm v2
        'blackberry9550', // Storm v2
        'blackberry9800', // Torch
        'webOS', // Palm Pre Experimental
        'incognito', // Other iPhone browser
        'webmate' // Other iPhone browser

    );
    $pattern = '/'.implode('|', $useragents).'/i';
    return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}
//アイキャッチサムネイル
add_theme_support('post-thumbnails');
add_image_size('thumb100',100,100,true);
add_image_size('thumb150',150,150,true);


//カスタムメニュー
register_nav_menus(array('navbar' => 'ナビゲーションバー'));

//RSS
add_theme_support('automatic-feed-links');

// 管理画面にオリジナルのスタイルを適用
add_editor_style("style.css");	// メインのCSS
add_editor_style('editor-style.css');	// これは入れておこう
if ( ! isset( $content_width ) ) $content_width = 580;
function custom_editor_settings( $initArray ){
	$initArray['body_id'] = 'primary';	// id の場合はこれ
	$initArray['body_class'] = 'post';	// class の場合はこれ
	return $initArray;
}
add_filter( 'tiny_mce_before_init', 'custom_editor_settings' );

//投稿用ファイルを読み込む
get_template_part('functions/create-thread');

//ページャー機能
function pagination($pages = '', $range = 4)
{
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     } 

     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link

(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;

Previous</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems

))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link

($i)."' class=\"inactive\">".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged +

1)."\">Next &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a

href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}

//ヘッダーを綺麗に
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_generator' );

//moreリンク
function custom_content_more_link( $output ) {
    $output = preg_replace('/#more-[\d]+/i', '', $output );
    return $output;
}
add_filter( 'the_content_more_link', 'custom_content_more_link' );

//セルフピンバック禁止
function no_self_ping( &$links ) {
    $home = home_url();
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, $home ) )
            unset($links[$l]);
}
add_action( 'pre_ping', 'no_self_ping' );

//iframeのレスポンシブ対応
function wrap_iframe_in_div($the_content) {
  if ( is_singular() ) {
    $the_content = preg_replace('/< *?iframe/i', '<div class="youtube-container"><iframe', $the_content);
    $the_content = preg_replace('/<\/ *?iframe *?>/i', '</iframe></div>', $the_content);
  }
  return $the_content;
}
add_filter('the_content','wrap_iframe_in_div');

//ウイジェット追加
if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(4) )
register_sidebars(1,
    array(
    'name'=>'サイドバーウイジェット',
    'before_widget' => '<ul><li>',
    'after_widget' => '</li></ul>',
    'before_title' => '<h4 class="menu_underh2">',
    'after_title' => '</h4>',
    ));
register_sidebars(1,
    array(
    'name'=>'スクロール広告用',
    'description' => '「テキスト」をここにドロップして内容を入力して下さい。アドセンスは禁止です。※PC以外では非表示部分',
    'before_widget' => '<ul><li>',
    'after_widget' => '</li></ul>',
    'before_title' => '<h4 class="menu_underh2" style="text-align:left;">',
    'after_title' => '</h4>',
    ));
register_sidebars(1,
    array(
    'name'=>'Googleアドセンス用336px',
    'description' => '「テキスト」をここにドロップしてコードを入力して下さい。タイトルは反映されません。',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<h4 style="display:none">',
    'after_title' => '</h4>',
    ));

register_sidebars(1,
    array(
    'name'=>'Googleアドセンスのスマホ用300px',
    'description' => '「テキスト」をここにドロップしてコードを入力して下さい。タイトルは反映されません。',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<h4 style="display:none">',
    'after_title' => '</h4>',
    ));


//更新日の追加
function get_mtime($format) {
    $mtime = get_the_modified_time('Ymd');
    $ptime = get_the_time('Ymd');
    if ($ptime > $mtime) {
        return get_the_time($format);
    } elseif ($ptime === $mtime) {
        return null;
    } else {
        return get_the_modified_time($format);
    }
}

//テーマカスタマイザーで編集しない方は削除して下さい（ここから）

function stinger_customize_register($wp_customize) {

$wp_customize->add_section( 'stinger_logo_image', array(
'title' => 'ロゴ画像',
'priority' => 10,
) );
 
$wp_customize->add_setting( 'stinger_logo_image', array(
'default' => '',
'type' => 'option',
'capability' => 'edit_theme_options',
) );
 
$wp_customize->add_control( new WP_Customize_Image_Control(
$wp_customize,
'logo_Image',
array(
'label' => '画像',
'section' => 'stinger_logo_image',
'settings' => 'stinger_logo_image',
)
) );
     
    // Color
    $wp_customize->add_section( 'stinger_menu_customize', array(
    'title' => __( '基本色（キーカラー）', 'stinger' ),
    'priority' => 30,
    ) );
	  
	$wp_customize->add_setting( 'stinger_menu_logocolor', array( 'default' => '#1a1a1a', ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stinger_menu_logocolor', array(
    'label' => __( 'グループ1（ブログタイトル色など）', 'stinger' ),
    'section' => 'stinger_menu_customize',
    'settings' => 'stinger_menu_logocolor',
    ) ) );

    $wp_customize->add_setting( 'stinger_menu_bgcolor', array( 'default' => '#f3f3f3', ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stinger_menu_bgcolor', array(
    'label' => __( 'グループ2（吹き出し背景など）', 'stinger' ),
    'section' => 'stinger_menu_customize',
    'settings' => 'stinger_menu_bgcolor',
    ) ) );
     
    $wp_customize->add_setting( 'stinger_menu_color', array( 'default' => '#1a1a1a', ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stinger_menu_color', array(
    'label' => __( '吹き出し内の文字（H2）', 'stinger' ),
    'section' => 'stinger_menu_customize',
    'settings' => 'stinger_menu_color',
    ) ) );
	  
    $wp_customize->add_setting( 'stinger_menu_comcolor', array( 'default' => '#f3f3f3', ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stinger_menu_comcolor', array(
    'label' => __( 'グループ3（淡い色推奨）', 'stinger' ),
    'section' => 'stinger_menu_customize',
    'settings' => 'stinger_menu_comcolor',
    ) ) );
	  
         }
    add_action('customize_register', 'stinger_customize_register');
    
    function stinger_customize_css()
    {
    //初期カラー
    $menu_color = get_theme_mod( 'stinger_menu_color', '#1a1a1a');
    $menu_bgcolor = get_theme_mod( 'stinger_menu_bgcolor', '#f3f3f3');
    $menu_logocolor = get_theme_mod( 'stinger_menu_logocolor', '#1a1a1a');
    $menu_comcolor = get_theme_mod( 'stinger_menu_comcolor', '#f3f3f3');
    ?>
<style type="text/css">
/*グループ1
------------------------------------------------------------*/
/*ブログタイトル*/
header .sitename a {
 color: <?php echo $menu_logocolor;
?>;
}
/* メニュー */
nav li a {
 color: <?php echo $menu_logocolor;
?>;
}
/*キャプション */

header h1 {
 color: <?php echo $menu_logocolor;
?>;
}
header .descr {
 color: <?php echo $menu_logocolor;
?>;
}
/* アコーディオン */
#s-navi dt.trigger .op {
	color: <?php echo $menu_logocolor;
?>;
}
.acordion_tree li a {
	color: <?php echo $menu_logocolor;
?>;
}
/* サイド見出し */
aside h4 {
 color: <?php echo $menu_logocolor;
?>;
}
/* フッター文字 */
#footer,#footer .copy {
color: <?php echo $menu_logocolor;
?>;
}
/*グループ2
------------------------------------------------------------*/
/* 中見出し */
h2 {
 background: <?php echo $menu_bgcolor;
?>;
 color: <?php echo $menu_color;
?>;
}
h2:after {
 border-top: 10px solid <?php echo $menu_bgcolor;
?>;
}
h2:before {
 border-top: 10px solid <?php echo $menu_bgcolor;
?>;
}
/*小見出し*/
.post h3 {
 border-bottom: 1px <?php echo $menu_bgcolor;
?> dotted;
}
/* 記事タイトル下の線 */
.blogbox {
 border-top-color: <?php echo $menu_bgcolor;
?>;
 border-bottom-color: <?php echo $menu_bgcolor;
?>;
}
/* コメントボタン色 */
#comments input[type="submit"] {
background-color: <?php echo $menu_bgcolor;
?>;
}
#comments input[type="submit"] {
color: <?php echo $menu_color;
?>;
}
/* RSSボタン */
.rssbox a {
	background-color: <?php echo $menu_bgcolor;
?>;
}
/*グループ3
------------------------------------------------------------*/
/* 記事タイトル下 */
.blogbox {
 background: <?php echo $menu_comcolor;
?>;
}
/*h4*/
.post h4{
background-color:<?php echo $menu_comcolor;
?>;
}
/* 検索フォーム */
#s {
 background: <?php echo $menu_comcolor;
?>;
}
#searchsubmit{
 background: <?php echo $menu_comcolor;
?>;
}
/* コメント */
#comments {
 background: <?php echo $menu_comcolor;
?>;
}
/* カレンダー曜日背景 */
#wp-calendar thead tr th {
 background: <?php echo $menu_comcolor;
?>;
}
</style>
<?php
    }
    add_action( 'wp_head', 'stinger_customize_css');

//カスタマイザーで色を設定しない方は削除して下さい（ここまで）