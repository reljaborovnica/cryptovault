const myLinks = document.querySelectorAll('.myLink');
 
myLinks.forEach(myLink => {
    myLink.addEventListener("click", function(event) {
        event.preventDefault();
    });
       
    myLink.addEventListener("dblclick", function() {
        window.location.href = this.href;
    });
})
 