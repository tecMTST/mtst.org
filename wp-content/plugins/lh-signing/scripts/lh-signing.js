function lh_signing_run_js(){

var lh_signing_forms = document.getElementsByClassName("lh_signing-form");

for (var i = lh_signing_forms.length - 1; i >= 0; i--){

if (lh_signing_forms[i].getAttribute("data-lh_signing-nonce")){

lh_signing_forms[i].getElementsByClassName("lh_signing-nonce")[0].value = lh_signing_forms[i].getAttribute("data-lh_signing-nonce");

}

}

}

lh_signing_run_js();