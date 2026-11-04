var form = document.getElementById("addStudentForm");

if (form) {
  form.addEventListener("submit", function (event) {
    event.preventDefault();

    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();

    xhr.open("POST", "ajax_validate_student.php", true);

    xhr.onload = function () {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);

        var message = document.getElementById("ajaxMessage");

        if (response.valid) {
          message.textContent = "";

          form.submit();
        } else {
          message.textContent = response.message;
        }
      }
    };

    xhr.send(formData);
  });
}
