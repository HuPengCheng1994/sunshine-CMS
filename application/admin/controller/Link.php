<?php

namespace app\admin\controller;

use app\admin\model\Link as LinkModel;

class Link extends Common
{
    protected $model = null;

    public function initialize()
    {
        parent::initialize(); //继承父类初始化方法
        $this->model = new LinkModel;
    }

    /**
     * 图片友情链接
     * @return [type] [description]
     */
    public function image()
    {
        $link = $this->model->where('type', 2)->paginate(10);
        return $this->fetch('image', ['link' => $link]);
    }

    /**
     * 文字友情链接
     * @return [type] [description]
     */
    public function text()
    {
        $link = $this->model->where('type', 1)->paginate(10);
        return $this->fetch('text', ['link' => $link]);
    }

    /**
     * 友情链接添加页面
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function create($type)
    {
        return $this->fetch('create', ['type' => $type]);
    }

    /**
     * 友情链接添加操作
     * @return [type] [description]
     */
    public function save()
    {
        $data     = input();
        $validate = validate('Link');
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }
        $result = $this->model->save($data);
        if($result){
            $this->log->saveLog(true,$this->a,$this->mca,$this->c,$this->model->id,1);
            $this->success('添加成功');
        } else {
            $this->log->saveLog(false,$this->a,$this->mca,$this->c,0,3);
            $this->error('添加失败');
        }
    }

    /**
     * 友情链接编辑页面
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $link = $this->model->where('id', $id)->find();
        return $this->fetch('edit', ['link' => $link, 'type' => $link['type']]);
    }

    /**
     * 友情链接编辑操作
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update($id)
    {
        if (Request()->isPost() && Request()->isAjax()) {
            $update   = input();
            $validate = validate('Link');
            if (!$validate->check($update)) {
                $this->error($validate->getError());
            }
            $result = $this->model->save($update, ['id' => $id]);
            if($result){
                $this->log->saveLog(true,$this->a,$this->mca,$this->c,$id,1);
                $this->success('编辑成功');
            } else {
                $this->log->saveLog(false,$this->a,$this->mca,$this->c,$id,1);
                $this->error('编辑失败');
            }
        }
    }

    /**
     * 友情链接删除
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if (Request()->isAjax() && Request()->isGet()) {
            $link   = $this->model->get($id);
            $link['pic'] ? @unlink('./'.$link['pic']) : '';
            $result = $link->delete();
            if($result) {
                $this->log->saveLog(true,$this->a,$this->mca,$this->c,$link['name'],3);
                $this->success('删除成功');
            } else {
                $this->log->saveLog(false,$this->a,$this->mca,$this->c,$link['name'],3);
                $this->error('删除失败');
            }
        }
    }

}
