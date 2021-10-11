
var app = angular.module('hrhApp', []);

app.constant('constants', {
  BASE_URL: 'http://localhost/hrh_dashboard/dataprep/',
  getDistrictAttendnace: "person_attend/2021-08-01/2021-08-01",
  getAgeDataUrl: "ageRanges",
  appVersion: 1.0
});

//http service
app.service('httpService', function ($http, constants) {

  this.get = function (endPoint) {
    var url = `${constants.BASE_URL}${endPoint}`;
    return $http.get(url)
  }

  this.post = function (endPoint, payload) {
    var url = `${constants.BASE_URL}${endPoint}`;
    return $http.post(url, payload)
  }

});


//Dashbaord Controller
app.controller('DashboardCtrl', function ($scope, $rootScope, $timeout,$interval, httpService, constants) {


$scope.staff = 0;

$scope.test = function () {
    
    httpService.get(constants.getAgeDataUrl).then((response) => {

       console.log(response);
       let ageData = response.data.agedata;
       let genderData = response.data.genderdata;
       let cadreData = response.data.cadredata;
       let attendanceData = response.data.attendance;
       $scope.ageGraph(ageData);
       $scope.genderChart(genderData);
       $scope.cadreChart(cadreData);
       $scope.attendanceChart(attendanceData);

    }, (error) => {
      console.log('error')
    });
  }


$scope.ageGraph = (ageData)=>{

    Highcharts.chart('ageDistribution', {
    chart: {
        type: 'column'
    },
    title: {
      text: 'Uganda  Health Workers Age Distribution'
    },
    xAxis: {
        categories: ageData.labels,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'No of Staff'
        }
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Age distribution',
        data: ageData.values

    }], 
    credits: {
    enabled: false
      }
    });

  }

  
  $scope.genderChart = (genderData)=>{

    Highcharts.chart('gender_chart', {
         chart: {
           type: 'pie',
         },
         title: {
          text: 'iHRIS National Health Worker Force Gender Distribution'
           },
           tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
          series: [{name: 'Percentage of employees',data: genderData.values }],
          credits: {
            enabled: false
          }
      });
}

$scope.cadreChart = (cadreData)=>{

  Highcharts.chart('cadredistribution', {
       chart: {
         type: 'pie',
       },
       accessibility: {
        point: {
            valueSuffix: '%'
        }
       },
       title: {
        text: 'iHRIS National Health Worker Force Cadre Distribution'
         },
         tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              depth: 35,
              dataLabels: {
                  enabled: true,
                  format: '{point.name} : {point.percentage:.1f}%'
              }
          }
      },
        series: [{name: 'Percentage of employees',data: cadreData.values }],
        credits: {
          enabled: false
        }
    });
}


$scope.attendanceChart= (attendance)=>{

  Highcharts.chart('absoluteAbsenteeism', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Overall Attendance for Uganda'
    },
    xAxis: {
        categories: attendance.labels,
        tickmarkPlacement: 'on',
        title: {
            enabled: false
        }
    },
    yAxis: {
        title: {
            text: 'Staff Percentage'
        }
    },
    tooltip: {
        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b> ({point.y:,.0f} Days)<br/>',
        split: true
    },
    plotOptions: {
        area: {
            stacking: 'percent',
            lineColor: '#ffffff',
            lineWidth: 1,
            marker: {
                lineWidth: 1,
                lineColor: '#ffffff'
            }
        }
    },
    series:attendance.plots,
    credits: {
    enabled: false
  }
});

}


  $scope.test();

  
});
