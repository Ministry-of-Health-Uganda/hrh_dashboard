<div ng-controller="AlliedCouncilCtrl" >

<div class="text-center" ng-if="loading && allied.length<1">
  <img src="<?php echo base_url(); ?>assets/images/loader.gif" >
</div>


<table class="table table-striped table-bordered mytable" ng-if="!loading && allied.length>0">
    <thead>
       <tr ng-if="allied.length>0">
           <th ng-repeat="attr in getKeys(allied[0])" >{{attr}}</th>
       </tr>
    </thead>
    <tbody>
       <tr ng-repeat="person in allied track by $index">
            <td ng-repeat="attr in getValues(person) ">{{ attr }}</td>
       </tr>
    </tbody>

</table>

</div>


