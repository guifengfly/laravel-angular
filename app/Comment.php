<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /*添加评论*/
    public function add(){
        /*检查用户是否登陆*/
        if(!user_ins()->is_logged_in())
            return err('lonin required!');
        if (!rq('content'))
            return err('empty content!');
        if (
            (rq('question_id')&&rq('answer_id'))||
            (!rq('question_id')&&!rq('answer_id'))
        )
            return err('question_id and answer_id or required!');
        if (rq('question_id')){
            /*评论问题*/
            $question = question_ins()->find(rq('question_id'));
            /*检查问题是否存在*/
            if (!$question)
                return err('question not exists!');
            $this->question_id=rq('question_id');
        }else{
            /*评论答案*/
            $answer = answer_ins()->find(rq('answer_id'));
            /*检查答案是否存在*/
            if (!$answer)
                return err('answer not exists!');
            $this->answer_id=rq('answer_id');
        }
        /*检查是否在回复评论*/
        if (rq('reply_to')){
            $target = $this->find(rq('reply_to'));
            /*检查目标评论是否存在*/
            if (!$target)
                return err('target comment not exists!');
            /*检查是否在回复自己的评论*/
            if ($target->user_id == session('user_id'))
                return err('cannot reply to yourdelf!');
            $this->reply_to = rq('reply_to');
        }
        $this->content=rq('content');
        $this->user_id=session('user_id');
        /*保存数据*/
        return $this->save()?
            suc(['id'=>$this->id]) :
            err('db insert failed!');
    }


    public function read(){
        if (!rq('question_id')&&!rq('answer_id'))
            return err('question_id or answer_id is required!');
        if (rq('question_id')){
            $question = question_ins()
                ->with('user')
                ->find(rq('question_id'));
            if (!$question)
                return err('question not exists!');
            $data = $this->with('user')->where('question_id',rq('question_id'));
        }else{
            $answer = answer_ins()->with('user')->find(rq('answer_id'));
            if (!$answer)
                return err('answer not exists!');
            $data = $this->with('user')->where('answer_id',rq('answer_id'));
        }
        $data = $data->get()->keyBy('id');
        return suc(['data'=>$data]);
    }
    /*删除评论*/
    public function remove(){
        /*检查用户是否登陆*/
        if(!user_ins()->is_logged_in())
            return err('login required!');
        if (!rq('id'))
            return err('id is required!');
        $comment = $this->find(rq('id'));
        if (!$comment)
            return err('comment not exists!');
        if ($comment->user_id != session('user_id'))
            return err('permission denied!');
        $this->where('reply_to',rq('id'))->delete();
        return $comment->delete()?
            suc():
            err('db delete failed!');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
