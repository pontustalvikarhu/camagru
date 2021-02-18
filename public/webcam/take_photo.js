const videoPlayer = document.querySelector("#player");
const canvasElement = document.querySelector("#canvas");
const captureButton = document.querySelector("#capture-btn");
const imagePicker = document.querySelector("#image-picker");
const imagePickerArea = document.querySelector("#pick-image");
const newImages = document.querySelector("#newImages");

// Image dimensions
const width = 320;
const height = 240;
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

// Capture the image, save it and then paste it to the DOM
captureButton.addEventListener("click", event => {
  // Draw the image from the video player on the canvas
  console.log("(1) Capturing image with .js in webcam folder.");
  canvasElement.style.display = "block";
  const context = canvasElement.getContext("2d");
  context.drawImage(videoPlayer, 0, 0, canvas.width, canvas.height);

  // Convert the data so it can be saved as a file
  let picture = canvasElement.toDataURL();

  // Save the file by posting it to the server
  fetch("./api/save_image.php", {
    method: "post",
    body: JSON.stringify({ data: picture })
  })
  //.then(response => response.json())
  .then(data => {
	console.log('Success:', data);
	console.log('Image captured.')
  })
	.catch(error => console.log(`Error detected: ${error}`));
	
});

window.addEventListener("load", event => startMedia());
