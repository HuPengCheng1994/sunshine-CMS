<?php

namespace app\admin\controller;
use think\Controller;


class File extends Common
{   

    public function initialize()
    {
        parent::initialize(); //继承父类初始化方法
    }

    /**
     * 图片上传操作
     * @return [json] [上传状态和路径]
     */
    public function upload()
    {

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move('./uploads');
        if ($info) {
            $file = $info->getSaveName();
            $file = "/uploads/" . str_replace("\\",'/',$file);
            $msg = '上传成功';
            return json_encode(['code' => '1', 'msg' => $msg, 'path' => $file]);

        } else {
            // 上传失败获取错误信息
            $msg = $file->getError();
            return json_encode(['code' => '2', 'msg' => $msg]);
        }
    }

    /**
     * 图片删除操作
     * @return [code] [删除状态]
     */
    public function delete()
    {
        $path = input('path');
        $result = unlink('./'.$path);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }
}