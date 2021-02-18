const videoPlayer = document.querySelector("#player");
const canvasElement = document.querySelector("#canvas");
const captureButton = document.querySelector("#capture-btn");
const confirmButton = document.querySelector("#confirm-btn");
const stickerButton = document.querySelector("#sticker-btn");
const imagePicker = document.querySelector("#image-picker");
const imagePickerArea = document.querySelector("#pick-image");
const newImages = document.querySelector("#newImages");
const submitImage = document.querySelector("#image");

// Image dimensions
const width = 400;
const height = 300;
let zIndex = 1;

const createImage = (src, alt, title, width, height, className) => {
  let newImg = document.createElement("img");

  if (src !== null) newImg.setAttribute("src", src);
  if (alt !== null) newImg.setAttribute("alt", alt);
  if (title !== null) newImg.setAttribute("title", title);
  if (width !== null) newImg.setAttribute("width", width);
  if (height !== null) newImg.setAttribute("height", height);
  if (className !== null) newImg.setAttribute("class", className);

  return newImg;
};

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

// Capture the image, save it and then paste it to the DOM
captureButton.addEventListener("click", event => {
  // Draw the image from the video player on the canvas
  console.log("(2) Capturing image with .js in js folder.");
  canvasElement.style.display = "block";
  const context = canvasElement.getContext("2d");
  context.drawImage(videoPlayer, 0, 0, canvas.width, canvas.height);
  photoAvailable = true;
  // Convert the data so it can be saved as a file
  /*let picture = canvasElement.toDataURL();

  // Save the file by posting it to the server
  fetch("../webcam/api/save_image.php", {
    method: "post",
    body: JSON.stringify({ data: picture })
  })
  .then(response => response.json())
  .then(data => {
	//console.log(`Path: ${data['path']}`);
	imagePath = data['path']
	console.log(`Image captured. Path is ${imagePath}.`)
  })
	.catch(error => console.log(`Error detected: ${error}`));*/
});

confirmButton.addEventListener("click", event => {
	// Convert the data so it can be saved as a file
	let picture = canvasElement.toDataURL();
	if (photoAvailable !== true) {
		alert('No photo has been taken!');
	}
	else {
	// Save the file by posting it to the server
	fetch("../webcam/api/save_image.php", {
	  method: "post",
	  body: JSON.stringify({ data: picture })
	})
	.then(response => response.json())
	.then(data => {
	  //console.log(`Path: ${data['path']}`);
	  imagePath = data['path']
	  submitImage.value = imagePath;
	  console.log(`Image captured. Path is ${imagePath}.`)
	})
	  .catch(error => console.log(`Error detected: ${error}`));}
	//console.log(`Submit image that should've been send by post: ${submitImage.value}`);
	photoAvailable = false;
});

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
   function SetActive(id) {
	//console.log(`stickers.length: ${stickers.length}`)
	var currentActive = document.getElementsByClassName("active_sticker");
	//console.log(currentActive);
	if (currentActive.length > 0){
		//console.log('Removing active sticker.');
		//console.log(currentActive[0]);
		currentActive[0].classList.remove("active_sticker");
}
   var s = document.getElementById(id);
   //console.log(`Sticker ID: ${s.id}`);
    s.className = "active_sticker";
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
		} else {
			console.log('No sticker selected!');
		}
  drawn = 1;
}