const sidebar = document.getElementById('sidebar');
const button = document.getElementById('toggle');

button.addEventListener('click', _ => {
  sidebar.classList.toggle('collapsed');
});

const videoPlayer = document.querySelector("#player");
const canvasElement = document.querySelector("#canvas");
const captureButton = document.querySelector("#capture-btn");
const confirmButton = document.querySelector("#confirm-btn");
const stickerButton = document.querySelector("#sticker-btn");
const imagePicker = document.querySelector("#image-picker");
const imagePickerArea = document.querySelector("#pick-image");
const newImages = document.querySelector("#newImages");
const submitImage = document.querySelector("#image");
const stickers = document.querySelector("#stickers");
const stickerPos = document.querySelector("#sticker-pos");

// Image dimensions
const width = 400;
const height = 300;

const startMedia = () => {
  if (!("mediaDevices" in navigator)) {
    navigator.mediaDevices = {};
  }

  if (!("getUserMedia" in navigator.mediaDevices)) {
    navigator.mediaDevices.getUserMedia = constraints => {
      const getUserMedia =
        navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

      if (!getUserMedia) {
        return Promise.reject(new Error("getUserMedia is not supported"));
      } else {
        return new Promise((resolve, reject) =>
          getUserMedia.call(navigator, constraints, resolve, reject)
        );
      }
    };
  }

  navigator.mediaDevices
    .getUserMedia({ video: true })
    .then(stream => {
      videoPlayer.srcObject = stream;
      videoPlayer.style.display = "block";
    })
    .catch(err => {
      imagePickerArea.style.display = "block";
    });
};

let photoAvailable = false
let imagePath = ''
let preview_no = 0

let sticker_i = 0;
let stickers_arr = [];
let sticker_pos = [];


// Capture the image, save it and then paste it to the DOM
captureButton.addEventListener("click", event => {
	if (stickerSelected !== true){
		alert('No sticker has been selected. Please select a sticker.')
	}
	else{
		// Draw the image from the video player on the canvas
		canvasElement.style.display = "block";
		const context = canvasElement.getContext("2d");
		context.drawImage(videoPlayer, 0, 0, canvas.width, canvas.height);
		photoAvailable = true;
		let img = document.createElement("img");
		img.src = canvasElement.toDataURL('image/png');
		let src = document.getElementById("sidebar");
		img.id = 'preview_' + preview_no;
		++preview_no
		img.setAttribute('onclick', "SetActiveBase(this.id)")
		src.appendChild(img);
	}
});

function SetActiveBase(id){
	let newImg = document.getElementById(id)
	context.drawImage(newImg, 0, 0, canvas.width, canvas.height)
}

confirmButton.addEventListener("click", event => {
	// Convert the data so it can be sent to backend.
	if (photoAvailable !== true) {
		alert('No photo has been taken/selected! Please either take a photo, or select one from the previews on the lefthand side.')
	}
	else {
	  submitImage.value = canvasElement.toDataURL('image/png')
	document.getElementById("confirm-btn").className = "btn btn-saved";
	// Save stickers and sticker positions to form element.
	stickers.value = stickers_arr
	stickerPos.value = sticker_pos
	}
})

window.addEventListener("load", event => startMedia());

/*
**
** Code for placing stickers.
**
*/

var baseImg = document.getElementById("canvas");
baseImg.onmousedown = PlaceSticker;
let imgEle1 = document.getElementById("canvas");
let resEle = document.querySelector("canvas");
var context = resEle.getContext("2d");
resEle.width = imgEle1.width;
resEle.height = imgEle1.height;
context.globalAlpha = 1.0;
context.drawImage(imgEle1, 0, 0);
let stickerSelected = false;

function SetActive(id) {
	var currentActive = document.getElementsByClassName("active_sticker");
	if (currentActive.length > 0){
		currentActive[0].classList.remove("active_sticker");
}
   var s = document.getElementById(id);
    s.className = "active_sticker";
	stickerSelected = true;
	captureButton.className = "btn btn-primary";
	captureButton.disabled = false;
   }

function FindPosition(oElement)
{
  if(typeof( oElement.offsetParent ) != "undefined")
  {
    for(var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent)
    {
      posX += oElement.offsetLeft;
      posY += oElement.offsetTop;
    }
      return [ posX, posY ];
    }
    else
    {
      return [ oElement.x, oElement.y ];
    }
}

function PlaceSticker(e)
{
  var PosX = 0;
  var PosY = 0;
  var ImgPos;
  ImgPos = FindPosition(baseImg);
  if (!e) var e = window.event;
  if (e.pageX || e.pageY)
  {
    PosX = e.pageX;
    PosY = e.pageY;
  }
  else if (e.clientX || e.clientY)
    {
      PosX = e.clientX + document.body.scrollLeft
        + document.documentElement.scrollLeft;
      PosY = e.clientY + document.body.scrollTop
        + document.documentElement.scrollTop;
    }
  PosX = PosX - ImgPos[0];
  PosY = PosY - ImgPos[1];
	  if (document.getElementsByClassName("active_sticker")[0]){
		  context.drawImage(document.getElementsByClassName("active_sticker")[0], PosX, PosY);
		  // store XY in array Sticker_pos[] at sticker_i, and sticker in array stickers[].
		  stickers_arr[sticker_i] = document.getElementsByClassName("active_sticker")[0].src;
		  sticker_pos[sticker_i] = [PosX, PosY];
		  ++sticker_i;
		}
}

  document.getElementById('upload').onchange = function(e) {
	var img = new Image();
	img.onload = draw;
	img.onerror = failed;
	img.src = URL.createObjectURL(this.files[0]);
  };
  function draw() {
	var canvas = document.getElementById('canvas');
	canvas.width = 400;
	canvas.height = 300;
	var ctx = canvas.getContext('2d');
	ctx.drawImage(this, 0, 0, canvas.width, canvas.height);
	photoAvailable = true;
  }
  
  function failed() {
	console.error("The provided file couldn't be loaded as an Image media");
  }
