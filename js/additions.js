function loadUsersOnline() {
    $.get("./functions/additions.php?onlineusers=result", function(data) {
        $(".usersonline").text("Users Online: " + data);
    });
}

setInterval(function() {
    loadUsersOnline();
}, 500);

$(document).ready(function() {
    loadUsersOnline(); 
});

