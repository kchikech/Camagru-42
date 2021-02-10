const radios = document.querySelectorAll('.form-check-input.sup');
const canvasSup = document.querySelector('.canvas_sup');
const canvasSup1 = document.querySelector('.canvas_sup1');
const canvasSup2 = document.querySelector('.canvas_sup2');
const filterContainer = document.querySelector('.filters_container');
const canvass = document.querySelector('.photo');
var video = document.getElementById("vid-show");
var previmg = document.getElementById("previmg");


function likeButton(post_id) {
    var request = new XMLHttpRequest();
    // Instantiating the request object
    request.open("POST", "http://192.168.99.100/camagru/posts/add_like/" + post_id);
    // Defining event listener for readystatechange event
    request.onreadystatechange = function () {
        // Check if the request is compete and was successful\
        if (this.readyState === 4 && this.status === 200) {
            var data = JSON.parse(this.responseText);
            if (data.logged == 'false') {
                alert('Please Login to Like this post');
                return;
            }
            var like = document.getElementById("like" + post_id);
            like.innerHTML = Number(data.plikes) + " likes";
            likeColor(data.likes, post_id);
        }
    };
    // Sending the request to the server
    request.send();
}

function likeColor(like, post_id) {
    if (like === 0) {
        var l = document.getElementById("like" + post_id).parentElement;
        l.classList.add("has-text-danger");

    }
    else {
        var l = document.getElementById("like" + post_id).parentElement;
        l.classList.remove("has-text-danger");

    }
}
//  show modal Delete post

function openModal1() {
    let modalTrigger = document.querySelectorAll('.modal-delete');
    modalTrigger.forEach(function (trigger) {
        trigger.addEventListener('click', function (event) {
            // remove "#" from #modal
            const target = this.getAttribute('href').substr(1);
            // use dynamic target to reference given modal
            const modalWindow = document.getElementById(target);
            if (modalWindow.classList) {
                modalWindow.classList.add('is-active');
                console.log(modalWindow);

            }
            event.preventDefault();
        });
    });
}

// modal show likes 
function openModal() {
    let modalTrigger = document.querySelectorAll('.modal-likes');
    modalTrigger.forEach(function (trigger) {
        trigger.addEventListener('click', function (event) {
            // remove "#" from #modal
            const target = this.getAttribute('href').substr(1);
            // use dynamic target to reference given modal
            const modalWindow = document.getElementById(target);
            if (modalWindow.classList) {
                modalWindow.classList.add('is-active');
            }
            event.preventDefault();
        });
    });
}

function closeModal() {
    let closeBtns = document.querySelectorAll('.delete');
    let modalOverlays = document.querySelectorAll('.modal-background');
    closeBtns.forEach(function (btn) {
        btn.addEventListener('click', function (event) {
            // target the whole modal
            const modalWindow = this.parentNode.parentNode.parentNode;
            modalWindow.classList.remove('is-active');
        });
    });
    modalOverlays.forEach(function (overlay) {
        // get the whole modal using overlay argument
        const modalWindow = overlay.parentNode;
        // close modal if click event is fired on overlay background
        overlay.addEventListener('click', function () {
            modalWindow.classList.remove('is-active');
        });
    });
}


function fireWhenReady(func) {
    document.addEventListener('DOMContentLoaded', func);
}
fireWhenReady(openModal);
fireWhenReady(openModal1);
fireWhenReady(closeModal);






Number.prototype.map = function (in_min, in_max, out_min, out_max) {
    return (this - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
}

function preview_image(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('previmg');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
if (window.location.href.includes("http://192.168.99.100/camagru/posts/add")) {

    window.addEventListener("load", function () {
        // [1] GET ALL THE HTML ELEMENTS

        var video = document.getElementById("vid-show"),
            canvas = document.getElementById("vid-canvas"),
            take = document.getElementById("vid-take");

        // [2] ASK FOR USER PERMISSION TO ACCESS CAMERA
        // WILL FAIL IF NO CAMERA IS ATTACHED TO COMPUTER
        navigator.mediaDevices.getUserMedia({
            video: true,
            audio: false
        })
            .then(function (stream) {
                // [3] SHOW VIDEO STREAM ON VIDEO TAG
                video.srcObject = stream;
                video.play();

                // [4] WHEN WE CLICK ON "TAKE PHOTO" BUTTON
                take.addEventListener("click", function () {
                    // Create snapshot from video
                    var draw = document.querySelector('.photo');
                    draw.width = 640;
                    draw.height = 495;
                    var context2D = draw.getContext("2d");

                    context2D.drawImage(video, 0, 0, draw.width, draw.height);
                    document.getElementById('imgInputData').value = draw.toDataURL('image/jpeg');

                    canvas.draw;
                    canvasSup1.style.display  = "block";
                });

            })
            .catch(function (err) {
                alert("please enable your camera ");
            });
    });
}

function switch_type(evt, type) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" is-active", "");
    }
    const checked = false;

    if (type == 'camera') {
        document.getElementById("type").value = "camera";
        const cbs = document.querySelectorAll('input[name=super]:checked');
        cbs.forEach((cb) => {
            cb.checked = checked;
        });

    } else {
        document.getElementById("type").value = "upload";
        const cbs = document.querySelectorAll('input[name=super]:checked');
        cbs.forEach((cb) => {
            cb.checked = checked;
        });
    }
    // canvasSup1.style.display = "none";
    // canvasSup.style.display = "none";

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(type).style.display = "flex";
    evt.currentTarget.className += " is-active";

}
// document.getElementById("camera").click();



if (video) {
    video.addEventListener('click', e => {

        let r = e.target.offsetWidth / e.target.offsetHeight;
        document.querySelector('#x').value = e.offsetX.map(0, e.target.offsetWidth, 0, 800);
        document.querySelector('#y').value = e.offsetY.map(0, e.target.offsetHeight, 0, 800 / r);
        canvasSup.style.left = `${e.offsetX}px`;
        canvasSup.style.top = `${e.offsetY}px`;


    });
    var take = document.getElementById("vid-take");
    take.addEventListener("click", function () {
        canvasSup1.style.left = canvasSup.style.left;
        canvasSup1.style.top = canvasSup.style.top;
    });

}
if (previmg) {
    previmg.addEventListener('click', e => {

        let r = e.target.offsetWidth / e.target.offsetHeight;
        document.querySelector('#x').value = e.offsetX.map(0, e.target.offsetWidth, 0, 800);
        document.querySelector('#y').value = e.offsetY.map(0, e.target.offsetHeight, 0, 800 / r);
        canvasSup2.style.left = `${e.offsetX}px`;
        canvasSup2.style.top = `${e.offsetY}px`;


    });
}

if (radios) {
    // console.log("OK");

    radios.forEach(cur => {

        cur.addEventListener('change', () => {
            canvasSup.src = document.querySelector('input[name=super]:checked').value;
            canvasSup1.src = document.querySelector('input[name=super]:checked').value;
            canvasSup2.src = document.querySelector('input[name=super]:checked').value;

            // if (document.getElementById("type").value === "camera") {
                canvasSup.style.display = 'block';
                canvasSup2.style.display = 'none';

            if (document.getElementById("type").value === "upload") {
                canvasSup.style.display = 'none';
                canvasSup2.style.display = 'block';

            }
            document.querySelector('#x').value = 400;
            document.querySelector('#y').value = 300;
            // captureBtn.disabled = allowPic(pictureIsAllowed);
        });
    });
}