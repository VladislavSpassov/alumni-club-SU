const homeBtn = document.getElementById('home');

homeBtn.addEventListener('click', () => {
  redirect('../home/home.html');
});


const usersBtn = document.getElementById('users');

usersBtn.addEventListener('click', () => {
  redirect("../users/users.html");
});

const statisticsBtn = document.getElementById('statistics');

statisticsBtn.addEventListener('click', () => {
  redirect("../statistics/statistics.html");
});

const logoutBtn = document.getElementById('logout');

logoutBtn.addEventListener('click', () => {
  logout();
});

const submitBtn = document.getElementById('submit-button');

submitBtn.addEventListener('click', (event) => {
  event.preventDefault();

  const password = document.getElementById("password");
  const firstName = document.getElementById("firstName");
  const lastName = document.getElementById("lastName");
  const email = document.getElementById("email");

  if (!validateEmail(email.value)) {
    showError(email, "Невалиден имейл адрес.");
    return;
  }
  if (!validatePassword(password.value)) {
    showError(password, "Невалидна парола.");
    return;
  }
  if (!firstName.value || !lastName.value || !isBetween(firstName.value.length, 1, 50) || !isBetween(lastName.value.length, 1, 50)) {
    showError(firstName, "Невалидно име или фамилия.");
    return;
  }
  showSuccess(password, "Промените са запазени успешно.");
  const formData = {
    password: password.value,
    firstName: firstName.value,
    lastName: lastName.value,
    email: email.value
  };

  updateProfile(formData);
});

function logout() {
  fetch('../../endpoints/logout.php', {
    method: 'GET'
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error('Error logout user.');
      }
      return response.json();
    })
    .then(response => {
      if (response.success) {
        redirect('../login/login.html');
      }
    })
    .catch(error => {
      const message = 'Error logout user.';
      console.error(message);
    });
}

function redirect(path) {
  window.location = path;
}

async function getProfileInfo() {
  fetch("../../endpoints/get_profile_info.php", {
    method: "GET",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error loading profile info.");
      }
      return response.json();
    })
    .then((data) => {
      const userInfo = data.value;
      appendProfileInfo(userInfo);
    })
    .catch((error) => {
      console.error("Error when loading profile info: " + error);
    });
}

function appendProfileInfo(userInfo) {
  document.getElementById("username").value = userInfo.username;
  document.getElementById("password").value = userInfo.password;
  document.getElementById("firstName").value = userInfo.firstName;
  document.getElementById("lastName").value = userInfo.lastName;
  document.getElementById("email").value = userInfo.email;
  document.getElementById("speciality").value = userInfo.speciality;
  document.getElementById("graduationYear").value = userInfo.graduationYear;
  document.getElementById("groupUni").value = userInfo.groupUni;
  document.getElementById("faculty").value = userInfo.faculty;
}

async function updateProfile(formData) {
  const data = new FormData();

  fetch('../../endpoints/update_profile.php', {
    method: 'PUT',
    headers: {
      "Content-Type": "application/json; charset=utf-8",
    },
    body: JSON.stringify(formData),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error('Error updating profile info.');
      }
      return response.json();
    })
    .then((data) => {
      if (data.success === true) {
        console.log("The profile is updated successfully.");
      } else {
        console.log('The profile is NOT updated successfully.');
      }
    })
    .catch(error => {
      const message = 'Error when updating profile.';
      console.log(error);
      console.error(message);
    });
}

function validateEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function validatePassword(password) {
  const re = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{6,10}$)");
  return re.test(password);
}

const isEmpty = value => value === '' ? false : true;

const showError = (input, message) => {
  const formField = input.parentElement;

  formField.classList.remove('success');
  formField.classList.add('error');

  const error = formField.querySelector('small');
  error.textContent = message;
}

const showSuccess = (input, message) => {
  const formField = input.parentElement;

  formField.classList.remove('error');
  formField.classList.add('success');

  const success = formField.querySelector('small');
  success.textContent = message;
}

const isBetween = (length, min, max) => length < min || length > max ? false : true;

function getUserRole() {
  fetch('../endpoints/get_user_role.php', {
    method: 'GET'
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error('Error getting user role.');
      }
      return response.json();
    })
    .then(response => {
      if (response.value != "admin") {
        var statistics = document.getElementById('statistics');
        statistics.parentNode.removeChild(statistics);
        var users = document.getElementById('users');
        users.parentNode.removeChild(users);
      }
    })
    .catch(error => {
      const message = 'Error getting user role.';
      console.error(message);
    });
}

getProfileInfo();
getUserRole();