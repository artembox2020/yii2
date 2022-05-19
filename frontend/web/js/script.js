console.log('script js!');
function fadeOutEffect(fadeTarget) {
    var fadeEffect = setInterval(function () {
        if (!fadeTarget.style.opacity) {
            fadeTarget.style.opacity = 1;
        }
        if (fadeTarget.style.opacity > 0) {
            fadeTarget.style.opacity -= 0.2;
        } else {
            fadeTarget.style = "";
            fadeTarget.classList.add('invisible');
            clearInterval(fadeEffect);
        }
    }, 40);
};

function fadeInEffect(fadeTarget) {
    var fadeEffect = setInterval(function () {
        if (!fadeTarget.style.opacity) {
            fadeTarget.style.opacity = 0.0;
            fadeTarget.classList.remove('invisible');
        }
        if (fadeTarget.style.opacity < 1) {
            fadeTarget.style.opacity = parseFloat(fadeTarget.style.opacity) + 0.2;
        } else {
            fadeTarget.style = "";
            clearInterval(fadeEffect);
        }
    }, 40);
}