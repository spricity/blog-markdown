<?php
date_default_timezone_set('Asia/Shanghai');
include_once('lib/autoload.php');
$session = new Session();
$ci = new Controller;

$action = _g('a', 'markdown');
switch($action){
    case 'markdown':
        go_markdown();
    break;
    case 'admin';
        go_admin();
    break;
    case 'login':
        go_login();
    break;
    case 'edit':
        go_edit();
    break;
    case 'preview':
        ajax_markdown();
    break;
    case 'tree':
        go_tree();
    break;
    case 'edit_tree':
        ajax_tree();
        break;
}

function go_markdown(){
    global $session;
    global $ci;
    $map = directory_map(DIR_DOCS . DS . '.config', 1);
    $url = _g('c');
    $child_url = _g('f');
    $render = array();
    foreach($map as $file){
        $data[] = $item = load_package('.config/' . $file, $child_url);
        $sort[] = $item['sort'];
        if($item['url'] === $url){
            // dbg($item);
            $nav_active = $item['category'];
            $menu = $item['package']['menu'];
            $category = $item['package']['category'];
            $path = $item['path'];
            $act = $item['url'];
        }
    }
    array_multisort($sort ,SORT_ASC ,SORT_REGULAR ,$data);//按点击排序
    if(!isset($menu)){
        $nav_active = $data[0]['package']['category'];
        $menu = $data[0]['package']['menu'];
        $category = $data[0]['package']['category'];
        $path = $data[0]['path'];
        $act = $data[0]['url'];
    }
    $navs = $data;
    include_once('view/home.php');
}

function go_edit(){
    global $session;
    global $ci;
    $map = directory_map(DIR_DOCS . DS . '.config', 1);
    $url = _g('c');
    $child_url = _g('f');
    $render = array();
    foreach($map as $file){
        $data[] = $item = load_package('.config/' . $file, $child_url);
        $sort[] = $item['sort'];
        if($item['url'] === $url){
            $nav_active = $item['category'];
            $menu = $item['package']['menu'];
            $category = $item['package']['category'];
            $path = $item['path'];
            $act = $item['url'];
            $current_select = $item['current_select'];
        }
    }
    array_multisort($sort ,SORT_ASC ,SORT_REGULAR ,$data);//按点击排序
    if(!isset($menu)){
        $nav_active = $data[0]['package']['category'];
        $menu = $data[0]['package']['menu'];
        $category = $data[0]['package']['category'];
        $path = $data[0]['path'];
        $act = $data[0]['url'];
        $current_select = $data[0];
    }
    $navs = $data;
    // dbg($current_select);
    include_once('view/edit.php');
}

function go_add($navs){
    $nav_active = 'add';
    global $session;
    global $ci;
    if(!$session->get('islogin')){
        redirect(BASEURL . '?a=login');
    }
    $url = _g('c');
    $title = '添加导航';
    $category = _g('category');
    if($url === 'save'){
        $ci->value['name'] = $name = _p('name');
        $ci->value['category'] = $_category = _p('category');
        $ci->value['sort'] = $sort = _p('sort');
        $ci->value['url'] = $url = _p('url');
        if(!$name){
            $ci->error['name'] = '导航名称不能为空';
        }
        if(!$_category){
            $ci->error['category'] = '导航标识不能为空';
        }
        if(!$sort){
            $ci->error['sort'] = '排序不能为空';
        }elseif(!preg_match('/^\d+$/', $sort, $match)){
            $ci->error['sort'] = '请输入数字，数字越小，越靠前';
        }

        if($category){
            foreach($ci->navs as $m){
                if($category && $m['category'] === $category){
                    $_updateItem = $m;
                }
                if($m['category'] === $_category && $m['category'] !== $category){
                    $ci->error['category'] = '标识符已经存在';
                }
            }
            $updateItem['name'] = $name;
            $updateItem['category'] = $_category;
            $updateItem['sort'] = $sort;
            $updateItem['url'] = $url;
            // dbg($_updateItem);
            $menu = array();
            foreach($_updateItem['package']['menu'] as $key=>$up){
                // dbg($up);

                $_menu = array(
                    "sort" => $up['sort'],
                    "name" => $up['name'],
                    "path" => explode(',', $up['path']),
                    "url" => $up['url'],
                );
                $child = array();
                if(isset($up['child']) && count($up['child'] > 0)){
                    foreach($up['child'] as $__child){
                        $_child = array(
                            "name" => $__child['name'],
                            "path" => explode(',', $up['path']),
                            "url" => $__child['url'],
                        );
                        $child[] = $_child;
                    }
                }
                $_menu['child'] = $child;
                $menu[] = $_menu;
            }
            $updateItem['menu'] = $menu;
            $path = DIR_DOCS . DS . $category;
            delete_files(DIR_DOCS . DS . '.config' . DS . $_category . '.json');
            write_file(DIR_DOCS . DS . '.config' . DS . $category . '.json', json_encode($updateItem));
            redirect(BASEURL . '?a=admin');
        }else{
            foreach($ci->navs as $m){
                if($m['category'] === $_category){
                    $ci->error['category'] = '标识符已经存在';
                }
            }
        }


        if(!isset($ci->error)){
            $json = array(
                'name'=>$name,
                'category'=>$category,
                'sort'=>$sort,
                'url'=>$url,
                'menu'=>array()
            );
            $path = DIR_DOCS . DS . $category;
            write_file(DIR_DOCS . DS . '.config' . DS . $category . '.json', json_encode($json));
        }
    }

    if($url === 'modify'){
        $current_nav = array();
        foreach($ci->navs as $nav){
            if($nav['category'] === $category){
                $current_nav = $nav;
            }
        }
        $ci->value['name'] = $current_nav['name'];
        $ci->value['category'] = $current_nav['category'];
        $ci->value['sort'] = $current_nav['sort'];
        $ci->value['url'] = $current_nav['url'];
        $title = '【编辑】' . $current_nav['name'];
    }


    include_once('view/add.php');
}

