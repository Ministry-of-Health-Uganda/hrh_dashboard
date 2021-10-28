<div ng-controller="PharmaCouncilCtrl">

<div class="text-center" ng-if="loading && councils.length<1">
  <img src="<?php echo base_url(); ?>assets/images/loader.gif" >
</div>

<table class="table table-striped table-bordered mytable" ng-if="councils.length>0">
    <thead>
       <tr>
           <th ng-repeat="attr in getKeys(councils[0])">{{attr}}</th>
       </tr>
    </thead>
    <tbody>
       <tr ng-repeat="person in councils track by $index">
            <td ng-repeat="attr in getValues(person) ">{{ attr }}</td>
       </tr>
    </tbody>

</table>

</div>


