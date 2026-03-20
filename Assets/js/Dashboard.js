// ====================== Nexure Risk Score 1.0 ======================

const score = window.nexureRiskScore || 0;

const maxScore = 999;

const bar = document.querySelector('.score-bar');

const indicator = document.getElementById('score-indicator');

function updateIndicatorPosition() {

    if (bar && indicator) {

        const percent = Math.min((score / maxScore) * 100, 100);

        indicator.style.left = `calc(${percent}% - 1.5px)`;

    }
    
}

updateIndicatorPosition();

document.addEventListener('DOMContentLoaded', () => {

    const timeElement = document.getElementById('userSystemTime');

    function formatTime(date) {

        const options = {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true,
        };

        const timePart = date.toLocaleTimeString(undefined, options);

        const day = date.getDate();

        const month = date.toLocaleString(undefined, { month: 'long' });

        const year = date.getFullYear();

        return `${month} ${day}, ${year} ${timePart}`;

    }

    function updateTime() {

        const now = new Date();

        timeElement.textContent = `${formatTime(now)}`;

    }

    if (timeElement) {

        updateTime();

        setInterval(updateTime, 1000);

    }

});

// ============ Identity Verification Javascript Code =============

document.addEventListener('DOMContentLoaded', () => {

    const verifyBtn = document.getElementById('verifyBtn');

    if (verifyBtn) {

        verifyBtn.addEventListener('click', async () => {

            try {

                const res = await fetch('/Modules/NexureSolutions/Identity/index.php');

                const data = await res.json();

                if (data.url) {

                    window.location.href = data.url;

                } else {

                    alert('Failed to start verification. Please try again.');

                }

            } catch (err) {

                console.error('Verification error:', err);

                alert('An error occurred. Please try again.');

            }

        });

    }

});


// ====================== Dropdown JS Code ======================

document.addEventListener('DOMContentLoaded', function () {
    const moreButton = document.querySelector('.more-button');
    const more = document.querySelector('.more');

    moreButton.addEventListener('click', function (e) {
        e.preventDefault();
        more.classList.toggle('active');
    });

    document.addEventListener('click', function (e) {
        if (!more.contains(e.target)) {
            more.classList.remove('active');
        }
    });
});

// ====================== Account Opening Business Account Type Form Fields ======================

document.addEventListener("DOMContentLoaded", function() {
    
    const accountTypeSelect = document.querySelector('select[name="accounttype"]');

    const businessInfoSection = document.querySelectorAll('.background-grey-100.margin-bottom-20px.margin-top-30px, .nexure-grid.nexure-three-grid.no-row-gap.width-100');

    const businessHeader = Array.from(businessInfoSection).find(el => 
        el.querySelector && el.querySelector('p') && el.querySelector('p').textContent.trim().toLowerCase() === 'business information'
    );

    if (!businessHeader) return;

    const businessFormGrid = businessHeader.nextElementSibling;

    businessHeader.style.display = 'none';

    businessFormGrid.style.display = 'none';

    accountTypeSelect.addEventListener('change', function() {

        if (this.value.toLowerCase() === 'business') {

            businessHeader.style.display = '';
            businessFormGrid.style.display = '';

        } else {

            businessHeader.style.display = 'none';
            businessFormGrid.style.display = 'none';

        }

    });

});

// ====================== Dashboard Modals Javascript Function ======================

function toggleModal(modal, show) {
    if (!modal) return;
    modal.style.display = show ? "block" : "none";
}

var modalChangeBalance = document.getElementById("setbalanceModal");
var modalPayBalance = document.getElementById("paybalanceModal");
var modalCreditBalance = document.getElementById("creditLimitModal");
var modalDeveloper = document.getElementById("developerModal");

function openDeveloperModal() {
    toggleModal(modalDeveloper, true);
}

function closeDeveloperModal() {
    toggleModal(modalDeveloper, false);
}

function openPaymentModal() {
    toggleModal(modalPayBalance, true);
}

function closePaymentModal() {
    toggleModal(modalPayBalance, false);
}

function openCreditModal() {
    toggleModal(modalCreditBalance, true);
}

function closeCreditModal() {
    toggleModal(modalCreditBalance, false);
}

function openBalanceModal() {
    toggleModal(modalChangeBalance, true);
}

function closeBalanceModal() {
    toggleModal(modalChangeBalance, false);
}

// ====================== Dashboard Time of Day Text ======================

const greetingElement = document.getElementById('greetingMessage');

if (greetingElement) {

    const myDate = new Date();

    const hrs = myDate.getHours();

    let greet;

    if (hrs < 12)
        greet = 'Good Morning';
    else if (hrs >= 12 && hrs <= 17)
        greet = 'Good Afternoon';
    else
        greet = 'Good Evening';

    greetingElement.innerHTML = greet;

}
