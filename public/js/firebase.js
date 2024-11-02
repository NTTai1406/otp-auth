import { initializeApp } from "https://www.gstatic.com/firebasejs/9.8.3/firebase-app.js";
import {
    getAuth,
    RecaptchaVerifier,
    signInWithPhoneNumber
} from "https://www.gstatic.com/firebasejs/9.8.3/firebase-auth.js";

// Cấu hình Firebase
const firebaseConfig = {
    apiKey: "AIzaSyBxQzNc-JROoJzeCE9nF6PVFtGnUjLvUwM",
    authDomain: "otp-1552f.firebaseapp.com",
    projectId: "otp-1552f",
    storageBucket: "otp-1552f.appspot.com",
    messagingSenderId: "923304839734",
    appId: "1:923304839734:web:9f5ca2cbbef43e93e4e697"
};

// Khởi tạo Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
auth.languageCode = "en";

// Hiển thị reCAPTCHA khi trang tải
window.onload = function () {
    render();
};

function render() {
    window.recaptchaVerifier = new RecaptchaVerifier(
        "recaptcha-container",
        {
            size: "normal",
            callback: (response) => {
                alert("Recaptcha successfully completed! Enter your phone number, please.");
                $("#send_otp").removeAttr("disabled");
            },
            "expired-callback": () => {
                alert("Recaptcha has expired! Please try again.");
            }
        },
        auth
    );
    recaptchaVerifier.render();
}

// Hàm gửi OTP
window.sendOTP = function () {
    const phoneNumber = $("#phone_number").val();
    const appVerifier = window.recaptchaVerifier;

    signInWithPhoneNumber(auth, phoneNumber, appVerifier)
        .then((confirmationResult) => {
            window.confirmationResult = confirmationResult;
            alert("OTP has been sent! Please check your phone.");
            $("#verification_input").show();
            $("#phone_input").hide();
        })
        .catch((error) => {
            alert("Error during OTP sending: " + error.message);
        });
};

// Hàm xác minh OTP
window.verifyOTP = function () {
    const code = $("#verification_code").val();

    confirmationResult.confirm(code)
        .then((result) => {
            alert("OTP has been verified successfully!");
            const user = result.user;
            $("#verification_input").hide();
            $("#phone_input").show();
            $("#verification_code").val("");
        })
        .catch((error) => {
            alert("Error during OTP verification: " + error.message);
        });
};

// Hàm thử lại
window.tryAgain = function () {
    $("#verification_input").hide();
    $("#phone_input").show();
    $("#verification_code").val("");
};
