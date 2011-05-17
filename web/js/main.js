// sfWidgetFormChoiceStates
function ChoiceStateRegionSet(reg) {
  var fs='fieldset_'+reg.id;
  var cboxes=document.getElementById(fs).getElementsByTagName('input');
  for (j=0; j<cboxes.length; j++) {
    cboxes[j].checked=((reg.checked==true)? true : false);
  }
}
function ChoiceStateAllSet(all,divid) {
  var fieldsets=document.getElementById(divid).getElementsByTagName('div');
  for (k=0; k<(fieldsets.length-1); k++) {
    var fs = fieldsets[k];
//    alert(fs.id);
    var cboxes=document.getElementById(fs.id).getElementsByTagName('input');
    for (j=0; j<cboxes.length; j++) {
      cboxes[j].checked=((all.checked==true)? true : false);
    }
  }
}
function ChoiceStateStateSet(state,regid,allid) {
  var reg = document.getElementById(regid);
  var all = document.getElementById(allid);
  if (state.checked == false) {
    reg.checked == false;
    all.checked == false;
  }
}
