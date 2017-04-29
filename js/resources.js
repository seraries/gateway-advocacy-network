var app = angular.module('resourceApp', []);
// filter resources by type and create sections for each with divs for each single resource
app.filter('resFilter', [ function() {
	return function(items, searchText) {
		var filtered = [];
		angular.forEach(items, function(item) {
    	if( item.mainIssue.indexOf(searchText) >= 0 ) {
    		filtered.push(item);
    	}
		});
		return filtered;
	}
}]);

app.controller('resourceCtrl', ['$scope', '$window', '$http', function($scope, $window, $http) {
	$scope.campaign = false;
	$scope.civic = false;
	$scope.criminal = false;
	$scope.disability = false;
	$scope.economic = false;
	$scope.education = false;
	$scope.environment = false;
	$scope.gun = false;
	$scope.health = false;
	$scope.immigration = false;
	$scope.lgbtqia = false;
	$scope.racial = false;
	$scope.voter = false;
	$scope.women = false;
	// also create buttons using this array and use it for checkboxes of issues
	$scope.resTypes = [{name: "Campaigns and Elections", show: "campaign"}, {name: "Civic Involvement", show: "civic"}, 
	{name: "Criminal Justice", show: "criminal"}, {name: "Disability", show: "disability"}, {name: "Economic Justice", show: "economic"}, 
	{name: "Education", show: "education"}, {name: "Environment", show: "environment"}, {name: "Gun Control", show: "gun"}, 
	{name: "Health Care", show: "health"}, {name: "Immigration and Refugees", show: "immigration"}, {name: "LGBTQIA", show: "lgbtqia"}, 
	{name: "Racial Justice", show: "racial"}, {name: "Voter Rights", show: "voter"}, {name: "Women's Rights", show: "women"}];

	$scope.resources = [];
	$http.get("../php/getResources.php").then(function(response) {
  	$scope.resources = response.data;
  });

  $scope.toggleBtn = function(showString) {
		// make the other buttons' resources disappear
		// for each resource type, if its show value is not equal to showString, set the scope.showValue to false
		angular.forEach($scope.resTypes, function(type){
			// if it's a resource that doesn't match the showString, hide it
			if (type.show != showString) {
				$scope[type.show] = false;
			}
		});
		// make this resource type's resources either appear or disappear	
		$scope[showString] = !$scope[showString]; 
  }

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

	$scope.addResource = function() {
		if ($scope.isValidLogin) {
			// get array of checked values for otherIssues; turn that array of strings into a single comma
			// separated string and assign it to addResInfo.otherIssues
			if($scope.selection.length > 0){
				$scope.addResInfo.otherIssues = $scope.selection.join(", ");
			}
			//it must be empty--no checkboxes selected--so set a default of an empty string to pass db table
			else {
				$scope.addResInfo.otherIssues = "";
			}

	    $http.post("../php/addResource.php", $scope.addResInfo).then(function(response) {
	    	// get new array of resources
				$scope.resources = response.data;
	    	// clear form
	    	$scope.addResInfo = {};
	    	$scope.issue = {};

	    });
		}
	}

	$scope.findResource = function() {
		if($scope.isValidLogin) {
			$http.post("../php/findResource.php", $scope.editResInfo.name).then(function(response) {
				// only getting back one matching bill, hence [0]. Set form details to match database 
				$scope.editResInfo = response.data[0]; 
			});
		}
	}

	$scope.editResource = function() {
		if($scope.isValidLogin) {
						if($scope.selection.length > 0){
				$scope.editResInfo.otherIssues = $scope.selection.join(", ");
		}
			//it must be empty--no checkboxes selected--so set a default of an empty string to pass db table
		else {
				$scope.editResInfo.otherIssues = "";
		}
		$http.post("../php/editResource.php", $scope.editResInfo).then(function(response) {
				// get array of announcements again (newly updated)
				$scope.resources = response.data;
				// reset form when done
				$scope.editResInfo = {};
				$scope.editForm.$setUntouched();
				$scope.issue = {}; // this doesn't seem to work to clear checkboxes
			});	
		}
	}

}]);