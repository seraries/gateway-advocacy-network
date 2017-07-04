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
	$scope.gun = false;
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
	{name: "Gun Control", show: "gun"}, {name: "Health Care", show: "health"}, {name: "International", show: "international"}, {name: "Immigration and Refugees", show: "immigration"}, 
	{name: "LGBTQIA", show: "lgbtqia"}, {name: "Other", show: "other"}, {name: "Racial Justice", show: "racial"},
	{name: "Voter Rights", show: "voter"}, {name: "Women's Rights", show: "women"}];

	$scope.groups = [];
	$http.get("../php/getGroups.php").then(function(response) {
  	$scope.groups = response.data; // when I have more than 1 group in db, remove the [0]
  });
  /*
  $http.post("../php/deleteGroup.php", "Example Group").then(function(response) {
	// delete hard-coded group and get new array of resources
		$scope.groups = response.data;
	});*/

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
	
	// search for groups by zip code and radial distance
	$scope.searchZip = function() {
		// set to empty each time so it doesn't hold previous search's values
		$scope.zipGroups = [];
		$scope.zips = [];
		// if user doesn't pick from how many miles from dropdown, set to 1 mile radius
		if (!$scope.zipDistance) {
			$scope.zipDistance = 1;
		}
		// url string to access api and get json with appropriate zip codes
		var zipAPIString = "https://www.zipcodeapi.com/rest/js-HZv4WyW9j6pmudnHLGPBitoGhwnwGKsnJXrGwoQQvfc0tzIfgxQy1eeiLCPrmiO6/radius.json/" +
						$scope.zipSearch + "/" + $scope.zipDistance + "/miles"

		$http.get(zipAPIString).then(function(response) {
			// array of objects that have zip codes and data about the zip codes
			var zipInfo = response.data.zip_codes;
			// create array of just the zip codes
			angular.forEach(zipInfo, function(zip) {
				$scope.zips.push(zip.zip_code);
			});
			// create array of the groups in our database that have a zipcode matching one in 
			// the array of zip codes we just got from the api data
			$scope.zipGroups = $.grep($scope.groups, function(obj){
				return ($scope.zips.indexOf(obj.zip) != -1);
			});
		});	
	}

	// further refine the search for groups by zip to filter by the group's focus issues
	$scope.focusFilter = function(element) {
		// if they select "All" for the focus/issues, return all matching zip groups
		if ($scope.zipFocus === "All") {
			return true;
		}
		// if there is a focus selected, but it's not "All", return only groups that share that focus
		else if($scope.zipFocus) {
			return (element.issues.indexOf($scope.zipFocus) > -1);
		}
		// if there isn't a focus issue selected for filtering, return all matching zip groups
	  	return true; 
	};


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

	$scope.isValidLink = function(link) {
		var linkRE = /^(#|http:\/\/|https:\/\/)/;
		return linkRE.test(link);
	}

	$scope.isValidZip = function(zip) {
		var zipRE = /^\d{5}$/;
		return zipRE.test(zip);
	}

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
	    	$scope.addForm.$setUntouched();
    		for (i = 0; i < $scope.resTypes.length; i++) {
				$scope.resTypes[i].selected = false; // unselect all checkboxes
			}

	    });
		}
	}

	$scope.findGroup = function() {
		if($scope.isValidLogin) {
			$http.post("../php/findGroup.php", $scope.editGroupInfo.name).then(function(response) {
				// reset checkboxes in case of previous, unsubmitted info being put in the group name input
				for (i = 0; i < $scope.resTypes.length; i++) {
					$scope.resTypes[i].selected = false; // unselect all checkboxes
				}
				// only getting back one matching group, hence index [0]. Set form details to match database 
				$scope.editGroupInfo = response.data[0]; 
				// set checkboxes on edit form to match what's already in DB
				if($scope.editGroupInfo.issues === ""){
					// do nothing, it's empty so I don't need to mark checkboxes for it
				}
				else {
					var issueArray = $scope.editGroupInfo.issues.split(", ");
					for (i = 0; i < $scope.resTypes.length; i++) {
						if(issueArray.indexOf($scope.resTypes[i].name) > -1) {
							$scope.resTypes[i].selected = true; // set checkbox on edit form to match info in DB
						}
					}
				}
			});
		}
	}

	$scope.editGroup = function() {
		if($scope.isValidLogin) {
			if($scope.selection.length > 0){
				$scope.editGroupInfo.issues = $scope.selection.join(", ");
			}
			//it must be empty--no checkboxes selected--so set a default of an empty string to pass db table
			else {
				$scope.editGroupInfo.issues = "";
			}
			$http.post("../php/editGroup.php", $scope.editGroupInfo).then(function(response) {
				// get array of announcements again (newly updated)
				$scope.groups = response.data;
				// reset form when done
				$scope.editGroupInfo = {};
				$scope.editForm.$setUntouched();
				for (i = 0; i < $scope.resTypes.length; i++) {
					$scope.resTypes[i].selected = false; // unselect all checkboxes
				}
			});	
		}
	}
	
}]);