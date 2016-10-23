<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /*创建问题*/
    public function add(){
        /*检查用户是否登陆*/
        if(!user_ins()->is_logged_in())
            return err('lonin required!');
        /*检查是否存在标题*/
        if(!rq('title'))
            return err('required title');
        $this->title = rq('title');
        $this->user_id=session('user_id');
        if (rq('desc'))
            $this->desc=rq('desc');//如果存在描述就添加描述
        return $this->save()?
            suc(['id'=>$this->id]) :
            err('db insert failed!');
    }
    /*更新问题*/
    public function change(){
        /*检查用户是否登陆*/
        if(!user_ins()->is_logged_in())
            return err('lonin required!');
        /*检查传参中是否有id*/
        if(!rq('id'))
            return err('id is required!');
        /*获取制定id的model*/
        $question = $this->find(rq('id'));
        if (!$question)
            return err('question not exists');
        if ($question->user_id != session('user_id'))
            return err('permission dnied!');
        if (rq('title'))
            $question->title=rq('title');
        if (rq('desc'))
            $question->desc=rq('desc');
        /*保存数据*/
        return $question->save()?
            suc():
            err('db update failed!');
    }

    public function read_by_user_id($user_id){
        $user=user_ins()->find($user_id);
        if (!$user)return err('user not exists');
        $r=$this->where('user_id',$user_id)->get()->keyBy('id');
        return suc($r->toArray());
    }

    /*查看问题*/
    public function read(){
        /*请求参数是否有id，如果有id直接返回id所在的行*/
        if(rq('id')){
            $r=$this
                ->with('answers_with_user_info')
                ->find(rq('id'));
            return suc(['data'=>$r]);
        }

        if (rq('user_id')){
            $user_id=rq('user_id')=='self'?
                session('user_id'):
                rq('user_id');
            return $this->read_by_user_id($user_id);
        }
        /*skip条件用于分页*/
        list($limit,$skip)=paginate(rq('page'),rq('limit'));
        /*构建Query并返回collection数据*/
        $r = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id','title','desc','user_id','created_at','updated_at'])
            ->keyBy('id');

        return suc(['data'=>$r]);

    }
    /*删除问题*/
    public function remove(){
        if(!user_ins()->is_logged_in())
            return err('lonin required!');
        /*检查传参是否有id*/
        if(!rq('id'))
            return err('id is required!');
        /*获取传参id所对应的model*/
        $question = $this->find(rq('id'));
        if (!$question)
            return err('question not exists!');
        /*检查当钱用户是否有权限*/
        if ( session('user_id')!= $question->user_id)
            return err('permission dnied!');
        /*删除问题*/
        return $question->delete()?
            suc():
            err('db delete failed!');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function answers(){
        return $this->hasMany('App\Answer');
    }
    public function answers_with_user_info(){
        return $this->answers()->with('user')->with('users');
    }
}
