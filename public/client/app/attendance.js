var app = angular.module('myApp', []);
app.controller('myCtrl', function($scope,$http) {
    $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
    $scope.selected_students= {};
    $scope.showLoading = false;
    $scope.answers  = {};
    $scope.lessonList= {};

    $scope.loadStudents = function(basePath){
        if($scope.filter.trim() != ''){
            $scope.showLoading = true;
            //console.log(basePath);
            var url = basePath+'/admin/student/getstudents?filter='+$scope.filter;
            $http.get(url).
                success(function(data, status, headers, config) {
                    $scope.showLoading = false;
                    $scope.students = data;
                    //console.log(data);
                }).
                error(function(data, status, headers, config) {
                    $scope.showLoading = false;
                    // log error
                    //console.log('ajax error');
                });
        }
        else{
            $scope.students =[];

        }


    }

    $scope.studentChecked = function(id,student){
     //   console.log(student);
        if($scope.answers[id]){ //If it is checked

            $scope.add(student);
        }
        else{
           $scope.remove(student);
        }

        console.log($scope.selected_students);
    }

    $scope.loadBulkStudents = function(){

        if($scope.course_id   != ''){
            $scope.loadLessons();
            $scope.showLoading = true;
            //console.log(basePath);
            var url = basePath+'/admin/student/getsessionstudents/'+$scope.course_id  ;
            $http.get(url).
                success(function(data, status, headers, config) {
                    $scope.showLoading = false;
                    $scope.students = data;
                    //console.log(data);
                }).
                error(function(data, status, headers, config) {
                    $scope.showLoading = false;
                    // log error
                    //console.log('ajax error');
                });
        }
        else{
            $scope.students =[];
        }


    }

    $scope.add = function(student){
        $scope.selected_students[student.student_id] = student;
        //console.log($scope.selected_students);
    }

    $scope.remove = function(student){
        delete  $scope.selected_students[student.student_id];
    }

    $scope.saveAttendance = function(basePath){
        var total = Object.keys($scope.selected_students).length;
        if(confirm('You are about to submit attendance records for '+total+' students. Proceed?')){

            $scope.showLoading = true;
            //console.log(basePath);
            var url = basePath+'/admin/student/processattendance';

            var data = {
                lesson_id : $scope.lesson_id,
                course_id   : $scope.course_id  ,
                students : $scope.selected_students
            };

            console.log(data);

            var res = $http.post(url, data);
            res.success(function(data, status, headers, config) {
                // $scope.message = data;
                if(data.status){
                    alert('Save complete!');
                    $scope.selected_students = {};
                    $scope.answers  = {};
                    $scope.lesson_id = '';
                }

                $scope.showLoading = false;
            });
            res.error(function(data, status, headers, config) {
                alert( "failure message: " + JSON.stringify({data: data}));
                $scope.showLoading = false;
            });

        }


    }

    $scope.loadLessons = function(){
        if($scope.course_id   != ''){
            $scope.showLoading = true;
            //console.log(basePath);
            var url = basePath+'/admin/student/getsessionlessons/'+$scope.course_id  ;
            $http.get(url).
                success(function(data, status, headers, config) {
                    $scope.showLoading = false;
                    $scope.lessonList = data;
                    //console.log(data);
                }).
                error(function(data, status, headers, config) {
                    $scope.showLoading = false;
                    // log error
                    //console.log('ajax error');
                });
        }
        else{
            $scope.students =[];
        }
    }

});

