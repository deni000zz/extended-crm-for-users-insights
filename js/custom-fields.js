angular.module('usinCustomFieldsApp')
	.controller('ecuiKeysSelectCtrl', function($scope, options){
		$scope.keyOptions = options.ecuiKeyOptions;
	});