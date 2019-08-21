<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用调试模式
    'app_debug'              => false,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'json',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => 'htmlspecialchars',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'store',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => '',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => false,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '\\app\\common\\exception\\ExceptionHandler',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

     
    'cache' =>  [
        // 使用复合缓存类型
        'type'  =>  'complex',
        // 默认使用的缓存
        'default'   =>  [
            // 驱动方式
            'type'   => 'File',
            // 缓存保存目录
            'path'   => CACHE_PATH,
        ],
        // 文件缓存
        'file'   =>  [
            // 驱动方式
            'type'   => 'file',
            // 设置不同的缓存保存目录
            'path'   => CACHE_PATH ,
        ],  
        // redis缓存
        'redis'   =>  [
            // 驱动方式
            'type'   => 'redis',
            // 服务器地址
            'host'       => '127.0.0.1',
            'port'      => 6379
        ],     
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => '',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],

    'TAOKE_ZONEID'             =>'106395750448',

    //微信开发平台配置
    'WX_APPID'                 => 'XXXXXXXXXXXX',
    'WX_SECRET'                => '1044572356963f96ca075d1d646bc43e',

    //阿里短信服务
    'SMS_ACCESSKEYID'           => 'XXXXXXXXXXXX',
    'SMS_SECRET'                => 'XXXXXXXXXXX',


    //淘宝客联盟APP秘钥
    'LM_APPSECRET'             =>'0b46be258f57276a8fbebc79324796b8',   
    'LM_APPKEY'                =>'27748514',     

    //淘宝联盟网站秘钥
    'WEB_APPSECRET'             =>'XXXXXXXXXXXX', 
    'WEB_APPKEY'                 =>'25375286',

    //百川秘钥
    'BC_APPSECRET'             =>'0ef71c19efe6bfd29743f9a2863ca58d', 
    'BC_APPKEY'                 =>'27794009',    

    //邀请码 会员 和渠道
    'SID_CODE'                  =>'XXXX',   //会员
    'RID_CODE'                  =>'XXXX',   //渠道

    //大淘客联盟APPKEY
    'DTK_APPKEY'                => 'b7d04eafc6', 
    //大淘客新平台应用使用
    'DTK_APPKEY2'                => 'XXXXXX', 
    'DTK_SECRET'                => '2f84691dae08b8ee1d580f1b2699fbee', 

    //轻淘客API key
    'QTK_APPKEY'                 => 'Do9oyAo8',      
    //好单库KEY
    'HDK_APPKEY'                =>'HUBAOLIN',
    //折淘客
    'ZTK_APPKEY'                =>'e3e1cec00e704bda8f188d3990bc6321',

    //18助手秘钥
    'ZHUSHOU_18KEY'             =>'XXXXXXXXXXXXXXXXX',

    //JD联盟key
    'JD_APPKEYT'                  =>'XXXXXXXXXXXXXXXXX',
    'JD_SECRE'                    =>'34093d0438c64d198ba1a8102e97bbd7',
    //JD的回调地址
    'JD_CALL'                   =>'http://app.52juanmi.com/index.php/store/setting/authorjd',

    //喵有券http://openapi.weimohe.cn
     'MAYI_KEY'                  =>'02378dbc-8bdb-3ba4-9875-27b2e0c9274e',
     //京东联盟的ID
     'JD_ID'                     =>'1001257871',

    //拼多多KEY
    'PDD_CLIENT'                =>'fc295c2850e14ff5a202567343bd27fe',
    'PDD_APPSECRET'             =>'bf8b5fd91dd5f7cd760a310a75b79863436f26e4',
    'PDD_ZONEID'                =>'8176208_48438150',

    //下载页面地址
    'DOWN_APP_URL'              =>'http://www.pgyer.com/JuanMI',
    //系统域名
    'SYSTEM_DOMAIN'             =>'http://app.52juanmi.com/',

    //公众号的APPID 秘钥
    'WX_GZ_APPID'                 => 'XXXXXXXXXX',
    'WX_GZ_SECRET'                => '3aed8c23f8ca2ae36e9c03eda6a95f9c',

    //百度短网址https://dwz.cn/
    'BAIDU_TOKEN'                 => 'XXXXXXXXXXXXXXX',

    //腾讯信鸽推送android 不开启  为空字符串
    'XINGGE_ACCID'                 =>'XXXXXXXXXXX',
    'XINGGE_ACCESSKEY'            =>'909e7d03cc98c159f27b753e9d8cb202',

    //返利比例配置信息
    //rate: 自购/分享  ratejd=京东  ratepdd=拼多多
    //rate1: 一级返利
    //rate2：二级返利
    'fanli_config'              =>[
                                    ['level'=>0,'levelname'=>'普通会员','rate'=>0.50,'rate1'=>0.06,'rate2'=>0.03,'ratejd'=>0.44,'ratepdd'=>0.44],
                                    ['level'=>1,'levelname'=>'超级会员','rate'=>0.7,'rate1'=>0.08,'rate2'=>0.04,'ratejd'=>0.50,'ratepdd'=>0.50],
                                    ['level'=>3,'levelname'=>'超级合伙人','rate'=>0.66,'rate1'=>0.10,'rate2'=>0.05,'ratejd'=>0.56,'ratepdd'=>0.56],
                                   
                                ]
];
