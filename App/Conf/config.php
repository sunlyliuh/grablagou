<?php
//注意，请不要在这里配置SAE的数据库，配置你本地的数据库就可以了。
return array(
    //'配置项'=>'配置值'
    'SHOW_PAGE_TRACE'=>false,
//    'URL_HTML_SUFFIX'=>'.html',
    
    /* 数据库设置 */
    'DB_TYPE'               => 'mysql',     // 数据库类型
    'DB_NAME'               => 'lagou',          // 数据库名
    'DB_HOST'               => 'localhost', // 服务器地址
    'DB_USER'               => 'root',      // 用户名
    'DB_PWD'                => '123456',          // 密码
    'DB_PREFIX'             => 'lagou_',    // 数据库表前缀
    
    'DEFAULT_MODULE'        => 'Article', // 默认模块名称
    'DEFAULT_ACTION'        => 'index', // 默认操作名称
    
    /* Cookie设置 */
    'COOKIE_EXPIRE'         => 3600,    // Coodie有效期
    'COOKIE_PATH'           => '/',     // Cookie路径
    'COOKIE_PREFIX'         => '',      // Cookie前缀 避免冲突
    /* layout 开启 */
    'LAYOUT_ON'             => true,
    'LAYOUT_NAME'           => 'layout/default',
    'TMPL_LAYOUT_ITEM'      => '{__CONTENT__}',
    /* url设置 */
    'URL_CASE_INSENSITIVE'  => true, // 不区分大小写
    'URL_MODEL'             => '2',
    'URL_PATHINFO_DEPR'     => '-',
    //启用路由功能
    'URL_ROUTER_ON'=>true,
    //路由定义
    'URL_ROUTE_RULES'=> array(
//        'blog/:year\d/:month\d'=>'Blog/archive', //规则路由
//        'blog/:id\d'=>'Blog/read', //规则路由
//        'blog/:cate'=>'Blog/category', //规则路由
//        '/(\d+)/' => 'Blog/view?id=:1',//正则路由
//        'article/detail/id/:id\d' => 'article/detail/:id_html',
//        'article/detail/(\d+)\.html' => 'article/detail?id=:1',
//        'news/read/:id'          => '/news/:1',
//        'article/detail/id/:id' => 'article/detail/:id_html',
//        'article/index/cat_(\d+)\.html' => 'article/index?catid=:1',
//        '/article\/detail\/(\d+)\.html/' => 'article/detail?id=:1',
//         '/^blog\/(\d+)$/'        => 'Blog/read?id=:1',
//        '/^blog\/(\d+)\/(\d+)$/' => 'Blog/achive?year=:1&month=:2',
//        '/^blog\/(\d+)_(\d+)$/'  => 'blog.php?id=:1&page=:2',
    ),
);
?>