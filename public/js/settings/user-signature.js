var wrapper = document.getElementById("signature-pad");
var clearButton = document.getElementById("clear_signature");
var undoButton = document.getElementById("undo_signature");
var saveSignatureButton = document.getElementById("save_signature");
var canvas = wrapper.querySelector("canvas");
var signaturePad = new SignaturePad(canvas, {
  // It's Necessary to use an opaque color when saving image as JPEG;
  // this option can be omitted if only saving as PNG or SVG
  backgroundColor: 'rgb(255, 255, 255)'
});


function download(dataURL, filename) {
    var blob = dataURLToBlob(dataURL);
    var url = window.URL.createObjectURL(blob);
    // pass file to input file
    var input = document.createElement("input");
    input.style = "display:none";
    input.type = "file";

    var data = new FormData();
    data.append('file', blob);
    data.append('_token', $("[name='_token']").val());

    $.ajax({
        url :  "/personal/signature/upload",
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        success: function(data) {
            toastr.success("Signature uploaded!");
            $("#save_signature").removeClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light disabled");
            setTimeout(() => {
                location.reload();
            }, 1500);
        },    
        error: function(response) {
            $("#save_signature").removeClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light disabled").prop("disabled", false);
            var body = responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
                return;
            }
            toastr.error("Signature upload failed!");
        }
    });
}

// One could simply use Canvas#toBlob method instead, but it's just to show
// that it can be done using result of SignaturePad#toDataURL.
function dataURLToBlob(dataURL) {
  // Code taken from https://github.com/ebidel/filer.js
  var parts = dataURL.split(';base64,');
  var contentType = parts[0].split(":")[1];
  var raw = window.atob(parts[1]);
  var rawLength = raw.length;
  var uInt8Array = new Uint8Array(rawLength);

  for (var i = 0; i < rawLength; ++i) {
    uInt8Array[i] = raw.charCodeAt(i);
  }

  return new Blob([uInt8Array], { type: contentType });
}

clearButton.addEventListener("click", function (event) {
  signaturePad.clear();
});

undoButton.addEventListener("click", function (event) {
  var data = signaturePad.toData();

  if (data) {
    data.pop(); // remove the last dot or line
    signaturePad.fromData(data);
  }
});

saveSignatureButton.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("Please provide a signature first.");
  } else {
    if(document.getElementById('i_agree').checked){
      $(this).addClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light disabled").prop("disabled", true);
      var dataURL = signaturePad.toDataURL();
      download(dataURL, "signature.png");
    }else{
      alert("Please check the checkbox if you agree.");
    }
    
  }
});
