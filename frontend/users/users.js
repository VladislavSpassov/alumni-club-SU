const logoutBtn = document.getElementById('logout');

logoutBtn.addEventListener('click', () => {
    logout();
})

const homeBtn = document.getElementById('home');

homeBtn.addEventListener('click', () => {
  redirect('../home/home.html');
})

const profileBtn = document.getElementById('profile');

profileBtn.addEventListener('click', () => {
    redirect('../profile/profile.html');
})

const statisticsBtn = document.getElementById('statistics');

statisticsBtn.addEventListener('click', () => {
    redirect("../statistics/statistics.html");
})

function logout() {
    fetch('../../backend/endpoints/logout.php', {
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

async function getUsers() {
    fetch("../../backend/endpoints/allUsers.php", {
            method: "GET",
            headers: {
                "Content-Type": "application/json; charset=utf-8",
            },
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Error loading users.");
            }
            return response.json();
        })
        .then((data) => {
            users = data.value;
            appendUsers(users);
        })
        .catch((error) => {
            console.error("Error when loading users: " + error);
        });
};

function appendUsers(users) {
    var userSection = document.getElementById('list-of-users');

    Object.values(users).forEach(function(data) {
        const { id, password, ...res } = data;
        var article = document.createElement("article");

        Object.values(res).forEach(function(property) {
            var paragraph = document.createElement("p");
            paragraph.innerHTML = property;
            article.appendChild(paragraph);
        });

        userSection.appendChild(article);
    });
}

getUsers();