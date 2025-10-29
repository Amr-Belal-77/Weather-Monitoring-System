
// get current hour
const hour = new Date().getHours();
const video = document.getElementById("bg-video");
const source = video.querySelector("source");
console.log("Hour is:", hour);  // هتظهر في الكونسول


let videoSrc = "../videos/morning.mp4";

if (hour >= 6 && hour < 12) {
    videoSrc = "../videos/morning.mp4";
}
else if (hour >= 12 && hour < 18) {
    videoSrc = "../videos/maghrib.mp4";
}
else if (hour >= 18 && hour < 21) {
    videoSrc = "../videos/night.mp4";
}
else {
    videoSrc = "../videos/rain.mp4";
}

console.log("Video source:", videoSrc); // اتأكد أي ملف اتختار

source.setAttribute("src", videoSrc);
video.load();









// --- 1. Get references to all necessary HTML elements ---
    
// The main title element
const titleElement = document.getElementById('title-control-box');

// Button container elements (These have the background color)
const dataContainer = document.querySelector('.button-data');
const controlContainer = document.querySelector('.button-control');

// The actual button elements (Used to read the text content)
const dataButton = document.getElementById('button-data');
const controlButton = document.getElementById('button-control');

// The content sections
const dataContent = document.getElementById('iot-data-content');
const controlContent = document.getElementById('iot-control-content');

// --- 2. Function to handle button clicks (The Core Logic) ---
function handleButtonClick(clickedContainer, clickedButton, contentToShow, contentToHide) {
        
    // 1. Update the H2 Title
    titleElement.textContent = clickedButton.textContent;

    // 2. Manage Button Active State (Color Persistence via CSS .active class)
        
    // Remove 'active' class from all containers
    dataContainer.classList.remove('active');
    controlContainer.classList.remove('active');
        
    // Add 'active' class to the clicked container
    clickedContainer.classList.add('active');

    // 3. Toggle Content Visibility (The Div Switch)
        
    // Hide the content that shouldn't be visible
    contentToHide.classList.add('hidden');
        
    // Show the content that should be visible
    contentToShow.classList.remove('hidden');
}

// --- 3. Attach Event Listeners ---
    
// Attach listener to the IoT Data container (where the color is applied)
dataContainer.addEventListener('click', () => {
    handleButtonClick(dataContainer, dataButton, dataContent, controlContent);
});

// Attach listener to the IoT Control container (where the color is applied)
controlContainer.addEventListener('click', () => {
    handleButtonClick(controlContainer, controlButton, controlContent, dataContent);
});

// --- 4. Set Initial State (IoT Data is visible and active on page load) ---
    
// Ensure the Data button starts in the active state
dataContainer.classList.add('active');
    
// Ensure the title matches the initial active button
titleElement.textContent = dataButton.textContent;
    
// Ensure the Data content is visible and Control content is hidden
dataContent.classList.remove('hidden');
controlContent.classList.add('hidden');