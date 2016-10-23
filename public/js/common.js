;(function(){
	'use strict';
	angular.module('common',[])
	.controller('HomeController', [
		'$scope',
		'TimelineService',
		'AnswerService',
		function($scope,TimelineService,AnswerService){
		var $win;
		$scope.Timeline=TimelineService;
		TimelineService.reset_state();
		TimelineService.get();
		$win=$(window);

		$win.on('scroll',  function() {
			if ($win.scrollTop()-($(document).height()-$win.height())>-30){
				TimelineService.get();
			}
		});
		/*监控回答数据的变化 如果回答数据有变化 同时其他模块中的回答数据*/
		$scope.$watch(function(){
			return AnswerService.data;
		},function(new_data,old_data){
			var timeline_data=TimelineService.data;
			for(var k in new_data){
				/*更新时间线中的数据*/
				for (var i = 0; i < timeline_data.length; i++) {
					if (k==timeline_data[i].id) {
						timeline_data[i]=new_data[k];
					}
				}
			}
			TimelineService.data=AnswerService.count_vote(TimelineService.data);

		},true);
	}])
	.service('TimelineService', ['$http','AnswerService',function($http,AnswerService){
		var me=this;
		me.data=[];
		me.current_page=1;
		me.no_more_data = false;
		me.get=function(conf){
			if (me.pending|| me.no_more_data) {
				return;
			}
			me.pending = true;
			conf=conf||{page:me.current_page};

			$http.post('api/timeline', conf)
			.then(function(req){
				if (req.data.status) {
					if (req.data.data.length) {
						me.data=me.data.concat(req.data.data);
						me.data=AnswerService.count_vote(me.data);
						me.current_page++;						
					}else{
						me.no_more_data=true;
					}

				}else{
					console.error('network error');
				}
			}, function(err){
				console.error('network error');
			})
			.finally(function(){
				me.pending = false;
			})
		}
		me.vote=function(conf){
          /*调用核心投票功能*/
          var $r = AnswerService.vote(conf)
          if ($r)
            $r.then(function (r) {
              /*如果投票成功 就更新AnswerService中的数据*/
              if (r)
                AnswerService.update_data(conf.id);
            })
		}
		me.reset_state=function(){
			me.data=[];
			me.current_page=1;
			me.no_more_data=0;			
		}
	}])	

})();