function go_add_tree1(){
    global $session;
    global $ci;
    $t = _g('t');
    if(!$t){
        show_404();
    }
    $current_data = array();
    foreach($ci->navs as $nav){
        if($nav['category'] === $t){
            $current_data = $nav;
        }
    }
    $nav_active = $t;

    // 保存一级目录
    if(_g('do') === 'save'){
        $ci->value['name'] = $name = _p('name');
        $ci->value['sort'] = $sort = _p('sort');
        $ci->value['url'] = $url = _p('url');
        if(!$name){
            $ci->error['name'] = '一级目录名称不能为空';
        }
        if(!$sort){
            $ci->error['sort'] = '排序不能为空';
        }elseif(!preg_match('/^\d+$/', $sort, $match)){
            $ci->error['sort'] = '请输入数字，数字越小，越靠前';
        }

        $push_data = array(
            'sort' => $sort,
            'name' => $name,
            'url' => $url,
            'path' => '',
            'child' => array()
        );
        $current_data['package']['menu'][] = $push_data;
        $updateItem = __update_config($current_data['package']['menu'], $current_data['name'], $current_data['category'], $current_data['sort'], $current_data['url']);
        write_file(DIR_DOCS . DS . '.config' . DS . $t . '.json', json_encode($updateItem));
        redirect(BASEURL . '?c=' . $current_data['url']);
    }
    $title = $current_data['name'] . ' > <span class="strong">添加一级目录</span>';
    include_once('view/admin/add_t1.php');
}

function go_add_tree2(){
    global $session;
    global $ci;
    $t = _g('t');
    $t1 = _g('t1');
    if(!$t || !$t1){
        show_404();
    }
    $current_data = array();
    foreach($ci->navs as $nav){
        if($nav['category'] === $t){
            $current_data = $nav;
        }
    }
    $nav_active = $t;

    // 保存一级目录
    if(_g('do') === 'save'){
        $ci->value['name'] = $name = _p('name');
        $ci->value['sort'] = $sort = _p('sort');
        $ci->value['url'] = $url = _p('url');
        if(!$name){
            $ci->error['name'] = '一级目录名称不能为空';
        }
        if(!$sort){
            $ci->error['sort'] = '排序不能为空';
        }elseif(!preg_match('/^\d+$/', $sort, $match)){
            $ci->error['sort'] = '请输入数字，数字越小，越靠前';
        }

        $push_data = array(
            'sort' => $sort,
            'name' => $name,
            'url' => $url,
            'path' => '',
            'child' => array()
        );
        $current_data['package']['menu'][] = $push_data;
        $updateItem = __update_config($current_data['package']['menu'], $current_data['name'], $current_data['category'], $current_data['sort'], $current_data['url']);
        write_file(DIR_DOCS . DS . '.config' . DS . $t . '.json', json_encode($updateItem));
        redirect(BASEURL . '?c=' . $current_data['url']);
    }
    $title = $current_data['name'] . ' > ' . $current_data['current_select']->name .' > <span class="strong">添加二级目录</span>';
    include_once('view/admin/add_t1.php');
}

