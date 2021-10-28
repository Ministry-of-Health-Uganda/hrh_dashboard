<div ng-controller="DentalMedicalsCouncilCtrl" >

<div class="text-center" ng-if="loading && medicals.length<1">
  <img src="<?php echo base_url(); ?>assets/images/loader.gif" >
</div>


<table class="table table-striped table-bordered mytable" ng-if="medicals.length>0">
    <thead>
       <tr>
           <th ng-repeat="attr in getKeys(medicals[0])">{{attr || ''}}</th>
       </tr>
    </thead>
    <tbody>
       <tr ng-repeat="person in medicals track by $index">
            <td ng-repeat="attr in getValues(person) ">{{ attr || '' }}</td>
       </tr>
    </tbody>

</table>

</div>


