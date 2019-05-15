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

Route::rule('/n$', 'admin/login/index'); // 后台登录访问路由
Route::rule('/z$', 'admin/register/create'); // 后台注册访问路由
Route::post('/register/save$', 'admin/register/save'); // 后台用户保存访问路由
Route::rule('/t$', 'admin/login/logout'); // 后台退出访问路由
Route::rule('/admin$', 'admin/index/index'); // 后台首页访问路由

Route::rule('/config$', 'admin/config/index'); // 后台网站配置访问路由

Route::get('/user$', 'admin/user/index'); // 后台用户列表访问路由
Route::get('/user/create$', 'admin/user/create'); // 后台用户添加访问路由
Route::post('/user/save$', 'admin/user/save'); // 后台用户保存访问路由
Route::get('/user/delete/:id$', 'admin/user/delete'); // 后台用户删除访问路由
Route::get('/user/edit/:id$', 'admin/user/edit'); // 后台用户修改页面访问路由
Route::post('/user/update/:id$', 'admin/user/update'); // 后台用户修改访问路由


Route::get('/role$', 'admin/role/index'); // 后台角色列表访问路由
Route::get('/role/create$', 'admin/role/create'); // 后台角色添加访问路由
Route::post('/role/save$', 'admin/role/save'); // 后台角色保存访问路由
Route::get('/role/delete/:id$', 'admin/role/delete'); // 后台角色删除访问路由
Route::get('/role/edit/:id$', 'admin/role/edit'); // 后台角色修改页面访问路由
Route::post('/role/update/:id$', 'admin/role/update'); // 后台角色修改保存访问路由
Route::get('/role/config/:id$', 'admin/role/config'); // 后台角色权限配置页面访问路由
Route::post('/role/configSave/:id$', 'admin/role/configSave'); // 后台角色权限配置保存访问路由

Route::get('/auth$', 'admin/auth/index'); // 后台权限列表访问路由
Route::get('/auth/create$', 'admin/auth/create'); // 后台权限添加访问路由
Route::post('/auth/save$', 'admin/auth/save'); // 后台权限保存访问路由
Route::get('/auth/delete/:id$', 'admin/auth/delete'); // 后台权限保存访问路由
Route::get('/auth/edit/:id$', 'admin/auth/edit'); // 后台权限修改访问路由
Route::post('/auth/update/:id$', 'admin/auth/update'); // 后台权限修改保存访问路由

Route::get('/cate$', 'admin/cate/index'); // 后台用户列表访问路由
Route::get('/cate/create$', 'admin/cate/create'); // 后台用户添加访问路由
Route::post('/cate/save$', 'admin/cate/save'); // 后台用户保存访问路由
Route::get('/cate/delete/:id$', 'admin/cate/delete'); // 后台用户删除访问路由
Route::get('/cate/edit/:id$', 'admin/cate/edit'); // 后台用户修改页面访问路由
Route::post('/cate/update/:id$', 'admin/cate/update'); // 后台角色修改保存访问路由

Route::get('/log$', 'admin/log/index'); // 后台日志列表访问路由

Route::get('/link$', 'admin/link/index'); // 后台友情链接列表访问路由
Route::get('/image$', 'admin/link/image'); // 后台友情链接图片列表访问路由
Route::get('/text$', 'admin/link/text'); // 后台友情链接文字列表访问路由
Route::get('/create/:type$', 'admin/link/create'); // 后台友情链接添加访问路由
Route::post('/save$', 'admin/link/save'); // 后台友情链接保存访问路由
Route::get('/edit/:id$', 'admin/link/edit'); // 后台友情链接修改访问路由
Route::post('/update/:id$', 'admin/link/update'); // 后台友情链接修改保存访问路由
Route::get('/delete/:id$', 'admin/link/delete'); //  后台友情链接删除访问路由

Route::get('/article/index/:cid$', 'admin/article/index'); // 文章访问路由
Route::get('/article/create/:cid$', 'admin/article/create'); // 文章添加访问路由
Route::post('/article/save/:cid$', 'admin/article/save'); //文章添加访问路由
Route::get('/article/delete/:id$', 'admin/article/delete'); // 后台文章删除访问路由
Route::post('/article/update/:id$', 'admin/article/update'); // 后台文章修改保存访问路由
Route::get('/article/edit/:id$', 'admin/article/edit'); // 后台文章修改访问路由

Route::get('/', 'admin/login/index'); // 前台首页路由

Route::post('/upload$', 'admin/file/upload'); // 图片添加
Route::post('/delete$', 'admin/file/delete'); // 图片删除

