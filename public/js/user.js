;(function(){
	'use strict';
	angular.module('user',['answer'])
	.controller('SignupController', [
		'$scope',
		'UserService', 
		function($scope,UserService){
		$scope.User=UserService;
		$scope.$watch(function(){
			return UserService.signup_data;
		},function(n,o){
			if (n.username!=o.username) {
				UserService.username_exists();
			}
		},true);
	}])
	.controller('LoginController', [
		'$scope',
		'UserService', 
		function($scope,UserService){
		$scope.User=UserService;
	}])	
	.controller('UserController', [
		'$scope', 
		'$stateParams',
		'UserService',
		'AnswerService',
		'QuestionService',
		function($scope,$stateParams,UserService,AnswerService,QuestionService){
		$scope.User=UserService;
		UserService.read($stateParams);
		AnswerService.read({user_id:$stateParams.id})
		.then(function(req){
			if (req) {
				UserService.his_answers=req;
			}
		}, function(err){});
		QuestionService.read({user_id:$stateParams.id})
		.then(function(req){
			if (req) {
				UserService.his_questions=req;
			}
		}, function(err){});

	}])	

	.service('UserService', ['$state','$http',function($state,$http){
		var me = this;
		me.signup_data= {};
		me.login_data={};
		me.data={};
		me.read=function(param){
			return $http.post('/api/user/read', param)
			.then(function(req){
				if (req.data.status) {
					me.current_user=req.data.data;
					me.data[param.id]=req.data.data;
					
				}else{
					if (req.data.msg=='login required') {
						$state.go('login');
					}
				}

			}, function(err){

			})
		}
		me.signup = function(){
			$http.post('/api/user/singup',me.signup_data)
			.then(function(req){
				if (req.data.status) {
					me.signup_data={};
					$state.go('login');
				}
			},function(err){

			})
		}
		me.login=function(){
			$http.post('/api/login',me.login_data)
			.then(function(req){
				if (req.data.status==1) {
					location.href='/';
				}else{
					me.login_failed=true;
				}
			},function(err){

			})			
		}
		me.username_exists=function(){
			$http.post('/api/user/exist', 
				{username:me.signup_data.username})
			.then(function(req){
				if (req.data.status&&req.data.data.count) {
					me.signup_username_exists=true;
				}else{
					me.signup_username_exists=false;
				}
			}, function(err){
				console.log(err);
			})
		}
	}])	

})();
