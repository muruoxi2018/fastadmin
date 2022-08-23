<?php

return [
    'autoload' => false,
    'hooks' => [
        'upgrade' => [
            'cms',
            'myactivity',
        ],
        'app_init' => [
            'cms',
            'epay',
            'xunsearch',
        ],
        'view_filter' => [
            'cms',
            'third',
        ],
        'user_sidenav_after' => [
            'cms',
            'myactivity',
            'vip',
        ],
        'xunsearch_config_init' => [
            'cms',
        ],
        'xunsearch_index_reset' => [
            'cms',
        ],
        'config_init' => [
            'summernote',
            'third',
        ],
        'user_delete_successed' => [
            'third',
        ],
        'user_logout_successed' => [
            'third',
        ],
        'module_init' => [
            'third',
        ],
        'action_begin' => [
            'third',
        ],
    ],
    'route' => [
        '/cms/$' => 'cms/index/index',
        '/cms/t/[:diyname]$' => 'cms/tag/index',
        '/cms/p/[:diyname]$' => 'cms/page/index',
        '/cms/s$' => 'cms/search/index',
        '/cms/d/[:diyname]$' => 'cms/diyform/index',
        '/cms/d/[:diyname]/post' => 'cms/diyform/post',
        '/cms/d/[:diyname]/[:id]' => 'cms/diyform/show',
        '/cms/special/[:diyname]' => 'cms/special/index',
        '/u/[:id]' => 'cms/user/index',
        '/cms/[:diyname]$' => 'cms/channel/index',
        '/cms/[:catename]/[:id]$' => 'cms/archives/index',
        '/example$' => 'example/index/index',
        '/example/d/[:name]' => 'example/demo/index',
        '/example/d1/[:name]' => 'example/demo/demo1',
        '/example/d2/[:name]' => 'example/demo/demo2',
        '/third$' => 'third/index/index',
        '/third/connect/[:platform]' => 'third/index/connect',
        '/third/callback/[:platform]' => 'third/index/callback',
        '/third/bind/[:platform]' => 'third/index/bind',
        '/third/unbind/[:platform]' => 'third/index/unbind',
        '/xunsearch$' => 'xunsearch/index/index',
        '/xunsearch/[:name]' => 'xunsearch/index/search',
    ],
    'priority' => [],
    'domain' => '',
];
