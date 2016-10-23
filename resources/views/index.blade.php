<!doctype html>
<html lang="zh" ng-controller="BaseController" ng-app="xiaofengo" user_id="{{session('user_id')}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet/css" href="/node_modules/normalize-css/normalize.css">
    <link rel="stylesheet" href="/css/base.css">
    <script type="text/javascript" src="/node_modules/jquery/dist/jquery.js"></script>
    <script type="text/javascript" src="/node_modules/angular/angular.js"></script>
    <script type="text/javascript" src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script type="text/javascript" src="/js/base.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/user.js"></script>
    <script type="text/javascript" src="/js/question.js"></script>
    <script type="text/javascript" src="/js/answer.js"></script>
    <title>晓凤</title>
</head>
<body>
<div class="navbar clearfix">
<div class="container">
    <div class="fl">
        <div ui-sref="home" class="navbar-item brand">晓乎</div>   
        <form  ng-controller="QuestionController" ng-submit="Question.go_add_question()"id="quick_ask">
        <div class="navbar-item">
            <input type="text"ng-model="Question.new_question.title"></input>
            </div>
            <div class="navbar-item">
            <button  type="submit">提问</button>
            </div>
        </form>
    </div>
    <div class="fr">
        <a ui-sref='home' class="navbar-item">首页</a>
        @if (is_logged_in())
        <a ui-sref='user'>{{session('username')}}</a>
        <a ng-click="loginout()"class="navbar-item">登出</a>
        @else
        <a ui-sref='login' class="navbar-item">登陆</a>
        <a ui-sref='singup' class="navbar-item">注册</a>
        
        @endif
    </div>
</div>
</div>
<div class="page">
    <div ui-view></div>
</div>
<script type="text/ng-template" id="comment.tpl">
    <div class="comment-block">
        <div class="hr"></div>
        <div class="comment-item-set">
            <div class="rect"></div>
            <div class="gray tac well" ng-if="!helper.obj_length(data)">暂无评论</div>
            <div ng-if="helper.obj_length(data)"
                 ng-repeat="item in data.data" class="comment-item clearfix">
                <div class="user">[: item.user.username :]:</div>
                <div class="comment-content">[: item.content :]</div>
            </div>
            {{--<div class="comment-item clearfix">--}}
            {{--<div class="user">sdf</div>--}}
            {{--<div class="comment-content">--}}
            {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore dol--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="comment-item clearfix">--}}
            {{--<div class="user">Lee</div>--}}
            {{--<div class="comment-content">--}}
            {{--Lorem--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
        <div class="input-group">
            <form ng-submit="_.add_comment()" class="comment_form">
                <input type="text"
                       ng-model="Answer.new_comment.content"
                       placeholder="说些什么...">
                <button class="primary" type="submit">评论</button>
            </form>
        </div>
    </div>
</script>
</body>
</html>