angular.module('usinApp')

	//NOTES QUERY
	.factory('EcuiNotesQuery', function(options, $http){
		return {
			stickNote : function(noteId){
				var url = options.ajaxURL,
					data = {'action': 'ecui_stick_note', 'note_id':noteId, 'nonce':options.ecui_nonce};

				return $http({
					method : 'get',
					url : url,
					params : data
				});
			},
			unstickNote : function(noteId){
				var url = options.ajaxURL,
					data = {'action': 'ecui_unstick_note', 'note_id':noteId, 'nonce':options.ecui_nonce};

				return $http({
					method : 'get',
					url : url,
					params : data
				});
			}
		};
	})

	//STICKY NOTES CONTROLLER
	.controller('ecuiStickyNotesCtrl', function($scope, options, EcuiNotesQuery){
		
		var toggleState = function(callback){
			if(!$scope.noteLoading){
				$scope.startNoteLoading();
				callback.call(this, $scope.note.id)
				.then(
					function(res){
						var data = res.data;
						$scope.stopNoteLoading();
						if(data.success && data.notes){
							$scope.updateNotes(data.notes);
						}else{
							$scope.doOnError(data);
						}
					}, 
					$scope.doOnError);
			}
		};
		
		$scope.stick = function(){
			toggleState(EcuiNotesQuery.stickNote);
		};
		
		$scope.unstick = function(){
			toggleState(EcuiNotesQuery.unstickNote);
		};
	
	})
	
	//GROUP ICONS FILTER
	.filter('groupTagHtml', ['$sce', 'options', function($sce, options){
		var cache = {};
		return function(groupId){
			if(cache[groupId]){
				return cache[groupId];
			}
			var filtered = options.groups.filter(function(group){
				return group.key == groupId;
			});
			
			if(filtered && filtered.length){
				var group = filtered[0],
					html;
					
				if(group.icon){
					html = $sce.trustAsHtml('<span class="usin-group-tag ecui-icon-tag" title="'+group.val+'" style="color:#'+group.color+';"><i class="fa '+group.icon+'"></i></span>');
				}else{
					html = $sce.trustAsHtml('<span class="usin-group-tag" title="'+group.val+'" style="background-color:#'+group.color+';">'+group.val+'</span>');
				}
				cache[groupId] = html;
				return html;
			}
			return '';
		};
	}]);