function go_admin(){
    global $session;
    global $ci;
    if(!$session->get('islogin')){
        redirect(BASEURL . '?a=login');
    }
    $navs = array(
        array(
            'url'=>'',
            'name' => '首页',
            'category' => 'home'
        ),
        array(
            'url'=>'list&a=admin',
            'name' => '目录结构',
            'category' => 'list'
        ),
        array(
            'url'=>'add&a=admin',
            'name' => '添加目录',
            'category' => 'add'
        ),
        array(
            'url'=>'pwd&a=admin',
            'name' => '密码管理',
            'category' => 'password'
        )
    );
    $url = _g('c');
    switch($url){
        case 'pwd';
            $nav_active = 'password';
            include_once('view/admin/pwd.php');
        break;
        case 'repwd';
            $nav_active = 'password';
            include_once('view/admin/pwd.php');
        break;
        case 'add';
            $nav_active = 'add';
            $act = _g('act');
            switch($act){
                // case 't1': go_add_tree1(); break;
                // case 't2': go_add_tree2(); break;
                default: go_add($navs);
            }
        break;
        default;
            $nav_active = 'list';
            include_once('view/admin/list.php');
            break;
    }
    // include_once('view/admin.php');
}

function go_login(){
    global $ci;
    global $session;
    $navs = array(
        array(
            'url'=>'login&a=login',
            'name' => '登录',
            'category' => 'login'
        )
    );
    $nav_active = 'login';
    $url = _g('c');
    if($url === 'check'){
        $ci->value['email'] = $email = _p('email');
        $ci->value['password'] = $password = _p('password');
        if(!$email){
            $ci->error['email'] = '邮箱不能为空';
        }
        if(!$password){
            $ci->error['password'] = '密码不能为空';
        }

        $package = read_file(BASEDIR . DS . 'lib' . DS . 'lock.password');
        $package = json_decode($package);
        if($package->email === $email && $password === $package->pwd){
            $session->set('islogin' , true);
            redirect(BASEURL);
        }else{
            $ci->error['password'] = '用户名和密码不对';
        }
    }
    include_once('view/login.php');
}
function ajax_markdown(){
    global $session;
    global $ci;
    $content = htmlspecialchars_decode(_p('content'));
    $path = _p('path');
    $title = _p('title');

    //save content
    $realpath = DIR_DOCS . DS . $path;
    write_file($realpath, $content, FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);

    // save title

    // reload preview data
    include_once('view/preview_markdown.php');
}

function go_tree(){
    global $session;
    global $ci;
    $category = _g('category');
    include_once('view/admin/tree.php');
}
function ajax_tree(){
    $category = _g('category');
    $action = _g('c');
    global $session;
    global $ci;
    $current_data = array();
    foreach($ci->navs as $nav){
        if($nav['category'] === $category){
            $current_data = $nav;
        }
    }
    if($action == 'save'){
        $menu = htmlspecialchars_decode(_p('content'));
        $menu = json_decode($menu, true);
        $updateItem = __update_config($menu['tree'], $current_data['name'], $current_data['category'], $current_data['sort'], $current_data['url']);
        write_file(DIR_DOCS . DS . '.config' . DS . $category . '.json', json_encode($updateItem));
        json(200, $menu);
    }else{
        json(200, $current_data['package']['menu']);
    }
}

function __update_config($__menu, $name, $_category, $sort, $url){
    $updateItem['name'] = $name;
    $updateItem['category'] = $_category;
    $updateItem['sort'] = $sort;
    $updateItem['url'] = $url;
    $menu = array();
    foreach($__menu as $key=>$up){

        // dbg($path);
        if(!$up['path']){
            $up['path'] = $_category . '/'. date('Y-m-d-H-i-s__', time()) . md5($up['name'] . $up['url']) . '.md';
        }
        if(!$up['url']){
            $up['url'] = $_category . '-item-' . $key . time();
        }
        $_menu = array(
            "sort" => $up['sort'],
            "name" => $up['name'],
            "path" => explode(',', $up['path']),
            "url" => $up['url'],
        );
        $child = array();
        if(isset($up['child']) && count($up['child'] > 0)){
            foreach($up['child'] as $child_key => $__child){
                if(!$__child['path']){
                    $__child['path'] = $_category . '/'. date('Y-m-d-H-i-s__', time()) . md5($__child['name'] . $__child['url']) . '.md';
                }
                if(!$__child['url']){
                    $__child['url'] = $_category . '-item-child-' . $child_key . time();
                }
                $_child = array(
                    "name" => $__child['name'],
                    "path" => explode(',', $__child['path']),
                    "url" => $__child['url'],
                );
                $child[] = $_child;
            }
        }
        $_menu['child'] = $child;
        $menu[] = $_menu;
    }
    $updateItem['menu'] = $menu;
    return $updateItem;
}
?>
