// Xeditable
angular
	.module('xeditableModule', ['xeditable'])
	.run(function(editableOptions, editableThemes) {
		editableThemes.bs3.inputClass = 'input-sm';
		editableThemes.bs3.buttonsClass = 'btn-sm';
		editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
	})

	.controller('TextSimpleCtrl', function ($scope) {
		$scope.user = {
			name: 'awesome user'
		};
	})

	.controller('SelectLocalCtrl', function($scope, $filter) {
		$scope.user = {
			status: 2
		};

		$scope.statuses = [
			{value: 1, text: 'status1'},
			{value: 2, text: 'status2'},
			{value: 3, text: 'status3'},
			{value: 4, text: 'status4'}
		];

		$scope.showStatus = function() {
			var selected = $filter('filter')($scope.statuses, {value: $scope.user.status});
			return ($scope.user.status && selected.length) ? selected[0].text : 'Not set';
		};
	})

	.controller('TextareaCtrl', function($scope) {
		$scope.user = {
			desc: 'Awesome user \ndescription!'
		};
	})

	.controller('CheckboxCtrl', function($scope) {
		$scope.user = {
			remember: true
		};
	})

	.controller('BsdateCtrl', function($scope) {
		$scope.user = {
			dob: new Date(1984, 4, 15)
		};
	})

	.controller('Html5InputsCtrl', function($scope) {
		$scope.user = {
			email: 'email@example.com',
			tel: '123-45-67',
			number: 29,
			range: 10,
			url: 'http://example.com',
			search: 'blabla',
			color: '#6a4415',
			date: null,
			time: '12:30',
			datetime: null,
			month: null,
			week: null
		};
	})



