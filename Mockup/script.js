const completeTrack = document.getElementById('complete-track'); // Completed track file
const vocalTrack = document.getElementById('vocals-track'); // Completed track file
const drumsTrack = document.getElementById('drums-track'); // Completed track file
const bassTrack = document.getElementById('bass-track'); // Completed track file
const form = document.getElementById('form1');
const errorElement = document.getElementById('error');
const checkBox = document.getElementById('accept-terms'); // terms and conditions check box
var fileType = document.getElementById("file-type").value; //gets the selected file type
var fileTypeSelection = document.getElementById("file-type");


//checks if inputs are valid when submitted.
form.addEventListener('submit', (e) => {
  //let errors = false

  //Checks a file type has been selected
  if (fileType === "") {
    setErrorFor(fileTypeSelection, 'Select file type');
    e.preventDefault()
  }else{
    setSuccessFor(fileTypeSelection);
  }

  //COMPLETE TRACK CHECKS
  //Checks a file has been selected
  if (completeTrack.value === "") {
    setErrorFor(completeTrack, 'Select a file');
    e.preventDefault()
  } else //Checks file type matches selected file type
    if (!(completeTrack.files[0].type === "audio/" + fileType)) {
      setErrorFor(completeTrack, 'File does not match selected file type');
      e.preventDefault()
    } else {
      setSuccessFor(completeTrack);
    }


  //VOCAL TRACK CHECKS
  if (vocalTrack.value === "") {
    setErrorFor(vocalTrack, 'Select a file');
    e.preventDefault()
  }else //Checks file type matches selected file type
    if (!(vocalTrack.files[0].type === "audio/" + fileType)) {
      setErrorFor(vocalTrack, 'File does not match selected file type');
      e.preventDefault()
    } else {
      setSuccessFor(vocalTrack);
    }

  //DRUMS TRACK CHECKS
  if (drumsTrack.value === "") {
    setErrorFor(drumsTrack, 'Select a file');
    e.preventDefault()
  } else //Checks file type matches selected file type
    if (!(drumsTrack.files[0].type === "audio/" + fileType)) {
      setErrorFor(drumsTrack, 'File does not match selected file type');
      e.preventDefault()
    } else {
      setSuccessFor(drumsTrack);
    }

  //BASS TRACK CHECKS
  if (bassTrack.value === "") {
<<<<<<< HEAD
setErrorFor(bassTrack, 'Select a file');
=======
    setErrorFor(bassTrack, 'Select a file');
>>>>>>> master
    e.preventDefault()
  } else //Checks file type matches selected file type
    if (!(bassTrack.files[0].type === "audio/" + fileType)) {
      setErrorFor(bassTrack, 'File does not match selected file type');
      e.preventDefault()
    } else {
      setSuccessFor(bassTrack);
    }

  if (checkBox.checked === false) {
    setErrorFor(checkBox.parentElement, 'Agree to terms and conditions');
    e.preventDefault()
  } else{
    setSuccessFor(checkBox.parentElement);
  }

  if (errors === true) {
    e.preventDefault();
  }

})


function setErrorFor(input, message) {
  const inputGroup = input.parentElement;
  const small = inputGroup.querySelector('small');
  inputGroup.classList.add('error');
  small.innerText = message;
  inputGroup.classList.remove('success');
}

function setSuccessFor(input) {
  const inputGroup = input.parentElement;
  inputGroup.classList.add('success');
  inputGroup.classList.remove('error');
}

//Gets currents selected file type
function getSelectFileType() {
  fileType = document.getElementById("file-type").value;
}
