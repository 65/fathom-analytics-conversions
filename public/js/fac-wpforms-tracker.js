document.addEventListener("DOMContentLoaded", function() {
    var elementsArray = document.querySelectorAll('[id^="wpforms-form-"]');
    elementsArray.forEach(function(elem) {
        elem.addEventListener("submit", function(e) {
            //console.log(e);
            var wpFormId = e.target.id;
            var wpFormElements = e.target.elements;
            //console.log(wpFormElements);
            for (var i=0; i< wpFormElements.length; i++) {
                var fieldName = wpFormElements[i].name,
                    fieldValue = wpFormElements[i].value;
                if(fieldName === 'wpforms[fac_event_id]' && fieldValue) {
                    fathom.trackGoal(fieldValue, 0);
                }
            }
        });
    });
});