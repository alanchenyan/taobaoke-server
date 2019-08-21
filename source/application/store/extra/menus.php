<?php
/**
 * 后台菜单配置
 *    'home' => [
 *       'name' => '首页',                // 菜单名称
 *       'icon' => 'icon-home',          // 图标 (class)
 *       'index' => 'index/index',         // 链接
 *     ],
 */
return [
    'index' => [
        'name' => '首页',
        'icon' => 'icon-home',
        'index' => 'index/index',
    ],
    'uisetting' => [
        'name' => '主页装修',
        'icon' => 'icon-goods',
        'index' => 'uisetting/index',
        'submenu' => [
            [
               'name' => '首页活动',
                'index' => 'uisetting/index',
            ],
            [
                'name' => '导航菜单',
                'index' => 'uisetting/navindex',
            ]
        ],
    ],
    'caiwu' => [
        'name' => '财务管理',
        'icon' => 'am-icon-twitter',
        'index' => 'caiwu/index',
        'submenu' => [
            [
               'name' => '提现管理',
                'index' => 'caiwu/index',
            ],
            [
                'name' => '货币日志',
                'index' => 'caiwu/index2',
            ]
        ],
    ],
    'order' => [
        'name' => '订单管理',
        'icon' => 'icon-order',
        'index' => 'order/alllist',
        'submenu' => [
            [
                'name' => '淘宝订单',
                'index' => 'order/list0',
            ],
            [
                'name' => '京东订单',
                'index' => 'order/list1',
            ],
            [
                'name' => '拼多多订单',
                'index' => 'order/list2',
            ],
            [
                'name' => '返利数据',
                'index' => 'order/fanlist',

            ],
            [
                'name' => '结算工资',
                'index' => 'order/complete',
            ],
        ],
    ],
    'user' => [
        'name' => '用户管理',
        'icon' => 'icon-user',
        'index' => 'user/index',
    ],
   'faquanc' => [
        'name' => '微商发圈',
        'icon' => 'icon-marketing',
        'index' => 'Faquanc/index2',
        'submenu' => [
            [
               'name' => '宣传素材',
                'index' => 'store/Faquanc/index2',
            ],
            [
                'name' => '精品推荐',
                'index' => 'store/Faquanc/index',
            ]
        ],
    ],
  
    'article' => [
        'name' => '文章管理',
        'icon' => 'icon-wxapp',
        'index' => 'article/index',
        'submenu' => [
            [
               'name' => '文章列表',
                'index' => 'article/index',
            ],
            [
                'name' => '增加文章',
                'index' => 'article/add',
            ],
            [
                'name' => '反馈信息',
                'index' => 'article/index2',
            ]
        ],
    ],
    'brandc' => [
        'name' => '分类管理',
        'icon' => 'am-icon-github',
        'index' => 'brandc/index',
        'submenu' => [
            [
               'name' => '品牌分类',
               'active' => true,
                'index' => 'Brandc/index',
            ],
            [
                'name' => '增加品牌',
                'index' => 'Brandc/add',
            ]
        ],
    ],
    
    'setting' => [
        'name' => '系统设置',
        'icon' => 'icon-setting',
        'index' => 'setting/store',
        'submenu' => [
            [
                'name' => '商城设置',
                'index' => 'setting/store',
            ],
            [
                'name' => '京东授权',
                'index' => 'setting/trade',
            ],
            [
                'name' => '配送设置',
                'index' => 'setting.delivery/index',
                'uris' => [
                    'setting.delivery/index',
                    'setting.delivery/add',
                    'setting.delivery/edit',
                ],
            ],
            [
                'name' => '上传设置',
                'index' => 'setting/storage',
            ],
            [
                'name' => '其他',
                'active' => true,
                'submenu' => [
                    [
                        'name' => '清理缓存',
                        'index' => 'setting.cache/clear'
                    ],
                    [
                        'name' => '环境检测',
                        'index' => 'setting.science/index'
                    ],
                ]
            ]
        ],
    ],
];
