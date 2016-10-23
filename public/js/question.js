;(function(){
	'use strict';
	angular.module('question',[])
	.controller('QuestionController', [
		'$scope',
		'QuestionService', 
		function($scope,QuestionService){
			$scope.Question=QuestionService;
		
	}])
	.controller('QuestionAddController', [
		'$scope',
		'QuestionService',
		function($scope,QuestionService){
	        if (!his.id)
	          $state.go('login');
		
	}])
	.controller('QuestionDetailController', [
		'$scope',
		'$stateParams',
		'AnswerService',
		'QuestionService',
		function($scope,$stateParams,AnswerService,QuestionService){
			$scope.Answer=AnswerService;
			QuestionService.read($stateParams);	
			if ($stateParams.answer_id) {
				QuestionService.current_answer_id=$stateParams.answer_id;
			}else{
				QuestionService.current_answer_id=null;
			}

	}])	
	.service('QuestionService', [
		'$state', 
		'$http',
		'AnswerService',
		function($state,$http,AnswerService){
			var me=this;
			me.new_question={};
			me.data={};
			me.update=function(){
				if (!me.current_question.title) {
					return false;
					console.error('title is required');
				}
				return $http.post('/api/question/change',me.current_question)
				.then(function(req){
					if (req.data.status) {}
					me.show_update_form=false;
				})
			}
			me.go_add_question=function(){
				$state.go('question.add')
			}
			me.read=function(params){
				return $http.post('/api/question/read', params)
				.then(function(req){
					if (req.data.status) {
						if (params.id) {

							me.data[params.id]=me.current_question=req.data.data.data;
							me.its_answers=me.current_question.answers_with_user_info;
							me.its_answers=AnswerService.count_vote(me.its_answers);
						}else{
							me.data=angular.merge({},me.data, req.data.data);							
						}
						console.log(me.its_answers);	
						return me.its_answers;

					}
					return false;
					
				}, function(err){

				})
			}
			me.vote=function(conf){
				var $r=AnswerService.vote(conf);
				if ($r) {
					$r.then(function(req){
						if (req) {
							me.update_answer(conf.id);
						}
					}, function(err){

					})		
				}
		
			}
			me.update_answer=function(answer_id){
				$http.post('api/answer/read', {id:answer_id})
				.then(function(req){
					if (req.data.status) {
						for(var i=0;i<me.its_answers.length;i++){
							var answer = me.its_answers[i];
							if (answer.id==answer_id) {
								me.its_answers[i]=req.data.data.data;
								AnswerService.data[answer_id]=req.data.data.data;
							}
						}
						console.log(me.its_answers);
					}
				})
			}
			me.add=function(){
				if (!me.new_question.title) {
					return;
				}
				$http.post('api/question/add', me.new_question)
				.then(function(req){
					if (req.data.status) {
						me.new_question={};
						$state.go('home');
					}
				}, function(err){
				})
			}
	}])	

})();

