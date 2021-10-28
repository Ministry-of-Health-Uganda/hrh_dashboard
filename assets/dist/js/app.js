
var app = angular.module('hrhApp', []);

app.constant('constants', {
  BASE_URL: 'https://hris.health.go.ug/hrh_dashboard/dataprep/',
  getAgeDataUrl: "ageRanges",
  appVersion: 1.0
});



//http service
app.service('httpService', function ($http, constants) {

  this.get = function (endPoint) {
    var url = `${constants.BASE_URL}${endPoint}`;
    return $http.get(url)
  }

  this.get = function (url) {
    return $http.get(url)
  }

  this.post = function (endPoint, payload) {
    var url = `${constants.BASE_URL}${endPoint}`;
    return $http.post(url, payload)
  }

  this.cache = function (key, value) {
    window.localStorage.setItem(key, JSON.stringify(value));
  }

  this.getCache = function (key) {
    var stored = window.localStorage.getItem(key);
    return (stored !== null) ? JSON.parse(stored) : [];
  }

});


//Dashbaord Controller
app.controller('DashboardCtrl', function ($scope, $rootScope, $timeout, $interval, httpService, constants) {


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
      console.g('error')
    });
  }


  $scope.ageGraph = (ageData) => {
    Highcharts.setOptions({
      colors: ['#28a745', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
    });

    Highcharts.chart('ageDistribution', {
      chart: {
        type: 'column'
      },
      title: {
        text: 'iHRIS Manage Uganda  Health Workers Age Distribution'
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


  $scope.genderChart = (genderData) => {

    Highcharts.chart('gender_chart', {
      chart: {
        type: 'pie',
      },
      title: {
        text: 'iHRIS Manage National Health Worker Force Gender Distribution'
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
      series: [{ name: 'Percentage of employees', data: genderData.values }],
      credits: {
        enabled: false
      }
    });
  }

  $scope.cadreChart = (cadreData) => {

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
      series: [{ name: 'Percentage of employees', data: cadreData.values }],
      credits: {
        enabled: false
      }
    });
  }


  $scope.attendanceChart = (attendance) => {

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
      series: attendance.plots,
      credits: {
        enabled: false
      }
    });

  }


  $scope.test();


});


//Professional Councils
app.controller('AlliedCouncilCtrl', function ($scope, $rootScope, $timeout, $interval, httpService, constants) {
  var CACHE_KEY = 'ALLIED';
  $scope.allied = httpService.getCache(CACHE_KEY) || [];
  $scope.loading = true;
  var alliedUrl = "https://hris2.health.go.ug/ahpc-qualify/person_last_reg.php/";


  $scope.getAllied = function () {

    httpService.get(alliedUrl).then((response) => {
      console.log(response);
      $scope.allied = response.data;
      $scope.loading = false;
      httpService.cache(CACHE_KEY, response.data);
    });
  }

  $rootScope.getValues = function (data) {
    return Object.values(data);
  }

  $rootScope.getKeys = function (data) {
    return Object.keys(data);
  }

  $scope.getAllied();

});

//mdecial council
app.controller('DentalMedicalsCouncilCtrl', function ($scope, $rootScope, $timeout, $interval, httpService, constants) {
  var CACHE_KEY = 'DENTAL_MEDICALS';
  $scope.loading = true;
  $scope.medicals = httpService.getCache(CACHE_KEY) || [];
  var dentalMedicals = "https://hris2.health.go.ug/umdpc/person_last_reg.php/";

  $scope.getMedicals = function () {

    httpService.get(dentalMedicals).then((response) => {
      $scope.loading = false;
      $scope.medicals = response.data;
      httpService.cache(CACHE_KEY, response.data);
    });
  }

  $rootScope.getValues = function (data) {
    return Object.values(data);
  }

  $rootScope.getKeys = function (data) {
    return Object.keys(data);
  }


  $scope.getMedicals();

});

app.controller('PhamarcistsCouncilCtrl', function ($scope, $rootScope, $timeout, $interval, httpService, constants) {
  var CACHE_KEY = "PHARMACISTS";
  $scope.loading = true;
  $scope.pharmacists = httpService.getCache(CACHE_KEY) || [];
  var pharmacyUrl = "https://hris2.health.go.ug/pharmacy_society/person_last_reg.php/";


  $scope.getPharmacists = function () {

    httpService.get(pharmacyUrl).then((response) => {
      $scope.loading = false;
      $scope.pharmacists = response.data;
      httpService.cache(CACHE_KEY, response.data);
    });
  }

  $rootScope.getValues = function (data) {
    return Object.values(data);
  }

  $rootScope.getKeys = function (data) {
    return Object.keys(data);
  }



  $scope.getPharmacists();

});



app.controller('PharmaCouncilCtrl', function ($scope, $rootScope, $timeout, $interval, httpService, constants) {
  var CACHE_KEY = "PHARMACIST_SOCIETY";
  $scope.loading = true;
  $scope.councils = httpService.getCache(CACHE_KEY) || [];
  var pharmaSocietyUrl = "https://hris2.health.go.ug/pharmacy_society/person_last_lic.php";


  $scope.getPharmacists = function () {
    httpService.get(pharmaSocietyUrl).then((response) => {
      console.log(response);
      $scope.loading = false;
      $scope.councils = response.data;
      httpService.cache(CACHE_KEY, response.data);
    });
  }

  $rootScope.getValues = function (data) {
    return Object.values(data);
  }

  $rootScope.getKeys = function (data) {
    return Object.keys(data);
  }


  $scope.getPharmacists();

});


