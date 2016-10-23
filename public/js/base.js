;(function(){
	'use strict'
	window.his ={
		id:parseInt($('html').attr('user_id'))
	};
	window.helper={};
	helper.obj_length=function(obj){
		if (obj)
		return Object.keys(obj).length;
	}
	angular.module('xiaofengo', ['ui.router','common','user','question','answer'])
	.config(function(
		$interpolateProvider,
		$stateProvider,
		$urlRouterProvider) {
		$interpolateProvider.startSymbol('[:');
		$interpolateProvider.endSymbol(':]');
		$urlRouterProvider.otherwise('/home');
		$stateProvider
		.state('home',{
			url:'/home',
			templateUrl:'/tpl/page/home/'
		})
		.state('login',{
			url:'/login',
			templateUrl:'/tpl/page/login'
		})
		.state('singup',{
			url:'/singup',
			templateUrl:'/tpl/page/singup'
		})	
		.state('question',{
			abstract:true,
			url:'/question',
			template:'<div ui-view></div>',
			controller:'QuestionController'
		})
		.state('question.detail',{
			url:'/detail/:id?answer_id',
			templateUrl:'tpl/page/question_detail'
		})							
		.state('question.add',{
			url:'/add',
			templateUrl:'tpl/page/question_add'
		})	
		.state('user',{
			url:'/user/:id',
			templateUrl:'tpl/page/user'
		})										
	})
	.controller('BaseController', ['$scope','$http', function($scope,$http){
		$scope.his=his;
        $scope.helper = helper;	
        $scope.loginout=function(){
        	$http.post('/api/logout',{})
        	.then(function(req){
        		if (req.data.status==1) {
        			location.href='/';
        		}

        	})
        }
	}])
	
})();