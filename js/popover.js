document.querySelector("#show-add").addEventListener("click",function(){
    document.querySelector(".popup").classList.add("active");
});

document.querySelector(".popup .close-btn").addEventListener("click",function(){
    document.querySelector(".popup").classList.remove("active");
});

$(document).ready(function () {
  // Initialize the Bootstrap Datepicker
  $('.fj-date input').datepicker({
    format: 'dd/mm/yyyy', // Set the expected date format
    autoclose: true
  });
});


// $('.fj-date').datepicker({
//     format: "dd/mm/yyyy"
// });
