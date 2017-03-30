var app = angular.module('localApp', []);

app.controller('localCtrl', ['$scope', '$window', '$http', function($scope, $window, $http) {
	$scope.campaign = false;
	$scope.civic = false;
	$scope.consumer = false;
	$scope.criminal = false;
	$scope.disability = false;
	$scope.economic = false;
	$scope.education = false;
	$scope.environment = false;
	$scope.health = false;
	$scope.international = false;
	$scope.immigration = false;
	$scope.lgbtqia = false;
	$scope.other = false;
	$scope.racial = false;
	$scope.voter = false;
	$scope.women = false;
	// also create buttons using this array and use it for checkboxes of issues
	$scope.resTypes = [{name: "Campaign Finance Reform", show: "campaign"}, {name: "Civic Involvement", show: "civic"}, 
	{name: "Consumer Rights", show: "consumer"}, {name: "Criminal Justice", show: "criminal"}, {name: "Disability", show: "disability"}, 
	{name: "Economic Justice", show: "economic"}, {name: "Education", show: "education"}, {name: "Environment", show: "environment"}, 
	{name: "Health Care", show: "health"}, {name: "International", show: "international"}, {name: "Immigration and Refugees", show: "immigration"}, 
	{name: "LGBTQIA", show: "lgbtqia"}, {name: "Other", show: "other"}, {name: "Racial Justice", show: "racial"},
	{name: "Voter Rights", show: "voter"}, {name: "Women's Rights", show: "women"}];

	$scope.groups = [];
	$http.get("../php/getGroups.php").then(function(response) {
  	$scope.groups = response.data; // when I have more than 1 group in db, remove the [0]
  });

	$scope.hideLogin = true;
	$scope.showFailedLogin = false;
	$scope.invalidSubmit = false;
	$scope.isValidLogin = false;

	$scope.loginSubmit = function() {
		if ($scope.loginForm.$valid) {
	    $http.post("../php/login.php", $scope.loginInfo).then(function(response) {
	      if(response.data.message === "ok") {
	      	$scope.hideLogin = true;
	      	$scope.isValidLogin = true;
	      	//$scope.loginInfo.username = "";
	      	//$scope.loginInfo.password = "";
	      }
	      else {
	      	$scope.showFailedLogin = true;
	      }
	    });
		}
	}
	$scope.zipGroups = [];
	$scope.searchZip = function(){
		$scope.zipGroups = $.grep($scope.groups, function(obj){
			return obj.zip === $scope.zipSearch;
		});
	}

	//selection of other issues from checkboxes
	$scope.selection = [];
    // helper method
    $scope.selectedIssues = function selectedIssues() {
      return filterFilter($scope.resTypes, { selected: true });
    };
    
    // watch resTypes for changes
    $scope.$watch('resTypes|filter:{selected:true}', function (nv) {
      $scope.selection = nv.map(function (issue) {
        return issue.name;
      });
    }, true);

	$scope.addGroup = function() {
		if ($scope.isValidLogin) {
			// get array of checked values for otherIssues; turn that array of strings into a single comma
			// separated string and assign it to addResInfo.otherIssues
			if($scope.selection.length > 0){
				$scope.addGroupInfo.issues = $scope.selection.join(", ");
			}
			//it must be empty--no checkboxes selected--so set a default of an empty string to pass db table
			else {
				$scope.addGroupInfo.issues = "";
			}

	    $http.post("../php/addGroup.php", $scope.addGroupInfo).then(function(response) {
	    	// get new array of resources
				$scope.groups = response.data;
	    	// clear form
	    	$scope.addGroupInfo = {};
	    	$scope.issue = {};

	    });
		}
	}
	
}]);